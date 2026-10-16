<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$topics = load_json('topics.json');
usort($topics, fn($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $topics[] = [
            'id' => generate_id('topic-'),
            'title' => trim($_POST['title'] ?? 'Yangi mavzu'),
            'section' => trim($_POST['section'] ?? 'practice'),
            'objective' => trim($_POST['objective'] ?? ''),
            'theory' => trim($_POST['theory'] ?? ''),
            'formulas' => array_values(array_filter(array_map('trim', explode("\n", $_POST['formulas'] ?? '')))),
            'example' => trim($_POST['example'] ?? ''),
            'tasks' => array_values(array_filter(array_map('trim', explode("\n", $_POST['tasks'] ?? '')))),
            'solid_id' => trim($_POST['solid_id'] ?? '') ?: null,
            'order' => count($topics) + 1,
        ];
        save_json('topics.json', $topics);
        flash('success', 'Mavzu qo‘shildi.');
        redirect(base_url('admin/topics.php'));
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        $topics = array_values(array_filter($topics, fn($t) => $t['id'] !== $id));
        save_json('topics.json', $topics);
        flash('success', 'Mavzu o‘chirildi.');
        redirect(base_url('admin/topics.php'));
    }
}

$page_title = "Mavzular";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Mavzular</h1>
        </div>

        <div class="table-wrap" style="margin-bottom:1.5rem">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sarlavha</th>
                        <th>Bo‘lim</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topics as $t): ?>
                        <tr>
                            <td><?= (int)($t['order'] ?? 0) ?></td>
                            <td>
                                <a href="<?= base_url('pages/theory.php?id=' . urlencode($t['id'])) ?>"><?= e($t['title']) ?></a>
                            </td>
                            <td><?= e($t['section'] ?? '') ?></td>
                            <td>
                                <form method="post" style="display:inline" onsubmit="return confirm('O‘chirasizmi?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($t['id']) ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 style="font-size:1.1rem">Yangi mavzu</h2>
        <form method="post" style="max-width:640px">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label>Sarlavha</label>
                <input type="text" name="title" required>
            </div>
            <div class="form-group">
                <label>Bo‘lim (practice / solids / architecture)</label>
                <input type="text" name="section" value="practice">
            </div>
            <div class="form-group">
                <label>O‘quv maqsadi</label>
                <textarea name="objective" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Nazariya</label>
                <textarea name="theory" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label>Formulalar (har qator — bitta)</label>
                <textarea name="formulas" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Misol</label>
                <textarea name="example" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Topshiriqlar (har qator — bitta)</label>
                <textarea name="tasks" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Solid ID (ixtiyoriy: cube, prism...)</label>
                <input type="text" name="solid_id">
            </div>
            <button type="submit" class="btn btn-primary">Qo‘shish</button>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
