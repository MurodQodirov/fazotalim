<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$topics = load_json('topics.json');
usort($topics, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
$id = $_GET['id'] ?? ($topics[0]['id'] ?? '');
$topic = find_by_id($topics, $id) ?? $topics[0] ?? null;

if (!$topic) {
    flash('error', 'Mavzu topilmadi.');
    redirect(base_url('pages/practice.php'));
}

$page_title = $topic['title'];
$extra_js = [];
if (!empty($topic['solid_id'])) {
    $extra_js = [
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r152/three.min.js',
        'geometry.js',
        'solids-preview.js',
    ];
}
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container theory-layout">
    <nav class="topic-nav">
        <h3 style="margin:0.25rem 0.7rem 0.75rem;font-size:0.95rem">Mavzular</h3>
        <?php foreach ($topics as $t): ?>
            <a href="<?= base_url('pages/theory.php?id=' . urlencode($t['id'])) ?>" class="<?= $t['id'] === $topic['id'] ? 'active' : '' ?>">
                <?= e($t['title']) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <article class="theory-content">
        <p class="eyebrow">O‘quv maqsadi</p>
        <h1><?= e($topic['title']) ?></h1>
        <p><?= e($topic['objective']) ?></p>

        <h2>1. Nazariy tushuntirish</h2>
        <p><?= e($topic['theory']) ?></p>

        <?php if (!empty($topic['solid_id'])): ?>
            <h2>2. Geometrik model</h2>
            <div class="solid-preview" style="height:240px;border-radius:12px;border:1px solid var(--line)"
                 data-solid-preview="<?= e($topic['solid_id']) ?>"
                 data-color="#22d3ee"></div>
            <p style="margin-top:0.75rem">
                <a class="btn btn-ghost btn-sm" href="<?= base_url('pages/lab.php?solid=' . urlencode($topic['solid_id'])) ?>">3D laboratoriyada ochish</a>
            </p>
        <?php endif; ?>

        <h2><?= !empty($topic['solid_id']) ? '3' : '2' ?>. Formulalar</h2>
        <div class="formula-box">
            <?php foreach ($topic['formulas'] as $f): ?>
                <div style="margin-bottom:0.35rem"><?= e($f) ?></div>
            <?php endforeach; ?>
        </div>

        <h2>Ishlangan misol</h2>
        <p><?= e($topic['example']) ?></p>

        <h2>Mustaqil topshiriqlar</h2>
        <ol class="step-list">
            <?php foreach ($topic['tasks'] as $task): ?>
                <li><?= e($task) ?></li>
            <?php endforeach; ?>
        </ol>

        <h2>Mavzu testi</h2>
        <p>Mavzuni yakunlagach bilimingizni tekshiring.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>">Testlarga o‘tish</a>
            <a class="btn btn-ghost" href="<?= base_url('pages/maruza.php') ?>">Ma’ruza matnlari</a>
        </div>
    </article>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
