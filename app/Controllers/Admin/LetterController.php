<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Pagination;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;
use App\Security\Auth;

class LetterController extends Controller
{
    private const ROMAN = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    public function index(): void
    {
        $q         = Request::input('q', '');
        $direction = Request::input('direction', '');
        $page      = max(1, (int) Request::input('page', 1));
        $perPage   = 10;

        $clauses = [];
        $params  = [];
        if ($q !== '') { $clauses[] = '(letter_number LIKE ? OR subject LIKE ? OR sender_name LIKE ?)'; $params[] = "%{$q}%"; $params[] = "%{$q}%"; $params[] = "%{$q}%"; }
        if ($direction !== '') { $clauses[] = 'direction = ?'; $params[] = $direction; }
        $where = $clauses === [] ? '' : 'WHERE ' . implode(' AND ', $clauses);

        $total = (int) ($this->db()->selectOne("SELECT COUNT(*) AS total FROM letters {$where}", $params)['total'] ?? 0);
        $offset = ($page - 1) * $perPage;

        $rows = $this->db()->select("SELECT * FROM letters {$where} ORDER BY id DESC LIMIT {$perPage} OFFSET {$offset}", $params);

        $query = http_build_query(array_filter(['q' => $q, 'direction' => $direction], static fn ($v) => $v !== ''));
        $base  = admin_url('/surat') . ($query !== '' ? '?' . $query : '');

        $this->adminView('admin/pages/letters/index', [
            'title'      => 'Surat & Disposisi',
            'rows'       => $rows,
            'q'          => $q,
            'direction'  => $direction,
            'pagination' => Pagination::make($total, $perPage, $page, $base),
        ]);
    }

    public function createIncoming(): void
    {
        $this->adminView('admin/pages/letters/incoming_form', ['title' => 'Registrasi Surat Masuk']);
    }

