<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$solids = load_content('solids.json');
$selected = $_GET['solid'] ?? ($solids[0]['id'] ?? 'cube');
$page_title = t('lab.title');
$body_class = "lab-page";
$extra_js = [
    'three.min.js',
    'geometry.js',
    'lab.js',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('lab.title')) ?></h1>
    <p><?= e(t('lab.lead')) ?></p>
</div>

<div class="container lab-layout">
    <aside class="lab-panel">
        <h2><?= e(t('nav.shapes')) ?></h2>
        <div class="solid-list">
            <?php foreach ($solids as $solid): ?>
                <button type="button" data-id="<?= e($solid['id']) ?>" class="<?= $solid['id'] === $selected ? 'active' : '' ?>">
                    <?= e($solid['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </aside>

    <div class="lab-viewport">
        <div class="lab-toolbar">
            <button type="button" data-view="iso" class="active"><?= e(t('lab.iso')) ?></button>
            <button type="button" data-view="front"><?= e(t('view.front')) ?></button>
            <button type="button" data-view="top"><?= e(t('view.top')) ?></button>
            <button type="button" data-view="side"><?= e(t('view.side')) ?></button>
            <button type="button" id="toggleEdges" class="active"><?= e(t('lab.edges')) ?></button>
            <button type="button" id="toggleFaces" class="active"><?= e(t('lab.faces')) ?></button>
            <button type="button" id="resetView"><?= e(t('lab.reset')) ?></button>
        </div>
        <canvas id="labCanvas"></canvas>
    </div>

    <aside class="lab-panel">
        <h2 id="infoName">—</h2>
        <p id="infoDesc" style="color:var(--muted);font-size:0.92rem"></p>

        <h3 style="margin-top:1.2rem"><?= e(t('lab.elements')) ?></h3>
        <ul class="info-list">
            <li><span><?= e(t('lab.faces')) ?></span><span id="infoFaces">—</span></li>
            <li><span><?= e(t('lab.edges')) ?></span><span id="infoEdges">—</span></li>
            <li><span><?= e(t('solid.vertices')) ?></span><span id="infoVertices">—</span></li>
        </ul>

        <div class="formula-box">
            <div><?= e(t('lab.volume')) ?>: <span id="infoVolume">—</span></div>
            <div style="margin-top:0.35rem"><?= e(t('lab.area')) ?>: <span id="infoArea">—</span></div>
        </div>

        <h3 style="margin-top:1.2rem"><?= e(t('lab.projections')) ?></h3>
        <div class="proj-views">
            <div class="proj-box">
                <span><?= e(t('lab.front')) ?></span>
                <svg id="projFront" viewBox="0 0 120 90"></svg>
            </div>
            <div class="proj-box">
                <span><?= e(t('lab.top')) ?></span>
                <svg id="projTop" viewBox="0 0 120 90"></svg>
            </div>
            <div class="proj-box">
                <span><?= e(t('lab.side')) ?></span>
                <svg id="projSide" viewBox="0 0 120 90"></svg>
            </div>
        </div>

        <h3 style="margin-top:1.2rem"><?= e(t('lab.theory')) ?></h3>
        <p id="infoTheory" style="color:var(--muted);font-size:0.9rem;margin:0"></p>
    </aside>
</div>

<script type="application/json" id="solidsData"><?= json_encode($solids, JSON_UNESCAPED_UNICODE) ?></script>
<script>
    // URL dagi solid parametrini lab.js ga uzatish
    window.FAZO_SELECTED_SOLID = <?= json_encode($selected) ?>;
</script>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
