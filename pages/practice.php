<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$topics = load_json('topics.json');
usort($topics, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
$page_title = "Metrik va pozitsion masalalar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1>Metrik va pozitsion masalalar</h1>
    <p>Masofa, haqiqiy uzunlik, tegishlilik, kesishish va kesimlar.</p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="card-grid">
        <?php foreach ($topics as $topic): ?>
            <a class="module-card" href="<?= base_url('pages/theory.php?id=' . urlencode($topic['id'])) ?>">
                <span class="module-icon"><?= str_pad((string)($topic['order'] ?? 0), 2, '0', STR_PAD_LEFT) ?></span>
                <h3><?= e($topic['title']) ?></h3>
                <p><?= e($topic['objective']) ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
