<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$solids = load_content('solids.json');
$page_title = t('sec.sirtlar');
$extra_js = [
    'three.min.js',
    'geometry.js',
    'solids-preview.js',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('sec.sirtlar')) ?></h1>
    <p><?= e(t('solids.lead')) ?></p>
</div>

<div class="container solid-grid" style="padding-bottom:3rem">
    <?php foreach ($solids as $solid): ?>
        <article class="solid-card">
            <div class="solid-preview" data-solid-preview="<?= e($solid['id']) ?>" data-color="<?= e($solid['color']) ?>"></div>
            <div class="solid-body">
                <h3><?= e($solid['name']) ?></h3>
                <div class="solid-meta">
                    <span><?= e(t('solid.faces')) ?>: <?= (int)$solid['faces'] ?></span>
                    <span><?= e(t('solid.edges')) ?>: <?= (int)$solid['edges'] ?></span>
                    <span><?= e(t('solid.vertices')) ?>: <?= (int)$solid['vertices'] ?></span>
                </div>
                <p><?= e($solid['description']) ?></p>
                <div class="solid-meta">
                    <span><?= e($solid['formula_volume']) ?></span>
                    <span><?= e($solid['formula_area']) ?></span>
                </div>
                <a class="btn btn-ghost btn-sm" href="<?= base_url('pages/lab.php?solid=' . urlencode($solid['id'])) ?>"><?= e(t('solid.openlab')) ?></a>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
