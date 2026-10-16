<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$users = load_json('users.json');
$tests = load_json('tests.json');
$topics = load_json('topics.json');
$videos = load_json('videos.json');
$page_title = "Admin panel";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>

    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Boshqaruv paneli</h1>
        </div>

        <div class="result-summary">
            <div class="result-tile">
                <strong><?= count($users) ?></strong>
                <span>Foydalanuvchilar</span>
            </div>
            <div class="result-tile">
                <strong><?= count($topics) ?></strong>
                <span>Mavzular</span>
            </div>
            <div class="result-tile">
                <strong><?= count($tests) ?></strong>
                <span>Testlar</span>
            </div>
            <div class="result-tile">
                <strong><?= count($videos) ?></strong>
                <span>Video darsliklar</span>
            </div>
        </div>

        <div class="card-grid" style="grid-template-columns:repeat(2,1fr)">
            <a class="module-card" href="<?= base_url('admin/videos.php') ?>">
                <span class="module-icon">V</span>
                <h3>Video darsliklar</h3>
                <p>YouTube havolasi orqali video darslik qo‘shish.</p>
            </a>
            <a class="module-card" href="<?= base_url('admin/test-form.php') ?>">
                <span class="module-icon">T</span>
                <h3>Test yaratish</h3>
                <p>Savollar, variantlar va to‘g‘ri javobni kiritish.</p>
            </a>
            <a class="module-card" href="<?= base_url('admin/users.php') ?>">
                <span class="module-icon">U</span>
                <h3>Foydalanuvchilar</h3>
                <p>Talaba va adminlarni ko‘rish, o‘chirish.</p>
            </a>
            <a class="module-card" href="<?= base_url('admin/results.php') ?>">
                <span class="module-icon">R</span>
                <h3>Natijalar</h3>
                <p>Talabalar test natijalari.</p>
            </a>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
