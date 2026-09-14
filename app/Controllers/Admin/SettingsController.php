<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Security\AuditLogger;

class SettingsController extends Controller
{
    public function index(): void
    {
        $settings = $this->db()->select('SELECT * FROM settings ORDER BY `key`');
        $map = [];
        foreach ($settings as $row) { $map[$row['key']] = $row['value']; }

        $this->adminView('admin/pages/settings/index', ['title' => 'Pengaturan', 'settings' => $map]);
    }

    public function store(): void
    {
        $data = Request::all();
        $keys = [
            'app_name', 'app_version', 'campus_name', 'campus_address',
            'campus_phone', 'campus_email', 'sarpras_head_name',
            'sarpras_head_nip', 'letter_city', 'letter_prefix', 'default_timezone',
            'max_upload_mb', 'allowed_upload_extensions', 'maintenance_alert_days',
        ];

        foreach ($keys as $key) {
            if (!isset($data[$key])) { continue; }
            $this->setSetting($key, trim((string) $data[$key]));
        }

        $logo = Request::file('logo_file');
        if ($logo !== null && ($logo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = upload_file($logo, 'settings');
            if ($result['ok']) {
                $this->setSetting('app_logo', $result['path']);
            } else {
                Session::flash('error', 'Gagal mengunggah logo: ' . $result['error']);
                $this->redirect(admin_url('/pengaturan'));
            }
        }

        $favicon = Request::file('favicon_file');
        if ($favicon !== null && ($favicon['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            $result = upload_file($favicon, 'settings');
            if ($result['ok']) {
                $this->setSetting('app_favicon', $result['path']);
            } else {
                Session::flash('error', 'Gagal mengunggah favicon: ' . $result['error']);
                $this->redirect(admin_url('/pengaturan'));
            }
        }

        AuditLogger::log('settings.update', 'settings', null);
        Session::flash('success', 'Pengaturan berhasil disimpan dan diterapkan ke seluruh laman.');
        $this->redirect(admin_url('/pengaturan'));
    }

    /** Simpan/Perbarui satu kunci pengaturan (upsert). */
    private function setSetting(string $key, string $value): void
    {
        $exists = $this->db()->selectOne('SELECT id FROM settings WHERE `key` = ?', [$key]);
        if ($exists !== null) {
            $this->db()->update('settings', ['value' => $value], ['key' => $key]);
        } else {
            $this->db()->insert('settings', ['key' => $key, 'value' => $value]);
        }
    }
}