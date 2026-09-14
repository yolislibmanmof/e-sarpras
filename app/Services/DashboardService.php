<?php

declare(strict_types=1);

namespace App\Services;

class DashboardService
{
    public function overview(): array
    {
        $data = [
            'stats' => [
                'assets'      => 0,
                'rooms'       => 0,
                'tickets'     => 0,
                'borrows'     => 0,
                'procurements'=> 0,
                'item_requests'=> 0,
            ],
            'tickets_by_status'    => [],
            'assets_by_category'   => [],
            'maintenance_upcoming' => [],
            'maintenance_overdue'  => 0,
            'recent_tickets'       => [],
            'recent_activity'      => [],
            'calendar'             => $this->buildCalendar([]),
        ];

        try {
            $db = db();

            $data['stats']['assets']       = $db->count('assets');
            $data['stats']['rooms']        = $db->count('rooms');
            $data['stats']['tickets']      = $db->count('tickets', ['status' => 'Menunggu Verifikasi']);
            $data['stats']['borrows']      = $db->count('borrow_requests', ['status' => 'Menunggu Verifikasi']);
            $data['stats']['procurements'] = $db->count('procurements', ['approval_status' => 'Menunggu']);
            $data['stats']['item_requests']= $db->count('item_requests', ['status' => 'Menunggu Verifikasi']);

            $data['tickets_by_status'] = $db->select(
                "SELECT status, COUNT(*) AS total FROM tickets GROUP BY status ORDER BY total DESC"
            );

            $data['assets_by_category'] = $db->select(
                "SELECT c.name, COUNT(a.id) AS total
                 FROM asset_categories c
                 LEFT JOIN assets a ON a.asset_category_id = c.id
                 GROUP BY c.id, c.name
                 ORDER BY total DESC
                 LIMIT 6"
            );

            $data['maintenance_upcoming'] = $db->select(
                "SELECT * FROM maintenance_schedules
                 WHERE schedule_date >= CURDATE() AND status <> 'Selesai'
                 ORDER BY schedule_date ASC
                 LIMIT 7"
            );

            $overdue = $db->selectOne(
                "SELECT COUNT(*) AS total FROM maintenance_schedules
                 WHERE schedule_date < CURDATE() AND status <> 'Selesai'"
            );
            $data['maintenance_overdue'] = (int) ($overdue['total'] ?? 0);

            $data['recent_tickets'] = $db->select(
                "SELECT * FROM tickets ORDER BY created_at DESC LIMIT 5"
            );

            $data['recent_activity'] = $db->select(
                "SELECT * FROM audit_logs ORDER BY id DESC LIMIT 8"
            );

            $calendarRows = $db->select(
                "SELECT schedule_date, COUNT(*) AS total
                 FROM maintenance_schedules
                 WHERE YEAR(schedule_date) = YEAR(CURDATE())
                   AND MONTH(schedule_date) = MONTH(CURDATE())
                 GROUP BY schedule_date"
            );
            $data['calendar'] = $this->buildCalendar($calendarRows);
        } catch (\Throwable $e) {
            // Database belum siap; kembalikan data kosong.
        }

        return $data;
    }

    private function buildCalendar(array $rows): array
    {
        $year  = (int) date('Y');
        $month = (int) date('n');
        $first = mktime(0, 0, 0, $month, 1, $year);
        $daysInMonth = (int) date('t', $first);
        $leading = ((int) date('N', $first)) - 1;

        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $counts = [];
        foreach ($rows as $row) {
            $day = (int) date('j', strtotime($row['schedule_date']));
            $counts[$day] = (int) $row['total'];
        }

        $days = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $days[] = ['day' => $d, 'count' => $counts[$d] ?? 0];
        }

        return [
            'year'       => $year,
            'month_name' => $months[$month - 1],
            'leading'    => $leading,
            'days'       => $days,
            'today'      => (int) date('j'),
        ];
    }
}