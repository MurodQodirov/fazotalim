<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$topics = load_content('topics.json');
usort($topics, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
$id = $_GET['id'] ?? ($topics[0]['id'] ?? '');
$topic = find_by_id($topics, $id) ?? $topics[0] ?? null;

if (!$topic) {
    flash('error', t('topic.notfound'));
    redirect(base_url('pages/practice.php'));
}

$page_title = $topic['title'];
$extra_js = [];
if (!empty($topic['solid_id'])) {
    $extra_js = [
        'three.min.js',
        'geometry.js',
        'solids-preview.js',
    ];
}
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container theory-layout">
    <nav class="topic-nav">
        <h3 style="margin:0.25rem 0.7rem 0.75rem;font-size:0.95rem"><?= e(t('topic.list')) ?></h3>
        <?php foreach ($topics as $row): ?>
            <a href="<?= base_url('pages/theory.php?id=' . urlencode($row['id'])) ?>" class="<?= $row['id'] === $topic['id'] ? 'active' : '' ?>">
                <?= e($row['title']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <article class="theory-content">
        <p class="eyebrow"><?= e(t('topic.goal')) ?></p>
        <h1><?= e($topic['title']) ?></h1>
        <p><?= e($topic['objective']) ?></p>

        <h2>1. <?= e(t('topic.theory')) ?></h2>
        <p><?= e($topic['theory']) ?></p>

        <?php if (!empty($topic['solid_id'])): ?>
            <h2>2. <?= e(t('topic.model')) ?></h2>
            <div class="solid-preview" style="height:240px;border-radius:12px;border:1px solid var(--line)"
                 data-solid-preview="<?= e($topic['solid_id']) ?>"
                 data-color="#22d3ee"></div>
            <p style="margin-top:0.75rem">
                <a class="btn btn-ghost btn-sm" href="<?= base_url('pages/lab.php?solid=' . urlencode($topic['solid_id'])) ?>"><?= e(t('solid.openlab')) ?></a>
            </p>
        <?php endif; ?>

        <h2><?= !empty($topic['solid_id']) ? '3' : '2' ?>. <?= e(t('topic.formulas')) ?></h2>
        <div class="formula-box">
            <?php foreach ($topic['formulas'] as $f): ?>
                <div style="margin-bottom:0.35rem"><?= e($f) ?></div>
            <?php endforeach; ?>
        </div>

        <h2><?= e(t('topic.example')) ?></h2>
        <p><?= e($topic['example']) ?></p>

        <h2><?= e(t('topic.tasks')) ?></h2>
        <ol class="step-list">
            <?php foreach ($topic['tasks'] as $task): ?>
                <li><?= e($task) ?></li>
            <?php endforeach; ?>
        </ol>

        <h2><?= e(t('topic.test')) ?></h2>
        <p><?= e(t('topic.test.lead')) ?></p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>"><?= e(t('topic.gotests')) ?></a>
            <a class="btn btn-ghost" href="<?= base_url('pages/maruza.php') ?>"><?= e(t('sec.maruza')) ?></a>
        </div>
    </article>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
