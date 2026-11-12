<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$results = load_json('results.json');
$user = current_user();

if (!$user || ($user['role'] ?? '') !== 'admin') {
    $uid = $user['id'] ?? '__none__';
    $results = array_values(array_filter($results, fn($r) => ($r['user_id'] ?? '') === $uid));
}

usort($results, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

// Pre/post taqqoslash (joriydagi foydalanuvchi uchun)
$pre = null;
$post = null;
if ($user) {
    foreach ($results as $r) {
        if (($r['user_id'] ?? '') !== $user['id']) continue;
        if (($r['test_type'] ?? '') === 'pretest' && $pre === null) $pre = $r;
        if (($r['test_type'] ?? '') === 'posttest' && $post === null) $post = $r;
    }
}

$page_title = t('results.title');
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('results.title')) ?></h1>
</div>

<div class="container" style="padding-bottom:3rem">
    <?php if ($user && ($pre || $post)): ?>
        <div class="result-summary">
            <div class="result-tile">
                <strong><?= $pre ? e((string)$pre['score']) . '%' : '—' ?></strong>
                <span><?= e(t('results.pre')) ?></span>
            </div>
            <div class="result-tile">
                <strong><?= $post ? e((string)$post['score']) . '%' : '—' ?></strong>
                <span><?= e(t('results.post')) ?></span>
            </div>
            <div class="result-tile">
                <strong>
                    <?php if ($pre && $post): ?>
                        <?= ($post['score'] - $pre['score']) >= 0 ? '+' : '' ?><?= e((string)round($post['score'] - $pre['score'], 1)) ?>%
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </strong>
                <span><?= e(t('results.growth')) ?></span>
            </div>
            <div class="result-tile">
                <strong><?= count($results) ?></strong>
                <span><?= e(t('results.total')) ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (empty($results)): ?>
        <div class="empty-state">
            <p><?= e(t('results.empty')) ?></p>
            <a class="btn btn-primary" href="<?= base_url('pages/tests.php') ?>"><?= e(t('tests.start')) ?></a>
        </div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th><?= e(t('th.date')) ?></th>
                        <th><?= e(t('th.user')) ?></th>
                        <th><?= e(t('th.test')) ?></th>
                        <th><?= e(t('th.ok')) ?></th>
                        <th><?= e(t('th.score')) ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><?= e(format_date($r['created_at'] ?? '')) ?></td>
                            <td><?= e($r['user_name'] ?? '') ?></td>
                            <td><?= e($r['test_title'] ?? '') ?></td>
                            <td><?= (int)($r['correct'] ?? 0) ?>/<?= (int)($r['total'] ?? 0) ?></td>
                            <td><strong><?= e((string)($r['score'] ?? 0)) ?>%</strong></td>
                            <td><a href="<?= base_url('pages/test-result.php?id=' . urlencode($r['id'])) ?>"><?= e(t('th.detail')) ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
