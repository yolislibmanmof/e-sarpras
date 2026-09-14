<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;
use App\Services\LetterDesign;

class LetterTemplateController extends Controller
{
    public function index(): void
    {
        $settings = $this->db()->select("SELECT * FROM settings WHERE `key` IN ('letter_logo','letter_address','letter_phone','letter_email','letter_website')");
        $header = [];
        foreach ($settings as $row) { $header[$row['key']] = $row['value']; }

        $this->adminView('admin/pages/letter_templates/index', [
            'title'  => 'Template Surat',
            'rows'   => $this->db()->select('SELECT * FROM letter_templates ORDER BY name'),
            'header' => $header,
            'd'      => LetterDesign::current(),
        ]);
    }

    public function store(): void
    {
        $data = Request::all();
        $errors = $this->validate($data, ['code' => 'required|max:100', 'name' => 'required|max:191', 'content' => 'required']);

        if ($errors !== []) {
            Session::flash('error', 'Data template tidak lengkap.');
            $this->redirect(admin_url('/template-surat'));
        }

        $id = $this->db()->insert('letter_templates', [
            'code'      => $data['code'],
            'name'      => $data['name'],
            'subject'   => $data['subject'] ?? null,
            'content'   => $data['content'],
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ]);

        AuditLogger::log('template.create', 'letter', $id);
        Session::flash('success', 'Template surat berhasil ditambahkan.');
        $this->redirect(admin_url('/template-surat'));
    }

    public function update(string $id): void
    {
        $template = $this->db()->selectOne('SELECT * FROM letter_templates WHERE id = ?', [$id]);
        if ($template === null) {
            Session::flash('error', 'Template tidak ditemukan.');
            $this->redirect(admin_url('/template-surat'));
        }

        $data = Request::all();
        $this->db()->update('letter_templates', [
            'name'      => $data['name'] ?? $template['name'],
            'subject'   => $data['subject'] ?? $template['subject'],
            'content'   => $data['content'] ?? $template['content'],
            'is_active' => isset($data['is_active']) ? 1 : 0,
        ], ['id' => $id]);

        AuditLogger::log('template.update', 'letter', $id);
        Session::flash('success', 'Template surat berhasil diperbarui.');
        $this->redirect(admin_url('/template-surat'));
    }

    public function destroy(string $id): void
    {
        $this->db()->delete('letter_templates', ['id' => $id]);
        AuditLogger::log('template.delete', 'letter', $id);
        Session::flash('success', 'Template surat berhasil dihapus.');
        $this->redirect(admin_url('/template-surat'));
    }

    /** Kelola kop surat resmi + desain surat (disimpan sebagai JSON). */
    public function storeHeader(): void
    {
        $data = Request::all();
        $fields = [
            'letter_address' => $data['letter_address'] ?? '',
            'letter_phone'   => $data['letter_phone']   ?? '',
            'letter_email'   => $data['letter_email']   ?? '',
            'letter_website' => $data['letter_website'] ?? '',
        ];

        foreach ($fields as $key => $value) {
            $exists = $this->db()->selectOne('SELECT id FROM settings WHERE `key` = ?', [$key]);
            if ($exists !== null) {
                $this->db()->update('settings', ['value' => trim($value)], ['key' => $key]);
            } else {
                $this->db()->insert('settings', ['key' => $key, 'value' => trim($value)]);
            }
        }

        $logo = Request::file('letter_logo');
        if ($logo !== null && ($logo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = upload_file($logo, 'settings');
            if ($result['ok']) {
                $exists = $this->db()->selectOne("SELECT id FROM settings WHERE `key` = 'letter_logo'");
                if ($exists !== null) {
                    $this->db()->update('settings', ['value' => $result['path']], ['key' => 'letter_logo']);
                } else {
                    $this->db()->insert('settings', ['key' => 'letter_logo', 'value' => $result['path']]);
                }
            } else {
                Session::flash('error', 'Gagal mengunggah logo surat: ' . $result['error']);
                $this->redirect(admin_url('/template-surat'));
            }
        }

        /* Simpan desain surat (JSON) */
        $design = $data['d'] ?? [];
        if (is_array($design) && $design !== []) {
            $allowed = array_keys(LetterDesign::defaults());
            $clean = [];
            foreach ($design as $k => $v) {
                if (in_array($k, $allowed, true)) { $clean[$k] = is_string($v) ? trim($v) : $v; }
            }
            $json = json_encode($clean, JSON_UNESCAPED_UNICODE);
            $exists = $this->db()->selectOne("SELECT id FROM settings WHERE `key` = 'letter_design'");
            if ($exists !== null) {
                $this->db()->update('settings', ['value' => $json], ['key' => 'letter_design']);
            } else {
                $this->db()->insert('settings', ['key' => 'letter_design', 'value' => $json]);
            }
        }

        AuditLogger::log('template.header.update', 'letter', null);
        Session::flash('success', 'Kop dan desain surat berhasil disimpan; seluruh cetakan surat kini memakai desain baru.');
        $this->redirect(admin_url('/template-surat'));
    }
}