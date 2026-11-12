<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$items = load_json('presentations.json');
$labels = course_labels();
$page_title = t('sec.prezentatsiyalar');
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('sec.prezentatsiyalar')) ?></h1>
    <p><?= e(t('pres.lead')) ?></p>
</div>

<div class="container test-grid">
    <?php if (!$items): ?>
        <div class="empty-state"><?= e(t('pres.empty')) ?></div>
    <?php endif; ?>
    <?php foreach ($items as $item): ?>
        <article class="test-card">
            <span class="badge badge-topic"><?= e($labels[$item['section'] ?? ''] ?? 'Umumiy') ?></span>
            <h3><?= e($item['title'] ?? '') ?></h3>
            <p><?= e($item['description'] ?? '') ?></p>
            <?php if (!empty($item['url'])): ?>
                <a class="btn btn-primary btn-sm" href="<?= e($item['url']) ?>" target="_blank" rel="noopener"><?= e(t('pres.open')) ?></a>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
