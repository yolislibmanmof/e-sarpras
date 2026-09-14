<?php

declare(strict_types=1);

if (!function_exists('upload_file')) {
    /**
     * Mengunggah file dengan validasi ketat ke storage/uploads/{folder}.
     *
     * @return array{ok: bool, path: ?string, error: ?string}
     */
    function upload_file(array $file, string $folder): array
    {
        $maxMb   = (int) config('upload.max_size_mb', 2);
        $allowed = config('upload.allowed_extensions', []);

        if (!isset($file['error']) || is_array($file['error'])) {
            return ['ok' => false, 'path' => null, 'error' => 'File tidak valid.'];
        }

        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['ok' => false, 'path' => null, 'error' => 'Tidak ada file yang diunggah.'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['ok' => false, 'path' => null, 'error' => 'Gagal mengunggah file.'];
        }

        if ($file['size'] > $maxMb * 1024 * 1024) {
            return ['ok' => false, 'path' => null, 'error' => 'Ukuran file maksimal ' . $maxMb . ' MB.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            return ['ok' => false, 'path' => null, 'error' => 'Jenis file tidak diizinkan.'];
        }

        $mimeAllowed = [
            'jpg'  => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png'  => ['image/png'],
            'webp' => ['image/webp'],
            'pdf'  => ['application/pdf'],
            'doc'  => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'xls'  => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (isset($mimeAllowed[$ext]) && !in_array($mime, $mimeAllowed[$ext], true)) {
            return ['ok' => false, 'path' => null, 'error' => 'Isi file tidak sesuai dengan ekstensinya.'];
        }

        $dir = rtrim(config('upload.base_path'), '/') . '/' . trim($folder, '/');
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $name = safe_filename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
            return ['ok' => false, 'path' => null, 'error' => 'Gagal menyimpan file.'];
        }

        return ['ok' => true, 'path' => trim($folder, '/') . '/' . $name, 'error' => null];
    }
}