<div class="calendar">
    <div class="calendar-head"><?= e($calendar['month_name'] . ' ' . $calendar['year']); ?></div>
    <div class="calendar-grid">
        <?php foreach (['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dow): ?>
            <span class="cal-dow"><?= $dow; ?></span>
        <?php endforeach; ?>

        <?php for ($i = 0; $i < $calendar['leading']; $i++): ?>
            <span class="cal-day cal-empty"></span>
        <?php endfor; ?>

        <?php foreach ($calendar['days'] as $day): ?>
            <span class="cal-day<?= $day['count'] > 0 ? ' cal-has' : '' ?><?= $day['day'] === $calendar['today'] ? ' cal-today' : '' ?>">
                <?= $day['day']; ?>
                <?php if ($day['count'] > 0): ?><em><?= $day['count']; ?></em><?php endif; ?>
            </span>
        <?php endforeach; ?>
    </div>
</div>