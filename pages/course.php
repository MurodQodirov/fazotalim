<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$id = $_GET['id'] ?? '';
$section = course_section($id);
$all = load_json('course.json');
$content = $all[$id] ?? null;

if (!$section || !$content) {
    flash('error', 'Bo‘lim topilmadi.');
    redirect(base_url('index.php'));
}

$page_title = $section['title'];
require dirname(__DIR__) . '/includes/header.php';
$sections = course_sections();
?>

<div class="container page-hero">
    <p class="eyebrow"><?= e($section['n']) ?></p>
    <h1><?= e($section['title']) ?></h1>
    <p><?= e($content['intro']) ?></p>
</div>

<div class="container theory-layout">
    <nav class="topic-nav">
        <h3 style="margin:0.25rem 0.7rem 0.75rem;font-size:0.95rem">Bo‘limlar</h3>
        <?php foreach ($sections as $s): ?>
            <a href="<?= base_url($s['file']) ?>" class="<?= $s['id'] === $id ? 'active' : '' ?>">
                <?= e($s['n']) ?>. <?= e($s['title']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <article class="theory-content">
        <?php foreach ($content['blocks'] as $block): ?>
            <h2><?= e($block['h']) ?></h2>
            <p><?= e($block['p']) ?></p>
        <?php endforeach; ?>

        <?php if (!empty($content['formulas'])): ?>
            <h2>Belgilar va formulalar</h2>
            <div class="formula-box">
                <?php foreach ($content['formulas'] as $f): ?>
                    <div style="margin-bottom:0.35rem"><?= e($f) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($content['steps'])): ?>
            <h2>Ish tartibi</h2>
            <ol class="step-list">
                <?php foreach ($content['steps'] as $step): ?>
                    <li><?= e($step) ?></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>

        <div class="hero-actions" style="margin-top:1.5rem">
            <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>">Testlar</a>
            <a class="btn btn-ghost" href="<?= base_url('pages/lab.php') ?>">3D laboratoriya</a>
        </div>
    </article>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
