<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$videos = load_json('videos.json');
$id = $_GET['id'] ?? '';
$video = find_by_id($videos, $id);

if (!$video) {
    flash('error', 'Video topilmadi.');
    redirect(base_url('pages/videos.php'));
}

$yt = $video['youtube_id'] ?? youtube_id($video['url'] ?? '');
if (!$yt) {
    flash('error', 'YouTube havolasi noto‘g‘ri.');
    redirect(base_url('pages/videos.php'));
}

$page_title = $video['title'];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e($video['title']) ?></h1>
    <?php if (!empty($video['description'])): ?>
        <p><?= e($video['description']) ?></p>
    <?php endif; ?>
</div>

<div class="container" style="padding-bottom:3rem;max-width:900px">
    <div class="video-frame">
        <iframe
            src="<?= e(youtube_embed($yt)) ?>"
            title="<?= e($video['title']) ?>"
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
    </div>
    <p style="margin-top:1rem">
        <a class="btn btn-ghost" href="<?= base_url('pages/videos.php') ?>">Barcha darsliklar</a>
    </p>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
