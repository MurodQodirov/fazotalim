<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$results = load_json('results.json');
usort($results, fn($a, $b) => strcmp($b['created_at'] ?? '', $a['created_at'] ?? ''));

$preScores = [];
$postScores = [];
foreach ($results as $r) {
    if (($r['test_type'] ?? '') === 'pretest') $preScores[] = (float)$r['score'];
    if (($r['test_type'] ?? '') === 'posttest') $postScores[] = (float)$r['score'];
}
$avg = fn(array $arr) => count($arr) ? round(array_sum($arr) / count($arr), 1) : null;

$page_title = "Natijalar (admin)";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Barcha natijalar</h1>
        </div>

        <div class="result-summary">
            <div class="result-tile">
                <strong><?= count($results) ?></strong>
                <span>Jami urinish</span>
            </div>
            <div class="result-tile">
                <strong><?= $avg($preScores) !== null ? $avg($preScores) . '%' : '—' ?></strong>
                <span>O‘rtacha pretest</span>
            </div>
            <div class="result-tile">
                <strong><?= $avg($postScores) !== null ? $avg($postScores) . '%' : '—' ?></strong>
                <span>O‘rtacha posttest</span>
            </div>
            <div class="result-tile">
                <strong>
                    <?php
                    $a = $avg($preScores);
                    $b = $avg($postScores);
                    echo ($a !== null && $b !== null) ? (($b - $a >= 0 ? '+' : '') . round($b - $a, 1) . '%') : '—';
                    ?>
                </strong>
                <span>O‘rtacha o‘sish</span>
            </div>
        </div>

        <?php if (empty($results)): ?>
            <div class="empty-state">Hali natijalar yo‘q.</div>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Sana</th>
                            <th>Foydalanuvchi</th>
                            <th>Test</th>
                            <th>Turi</th>
                            <th>Ball</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $r): ?>
                            <tr>
                                <td><?= e(format_date($r['created_at'] ?? '')) ?></td>
                                <td><?= e($r['user_name'] ?? '') ?></td>
                                <td><?= e($r['test_title'] ?? '') ?></td>
                                <td><?= e($r['test_type'] ?? '') ?></td>
                                <td><?= e((string)($r['score'] ?? 0)) ?>%</td>
                                <td><a href="<?= base_url('pages/test-result.php?id=' . urlencode($r['id'])) ?>">Batafsil</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
