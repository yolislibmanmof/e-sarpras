<?php

declare(strict_types=1);

namespace App\Controllers\PublicSite;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class BorrowController extends Controller
{
    private const BORROWER_TYPES = ['Mahasiswa', 'Dosen', 'Tendik', 'Unit/Prodi'];

    public function landing(): void
    {
        $db = $this->db();
        $code   = Request::input('code', '');
        $result = null;

        if ($code !== '') {
            $borrow = $db->selectOne('SELECT * FROM borrow_requests WHERE borrow_code = ?', [$code]);
            if ($borrow !== null) {
                $result = ['type' => 'Peminjaman Barang', 'code' => $borrow['borrow_code'], 'title' => $borrow['event_name'] ?? $borrow['purpose'], 'status' => $borrow['status'], 'approval' => $borrow['approval_status'], 'date' => $borrow['borrow_date']];
            } else {
                $booking = $db->selectOne("SELECT rb.*, r.name AS room_name FROM room_bookings rb LEFT JOIN rooms r ON r.id = rb.room_id WHERE rb.booking_code = ?", [$code]);
                if ($booking !== null) {
                    $result = ['type' => 'Peminjaman Ruangan', 'code' => $booking['booking_code'], 'title' => $booking['activity_name'] . ' (' . ($booking['room_name'] ?? '-') . ')', 'status' => $booking['status'], 'approval' => $booking['approval_status'], 'date' => $booking['start_at']];
                }
            }
        }

        $this->view('public/pages/borrow_landing', [
            'title'  => 'Peminjaman',
            'code'   => $code,
            'result' => $result,
            'stats'  => [
                'borrows'   => $db->count('borrow_requests'),
                'bookings'  => $db->count('room_bookings'),
                'approved'  => $db->count('borrow_requests', ['approval_status' => 'Disetujui']) + $db->count('room_bookings', ['approval_status' => 'Disetujui']),
            ],
        ], 'public/layouts/main');
    }

    public function borrowForm(): void
    {
        $this->view('public/pages/borrow_form', [
            'title'         => 'Peminjaman Barang',
            'borrowerTypes' => self::BORROWER_TYPES,
            'assets'        => $this->db()->select("SELECT id, code, name FROM assets WHERE is_borrowable = 1 AND status = 'Aktif' ORDER BY name"),
            'available'     => $this->db()->select("SELECT code, name FROM assets WHERE is_borrowable = 1 AND status = 'Aktif' ORDER BY id DESC LIMIT 6"),
        ], 'public/layouts/main');
    }

