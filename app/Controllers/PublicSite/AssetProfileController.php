<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Session;

class AssetProfileController extends Controller
{
    public function show(string $id): void
    {
        $db = $this->db();
        $asset = $db->selectOne(
            "SELECT a.*, c.name AS category_name, b.name AS building_name, r.name AS room_name
             FROM assets a
             LEFT JOIN asset_categories c ON c.id = a.asset_category_id
             LEFT JOIN buildings b ON b.id = a.building_id
             LEFT JOIN rooms r ON r.id = a.room_id
             WHERE a.id = ?",
            [$id]
        );

        if ($asset === null) {
            Session::flash('error', 'Aset tidak ditemukan.');
            $this->redirect(base_url('/gedung'));
        }

        $conditions = $db->select(
            'SELECT `condition`, note, created_at FROM asset_conditions WHERE asset_id = ? ORDER BY id DESC',
            [$id]
        );
        $maintenances = $db->select(
            "SELECT title, schedule_date, status FROM maintenance_schedules WHERE asset_id = ? ORDER BY schedule_date DESC LIMIT 6",
            [$id]
        );

        $publicUrl = base_url('/aset-profil/' . (int) $asset['id']);

        $this->view('public/pages/asset_profile', [
            'title'        => $asset['name'],
            'asset'        => $asset,
            'conditions'   => $conditions,
            'maintenances' => $maintenances,
            'publicUrl'    => $publicUrl,
            'qrUrl'        => qr_url($publicUrl),
        ], 'public/layouts/main');
    }
}