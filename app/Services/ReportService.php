<?php

declare(strict_types=1);

namespace App\Services;

class ReportService
{
    public const TYPES = [
        'assets'        => 'Inventaris Aset',
        'tickets'       => 'Tiket Kerusakan',
        'maintenance'   => 'Pemeliharaan',
        'borrows'       => 'Peminjaman Barang',
        'room_bookings' => 'Peminjaman Ruangan',
        'utilization'   => 'Utilitas Ruangan',
        'apar'          => 'K3L - APAR',
        'waste'         => 'K3L - Limbah',
        'surveys'       => 'Survei Kepuasan',
    ];

    public function build(string $type, string $from, string $to): array
    {
        $db = db();

        switch ($type) {
            case 'tickets':
                [$filter, $params] = $this->dateFilter('DATE(t.created_at)', $from, $to);
                return [
                    'title'   => 'Laporan Tiket Kerusakan',
                    'headers' => ['Kode', 'Judul', 'Kategori', 'Prioritas', 'Status', 'Pelapor', 'Tanggal', 'Selesai'],
                    'rows'    => $db->select(
                        "SELECT t.ticket_code, t.title, t.category, t.priority, t.status, t.reporter_name,
                                t.created_at, t.completed_at
                         FROM tickets t {$filter} ORDER BY t.id",
                        $params
                    ),
                ];

            case 'maintenance':
                [$filter, $params] = $this->dateFilter('ml.action_date', $from, $to);
                return [
                    'title'   => 'Laporan Pemeliharaan',
                    'headers' => ['Tanggal', 'Objek', 'Jenis', 'Petugas', 'Biaya', 'Catatan'],
                    'rows'    => $db->select(
                        "SELECT ml.action_date,
                                COALESCE(a.name, r.name, '-') AS object_name,
                                ml.maintenance_type,
                                COALESCE(t.name, v.name, '-') AS officer,
                                ml.cost, ml.notes
                         FROM maintenance_logs ml
                         LEFT JOIN assets a ON a.id = ml.asset_id
                         LEFT JOIN rooms r ON r.id = ml.room_id
                         LEFT JOIN technicians t ON t.id = ml.technician_id
                         LEFT JOIN vendors v ON v.id = ml.vendor_id
                         {$filter} ORDER BY ml.id",
                        $params
                    ),
                ];

            case 'borrows':
                [$filter, $params] = $this->dateFilter('DATE(br.borrow_date)', $from, $to);
                return [
                    'title'   => 'Laporan Peminjaman Barang',
                    'headers' => ['Kode', 'Peminjam', 'Status Peminjam', 'Tanggal Pinjam', 'Rencana Kembali', 'Status', 'Persetujuan'],
                    'rows'    => $db->select(
                        "SELECT br.borrow_code, br.borrower_name, br.borrower_type, br.borrow_date,
                                br.expected_return_date, br.status, br.approval_status
                         FROM borrow_requests br {$filter} ORDER BY br.id",
                        $params
                    ),
                ];

            case 'room_bookings':
                [$filter, $params] = $this->dateFilter('DATE(rb.start_at)', $from, $to);
                return [
                    'title'   => 'Laporan Peminjaman Ruangan',
                    'headers' => ['Kode', 'Kegiatan', 'Ruangan', 'Mulai', 'Selesai', 'Peserta', 'Status'],
                    'rows'    => $db->select(
                        "SELECT rb.booking_code, rb.activity_name, r.name AS room_name, rb.start_at, rb.end_at,
                                rb.participant_count, rb.status
                         FROM room_bookings rb
                         LEFT JOIN rooms r ON r.id = rb.room_id
                         {$filter} ORDER BY rb.id",
                        $params
                    ),
                ];

            case 'utilization':
                [$filter, $params] = $this->dateFilter('DATE(ul.start_at)', $from, $to);
                return [
                    'title'   => 'Laporan Utilitas Ruangan',
                    'headers' => ['Ruangan', 'Kegiatan', 'Mulai', 'Selesai', 'Peserta', 'Sumber'],
                    'rows'    => $db->select(
                        "SELECT r.name AS room_name, ul.activity_name, ul.start_at, ul.end_at,
                                ul.participant_count, ul.source_type
                         FROM utilization_logs ul
                         LEFT JOIN rooms r ON r.id = ul.room_id
                         {$filter} ORDER BY ul.id",
                        $params
                    ),
                ];

            case 'apar':
                [$filter, $params] = $this->dateFilter('ap.expiry_date', $from, $to);
                return [
                    'title'   => 'Laporan K3L - APAR',
                    'headers' => ['Kode', 'Lokasi', 'Jenis', 'Kapasitas', 'Kedaluwarsa', 'Status'],
                    'rows'    => $db->select(
                        "SELECT ap.code, COALESCE(ap.location, b.name, '-') AS location, ap.apar_type,
                                ap.capacity, ap.expiry_date, ap.status
                         FROM apar ap
                         LEFT JOIN buildings b ON b.id = ap.building_id
                         {$filter} ORDER BY ap.expiry_date",
                        $params
                    ),
                ];

            case 'waste':
                [$filter, $params] = $this->dateFilter('wl.handling_date', $from, $to);
                return [
                    'title'   => 'Laporan K3L - Limbah',
                    'headers' => ['Jenis', 'Sumber', 'Volume', 'Metode', 'Tanggal', 'Vendor'],
                    'rows'    => $db->select(
                        "SELECT wl.waste_type, wl.source, wl.volume, wl.handling_method, wl.handling_date, wl.vendor_name
                         FROM waste_logs wl {$filter} ORDER BY wl.id",
                        $params
                    ),
                ];

            case 'surveys':
                return [
                    'title'   => 'Laporan Survei Kepuasan',
                    'headers' => ['Survei', 'Jumlah Respons', 'Rata-rata Rating'],
                    'rows'    => $db->select(
                        "SELECT s.title,
                                (SELECT COUNT(*) FROM survey_responses sr WHERE sr.survey_id = s.id) AS response_count,
                                (SELECT ROUND(AVG(sa.rating_value), 2)
                                 FROM survey_answers sa
                                 JOIN survey_questions sq ON sq.id = sa.survey_question_id
                                 WHERE sq.survey_id = s.id AND sa.rating_value IS NOT NULL) AS avg_rating
                         FROM surveys s ORDER BY s.id"
                    ),
                ];

            case 'assets':
            default:
                [$filter, $params] = $this->dateFilter('a.acquisition_date', $from, $to);
                return [
                    'title'   => 'Laporan Inventaris Aset',
                    'headers' => ['Kode', 'Nama', 'Kategori', 'Gedung', 'Ruangan', 'Kondisi', 'Status', 'Nilai Perolehan', 'Tanggal Pengadaan'],
                    'rows'    => $db->select(
                        "SELECT a.code, a.name, c.name AS category_name, b.name AS building_name, r.name AS room_name,
                                a.condition, a.status, a.acquisition_value, a.acquisition_date
                         FROM assets a
                         LEFT JOIN asset_categories c ON c.id = a.asset_category_id
                         LEFT JOIN buildings b ON b.id = a.building_id
                         LEFT JOIN rooms r ON r.id = a.room_id
                         {$filter} ORDER BY a.code",
                        $params
                    ),
                ];
        }
    }

    private function dateFilter(string $column, string $from, string $to): array
    {
        if ($from === '' && $to === '') {
            return ['', []];
        }

        $clauses = [];
        $params  = [];
        if ($from !== '') { $clauses[] = $column . ' >= ?'; $params[] = $from; }
        if ($to !== '') { $clauses[] = $column . ' <= ?'; $params[] = $to; }

        return ['WHERE ' . implode(' AND ', $clauses), $params];
    }
}