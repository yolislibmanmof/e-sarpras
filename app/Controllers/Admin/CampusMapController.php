<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class CampusMapController extends Controller
{
    public function index(): void
    {
        $db = $this->db();
        $buildings = $db->select('SELECT id, name, code FROM buildings ORDER BY name ASC');
        $maps = [];

        if ($this->tableExists('campus_maps')) {
            $maps = $db->select(
                'SELECT cm.*, b.name AS building_name FROM campus_maps cm LEFT JOIN buildings b ON b.id = cm.building_id ORDER BY b.name, cm.floor_level'
            );
        }

        $this->adminView('admin/pages/campus_maps/index', [
            'title'           => 'Peta Kampus',
            'buildings'       => $buildings,
            'maps'            => $maps,
            'mapTableMissing' => !$this->tableExists('campus_maps'),
        ]);
    }

    public function store(): void
    {
        if (!$this->tableExists('campus_maps')) {
            Session::flash('error', 'Tabel campus_maps belum dibuat. Jalankan SQL terlebih dahulu.');
            $this->redirect(admin_url('/peta-kampus'));
            return;
        }

        $buildingId = (int) Request::input('building_id', 0);
        $floorLevel = (int) Request::input('floor_level', 1);
        $file = Request::file('svg_file');

        if ($buildingId <= 0 || $file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            Session::flash('error', 'Pilih gedung dan unggah berkas SVG.');
            $this->redirect(admin_url('/peta-kampus'));
        }

        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if ($ext !== 'svg') {
            Session::flash('error', 'Format harus .svg');
            $this->redirect(admin_url('/peta-kampus'));
        }

        $content = (string) file_get_contents($file['tmp_name']);
        if (strpos($content, '<svg') === false) {
            Session::flash('error', 'Berkas bukan SVG valid.');
            $this->redirect(admin_url('/peta-kampus'));
        }

        $exists = $this->db()->selectOne('SELECT id FROM campus_maps WHERE building_id = ? AND floor_level = ?', [$buildingId, $floorLevel]);
        if ($exists !== null) {
            $this->db()->update('campus_maps', ['svg_content' => $content], ['id' => $exists['id']]);
        } else {
            $this->db()->insert('campus_maps', ['building_id' => $buildingId, 'floor_level' => $floorLevel, 'svg_content' => $content]);
        }

        AuditLogger::log('campus_map.save', 'campus_map', $buildingId, null, ['floor' => $floorLevel]);
        Session::flash('success', 'Denah SVG berhasil disimpan.');
        $this->redirect(admin_url('/peta-kampus'));
    }

    public function destroy(string $id): void
    {
        if (!$this->tableExists('campus_maps')) {
            Session::flash('error', 'Tabel belum dibuat.');
            $this->redirect(admin_url('/peta-kampus'));
            return;
        }
        $this->db()->delete('campus_maps', ['id' => $id]);
        AuditLogger::log('campus_map.delete', 'campus_map', $id);
        Session::flash('success', 'Denah dihapus.');
        $this->redirect(admin_url('/peta-kampus'));
    }

    private function tableExists(string $table): bool
    {
        try {
            $row = $this->db()->selectOne(
                "SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ?",
                [$table]
            );
            return $row !== null && (int) ($row['c'] ?? 0) > 0;
        } catch (\Throwable $e) {
            return false;
        }
    }
}