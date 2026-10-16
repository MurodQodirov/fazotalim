<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$tests = load_json('tests.json');
$id = $_GET['id'] ?? '';
$edit = $id ? find_by_id($tests, $id) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? 'topic';
    $time = max(1, (int)($_POST['time_limit'] ?? 15));
    $rawQuestions = $_POST['questions'] ?? [];
    $questions = [];

    foreach ($rawQuestions as $q) {
        $text = trim($q['text'] ?? '');
        $options = array_values(array_filter(array_map('trim', $q['options'] ?? []), fn($o) => $o !== ''));
        if ($text === '' || count($options) < 2) {
            continue;
        }
        $correct = (int)($q['correct'] ?? 0);
        if ($correct < 0 || $correct >= count($options)) {
            $correct = 0;
        }
        $questions[] = [
            'id' => generate_id('q'),
            'text' => $text,
            'type' => 'single',
            'options' => $options,
            'correct' => $correct,
        ];
    }

    if ($title === '' || !$questions) {
        flash('error', 'Sarlavha va kamida bitta to‘liq savol kerak.');
        redirect(base_url('admin/test-form.php' . ($id ? '?id=' . urlencode($id) : '')));
    }

    $payload = [
        'id' => $edit['id'] ?? generate_id('test-'),
        'title' => $title,
        'type' => in_array($type, ['pretest', 'posttest', 'topic'], true) ? $type : 'topic',
        'description' => $description,
        'topic_id' => null,
        'time_limit' => $time,
        'questions' => $questions,
    ];

    if ($edit) {
        foreach ($tests as $i => $t) {
            if ($t['id'] === $edit['id']) {
                $tests[$i] = $payload;
                break;
            }
        }
        flash('success', 'Test yangilandi.');
    } else {
        $tests[] = $payload;
        flash('success', 'Test yaratildi.');
    }

    save_json('tests.json', $tests);
    redirect(base_url('admin/tests.php'));
}

$questions = $edit['questions'] ?? [
    ['text' => '', 'options' => ['', '', '', ''], 'correct' => 0],
    ['text' => '', 'options' => ['', '', '', ''], 'correct' => 0],
];

$page_title = $edit ? 'Testni tahrirlash' : 'Yangi test';
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem"><?= $edit ? 'Testni tahrirlash' : 'Yangi test' ?></h1>
            <a class="btn btn-ghost btn-sm" href="<?= base_url('admin/tests.php') ?>">Ro‘yxat</a>
        </div>

        <form method="post" id="testForm">
            <div class="form-group">
                <label>Nomi</label>
                <input type="text" name="title" required value="<?= e($edit['title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Tavsif</label>
                <textarea name="description" rows="2"><?= e($edit['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Turi</label>
                <select name="type">
                    <?php foreach (['topic' => 'Mavzu', 'pretest' => 'Boshlang‘ich', 'posttest' => 'Yakuniy'] as $k => $lab): ?>
                        <option value="<?= $k ?>" <?= ($edit['type'] ?? 'topic') === $k ? 'selected' : '' ?>><?= e($lab) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Vaqt (daqiqa)</label>
                <input type="number" name="time_limit" min="1" value="<?= (int)($edit['time_limit'] ?? 15) ?>">
            </div>

            <h2 style="font-size:1.05rem;margin-top:1.5rem">Savollar</h2>
            <div id="questions"></div>
            <p>
                <button type="button" class="btn btn-ghost btn-sm" id="addQuestion">+ Savol qo‘shish</button>
            </p>
            <button type="submit" class="btn btn-primary">Saqlash</button>
        </form>
    </div>
</div>

<template id="qTemplate">
    <div class="question-block q-edit">
        <div class="form-group">
            <label>Savol</label>
            <input type="text" name="questions[IDX][text]" required>
        </div>
        <div class="form-group">
            <label>Variantlar</label>
            <input type="text" name="questions[IDX][options][]" placeholder="A" required>
            <input type="text" name="questions[IDX][options][]" placeholder="B" required style="margin-top:0.35rem">
            <input type="text" name="questions[IDX][options][]" placeholder="C" style="margin-top:0.35rem">
            <input type="text" name="questions[IDX][options][]" placeholder="D" style="margin-top:0.35rem">
        </div>
        <div class="form-group">
            <label>To‘g‘ri javob</label>
            <select name="questions[IDX][correct]">
                <option value="0">A</option>
                <option value="1">B</option>
                <option value="2">C</option>
                <option value="3">D</option>
            </select>
        </div>
        <button type="button" class="btn btn-danger btn-sm remove-q">Savolni olib tashlash</button>
    </div>
</template>

<script>
    const box = document.getElementById('questions');
    const tpl = document.getElementById('qTemplate').innerHTML;
    const initial = <?= json_encode($questions, JSON_UNESCAPED_UNICODE) ?>;
    let n = 0;

    function addQuestion(data) {
        const i = n++;
        const wrap = document.createElement('div');
        wrap.innerHTML = tpl.replaceAll('IDX', i);
        const node = wrap.firstElementChild;
        if (data) {
            const text = node.querySelector('input[name$="[text]"]');
            if (text) text.value = data.text || '';
            const opts = node.querySelectorAll('input[name$="[options][]"]');
            (data.options || []).forEach((o, k) => { if (opts[k]) opts[k].value = o; });
            const sel = node.querySelector('select');
            if (sel) sel.value = String(data.correct ?? 0);
        }
        node.querySelector('.remove-q').addEventListener('click', () => {
            if (box.children.length > 1) node.remove();
        });
        box.appendChild(node);
    }

    initial.forEach(addQuestion);
    document.getElementById('addQuestion').addEventListener('click', () => addQuestion(null));
</script>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
