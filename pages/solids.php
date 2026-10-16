<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$solids = load_json('solids.json');
$page_title = "Sirtlar";
$extra_js = [
    'https://cdnjs.cloudflare.com/ajax/libs/three.js/r152/three.min.js',
    'geometry.js',
    'solids-preview.js',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>Sirtlar</h1>
    <p>Ko‘pyoqliklar va aylanma sirtlar — 3D model orqali.</p>
</div>

<div class="container solid-grid" style="padding-bottom:3rem">
    <?php foreach ($solids as $solid): ?>
        <article class="solid-card">
            <div class="solid-preview" data-solid-preview="<?= e($solid['id']) ?>" data-color="<?= e($solid['color']) ?>"></div>
            <div class="solid-body">
                <h3><?= e($solid['name']) ?></h3>
                <div class="solid-meta">
                    <span>Yoq: <?= (int)$solid['faces'] ?></span>
                    <span>Qirra: <?= (int)$solid['edges'] ?></span>
                    <span>Uch: <?= (int)$solid['vertices'] ?></span>
                </div>
                <p><?= e($solid['description']) ?></p>
                <div class="solid-meta">
                    <span><?= e($solid['formula_volume']) ?></span>
                    <span><?= e($solid['formula_area']) ?></span>
                </div>
                <a class="btn btn-ghost btn-sm" href="<?= base_url('pages/lab.php?solid=' . urlencode($solid['id'])) ?>">Laboratoriyada ochish</a>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
