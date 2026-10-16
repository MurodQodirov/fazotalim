<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_login();

$user = current_user();
$results = load_json('results.json');
$myResults = array_values(array_filter($results, fn($r) => ($r['user_id'] ?? '') === $user['id']));
usort($myResults, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$progress = load_json('progress.json');
$myProgress = null;
foreach ($progress as $p) {
    if (($p['user_id'] ?? '') === $user['id']) {
        $myProgress = $p;
        break;
    }
}

$page_title = "Kabinet";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e($user['name']) ?></h1>
    <p><?= e($user['email']) ?> · <?= ($user['role'] ?? '') === 'admin' ? 'Administrator' : 'Talaba' ?></p>
</div>

<div class="container" style="padding-bottom:3rem">
    <div class="result-summary">
        <div class="result-tile">
            <strong><?= count($myResults) ?></strong>
            <span>Ishlangan testlar</span>
        </div>
        <div class="result-tile">
            <strong><?= isset($myProgress['pretest_score']) ? e((string)$myProgress['pretest_score']) . '%' : '—' ?></strong>
            <span>Boshlang‘ich</span>
        </div>
        <div class="result-tile">
            <strong><?= isset($myProgress['posttest_score']) ? e((string)$myProgress['posttest_score']) . '%' : '—' ?></strong>
            <span>Yakuniy</span>
        </div>
        <div class="result-tile">
            <strong><?= isset($myProgress['last_score']) ? e((string)$myProgress['last_score']) . '%' : '—' ?></strong>
            <span>Oxirgi ball</span>
        </div>
    </div>

    <div class="hero-actions" style="margin-bottom:1.5rem">
        <a class="btn btn-primary" href="<?= base_url('pages/lab.php') ?>">3D laboratoriya</a>
        <a class="btn btn-ghost" href="<?= base_url('pages/tests.php') ?>">Testlar</a>
        <a class="btn btn-ghost" href="<?= base_url('pages/results.php') ?>">Natijalar</a>
    </div>

    <h2 style="margin-bottom:0.75rem">So‘nggi natijalar</h2>
    <?php if (empty($myResults)): ?>
        <div class="empty-state">Hali test ishlamagansiz.</div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Sana</th>
                        <th>Test</th>
                        <th>Ball</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($myResults, 0, 10) as $r): ?>
                        <tr>
                            <td><?= e(format_date($r['created_at'] ?? '')) ?></td>
                            <td><?= e($r['test_title'] ?? '') ?></td>
                            <td><?= e((string)($r['score'] ?? 0)) ?>%</td>
                            <td><a href="<?= base_url('pages/test-result.php?id=' . urlencode($r['id'])) ?>">Batafsil</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
