<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$results = load_json('results.json');
$id = $_GET['id'] ?? ($_SESSION['last_result_id'] ?? '');
$result = find_by_id($results, $id);

if (!$result) {
    flash('error', 'Natija topilmadi.');
    redirect(base_url('pages/tests.php'));
}

$tests = load_json('tests.json');
$test = find_by_id($tests, $result['test_id']);

$page_title = "Test natijasi";
require dirname(__DIR__) . '/includes/header.php';

$wrong = $result['total'] - $result['correct'];
$level = $result['score'] >= 80 ? 'Yuqori' : ($result['score'] >= 60 ? 'O‘rta' : 'Qayta ishlash kerak');
?>

<div class="container page-hero">
    <p class="eyebrow">Natija</p>
    <h1><?= e($result['test_title']) ?></h1>
    <p><?= format_date($result['created_at']) ?> · <?= e($result['user_name']) ?></p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="result-summary">
        <div class="result-tile">
            <strong><?= e((string)$result['score']) ?>%</strong>
            <span>Umumiy ball</span>
        </div>
        <div class="result-tile">
            <strong><?= (int)$result['correct'] ?></strong>
            <span>To‘g‘ri</span>
        </div>
        <div class="result-tile">
            <strong><?= (int)$wrong ?></strong>
            <span>Noto‘g‘ri</span>
        </div>
        <div class="result-tile">
            <strong><?= e($level) ?></strong>
            <span>Daraja</span>
        </div>
    </div>

    <?php if ($test): ?>
        <div class="theory-content" style="margin-bottom:1.5rem">
            <h2>Javoblar tahlili</h2>
            <?php foreach ($test['questions'] as $i => $q): ?>
                <?php
                $ans = null;
                foreach ($result['answers'] as $a) {
                    if ($a['question_id'] === $q['id']) {
                        $ans = $a;
                        break;
                    }
                }
                $ok = $ans && $ans['is_correct'];
                ?>
                <div class="question-block" style="border-color:<?= $ok ? 'rgba(52,211,153,0.45)' : 'rgba(251,113,133,0.45)' ?>">
                    <h3><?= ($i + 1) ?>. <?= e($q['text']) ?></h3>
                    <p style="margin:0;color:var(--muted)">
                        Sizning javobingiz:
                        <strong style="color:<?= $ok ? 'var(--ok)' : 'var(--danger)' ?>">
                            <?= isset($q['options'][$ans['given'] ?? -1]) ? e($q['options'][$ans['given']]) : 'Javob berilmagan' ?>
                        </strong>
                    </p>
                    <?php if (!$ok): ?>
                        <p style="margin:0.35rem 0 0;color:var(--ok)">
                            To‘g‘ri: <?= e($q['options'][$q['correct']]) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="hero-actions">
        <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>">Boshqa testlar</a>
        <a class="btn btn-ghost" href="<?= base_url('pages/results.php') ?>">Barcha natijalar</a>
        <?php if ($result['score'] < 60): ?>
            <a class="btn btn-secondary" href="<?= base_url('pages/practice.php') ?>">Mavzularni qayta o‘rganish</a>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
