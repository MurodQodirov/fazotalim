<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$tests = load_json('tests.json');
$page_title = "Testlar";
require dirname(__DIR__) . '/includes/header.php';

function badge_class(string $type): string
{
    return match ($type) {
        'pretest' => 'badge-pre',
        'posttest' => 'badge-post',
        default => 'badge-topic',
    };
}

function badge_label(string $type): string
{
    return match ($type) {
        'pretest' => 'Boshlang‘ich',
        'posttest' => 'Yakuniy',
        default => 'Mavzu',
    };
}
?>

<div class="container page-hero">
    <h1>Testlar</h1>
    <p>Boshlang‘ich, mavzuli va yakuniy baholash.</p>
    <?php if (is_logged_in()): ?>
        <div class="hero-actions" style="margin-top:1rem">
            <a class="btn btn-secondary" href="<?= base_url('pages/results.php') ?>">Natijalarim</a>
        </div>
    <?php endif; ?>
</div>

<div class="container test-grid">
    <?php foreach ($tests as $test): ?>
        <article class="test-card">
            <span class="badge <?= badge_class($test['type']) ?>"><?= badge_label($test['type']) ?></span>
            <h3><?= e($test['title']) ?></h3>
            <p><?= e($test['description']) ?></p>
            <div class="solid-meta">
                <span><?= count($test['questions']) ?> ta savol</span>
                <span><?= (int)$test['time_limit'] ?> daqiqa</span>
            </div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/take-test.php?id=' . urlencode($test['id'])) ?>">
                Testni boshlash
            </a>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
