<?php require base_path('views/public/partials/wow_style.php'); ?>
<?php $pctRequired = $totalCount > 0 ? (int) round($requiredCount / $totalCount * 100) : 0; ?>

<section class="sub-hero">
    <div class="hero-inner"></div>
    <div class="orb-field"><span class="orb o1"></span><span class="orb o2"></span><span class="orb o3"></span><span class="orb o4"></span><span class="orb o5"></span></div>
    <div class="container">
        <span class="crumb"><a href="<?= base_url('/survei'); ?>">Survei</a> &rarr; <?= e($survey['title']); ?></span>
        <h1><?= e($survey['title']); ?></h1>
        <p><?= e($survey['description'] ?? ''); ?></p>
    </div>
</section>

<section class="section page-body">
    <div class="container split-grid">
        <div class="public-form-card shine">
            <form method="POST" action="<?= base_url('/survei/' . $survey['slug']); ?>">
                <?= csrf_field(); ?>
                <div class="form-grid">
                    <div><label for="respondent_name">Nama (opsional)</label><input id="respondent_name" name="respondent_name"></div>
                    <div>
                        <label for="respondent_type">Status</label>
                        <select id="respondent_type" name="respondent_type">
                            <option value="">Anonim</option>
                            <?php foreach (['Mahasiswa', 'Dosen', 'Tendik'] as $type): ?>
                                <option value="<?= e($type); ?>"><?= e($type); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <?php foreach ($questions as $question): ?>
                    <div class="survey-question">
                        <label><?= e($question['question']); ?> <?= (int) $question['is_required'] === 1 ? ' *' : ''; ?></label>
                        <?php if ($question['question_type'] === 'rating'): ?>
                            <div class="rating-row">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <label class="rating-option">
                                        <input type="radio" name="answers[<?= (int) $question['id']; ?>]" value="<?= $i; ?>" <?= (int) $question['is_required'] === 1 ? 'required' : ''; ?>>
                                        <span><?= $i; ?></span>
                                    </label>
                                <?php endfor; ?>
                            </div>
                        <?php else: ?>
                            <textarea name="answers[<?= (int) $question['id']; ?>]" rows="3" <?= (int) $question['is_required'] === 1 ? 'required' : ''; ?>></textarea>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Kirim Tanggapan</button>
                    <a class="btn btn-outline" href="<?= base_url('/survei'); ?>">Kembali</a>
                </div>
            </form>
        </div>

        <aside class="side-panel">
            <div class="card info-card shine">
                <h4>Ringkasan Survei</h4>
                <ul class="mini-steps">
                    <li><b>&#9632;</b> Total pertanyaan: <strong><?= (int) $totalCount; ?></strong></li>
                    <li><b>&#9632;</b> Pertanyaan wajib: <strong><?= (int) $requiredCount; ?></strong></li>
                    <li><b>&#9632;</b> Respons masuk: <strong><?= (int) $responseCount; ?></strong></li>
                </ul>
                <div class="mini-row" style="margin-top:0.8rem;">
                    <span>Wajib</span>
                    <div class="mini-bar"><i style="--w:<?= $pctRequired; ?>%"></i></div>
                </div>
            </div>
            <div class="card info-card">
                <h4>Mengapa Suara Anda Penting?</h4>
                <ul class="mini-steps">
                    <li><b>&#10003;</b> Hasil survei dianalisis tiap semester.</li>
                    <li><b>&#10003;</b> Keluhan ditindaklanjuti secara terukur.</li>
                    <li><b>&#10003;</b> Data mendukung perbaikan dan akreditasi.</li>
                </ul>
            </div>
        </aside>
    </div>
</section>