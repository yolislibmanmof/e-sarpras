<div class="page-head">
    <h1>Peta Kampus (Denah SVG)</h1>
    <p>Unggah denah SVG per gedung &amp; lantai. Beri atribut <code>data-room="KODE_RUANGAN"</code> pada elemen agar dapat diklik publik.</p>
</div>

<div class="form-card">
    <h3 class="card-title">Unggah / Perbarui Denah</h3>
    <form method="POST" action="<?= admin_url('/peta-kampus'); ?>" enctype="multipart/form-data">
        <?= csrf_field(); ?>
        <div class="form-grid">
            <div>
                <label for="building_id">Gedung *</label>
                <select id="building_id" name="building_id" required>
                    <option value="">Pilih gedung</option>
                    <?php foreach ($buildings as $b): ?>
                        <option value="<?= (int) $b['id']; ?>"><?= e($b['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="floor_level">Lantai *</label>
                <input id="floor_level" name="floor_level" type="number" min="1" value="1" required>
            </div>
            <div class="form-full">
                <label for="svg_file">Berkas SVG *</label>
                <input id="svg_file" name="svg_file" type="file" accept=".svg" required>
                <p class="file-note">Contoh elemen klikabel: <code>&lt;rect data-room="R-101" .../&gt;</code></p>
            </div>
        </div>
        <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan Denah</button></div>
    </form>
</div>

<div class="table-card">
    <div class="table-wrap">
        <table class="data-table">
            <thead><tr><th>Gedung</th><th>Lantai</th><th>Diperbarui</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php if ($maps === []): ?>
                    <tr><td colspan="4" class="empty-note">Belum ada denah tersimpan.</td></tr>
                <?php else: ?>
                    <?php foreach ($maps as $m): ?>
                        <tr>
                            <td><?= e($m['building_name'] ?? '-'); ?></td>
                            <td>L<?= (int) $m['floor_level']; ?></td>
                            <td><?= e($m['updated_at']); ?></td>
                            <td>
                                <form method="POST" action="<?= admin_url('/peta-kampus/' . $m['id'] . '/hapus'); ?>" data-confirm="Hapus denah ini?">
                                    <?= csrf_field(); ?>
                                    <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>