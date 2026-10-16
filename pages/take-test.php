<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$tests = load_json('tests.json');
$id = $_GET['id'] ?? '';
$test = find_by_id($tests, $id);

if (!$test) {
    flash('error', 'Test topilmadi.');
    redirect(base_url('pages/tests.php'));
}

// Natijani qabul qilish
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answers'] ?? [];
    $correct = 0;
    $total = count($test['questions']);
    $detail = [];

    foreach ($test['questions'] as $q) {
        $qid = $q['id'];
        $given = isset($answers[$qid]) ? (int)$answers[$qid] : -1;
        $isCorrect = $given === (int)$q['correct'];
        if ($isCorrect) {
            $correct++;
        }
        $detail[] = [
            'question_id' => $qid,
            'given' => $given,
            'correct' => (int)$q['correct'],
            'is_correct' => $isCorrect,
        ];
    }

    $score = percent($correct, $total);
    $result = [
        'id' => generate_id('res_'),
        'user_id' => current_user()['id'] ?? 'guest',
        'user_name' => current_user()['name'] ?? 'Mehmon',
        'test_id' => $test['id'],
        'test_title' => $test['title'],
        'test_type' => $test['type'],
        'correct' => $correct,
        'total' => $total,
        'score' => $score,
        'answers' => $detail,
        'created_at' => date('Y-m-d H:i:s'),
    ];

    $results = load_json('results.json');
    $results[] = $result;
    save_json('results.json', $results);

    // Progress
    if (is_logged_in()) {
        $progress = load_json('progress.json');
        $uid = current_user()['id'];
        $found = false;
        foreach ($progress as &$p) {
            if ($p['user_id'] === $uid) {
                $p['tests_taken'] = ($p['tests_taken'] ?? 0) + 1;
                $p['last_score'] = $score;
                $p['updated_at'] = date('Y-m-d H:i:s');
                if ($test['type'] === 'pretest') {
                    $p['pretest_score'] = $score;
                }
                if ($test['type'] === 'posttest') {
                    $p['posttest_score'] = $score;
                }
                $found = true;
                break;
            }
        }
        unset($p);
        if (!$found) {
            $progress[] = [
                'user_id' => $uid,
                'tests_taken' => 1,
                'last_score' => $score,
                'pretest_score' => $test['type'] === 'pretest' ? $score : null,
                'posttest_score' => $test['type'] === 'posttest' ? $score : null,
                'topics_done' => [],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }
        save_json('progress.json', $progress);
    }

    $_SESSION['last_result_id'] = $result['id'];
    redirect(base_url('pages/test-result.php?id=' . urlencode($result['id'])));
}

$page_title = $test['title'];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <p class="eyebrow">Test</p>
    <h1><?= e($test['title']) ?></h1>
    <p><?= e($test['description']) ?> · <?= count($test['questions']) ?> savol · <?= (int)$test['time_limit'] ?> daqiqa</p>
</div>

<div class="container" style="padding-bottom:3rem;max-width:800px">
    <form method="post" action="">
        <?php foreach ($test['questions'] as $i => $q): ?>
            <div class="question-block">
                <h3><?= ($i + 1) ?>. <?= e($q['text']) ?></h3>
                <div class="option-list">
                    <?php foreach ($q['options'] as $oi => $opt): ?>
                        <label>
                            <input type="radio" name="answers[<?= e($q['id']) ?>]" value="<?= $oi ?>" required>
                            <span><?= e($opt) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary">Natijani ko‘rish</button>
        <a class="btn btn-ghost" href="<?= base_url('pages/tests.php') ?>">Bekor qilish</a>
    </form>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
