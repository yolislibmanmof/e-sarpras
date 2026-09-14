<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;

class AuditLogController extends Controller
{
    public function index(): void
    {
        $q      = Request::input('q', '');
        $module = Request::input('module', '');
        $user   = Request::input('user', '');
        $from   = Request::input('from', '');
        $to     = Request::input('to', '');
        $page   = max(1, (int) Request::input('page', 1));
        $perPage = 20;

        $clauses = [];
        $params  = [];

        if ($q !== '') {
            $clauses[] = '(al.action LIKE ? OR al.module LIKE ?)';
            $params[] = "%{$q}%";
            $params[] = "%{$q}%";
        }
        if ($module !== '') {
            $clauses[] = 'al.module = ?';
            $params[] = $module;
        }
        if ($user !== '') {
            $clauses[] = 'u.full_name LIKE ?';
            $params[] = "%{$user}%";
        }
        if ($from !== '') {
            $clauses[] = 'DATE(al.created_at) >= ?';
            $params[] = $from;
        }
        if ($to !== '') {
            $clauses[] = 'DATE(al.created_at) <= ?';
            $params[] = $to;
        }

        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne(
            "SELECT COUNT(*) AS total FROM audit_logs al LEFT JOIN users u ON u.id = al.user_id {$where}",
            $params
        )['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT al.*, u.full_name AS user_name
             FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             {$where}
             ORDER BY al.id DESC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $query = http_build_query(array_filter([
            'q' => $q, 'module' => $module, 'user' => $user, 'from' => $from, 'to' => $to,
        ], static fn ($v) => $v !== ''));
        $base = admin_url('/audit-log') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/audit_logs/index', [
            'title'      => 'Audit Log',
            'rows'       => $rows,
            'q'          => $q,
            'module'     => $module,
            'user'       => $user,
            'from'       => $from,
            'to'         => $to,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }
}