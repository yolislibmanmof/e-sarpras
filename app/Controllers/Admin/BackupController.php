<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Security\AuditLogger;

class BackupController extends Controller
{
    public function create(): void
    {
        $dbConfig = config('database');
        $filename = 'backup-' . $dbConfig['database'] . '-' . date('Y-m-d-His') . '.sql';

        try {
            $pdo = $this->db()->pdo();
            $tables = $pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

            $output = "-- Backup Database: {$dbConfig['database']}\n";
            $output .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n";
            $output .= "-- =====================================================\n\n";

            foreach ($tables as $table) {
                $output .= "DROP TABLE IF EXISTS `{$table}`;\n";

                $create = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch();
                $output .= $create['Create Table'] . ";\n\n";

                $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(\PDO::FETCH_ASSOC);
                if ($rows === []) {
                    continue;
                }

                $columns = array_keys($rows[0]);
                $columnList = implode(', ', array_map(static fn ($c) => "`{$c}`", $columns));

                foreach ($rows as $row) {
                    $values = array_map(static function ($v) use ($pdo) {
                        if ($v === null) {
                            return 'NULL';
                        }
                        return $pdo->quote((string) $v);
                    }, $row);
                    $valueList = implode(', ', $values);
                    $output .= "INSERT INTO `{$table}` ({$columnList}) VALUES ({$valueList});\n";
                }
                $output .= "\n";
            }

            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($output));
            echo $output;
            exit;
        } catch (\Throwable $e) {
            Session::flash('error', 'Gagal membuat backup: ' . $e->getMessage());
            $this->redirect(admin_url('/backup'));
        }
    }

    public function index(): void
    {
        AuditLogger::log('backup.view', 'backup', null);
        $this->adminView('admin/pages/backup/index', ['title' => 'Backup Database']);
    }
}