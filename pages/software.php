<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$software = load_json('software.json');
$page_title = "Dasturlar katalogi";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>Dasturlar</h1>
    <p>AutoCAD, Revit, SketchUp, Blender va boshqa vositalar.</p>
</div>

<div class="container soft-grid">
    <?php foreach ($software as $app): ?>
        <article class="soft-card">
            <h3><?= e($app['name']) ?></h3>
            <div class="soft-vendor"><?= e($app['vendor']) ?> · <?= e($app['category']) ?></div>
            <p><?= e($app['description']) ?></p>
            <div class="tag-row">
                <?php foreach ($app['uses'] as $use): ?>
                    <span class="tag"><?= e($use) ?></span>
                <?php endforeach; ?>
            </div>
            <p style="margin:0;font-size:0.9rem;color:var(--muted)">Daraja: <?= e($app['level']) ?></p>
            <?php if (!empty($app['resources'])): ?>
                <ul class="resource-list">
                    <?php foreach ($app['resources'] as $res): ?>
                        <li><a href="<?= e($res['url']) ?>" target="_blank" rel="noopener"><?= e($res['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
