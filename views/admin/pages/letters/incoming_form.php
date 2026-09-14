<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>

<div class="page-head">
    <h1>Registrasi Surat Masuk</h1>
    <p>Catat surat yang diterima dari unit internal maupun pihak eksternal.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= admin_url('/surat/masuk'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="letter_number">Nomor Surat</label>
                <input id="letter_number" name="letter_number" value="<?= e($old['letter_number'] ?? ''); ?>">
            </div>
            <div>
                <label for="letter_date">Tanggal Surat</label>
                <input id="letter_date" name="letter_date" type="date" value="<?= e($old['letter_date'] ?? ''); ?>">
            </div>

            <div>
                <label for="sender_name">Pengirim *</label>
                <input id="sender_name" name="sender_name" value="<?= e($old['sender_name'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="sender_unit">Unit / Instansi Pengirim</label>
                <input id="sender_unit" name="sender_unit" value="<?= e($old['sender_unit'] ?? ''); ?>">
            </div>

            <div>
                <label for="letter_type">Jenis Surat</label>
                <select id="letter_type" name="letter_type">
                    <?php foreach (['Permohonan', 'Laporan', 'Undangan', 'Pemberitahuan', 'Lainnya'] as $option): ?>
                        <option value="<?= e($option); ?>" <?= ($old['letter_type'] ?? 'Permohonan') === $option ? 'selected' : ''; ?>><?= e($option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="attachment">Berkas Surat (PDF/gambar)</label>
                <input id="attachment" name="attachment" type="file" accept=".pdf,.jpg,.jpeg,.png,.webp">
            </div>

            <div class="form-full">
                <label for="subject">Perihal *</label>
                <input id="subject" name="subject" value="<?= e($old['subject'] ?? ''); ?>" required>
            </div>

            <div class="form-full">
                <label for="content">Ringkasan Isi Surat</label>
                <textarea id="content" name="content" rows="3"><?= e($old['content'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan Surat Masuk</button>
            <a class="btn btn-secondary" href="<?= admin_url('/surat'); ?>">Kembali</a>
        </div>
    </form>
</div>