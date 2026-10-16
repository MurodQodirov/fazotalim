<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$items = load_json('presentations.json');
$labels = course_labels();
$page_title = "Prezentatsiyalar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>Prezentatsiyalar</h1>
    <p>Mavzular bo‘yicha slayd va taqdimot materiallari.</p>
</div>

<div class="container test-grid">
    <?php if (!$items): ?>
        <div class="empty-state">Hozircha prezentatsiya yo‘q.</div>
    <?php endif; ?>
    <?php foreach ($items as $item): ?>
        <article class="test-card">
            <span class="badge badge-topic"><?= e($labels[$item['section'] ?? ''] ?? 'Umumiy') ?></span>
            <h3><?= e($item['title'] ?? '') ?></h3>
            <p><?= e($item['description'] ?? '') ?></p>
            <?php if (!empty($item['url'])): ?>
                <a class="btn btn-primary btn-sm" href="<?= e($item['url']) ?>" target="_blank" rel="noopener">Ochish</a>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
