<div class="page-head">
    <h1><?= e($survey['title']); ?></h1>
    <p><?= e($survey['description'] ?? ''); ?></p>
</div>

<div class="grid-2">
    <div class="card">
        <h3 class="card-title">Pertanyaan</h3>
        <?php if ($questions === []): ?>
            <p class="empty-note">Belum ada pertanyaan.</p>
        <?php else: ?>
            <ul class="list-plain">
                <?php foreach ($questions as $question): ?>
                    <li>
                        <span><?= e($question['question']); ?> (<?= $question['question_type'] === 'rating' ? 'Rating' : 'Teks'; ?>)</span>
                        <?php if (can('survey.update')): ?>
                            <form method="POST" action="<?= admin_url('/pertanyaan/' . $question['id'] . '/hapus'); ?>" data-confirm="Hapus pertanyaan ini?">
                                <?= csrf_field(); ?>
                                <button class="btn btn-danger btn-sm" type="submit">Hapus</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (can('survey.update')): ?>
            <h4 class="sub-title">Tambah Pertanyaan</h4>
            <form method="POST" action="<?= admin_url('/survei/' . $survey['id'] . '/pertanyaan'); ?>">
                <?= csrf_field(); ?>
                <label for="question">Pertanyaan *</label>
                <input id="question" name="question" required>
                <div class="form-grid">
                    <div>
                        <label for="question_type">Jenis</label>
                        <select id="question_type" name="question_type">
                            <option value="rating">Rating 1-5</option>
                            <option value="text">Teks / Komentar</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_order">Urutan</label>
                        <input id="sort_order" name="sort_order" type="number" value="0">
                    </div>
                </div>
                <div class="checkbox-row">
                    <input type="checkbox" id="is_required" name="is_required" checked>
                    <label for="is_required">Wajib diisi</label>
                </div>
                <div class="form-actions"><button class="btn btn-primary btn-sm" type="submit">Tambah</button></div>
            </form>
        <?php endif; ?>
    </div>

    <div class="card">
        <h3 class="card-title">Rekap Hasil</h3>
        <?php if ($questions === []): ?>
            <p class="empty-note">Belum ada data untuk direkap.</p>
        <?php else: ?>
            <?php foreach ($questions as $question): ?>
                <h4 class="sub-title"><?= e($question['question']); ?></h4>
                <?php $r = $recap[$question['id']] ?? null; ?>
                <?php if ($r !== null && $r['type'] === 'rating'): ?>
                    <p><strong><?= e($r['avg']); ?> / 5</strong> dari <?= (int) $r['total']; ?> respons</p>
                    <div class="bar-track" style="margin-bottom:.8rem;">
                        <div class="bar-fill" style="width: <?= (int) round($r['avg'] / 5 * 100); ?>%"></div>
                    </div>
                <?php elseif ($r !== null): ?>
                    <?php if ($r['answers'] === []): ?>
                        <p class="empty-note">Belum ada komentar.</p>
                    <?php else: ?>
                        <ul class="list-plain">
                            <?php foreach ($r['answers'] as $answer): ?>
                                <li>
                                    <span><?= e($answer['answer_text']); ?></span>
                                    <span><?= e($answer['respondent_name'] ?? 'Anonim'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>