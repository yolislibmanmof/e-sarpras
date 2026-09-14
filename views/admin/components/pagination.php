<?php if (($pagination['last'] ?? 1) > 1): ?>
<nav class="pagination">
    <?php foreach ($pagination['links'] as $link): ?>
        <a href="<?= e($link['url']); ?>" class="page-link<?= $link['active'] ? ' active' : ''; ?>"><?= $link['page']; ?></a>
    <?php endforeach; ?>
</nav>
<?php endif; ?>