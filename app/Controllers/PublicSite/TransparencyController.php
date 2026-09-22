<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;

class TransparencyController extends Controller
{
    public function index(): void
    {
        $db = $this->db();

        $stats = [
            'total_assets'       => $db->count('assets'),
            'assets_good'        => $db->count('assets', ['condition' => 'Baik']),
            'total_rooms'        => $db->count('rooms'),
            'tickets_this_month' => (int) ($db->selectOne("SELECT COUNT(*) AS t FROM tickets WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())")['t'] ?? 0),
            'tickets_resolved'   => $db->count('tickets', ['status' => 'Selesai']),
            'surveys_answered'   => (int) ($db->selectOne("SELECT COUNT(*) AS c FROM survey_responses")['c'] ?? 0),
        ];

        $topRooms = $db->select(
            "SELECT r.name, COUNT(b.id) AS total
             FROM rooms r LEFT JOIN room_bookings b ON b.room_id = r.id
             GROUP BY r.id ORDER BY total DESC LIMIT 5"
        );

        /* Komposisi kondisi aset */
        $condRows = $db->select("SELECT `condition`, COUNT(*) AS total FROM assets GROUP BY `condition`");
        $condTotals = ['Baik' => 0, 'Rusak Ringan' => 0, 'Rusak Sedang' => 0, 'Rusak Berat' => 0];
        foreach ($condRows as $row) { $condTotals[$row['condition']] = (int) $row['total']; }

        /* Distribusi tiket per status */
        $ticketsByStatus = $db->select("SELECT status, COUNT(*) AS total FROM tickets GROUP BY status ORDER BY total DESC");

        /* Tren laporan 6 bulan terakhir */
        $months = [];
        for ($i = 5; $i >= 0; $i--) { $months[date('Y-m', strtotime("-{$i} months"))] = 0; }
        $trendRows = $db->select(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
             FROM tickets
             WHERE created_at >= DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 5 MONTH)
             GROUP BY ym ORDER BY ym ASC"
        );
        foreach ($trendRows as $t) { if (isset($months[$t['ym']])) { $months[$t['ym']] = (int) $t['total']; } }

        /* Rata-rata kepuasan dari jawaban rating */
        $sat = $db->selectOne("SELECT AVG(rating_value) AS avg_rating, COUNT(*) AS cnt FROM survey_answers WHERE rating_value IS NOT NULL");

        /* Feed aktivitas publik */
        $feed = $db->select(
            "SELECT h.new_status, h.created_at, t.ticket_code
             FROM ticket_histories h
             JOIN tickets t ON t.id = h.ticket_id
             ORDER BY h.id DESC LIMIT 8"
        );

        $this->view('public/pages/transparency', [
            'title'           => 'Transparansi',
            'stats'           => $stats,
            'topRooms'        => $topRooms,
            'condTotals'      => $condTotals,
            'ticketsByStatus' => $ticketsByStatus,
            'months'          => $months,
            'avgRating'       => $sat !== null ? (float) ($sat['avg_rating'] ?? 0) : 0.0,
            'ratingCount'     => $sat !== null ? (int) ($sat['cnt'] ?? 0) : 0,
            'feed'            => $feed,
            'lastUpdated'     => date('d M Y, H:i'),
        ], 'public/layouts/main');
    }
}