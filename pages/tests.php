<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$tests = load_content('tests.json');
$page_title = t('nav.tests');
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
        'pretest' => t('tests.badge.pre'),
        'posttest' => t('tests.badge.post'),
        default => t('tests.badge.topic'),
    };
}
?>

<div class="container page-hero">
    <h1><?= e(t('nav.tests')) ?></h1>
    <p><?= e(t('tests.lead')) ?></p>
    <?php if (is_logged_in()): ?>
        <div class="hero-actions" style="margin-top:1rem">
            <a class="btn btn-secondary" href="<?= base_url('pages/results.php') ?>"><?= e(t('tests.myresults')) ?></a>
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
                <span><?= e(t('tests.questions', ['n' => (string) count($test['questions'])])) ?></span>
                <span><?= e(t('tests.minutes', ['n' => (string) (int) $test['time_limit']])) ?></span>
            </div>
            <a class="btn btn-primary btn-sm" href="<?= base_url('pages/take-test.php?id=' . urlencode($test['id'])) ?>">
                <?= e(t('tests.start')) ?>
            </a>
        </article>
    <?php endforeach; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
