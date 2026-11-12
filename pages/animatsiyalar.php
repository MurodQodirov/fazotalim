<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$page_title = t('sec.animatsiyalar');
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('sec.animatsiyalar')) ?></h1>
    <p><?= e(t('anim.lead')) ?></p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="card-grid">
        <a class="module-card" href="<?= base_url('pages/lab.php') ?>">
            <span class="module-icon">3D</span>
            <h3><?= e(t('anim.lab.title')) ?></h3>
            <p><?= e(t('anim.lab.lead')) ?></p>
        </a>
        <a class="module-card" href="<?= base_url('pages/architecture.php') ?>">
            <span class="module-icon">AR</span>
            <h3><?= e(t('anim.arch.title')) ?></h3>
            <p><?= e(t('anim.arch.lead')) ?></p>
        </a>
        <a class="module-card" href="<?= base_url('index.php') ?>">
            <span class="module-icon">VHW</span>
            <h3><?= e(t('anim.home.title')) ?></h3>
            <p><?= e(t('anim.home.lead')) ?></p>
        </a>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