    public function storeBorrow(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'borrower_name' => 'required|max:191',
            'phone'         => 'required|max:50',
            'purpose'       => 'required|min:10',
            'borrow_date'   => 'required|date',
            'expected_return_date' => 'required|date',
        ]);

        $items = [];
        foreach ($_POST['items'] ?? [] as $row) {
            $assetId = (int) ($row['asset_id'] ?? 0);
            $qty     = (int) ($row['quantity'] ?? 0);
            if ($assetId > 0 && $qty > 0) { $items[] = ['asset_id' => $assetId, 'quantity' => $qty]; }
        }
        if ($items === []) { $errors['items'] = ['Pilih minimal satu barang yang akan dipinjam.']; }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(base_url('/peminjaman/barang'));
        }

        $code = $this->nextCode('borrow_requests', 'borrow_code', 'PJM');
        $borrowId = $this->db()->insert('borrow_requests', [
            'borrow_code'          => $code,
            'borrower_type'        => in_array($data['borrower_type'] ?? '', self::BORROWER_TYPES, true) ? $data['borrower_type'] : 'Mahasiswa',
            'borrower_name'        => $data['borrower_name'],
            'unit_name'            => $data['unit_name'] ?? null,
            'phone'                => $data['phone'],
            'email'                => $data['email'] ?? null,
            'purpose'              => $data['purpose'],
            'event_name'           => $data['event_name'] ?? null,
            'location'             => $data['location'] ?? null,
            'borrow_date'          => $data['borrow_date'],
            'expected_return_date' => $data['expected_return_date'],
            'status'               => 'Menunggu Verifikasi',
            'approval_status'      => 'Menunggu',
        ]);

        foreach ($items as $item) {
            $asset = $this->db()->selectOne('SELECT * FROM assets WHERE id = ?', [$item['asset_id']]);
            if ($asset === null) { continue; }
            $this->db()->insert('borrow_items', [
                'borrow_request_id' => $borrowId,
                'asset_id'          => $item['asset_id'],
                'item_name'         => $asset['name'],
                'quantity'          => $item['quantity'],
            ]);
        }

        AuditLogger::log('borrow.create', 'borrow', $borrowId, null, ['borrow_code' => $code]);
        Session::flash('success', 'Pengajuan peminjaman diterima. Kode: ' . $code);
        $this->redirect(base_url('/peminjaman?code=' . urlencode($code)));
    }

    public function bookingForm(): void
    {
        $this->view('public/pages/booking_form', [
            'title'         => 'Peminjaman Ruangan',
            'borrowerTypes' => self::BORROWER_TYPES,
            'rooms'         => $this->db()->select("SELECT id, code, name, capacity FROM rooms WHERE status = 'Aktif' ORDER BY name"),
            'upcoming'      => $this->db()->select(
                "SELECT rb.activity_name, rb.start_at, r.name AS room_name
                 FROM room_bookings rb LEFT JOIN rooms r ON r.id = rb.room_id
                 WHERE rb.status = 'Disetujui' AND rb.start_at >= NOW()
                 ORDER BY rb.start_at ASC LIMIT 5"
            ),
        ], 'public/layouts/main');
    }

    public function storeBooking(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'borrower_name' => 'required|max:191',
            'phone'         => 'required|max:50',
            'room_id'       => 'required|numeric',
            'activity_name' => 'required|max:255',
            'start_at'      => 'required|date',
            'end_at'        => 'required|date',
            'participant_count' => 'numeric',
        ]);

        if ($errors === [] && strtotime($data['end_at']) <= strtotime($data['start_at'])) {
            $errors['end_at'] = ['Waktu selesai harus setelah waktu mulai.'];
        }

        if ($errors === []) {
            $overlap = $this->db()->selectOne(
                "SELECT id FROM room_bookings WHERE room_id = ? AND status NOT IN ('Ditolak') AND start_at < ? AND end_at > ? LIMIT 1",
                [(int) $data['room_id'], $data['end_at'], $data['start_at']]
            );
            if ($overlap !== null) { $errors['start_at'] = ['Ruangan sudah dipinjam pada rentang waktu tersebut.']; }
        }

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(base_url('/peminjaman/ruangan'));
        }

        $code = $this->nextCode('room_bookings', 'booking_code', 'RBM');
        $bookingId = $this->db()->insert('room_bookings', [
            'booking_code'      => $code,
            'room_id'           => (int) $data['room_id'],
            'borrower_type'     => in_array($data['borrower_type'] ?? '', self::BORROWER_TYPES, true) ? $data['borrower_type'] : 'Mahasiswa',
            'borrower_name'     => $data['borrower_name'],
            'unit_name'         => $data['unit_name'] ?? null,
            'activity_name'     => $data['activity_name'],
            'start_at'          => $data['start_at'],
            'end_at'            => $data['end_at'],
            'participant_count' => (int) ($data['participant_count'] ?? 0),
            'facilities_needed' => $data['facilities_needed'] ?? null,
            'status'            => 'Menunggu Verifikasi',
            'approval_status'   => 'Menunggu',
        ]);

        AuditLogger::log('room_booking.create', 'room_booking', $bookingId, null, ['booking_code' => $code]);
        Session::flash('success', 'Pengajuan peminjaman ruangan diterima. Kode: ' . $code);
        $this->redirect(base_url('/peminjaman?code=' . urlencode($code)));
    }

    private function nextCode(string $table, string $column, string $prefix): string
    {
        $count = $this->db()->count($table);
        do {
            $count++;
            $code = $prefix . '-' . date('Ymd') . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
        } while ($this->db()->selectOne("SELECT id FROM `{$table}` WHERE `{$column}` = ?", [$code]) !== null);
        return $code;
    }
}