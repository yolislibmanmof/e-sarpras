<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class MaintenanceController extends Controller
{
    private const CATEGORIES = ['AC & Pendingin', 'Kelistrikan', 'Plumbing & Air', 'APAR & K3', 'Lift', 'Genset', 'Kebersihan', 'Lainnya'];
    private const FREQUENCIES = ['Harian', 'Mingguan', 'Bulanan', 'Triwulanan', 'Semesteran', 'Tahunan'];

    public function index(): void
    {
        $status = Request::input('status', '');
        $page   = max(1, (int) Request::input('page', 1));
        $perPage = 10;

        $where  = $status !== '' ? 'WHERE ms.status = ?' : '';
        $params = $status !== '' ? [$status] : [];

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM maintenance_schedules ms {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select(
            "SELECT ms.*, a.name AS asset_name, r.name AS room_name
             FROM maintenance_schedules ms
             LEFT JOIN assets a ON a.id = ms.asset_id
             LEFT JOIN rooms r ON r.id = ms.room_id
             {$where}
             ORDER BY ms.schedule_date ASC
             LIMIT {$perPage} OFFSET {$offset}",
            $params
        );

        $base = admin_url('/pemeliharaan') . ($status !== '' ? '?status=' . urlencode($status) : '');

        $this->adminView('admin/pages/maintenance/index', [
            'title'      => 'Pemeliharaan Berkala',
            'rows'       => $rows,
            'status'     => $status,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function create(): void
    {
        $this->adminView('admin/pages/maintenance/form', [
            'title'      => 'Tambah Jadwal Pemeliharaan',
            'categories' => self::CATEGORIES,
            'frequencies'=> self::FREQUENCIES,
            'assets'     => $this->db()->select('SELECT id, code, name FROM assets ORDER BY name'),
            'rooms'      => $this->db()->select('SELECT id, code, name FROM rooms ORDER BY name'),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'title'         => 'required|max:255',
            'schedule_date' => 'required|date',
        ]);

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/pemeliharaan/tambah'));
        }

        $id = $this->db()->insert('maintenance_schedules', [
            'asset_id'      => ($data['asset_id'] ?? '') === '' ? null : (int) $data['asset_id'],
            'room_id'       => ($data['room_id'] ?? '') === '' ? null : (int) $data['room_id'],
            'title'         => $data['title'],
            'category'      => $data['category'] ?? 'Lainnya',
            'schedule_date' => $data['schedule_date'],
            'frequency'     => $data['frequency'] ?? null,
            'status'        => 'Terjadwal',
            'description'   => $data['description'] ?? null,
            'created_by'    => auth_id(),
        ]);

        AuditLogger::log('maintenance.create', 'maintenance', $id, null, ['title' => $data['title']]);
        Session::flash('success', 'Jadwal pemeliharaan berhasil dibuat.');
        $this->redirect(admin_url('/pemeliharaan'));
    }

    public function complete(string $id): void
    {
        $schedule = $this->db()->selectOne('SELECT * FROM maintenance_schedules WHERE id = ?', [$id]);
        if ($schedule === null) {
            Session::flash('error', 'Jadwal tidak ditemukan.');
            $this->redirect(admin_url('/pemeliharaan'));
        }

        $data = Request::all();

        $this->db()->insert('maintenance_logs', [
            'maintenance_schedule_id' => (int) $id,
            'asset_id'                => $schedule['asset_id'],
            'room_id'                 => $schedule['room_id'],
            'technician_id'           => ($data['technician_id'] ?? '') === '' ? null : (int) $data['technician_id'],
            'vendor_id'               => ($data['vendor_id'] ?? '') === '' ? null : (int) $data['vendor_id'],
            'maintenance_type'        => 'Preventif',
            'action_date'             => date('Y-m-d'),
            'status'                  => 'Selesai',
            'cost'                    => (float) ($data['cost'] ?? 0),
            'sparepart'               => $data['sparepart'] ?? null,
            'notes'                   => $data['notes'] ?? null,
            'created_by'              => auth_id(),
        ]);

        $this->db()->update('maintenance_schedules', ['status' => 'Selesai'], ['id' => $id]);

        AuditLogger::log('maintenance.complete', 'maintenance', $id);
        Session::flash('success', 'Pemeliharaan selesai dan tercatat pada log.');
        $this->redirect(admin_url('/pemeliharaan'));
    }

    public function destroy(string $id): void
    {
        $this->db()->delete('maintenance_schedules', ['id' => $id]);
        AuditLogger::log('maintenance.delete', 'maintenance', $id);
        Session::flash('success', 'Jadwal pemeliharaan berhasil dihapus.');
        $this->redirect(admin_url('/pemeliharaan'));
    }

    public function logs(): void
    {
        $rows = $this->db()->select(
            "SELECT ml.*, a.name AS asset_name, r.name AS room_name, t.name AS technician_name, v.name AS vendor_name
             FROM maintenance_logs ml
             LEFT JOIN assets a ON a.id = ml.asset_id
             LEFT JOIN rooms r ON r.id = ml.room_id
             LEFT JOIN technicians t ON t.id = ml.technician_id
             LEFT JOIN vendors v ON v.id = ml.vendor_id
             ORDER BY ml.id DESC
             LIMIT 100"
        );

        $this->adminView('admin/pages/maintenance/logs', [
            'title' => 'Log Pemeliharaan',
            'rows'  => $rows,
        ]);
    }
}