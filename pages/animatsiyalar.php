<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$page_title = "Animatsiyalar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>Animatsiyalar</h1>
    <p>Jismni aylantirish va proyeksiyalarning o‘zgarishini 3D muhitda kuzating.</p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="card-grid">
        <a class="module-card" href="<?= base_url('pages/lab.php') ?>">
            <span class="module-icon">3D</span>
            <h3>Geometrik sirtlar laboratoriyasi</h3>
            <p>Kub, prizma, piramida, silindr, konus va sharni aylantirish. Old, yuqori, yon ko‘rinishlar.</p>
        </a>
        <a class="module-card" href="<?= base_url('pages/architecture.php') ?>">
            <span class="module-icon">AR</span>
            <h3>Bino modeli</h3>
            <p>Reja, fasad va yon ko‘rinishning 3D model bilan bog‘liqligi.</p>
        </a>
        <a class="module-card" href="<?= base_url('index.php') ?>">
            <span class="module-icon">VHW</span>
            <h3>Bosh sahifa epyuri</h3>
            <p>Kubning 3D ko‘rinishi va V / H / W proyeksiyalari.</p>
        </a>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
