<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;

class CampusMapController extends Controller
{
    public function index(): void
    {
        $db = $this->db();
        $buildings = $db->select('SELECT id, name, code FROM buildings ORDER BY name ASC');

        $bId = (int) Request::input('b', (int) ($buildings[0]['id'] ?? 0));
        if ($bId <= 0 && $buildings !== []) { $bId = (int) $buildings[0]['id']; }

        $floors = $db->select('SELECT * FROM floors WHERE building_id = ? ORDER BY level ASC', [$bId]);
        $fLevel = (int) Request::input('f', 0);

        $where = 'WHERE r.building_id = ?';
        $params = [$bId];
        if ($fLevel > 0) {
            $where .= ' AND fl.level = ?';
            $params[] = $fLevel;
        }

        $rooms = $db->select(
            "SELECT r.*, fl.level AS floor_level
             FROM rooms r
             LEFT JOIN floors fl ON fl.id = r.floor_id
             {$where}
             ORDER BY r.code ASC",
            $params
        );

        $mapSvg = null;
        if ($this->tableExists('campus_maps')) {
            $row = $db->selectOne(
                'SELECT svg_content FROM campus_maps WHERE building_id = ? AND floor_level = ?',
                [$bId, $fLevel]
            );
            $mapSvg = $row['svg_content'] ?? null;
        }

        /* Pemetaan kode ruangan -> id untuk klik pada denah SVG */
        $roomMap = [];
        foreach ($rooms as $r) { $roomMap[$r['code']] = (int) $r['id']; }

        $this->view('public/pages/campus_map', [
            'title'     => 'Peta Kampus',
            'buildings' => $buildings,
            'floors'    => $floors,
            'bId'       => $bId,
            'fLevel'    => $fLevel,
            'rooms'     => $rooms,
            'mapSvg'    => $mapSvg,
            'roomMap'   => $roomMap,
            'mapTableMissing' => !$this->tableExists('campus_maps'),
        ], 'public/layouts/main');
    }

    /** Periksa apakah tabel ada di database. */
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