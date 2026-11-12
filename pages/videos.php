<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$videos = load_json('videos.json');
usort($videos, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));
$sections = course_labels();
$page_title = t('nav.videos');
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('nav.videos')) ?></h1>
    <p><?= e(t('videos.lead')) ?></p>
</div>

<div class="container test-grid">
    <?php if (!$videos): ?>
        <div class="empty-state"><?= e(t('videos.empty')) ?></div>
    <?php endif; ?>
    <?php foreach ($videos as $v): ?>
        <?php $yt = $v['youtube_id'] ?? youtube_id($v['url'] ?? ''); ?>
        <article class="test-card">
            <?php if ($yt): ?>
                <img class="video-thumb" src="<?= e(youtube_thumb($yt)) ?>" alt="">
            <?php endif; ?>
            <span class="badge badge-topic"><?= e($sections[$v['section'] ?? ''] ?? 'Umumiy') ?></span>
            <h3><?= e($v['title']) ?></h3>
            <p><?= e($v['description'] ?? '') ?></p>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/watch.php?id=' . urlencode($v['id'])) ?>"><?= e(t('videos.watch')) ?></a>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