    public function storeIncoming(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'sender_name' => 'required|max:191',
            'subject'     => 'required|max:255',
        ]);

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/surat/masuk/tambah'));
        }

        $filePath = null;
        $file = Request::file('attachment');
        if ($file !== null && ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = upload_file($file, 'letters');
            if (!$result['ok']) {
                Session::flash('error', $result['error']);
                Session::flash('old', $data);
                $this->redirect(admin_url('/surat/masuk/tambah'));
            }
            $filePath = $result['path'];
        }

        $letterId = $this->db()->insert('letters', [
            'letter_number' => $data['letter_number'] ?? null,
            'letter_date'   => ($data['letter_date'] ?? '') === '' ? null : $data['letter_date'],
            'direction'     => 'incoming',
            'letter_type'   => $data['letter_type'] ?? 'Permohonan',
            'sender_name'   => $data['sender_name'],
            'sender_unit'   => $data['sender_unit'] ?? null,
            'subject'       => $data['subject'],
            'content'       => $data['content'] ?? null,
            'status'        => 'Masuk',
            'file_path'     => $filePath,
            'created_by'    => auth_id(),
        ]);

        if ($filePath !== null) {
            $this->db()->insert('letter_attachments', [
                'letter_id'   => $letterId,
                'file_path'   => $filePath,
                'caption'     => 'Berkas surat masuk',
                'uploaded_by' => auth_id(),
            ]);
        }

        AuditLogger::log('letter.incoming', 'letter', $letterId, null, ['subject' => $data['subject']]);
        Session::flash('success', 'Surat masuk berhasil diregistrasi.');
        $this->redirect(admin_url('/surat/' . $letterId));
    }

    public function createOutgoing(): void
    {
        $this->adminView('admin/pages/letters/outgoing_form', [
            'title'     => 'Buat Surat Keluar',
            'templates' => $this->db()->select("SELECT * FROM letter_templates WHERE is_active = 1 ORDER BY name"),
        ]);
    }

    public function storeOutgoing(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, [
            'template_id' => 'required|numeric',
            'recipient'   => 'required|max:191',
            'subject'     => 'required|max:255',
            'body'        => 'required|min:10',
        ]);

        if ($errors !== []) {
            Session::flash('errors', $errors);
            Session::flash('old', $data);
            $this->redirect(admin_url('/surat/keluar/tambah'));
        }

        $template = $this->db()->selectOne('SELECT * FROM letter_templates WHERE id = ?', [(int) $data['template_id']]);
        if ($template === null) {
            Session::flash('error', 'Template tidak ditemukan.');
            $this->redirect(admin_url('/surat/keluar/tambah'));
        }

        $number  = $this->nextNumber();
        $content = $this->renderTemplate($template['content'], $number, $data);

        $letterId = $this->db()->insert('letters', [
            'letter_number'  => $number,
            'letter_date'    => date('Y-m-d'),
            'direction'      => 'outgoing',
            'letter_type'    => $template['name'],
            'recipient_name' => $data['recipient'],
            'recipient_unit' => $data['recipient_unit'] ?? null,
            'subject'        => $data['subject'],
            'content'        => $content,
            'status'         => 'Terkirim',
            'created_by'     => auth_id(),
        ]);

        AuditLogger::log('letter.outgoing', 'letter', $letterId, null, ['number' => $number]);
        Session::flash('success', 'Surat keluar berhasil dibuat dengan nomor ' . $number . '.');
        $this->redirect(admin_url('/surat/' . $letterId));
    }

    public function show(string $id): void
    {
        $letter = $this->db()->selectOne('SELECT * FROM letters WHERE id = ?', [$id]);
        if ($letter === null) {
            Session::flash('error', 'Surat tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        $this->adminView('admin/pages/letters/show', [
            'title'        => 'Detail Surat',
            'letter'       => $letter,
            'attachments'  => $this->db()->select('SELECT * FROM letter_attachments WHERE letter_id = ? ORDER BY id', [$id]),
            'dispositions' => $this->db()->select(
                "SELECT d.*, uf.full_name AS from_name, ut.full_name AS to_name
                 FROM dispositions d
                 LEFT JOIN users uf ON uf.id = d.from_user_id
                 LEFT JOIN users ut ON ut.id = d.to_user_id
                 WHERE d.letter_id = ? ORDER BY d.id",
                [$id]
            ),
            'superiors' => $this->db()->select(
                "SELECT DISTINCT u.id, u.full_name, r.code AS role_code
                 FROM users u
                 JOIN user_roles ur ON ur.user_id = u.id
                 JOIN roles r ON r.id = ur.role_id
                 WHERE r.code IN ('pimpinan', 'dekan', 'super-admin-sarpras') AND u.is_active = 1
                 ORDER BY u.full_name"
            ),
            'isSuperior' => Auth::hasRole('pimpinan') || Auth::hasRole('dekan') || Auth::can('disposition.update'),
        ]);
    }

    public function file(string $id): void
    {
        $attachment = $this->db()->selectOne('SELECT * FROM letter_attachments WHERE id = ?', [$id]);
        if ($attachment === null) {
            $this->redirect(admin_url('/surat'));
        }

        $path = rtrim(config('upload.base_path'), '/') . '/' . $attachment['file_path'];
        if (!is_file($path)) {
            Session::flash('error', 'File tidak ditemukan.');
            $this->redirect(admin_url('/surat/' . $attachment['letter_id']));
        }

        $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp', 'pdf' => 'application/pdf'][$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . (string) filesize($path));
        header('Content-Disposition: inline; filename="' . basename($path) . '"');
        readfile($path);
        exit;
    }

    public function printLetter(string $id): void
    {
        $letter = $this->db()->selectOne('SELECT * FROM letters WHERE id = ?', [$id]);
        if ($letter === null) {
            Session::flash('error', 'Surat tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        $logo     = setting_value('letter_logo', '');
        $logoPath = $logo !== '' ? rtrim(config('upload.base_path'), '/') . '/' . $logo : '';

        $this->view('admin/pages/letters/print', [
            'letter'   => $letter,
            'hasLogo'  => $logoPath !== '' && is_file($logoPath),
            'logoUrl'  => $logo !== '' ? base_url('/media/' . $logo) : '',
            'kop'      => [
                'address' => setting_value('letter_address', setting_value('campus_address', '')),
                'phone'   => setting_value('letter_phone',   setting_value('campus_phone', '')),
                'email'   => setting_value('letter_email',   setting_value('campus_email', '')),
                'website' => setting_value('letter_website', ''),
            ],
        ]);
    }

    public function archive(string $id): void
    {
        $letter = $this->db()->selectOne('SELECT * FROM letters WHERE id = ?', [$id]);
        if ($letter === null) {
            Session::flash('error', 'Surat tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        $this->db()->update('letters', ['status' => 'Diarsipkan'], ['id' => $id]);
        AuditLogger::log('letter.archive', 'letter', $id);
        Session::flash('success', 'Surat telah diarsipkan.');
        $this->redirect(admin_url('/surat/' . $id));
    }

    public function sendDisposition(string $id): void
    {
        $letter = $this->db()->selectOne('SELECT * FROM letters WHERE id = ?', [$id]);
        if ($letter === null) {
            Session::flash('error', 'Surat tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        $toUserId = (int) Request::input('to_user_id', 0);
        if ($toUserId <= 0) {
            Session::flash('error', 'Pilih atasan tujuan disposisi.');
            $this->redirect(admin_url('/surat/' . $id));
        }

        $this->db()->insert('dispositions', [
            'letter_id'        => (int) $id,
            'disposition_date' => date('Y-m-d'),
            'from_user_id'     => auth_id(),
            'to_user_id'       => $toUserId,
            'content'          => Request::input('content', '') ?: 'Mohon tinjauan dan disposisi.',
            'status'           => 'Menunggu',
            'created_by'       => auth_id(),
        ]);

        $this->db()->update('letters', ['status' => 'Diteruskan ke Pimpinan'], ['id' => $id]);
        AuditLogger::log('disposition.send', 'letter', $id, null, ['to_user_id' => $toUserId]);
        Session::flash('success', 'Surat telah diteruskan ke pimpinan untuk disposisi.');
        $this->redirect(admin_url('/surat/' . $id));
    }

    public function followUp(string $id): void
    {
        $letter = $this->db()->selectOne('SELECT * FROM letters WHERE id = ?', [$id]);
        if ($letter === null) {
            Session::flash('error', 'Surat tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        $this->db()->update('letters', ['status' => 'Ditindaklanjuti'], ['id' => $id]);
        $this->db()->execute('UPDATE dispositions SET status = ? WHERE letter_id = ?', ['Ditindaklanjuti', $id]);
        AuditLogger::log('letter.follow_up', 'letter', $id);
        Session::flash('success', 'Tindak lanjut surat telah dicatat.');
        $this->redirect(admin_url('/surat/' . $id));
    }

    private function nextNumber(): string
    {
        $prefix = setting_value('letter_prefix', 'B');
        $year   = date('Y');
        $count  = (int) ($this->db()->selectOne(
            "SELECT COUNT(*) AS total FROM letters WHERE direction = 'outgoing' AND YEAR(letter_date) = ?",
            [$year]
        )['total'] ?? 0);

        do {
            $count++;
            $number = $prefix . '/' . str_pad((string) $count, 3, '0', STR_PAD_LEFT) . '/SARPRAS/' . self::ROMAN[(int) date('n') - 1] . '/' . $year;
        } while ($this->db()->selectOne('SELECT id FROM letters WHERE letter_number = ?', [$number]) !== null);

        return $number;
    }

    private function renderTemplate(string $template, string $number, array $data): string
    {
        $map = [
            '[LOGO_KAMPUS]'          => '',
            '[KOP_KAMPUS]'           => '',
            '[NOMOR_SURAT]'          => $number,
            '[LAMPIRAN]'             => $data['attachment_note'] ?? '-',
            '[PERIHAL]'              => $data['subject'],
            '[PENERIMA]'             => $data['recipient'] . (isset($data['recipient_unit']) && $data['recipient_unit'] !== '' ? ' - ' . $data['recipient_unit'] : ''),
            '[TEMPAT]'               => setting_value('letter_city', 'Kota'),
            '[TANGGAL_SURAT]'        => format_tanggal_panjang(date('Y-m-d')),
            '[HARI]'                 => format_tanggal_panjang(date('Y-m-d')),
            '[ISI_SURAT]'            => $data['body'],
            '[NAMA_PIHAK_PERTAMA]'   => $data['party_first'] ?? '-',
            '[NAMA_PIHAK_KEDUA]'     => $data['party_second'] ?? '-',
            '[PARAF_KEPALA_SARPRAS]' => '(paraf)',
            '[NAMA_KEPALA_SARPRAS]'  => setting_value('sarpras_head_name', 'Kepala Sarpras'),
            '[NIP_KEPALA_SARPRAS]'   => setting_value('sarpras_head_nip', '-'),
        ];

        return str_replace(array_keys($map), array_values($map), $template);
    }
}