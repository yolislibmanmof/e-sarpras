<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index(): void
    {
        $type = Request::input('type', 'assets');
        if (!array_key_exists($type, ReportService::TYPES)) {
            $type = 'assets';
        }

        $from = Request::input('from', '');
        $to   = Request::input('to', '');

        $report = (new ReportService())->build($type, $from, $to);

        $this->adminView('admin/pages/reports/index', [
            'title'  => 'Laporan',
            'types'  => ReportService::TYPES,
            'type'   => $type,
            'from'   => $from,
            'to'     => $to,
            'report' => $report,
        ]);
    }

    public function printView(): void
    {
        $type = Request::input('type', 'assets');
        if (!array_key_exists($type, ReportService::TYPES)) {
            $type = 'assets';
        }

        $from = Request::input('from', '');
        $to   = Request::input('to', '');

        $report = (new ReportService())->build($type, $from, $to);

        AuditLogger::log('report.print', 'report', null, null, ['type' => $type]);

        $this->view('admin/pages/reports/print', [
            'type'   => $type,
            'from'   => $from,
            'to'     => $to,
            'report' => $report,
        ]);
    }

    public function export(): void
    {
        $type = Request::input('type', 'assets');
        if (!array_key_exists($type, ReportService::TYPES)) {
            $type = 'assets';
        }

        $format = Request::input('format', 'csv');
        $from   = Request::input('from', '');
        $to     = Request::input('to', '');

        $report = (new ReportService())->build($type, $from, $to);
        $filename = 'laporan-' . $type . '-' . date('Ymd-His');

        AuditLogger::log('report.export', 'report', null, null, ['type' => $type, 'format' => $format]);

        if ($format === 'xls') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
            echo "<html><head><meta charset='UTF-8'></head><body>";
            echo '<h2>' . htmlspecialchars($report['title'], ENT_QUOTES, 'UTF-8') . '</h2>';
            echo '<table border="1"><thead><tr>';
            foreach ($report['headers'] as $header) {
                echo '<th>' . htmlspecialchars((string) $header, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($report['rows'] as $row) {
                echo '<tr>';
                foreach ($report['headers'] as $key => $header) {
                    $value = $this->cellValue($row, $key);
                    echo '<td>' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table></body></html>';
            exit;
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, $report['headers']);
        foreach ($report['rows'] as $row) {
            $line = [];
            foreach ($report['headers'] as $key => $header) {
                $line[] = $this->cellValue($row, $key);
            }
            fputcsv($out, $line);
        }
        fclose($out);
        exit;
    }

    private function cellValue(array $row, int $index): string
    {
        $keys = array_keys($row);
        $key  = $keys[$index] ?? null;
        if ($key === null) {
            return '';
        }

        $value = $row[$key];
        if ($value === null) {
            return '-';
        }
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
            return format_tanggal_waktu($value);
        }
        if (is_string($value) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return format_tanggal($value);
        }
        return (string) $value;
    }
}