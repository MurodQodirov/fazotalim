<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$results = load_json('results.json');
$id = $_GET['id'] ?? ($_SESSION['last_result_id'] ?? '');
$result = find_by_id($results, $id);

if (!$result) {
    flash('error', t('result.notfound'));
    redirect(base_url('pages/tests.php'));
}

$tests = load_content('tests.json');
$test = find_by_id($tests, $result['test_id']);

$page_title = t('result.title');
require dirname(__DIR__) . '/includes/header.php';

$wrong = $result['total'] - $result['correct'];
$level = $result['score'] >= 80 ? t('result.high') : ($result['score'] >= 60 ? t('result.mid') : t('result.low'));
?>

<div class="container page-hero">
    <p class="eyebrow"><?= e(t('result.eyebrow')) ?></p>
    <h1><?= e($test['title'] ?? $result['test_title']) ?></h1>
    <p><?= format_date($result['created_at']) ?> · <?= e($result['user_name']) ?></p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="result-summary">
        <div class="result-tile">
            <strong><?= e((string)$result['score']) ?>%</strong>
            <span><?= e(t('result.score')) ?></span>
        </div>
        <div class="result-tile">
            <strong><?= (int)$result['correct'] ?></strong>
            <span><?= e(t('result.correct')) ?></span>
        </div>
        <div class="result-tile">
            <strong><?= (int)$wrong ?></strong>
            <span><?= e(t('result.wrong')) ?></span>
        </div>
        <div class="result-tile">
            <strong><?= e($level) ?></strong>
            <span><?= e(t('result.level')) ?></span>
        </div>
    </div>

    <?php if ($test): ?>
        <div class="theory-content" style="margin-bottom:1.5rem">
            <h2><?= e(t('result.analysis')) ?></h2>
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
                        <?= e(t('result.yours')) ?>
                        <strong style="color:<?= $ok ? 'var(--ok)' : 'var(--danger)' ?>">
                            <?= isset($q['options'][$ans['given'] ?? -1]) ? e($q['options'][$ans['given']]) : e(t('result.blank')) ?>
                        </strong>
                    </p>
                    <?php if (!$ok): ?>
                        <p style="margin:0.35rem 0 0;color:var(--ok)">
                            <?= e(t('result.right')) ?> <?= e($q['options'][$q['correct']]) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="hero-actions">
        <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>"><?= e(t('result.moretests')) ?></a>
        <a class="btn btn-ghost" href="<?= base_url('pages/results.php') ?>"><?= e(t('result.all')) ?></a>
        <?php if ($result['score'] < 60): ?>
            <a class="btn btn-secondary" href="<?= base_url('pages/practice.php') ?>"><?= e(t('result.retry')) ?></a>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
