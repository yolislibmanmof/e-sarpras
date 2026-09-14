<?php $errors = \App\Core\Session::getFlash('errors'); ?>
<?php if (!empty($errors)): ?>
<div class="flash flash-error">
    <ul class="error-list">
        <?php foreach ($errors as $list): ?>
            <?php foreach ($list as $message): ?>
                <li><?= e($message); ?></li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>