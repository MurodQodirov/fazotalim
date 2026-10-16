<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$solids = load_json('solids.json');
$selected = $_GET['solid'] ?? ($solids[0]['id'] ?? 'cube');
$page_title = "3D laboratoriya";
$body_class = "lab-page";
$extra_js = [
    'https://cdnjs.cloudflare.com/ajax/libs/three.js/r152/three.min.js',
    'geometry.js',
    'lab.js',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>3D laboratoriya</h1>
    <p>Jismni aylantiring. Old, yuqori va yon ko‘rinishlarni solishtiring.</p>
</div>

<div class="container lab-layout">
    <aside class="lab-panel">
        <h2>Shakllar</h2>
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
            <button type="button" data-view="iso" class="active">Izometriya</button>
            <button type="button" data-view="front">Old (V)</button>
            <button type="button" data-view="top">Yuqori (H)</button>
            <button type="button" data-view="side">Yon (W)</button>
            <button type="button" id="toggleEdges" class="active">Qirralar</button>
            <button type="button" id="toggleFaces" class="active">Yoqlar</button>
            <button type="button" id="resetView">Qayta</button>
        </div>
        <canvas id="labCanvas"></canvas>
    </div>

    <aside class="lab-panel">
        <h2 id="infoName">—</h2>
        <p id="infoDesc" style="color:var(--muted);font-size:0.92rem"></p>

        <h3 style="margin-top:1.2rem">Elementlar</h3>
        <ul class="info-list">
            <li><span>Yoqlar</span><span id="infoFaces">—</span></li>
            <li><span>Qirralar</span><span id="infoEdges">—</span></li>
            <li><span>Uchlar</span><span id="infoVertices">—</span></li>
        </ul>

        <div class="formula-box">
            <div>Hajm: <span id="infoVolume">—</span></div>
            <div style="margin-top:0.35rem">Yuza: <span id="infoArea">—</span></div>
        </div>

        <h3 style="margin-top:1.2rem">Proyeksiyalar</h3>
        <div class="proj-views">
            <div class="proj-box">
                <span>Front (V) — old</span>
                <svg id="projFront" viewBox="0 0 120 90"></svg>
            </div>
            <div class="proj-box">
                <span>Top (H) — yuqori</span>
                <svg id="projTop" viewBox="0 0 120 90"></svg>
            </div>
            <div class="proj-box">
                <span>Side (W) — yon</span>
                <svg id="projSide" viewBox="0 0 120 90"></svg>
            </div>
        </div>

        <h3 style="margin-top:1.2rem">Nazariya</h3>
        <p id="infoTheory" style="color:var(--muted);font-size:0.9rem;margin:0"></p>
    </aside>
</div>

<script type="application/json" id="solidsData"><?= json_encode($solids, JSON_UNESCAPED_UNICODE) ?></script>
<script>
    // URL dagi solid parametrini lab.js ga uzatish
    window.FAZO_SELECTED_SOLID = <?= json_encode($selected) ?>;
</script>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
