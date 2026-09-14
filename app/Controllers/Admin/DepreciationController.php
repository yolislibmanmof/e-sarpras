<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class DepreciationController extends Controller
{
    public function index(): void
    {
        $rows = $this->db()->select(
            "SELECT a.id, a.code, a.name, a.acquisition_date, a.acquisition_value, a.useful_life_years, a.salvage_value, c.name AS category_name
             FROM assets a LEFT JOIN asset_categories c ON c.id = a.asset_category_id
             WHERE a.acquisition_value IS NOT NULL AND a.acquisition_value > 0
             ORDER BY a.code ASC"
        );

        $items = [];
        $totValue = 0.0; $totAccum = 0.0; $totBook = 0.0;

        foreach ($rows as $row) {
            $calc = $this->calc($row);
            $items[] = $row + $calc;
            $totValue += $calc['value'];
            $totAccum += $calc['accum'];
            $totBook  += $calc['book'];
        }

        $this->adminView('admin/pages/depreciation/index', [
            'title' => 'Penyusutan Aset',
            'items' => $items,
            'totals' => ['value' => $totValue, 'accum' => $totAccum, 'book' => $totBook],
        ]);
    }

    public function export(): void
    {
        $rows = $this->db()->select(
            "SELECT a.code, a.name, a.acquisition_date, a.acquisition_value, a.useful_life_years, a.salvage_value
             FROM assets a WHERE a.acquisition_value IS NOT NULL AND a.acquisition_value > 0 ORDER BY a.code ASC"
        );

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="penyusutan-aset-' . date('Ymd-His') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['Kode', 'Nama', 'Tgl Perolehan', 'Nilai Perolehan', 'Umur (th)', 'Nilai Sisa', 'Penyusutan/Tahun', 'Akumulasi', 'Nilai Buku']);

        foreach ($rows as $row) {
            $c = $this->calc($row);
            fputcsv($out, [
                $row['code'], $row['name'], $row['acquisition_date'] ?? '-',
                $c['value'], $row['useful_life_years'] ?? '-', $c['salvage'],
                round($c['annual'], 2), round($c['accum'], 2), round($c['book'], 2),
            ]);
        }
        fclose($out);
        exit;
    }

    public function updateParams(string $id): void
    {
        $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$id]);
        if ($asset === null) { Session::flash('error', 'Aset tidak ditemukan.'); $this->redirect(admin_url('/aset/penyusutan')); }

        $life = (int) Request::input('useful_life_years', 0);
        $salvage = (float) Request::input('salvage_value', 0);

        $this->db()->update('assets', [
            'useful_life_years' => $life > 0 ? $life : null,
            'salvage_value' => $salvage > 0 ? $salvage : null,
        ], ['id' => $id]);

        AuditLogger::log('asset.depreciation.update', 'asset', $id, null, ['life' => $life, 'salvage' => $salvage]);
        Session::flash('success', 'Parameter penyusutan aset diperbarui.');
        $this->redirect(admin_url('/aset/penyusutan'));
    }

    /** Metode garis lurus (straight-line). */
    private function calc(array $row): array
    {
        $value = (float) ($row['acquisition_value'] ?? 0);
        $salvage = (float) ($row['salvage_value'] ?? 0);
        $life = (int) ($row['useful_life_years'] ?? 0);

        $annual = 0.0; $accum = 0.0; $book = $value; $age = 0.0;

        if ($row['acquisition_date'] !== null) {
            $elapsed = (time() - strtotime($row['acquisition_date'])) / (365.25 * 86400);
            $age = max(0.0, $elapsed);
        }

        if ($life > 0 && $value > 0) {
            $annual = ($value - $salvage) / $life;
            $accum = min($annual * $age, $value - $salvage);
            $book = max($salvage, $value - $accum);
        }

        return [
            'value' => $value, 'salvage' => $salvage, 'life' => $life,
            'annual' => $annual, 'accum' => $accum, 'book' => $book, 'age' => $age,
        ];
    }
}