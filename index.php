<?php
$page_title = "Bosh sahifa";
$extra_js = [
    'https://cdnjs.cloudflare.com/ajax/libs/three.js/r152/three.min.js',
    'geometry.js',
    'hero-3d.js',
];
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="sheet">
            <div class="hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">Muallif: <?= e(SITE_AUTHOR) ?></p>
                    <h1>Fazoviy tasavvurni rivojlantirish</h1>
                    <p class="hero-lead">
                        Geometrik shakllarni 3D model va 2D chizma orqali o‘rganing.
                    </p>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="<?= base_url('pages/lab.php') ?>">3D laboratoriya</a>
                        <a class="btn btn-ghost" href="<?= base_url('pages/tests.php') ?>">Boshlang‘ich test</a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-stage">
                        <canvas id="heroCanvas" aria-label="Kubning 3D modeli"></canvas>
                    </div>
                    <div class="hero-views">
                        <div class="hero-proj">
                            <span>V · Old</span>
                            <svg id="heroFront" viewBox="0 0 120 90"></svg>
                        </div>
                        <div class="hero-proj">
                            <span>H · Yuqori</span>
                            <svg id="heroTop" viewBox="0 0 120 90"></svg>
                        </div>
                        <div class="hero-proj">
                            <span>W · Yon</span>
                            <svg id="heroSide" viewBox="0 0 120 90"></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <h2>Bo‘limlar</h2>
        </div>
        <div class="card-grid">
            <?php foreach (course_sections() as $s): ?>
            <a class="module-card" href="<?= base_url($s['file']) ?>">
                <span class="module-icon"><?= e($s['n']) ?></span>
                <h3><?= e($s['title']) ?></h3>
                <p><?= e($s['lead']) ?></p>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
