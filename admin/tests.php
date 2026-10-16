<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$tests = load_json('tests.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = $_POST['id'] ?? '';
    $tests = array_values(array_filter($tests, fn($t) => ($t['id'] ?? '') !== $id));
    save_json('tests.json', $tests);
    flash('success', 'Test o‘chirildi.');
    redirect(base_url('admin/tests.php'));
}

$page_title = "Testlar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Testlar</h1>
            <a class="btn btn-primary btn-sm" href="<?= base_url('admin/test-form.php') ?>">Yangi test</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nomi</th>
                        <th>Turi</th>
                        <th>Savollar</th>
                        <th>Vaqt</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$tests): ?>
                        <tr><td colspan="5">Hali test yo‘q.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($tests as $t): ?>
                        <tr>
                            <td><?= e($t['title']) ?></td>
                            <td><?= e($t['type']) ?></td>
                            <td><?= count($t['questions'] ?? []) ?></td>
                            <td><?= (int)($t['time_limit'] ?? 0) ?> daq</td>
                            <td>
                                <a href="<?= base_url('admin/test-form.php?id=' . urlencode($t['id'])) ?>">Tahrirlash</a>
                                ·
                                <a href="<?= base_url('pages/take-test.php?id=' . urlencode($t['id'])) ?>">Ochish</a>
                                <form method="post" style="display:inline;margin-left:0.4rem" onsubmit="return confirm('O‘chirasizmi?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                    <button class="btn btn-danger btn-sm" type="submit">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
