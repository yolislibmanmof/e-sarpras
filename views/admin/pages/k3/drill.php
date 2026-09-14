<div class="page-head">
    <h1>Simulasi Bencana</h1>
    <p>Dokumentasi kegiatan mitigasi dan tanggap darurat.</p>
</div>

<div class="grid-2">
    <div class="table-card">
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Jenis</th><th>Tanggal</th><th>Lokasi</th><th>Peserta</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($rows === []): ?>
                        <tr><td colspan="5" class="empty-note">Belum ada simulasi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= e($row['drill_type']); ?></td>
                                <td><?= e(format_tanggal($row['drill_date'])); ?></td>
                                <td><?= e($row['location'] ?? '-'); ?></td>
                                <td><?= (int) $row['participant_count']; ?></td>
                                <td>
                                    <?php if (can('k3.delete')): ?>
                                        <form method="POST" action="<?= admin_url('/k3/simulasi/' . $row['id'] . '/hapus'); ?>" data-confirm="Hapus catatan ini?">
                                            <?= csrf_field(); ?>
                                            <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Catat Simulasi</h3>
        <form method="POST" action="<?= admin_url('/k3/simulasi'); ?>">
            <?= csrf_field(); ?>
            <div class="form-grid">
                <div>
                    <label for="drill_type">Jenis Simulasi *</label>
                    <select id="drill_type" name="drill_type" required>
                        <?php foreach (['Kebakaran', 'Gempa Bumi', 'Evakuasi Darurat', 'Tumpahan Bahan Kimia', 'Banjir'] as $option): ?>
                            <option value="<?= e($option); ?>"><?= e($option); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label for="drill_date">Tanggal</label><input id="drill_date" name="drill_date" type="date"></div>
                <div><label for="location">Lokasi</label><input id="location" name="location"></div>
                <div><label for="participant_count">Jumlah Peserta</label><input id="participant_count" name="participant_count" type="number" value="0"></div>
                <div class="form-full"><label for="result">Hasil Evaluasi</label><textarea id="result" name="result" rows="2"></textarea></div>
            </div>
            <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Simpan</button></div>
        </form>
    </div>
</div>