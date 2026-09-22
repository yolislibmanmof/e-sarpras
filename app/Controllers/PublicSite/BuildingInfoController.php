<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;

class BuildingInfoController extends Controller
{
    public function index(): void
    {
        $db = $this->db();
        $q = Request::input('q', '');

        $where  = '';
        $params = [];
        if ($q !== '') {
            $where = 'WHERE b.name LIKE ? OR b.code LIKE ?';
            $params = ["%{$q}%", "%{$q}%"];
        }

        $rows = $db->select(
            "SELECT b.*, (SELECT COUNT(*) FROM rooms r WHERE r.building_id = b.id) AS room_count
             FROM buildings b {$where} ORDER BY b.name ASC",
            $params
        );

        $rooms = $db->select(
            "SELECT r.*, b.name AS building_name
             FROM rooms r
             LEFT JOIN buildings b ON b.id = r.building_id
             ORDER BY r.code ASC
             LIMIT 24"
        );

        $this->view('public/pages/buildings', [
            'title' => 'Gedung & Ruang',
            'rows'  => $rows,
            'rooms' => $rooms,
            'q'     => $q,
            'stats' => [
                'buildings'  => $db->count('buildings'),
                'rooms'      => $db->count('rooms'),
                'accessible' => $db->count('buildings', ['is_disability_friendly' => 1]),
                'assets'     => $db->count('assets'),
            ],
        ], 'public/layouts/main');
    }

    public function show(string $id): void
    {
        $db = $this->db();
        $building = $db->selectOne('SELECT * FROM buildings WHERE id = ?', [$id]);

        if ($building === null) {
            Session::flash('error', 'Gedung tidak ditemukan.');
            $this->redirect(base_url('/gedung'));
        }

        $floors = $db->select('SELECT * FROM floors WHERE building_id = ? ORDER BY level ASC', [$id]);
        $rooms  = $db->select('SELECT * FROM rooms WHERE building_id = ? ORDER BY code ASC', [$id]);

        $accessibleRooms = 0;
        foreach ($rooms as $room) {
            if ((int) $room['is_disability_friendly'] === 1) { $accessibleRooms++; }
        }

        $this->view('public/pages/building_detail', [
            'title'    => $building['name'],
            'building' => $building,
            'floors'   => $floors,
            'rooms'    => $rooms,
            'stats'    => [
                'floors'     => count($floors),
                'rooms'      => count($rooms),
                'accessible' => $accessibleRooms,
                'assets'     => $db->count('assets', ['building_id' => $id]),
            ],
        ], 'public/layouts/main');
    }

    /** Halaman detail RUANGAN untuk publik. */
    public function room(string $id): void
    {
        $db = $this->db();
        $room = $db->selectOne(
            "SELECT r.*, b.name AS building_name, b.photo AS building_photo
             FROM rooms r
             LEFT JOIN buildings b ON b.id = r.building_id
             WHERE r.id = ?",
            [$id]
        );

        if ($room === null) {
            Session::flash('error', 'Ruangan tidak ditemukan.');
            $this->redirect(base_url('/gedung'));
        }

        $floor = $room['floor_id'] !== null
            ? $db->selectOne('SELECT * FROM floors WHERE id = ?', [$room['floor_id']])
            : null;

        $assets = $db->select(
            'SELECT code, name, `condition` FROM assets WHERE room_id = ? ORDER BY code ASC',
            [$id]
        );

        $this->view('public/pages/room_detail', [
            'title'  => $room['name'],
            'room'   => $room,
            'floor'  => $floor,
            'assets' => $assets,
        ], 'public/layouts/main');
    }

    /** Kalender ketersediaan ruangan 14 hari ke depan (publik). */
    public function schedule(string $id): void
    {
        $db = $this->db();
        $room = $db->selectOne(
            "SELECT r.*, b.name AS building_name
             FROM rooms r
             LEFT JOIN buildings b ON b.id = r.building_id
             WHERE r.id = ?",
            [$id]
        );

        if ($room === null) {
            Session::flash('error', 'Ruangan tidak ditemukan.');
            $this->redirect(base_url('/gedung'));
        }

        $today = date('Y-m-d');
        $end   = date('Y-m-d', strtotime('+14 days'));

        $bookings = $db->select(
            "SELECT start_at, end_at, activity_name, status
             FROM room_bookings
             WHERE room_id = ?
               AND start_at >= ?
               AND start_at <= ?
               AND status NOT IN ('Ditolak', 'Dibatalkan')
             ORDER BY start_at ASC",
            [$id, $today . ' 00:00:00', $end . ' 23:59:59']
        );

        $byDate = [];
        for ($d = strtotime($today); $d <= strtotime($end); $d += 86400) {
            $byDate[date('Y-m-d', $d)] = [];
        }
        foreach ($bookings as $b) {
            $date = date('Y-m-d', strtotime($b['start_at']));
            if (isset($byDate[$date])) {
                $byDate[$date][] = [
                    'start' => date('H:i', strtotime($b['start_at'])),
                    'end'   => date('H:i', strtotime($b['end_at'])),
                    'title' => $b['activity_name'] ?? 'Dipinjam',
                ];
            }
        }

        $this->view('public/pages/room_schedule', [
            'title'  => 'Jadwal ' . $room['name'],
            'room'   => $room,
            'byDate' => $byDate,
            'start'  => $today,
            'end'    => $end,
        ], 'public/layouts/main');
    }
}