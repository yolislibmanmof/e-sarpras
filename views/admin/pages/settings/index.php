<div class="page-head">
    <h1>Pengaturan Sistem</h1>
    <p>Kelola konfigurasi dasar aplikasi dan informasi institusi.</p>
</div>

<div class="form-card">
    <form method="POST" action="<?= admin_url('/pengaturan'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <h3 class="card-title">Informasi Aplikasi</h3>
        <div class="form-grid">
            <div>
                <label for="app_name">Nama Aplikasi</label>
                <input id="app_name" name="app_name" value="<?= e($settings['app_name'] ?? ''); ?>">
            </div>
            <div>
                <label for="app_version">Versi</label>
                <input id="app_version" name="app_version" value="<?= e($settings['app_version'] ?? ''); ?>">
            </div>
            <div>
                <label for="default_timezone">Zona Waktu</label>
                <select id="default_timezone" name="default_timezone">
                    <?php foreach (['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura'] as $tz): ?>
                        <option value="<?= e($tz); ?>" <?= ($settings['default_timezone'] ?? 'Asia/Jakarta') === $tz ? 'selected' : ''; ?>><?= e($tz); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="max_upload_mb">Batas Upload (MB)</label>
                <input id="max_upload_mb" name="max_upload_mb" type="number" value="<?= e($settings['max_upload_mb'] ?? '2'); ?>">
            </div>
            <div>
                <label for="logo_file">Logo Kampus (unggah baru)</label>
                <input id="logo_file" name="logo_file" type="file" accept=".png,.jpg,.jpeg">
                <?php if (!empty($settings['app_logo'])): ?>
                    <img src="<?= base_url('/media/' . $settings['app_logo']); ?>" alt="Logo" style="width:44px;height:44px;border-radius:10px;object-fit:cover;margin-top:.6rem;border:1px solid var(--border);">
                <?php endif; ?>
                <p class="file-note">Kosongkan jika tidak ingin mengubah logo.</p>
            </div>
            <div>
                <label for="favicon_file">Favicon / Icon Web (unggah baru)</label>
                <input id="favicon_file" name="favicon_file" type="file" accept=".png,.jpg,.jpeg,.ico,.svg">
                <?php if (!empty($settings['app_favicon'])): ?>
                    <img src="<?= base_url('/media/' . $settings['app_favicon']); ?>" alt="Favicon" style="width:32px;height:32px;border-radius:8px;object-fit:cover;margin-top:.6rem;border:1px solid var(--border);">
                <?php endif; ?>
                <p class="file-note">Disarankan PNG/JPG kecil (mis. 64&times;64). Bila belum diunggah, ikon bawaan &ldquo;eS&rdquo; dipakai.</p>
            </div>
        </div>

        <h3 class="card-title" style="margin-top:1.4rem;">Informasi Institusi</h3>
        <div class="form-grid">
            <div>
                <label for="campus_name">Nama Kampus</label>
                <input id="campus_name" name="campus_name" value="<?= e($settings['campus_name'] ?? ''); ?>">
            </div>
            <div>
                <label for="campus_phone">Telepon</label>
                <input id="campus_phone" name="campus_phone" value="<?= e($settings['campus_phone'] ?? ''); ?>">
            </div>
            <div class="form-full">
                <label for="campus_address">Alamat</label>
                <textarea id="campus_address" name="campus_address" rows="2"><?= e($settings['campus_address'] ?? ''); ?></textarea>
            </div>
            <div>
                <label for="campus_email">Email</label>
                <input id="campus_email" name="campus_email" type="email" value="<?= e($settings['campus_email'] ?? ''); ?>">
            </div>
        </div>

        <h3 class="card-title" style="margin-top:1.4rem;">Kepala Sarpras</h3>
        <div class="form-grid">
            <div>
                <label for="sarpras_head_name">Nama</label>
                <input id="sarpras_head_name" name="sarpras_head_name" value="<?= e($settings['sarpras_head_name'] ?? ''); ?>">
            </div>
            <div>
                <label for="sarpras_head_nip">NIP</label>
                <input id="sarpras_head_nip" name="sarpras_head_nip" value="<?= e($settings['sarpras_head_nip'] ?? ''); ?>">
            </div>
        </div>

        <h3 class="card-title" style="margin-top:1.4rem;">Pengaturan Surat</h3>
        <div class="form-grid">
            <div>
                <label for="letter_city">Kota</label>
                <input id="letter_city" name="letter_city" value="<?= e($settings['letter_city'] ?? ''); ?>">
            </div>
            <div>
                <label for="letter_prefix">Prefix Nomor Surat</label>
                <input id="letter_prefix" name="letter_prefix" value="<?= e($settings['letter_prefix'] ?? 'B'); ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </div>
    </form>
</div>