<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;

class TransparencyController extends Controller
{
    public function index(): void
    {
        $db = $this->db();

        // Statistik agregat
        $stats = [
            'total_assets'     => $db->count('assets'),
            'assets_good'      => $db->count('assets', ['condition' => 'Baik']),
            'total_rooms'      => $db->count('rooms'),
            'tickets_this_month' => (int) ($db->selectOne(
                "SELECT COUNT(*) AS t FROM tickets WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())"
            )['t'] ?? 0),
            'tickets_resolved' => (int) ($db->count('tickets', ['status' => 'Selesai'])),
            'surveys_answered' => (int) ($db->selectOne("SELECT COUNT(*) AS c FROM survey_responses")['c'] ?? 0),
        ];

        // Top 5 ruangan paling sering dipinjam
        $topRooms = $db->select(
            "SELECT r.name, COUNT(b.id) AS total
             FROM rooms r LEFT JOIN room_bookings b ON b.room_id = r.id
             GROUP BY r.id ORDER BY total DESC LIMIT 5"
        );

        // Kondisi aset per kategori
        $assetCondition = $db->select(
            "SELECT c.name, a.condition, COUNT(*) AS total
             FROM assets a LEFT JOIN asset_categories c ON c.id = a.asset_category_id
             GROUP BY c.id, a.condition ORDER BY c.name, a.condition"
        );

        $this->view('public/pages/transparency', [
            'title'          => 'Transparansi',
            'stats'          => $stats,
            'topRooms'       => $topRooms,
            'assetCondition' => $assetCondition,
        ], 'public/layouts/main');
    }
}