<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;
use App\Security\Auth;

class DispositionController extends Controller
{
    public function fill(string $id): void
    {
        $disposition = $this->db()->selectOne('SELECT * FROM dispositions WHERE id = ?', [$id]);
        if ($disposition === null) {
            Session::flash('error', 'Disposisi tidak ditemukan.');
            $this->redirect(admin_url('/surat'));
        }

        if (!Auth::hasRole('pimpinan') && !Auth::hasRole('dekan') && !Auth::can('disposition.update')) {
            Session::flash('error', 'Hanya pimpinan yang dapat mengisi disposisi.');
            $this->redirect(admin_url('/surat/' . $disposition['letter_id']));
        }

        $instruction = trim((string) Request::input('instruction', ''));
        if ($instruction === '') {
            Session::flash('error', 'Isi disposisi wajib diisi.');
            $this->redirect(admin_url('/surat/' . $disposition['letter_id']));
        }

        $this->db()->update('dispositions', [
            'instruction'      => $instruction,
            'status'           => 'Didisposisi',
            'disposition_date' => date('Y-m-d'),
        ], ['id' => $id]);

        $this->db()->update('letters', ['status' => 'Didisposisi'], ['id' => $disposition['letter_id']]);

        AuditLogger::log('disposition.fill', 'letter', $disposition['letter_id']);
        Session::flash('success', 'Disposisi telah diisi dan dikembalikan ke Sarpras.');
        $this->redirect(admin_url('/surat/' . $disposition['letter_id']));
    }
}