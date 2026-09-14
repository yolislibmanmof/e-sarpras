<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>

<div class="page-head">
    <h1>Buat Surat Keluar</h1>
    <p>Surat dibuat dari template resmi dengan nomor otomatis, kop kampus, dan blok tanda tangan Kepala Sarpras.</p>
</div>

<?php require base_path('views/admin/components/form_errors.php'); ?>

<div class="form-card">
    <form method="POST" action="<?= admin_url('/surat/keluar'); ?>">
        <?= csrf_field(); ?>

        <div class="form-grid">
            <div>
                <label for="template_id">Template Surat *</label>
                <select id="template_id" name="template_id" required>
                    <option value="">Pilih template</option>
                    <?php foreach ($templates as $template): ?>
                        <option value="<?= (int) $template['id']; ?>" <?= (string) ($old['template_id'] ?? '') === (string) $template['id'] ? 'selected' : ''; ?>><?= e($template['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="attachment_note">Lampiran</label>
                <input id="attachment_note" name="attachment_note" value="<?= e($old['attachment_note'] ?? '-'); ?>" placeholder="Contoh: 1 berkas">
            </div>

            <div>
                <label for="recipient">Penerima *</label>
                <input id="recipient" name="recipient" value="<?= e($old['recipient'] ?? ''); ?>" required>
            </div>
            <div>
                <label for="recipient_unit">Unit Penerima</label>
                <input id="recipient_unit" name="recipient_unit" value="<?= e($old['recipient_unit'] ?? ''); ?>">
            </div>

            <div class="form-full">
                <label for="subject">Perihal *</label>
                <input id="subject" name="subject" value="<?= e($old['subject'] ?? ''); ?>" required>
            </div>

            <div class="form-full">
                <label for="body">Isi Surat * (akan dimasukkan ke badan template)</label>
                <textarea id="body" name="body" rows="5" required><?= e($old['body'] ?? ''); ?></textarea>
            </div>

            <div>
                <label for="party_first">Pihak Pertama (khusus BAST)</label>
                <input id="party_first" name="party_first" value="<?= e($old['party_first'] ?? ''); ?>">
            </div>
            <div>
                <label for="party_second">Pihak Kedua (khusus BAST)</label>
                <input id="party_second" name="party_second" value="<?= e($old['party_second'] ?? ''); ?>">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Buat Surat</button>
            <a class="btn btn-secondary" href="<?= admin_url('/surat'); ?>">Kembali</a>
        </div>
    </form>
</div>