<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$software = load_json('software.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $software[] = [
            'id' => generate_id('soft_'),
            'name' => trim($_POST['name'] ?? ''),
            'vendor' => trim($_POST['vendor'] ?? ''),
            'category' => trim($_POST['category'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'uses' => array_values(array_filter(array_map('trim', explode(',', $_POST['uses'] ?? '')))),
            'level' => trim($_POST['level'] ?? 'Boshlang‘ich'),
            'resources' => [],
            'icon' => 'cad',
        ];
        save_json('software.json', $software);
        flash('success', 'Dastur qo‘shildi.');
        redirect(base_url('admin/software.php'));
    }
    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        $software = array_values(array_filter($software, fn($s) => $s['id'] !== $id));
        save_json('software.json', $software);
        flash('success', 'O‘chirildi.');
        redirect(base_url('admin/software.php'));
    }
}

$page_title = "Dasturlar (admin)";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Dasturlar katalogi</h1>
        </div>

        <div class="table-wrap" style="margin-bottom:1.5rem">
            <table>
                <thead>
                    <tr>
                        <th>Nomi</th>
                        <th>Vendor</th>
                        <th>Kategoriya</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($software as $s): ?>
                        <tr>
                            <td><?= e($s['name']) ?></td>
                            <td><?= e($s['vendor']) ?></td>
                            <td><?= e($s['category']) ?></td>
                            <td>
                                <form method="post" style="display:inline" onsubmit="return confirm('O‘chirasizmi?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= e($s['id']) ?>">
                                    <button class="btn btn-danger btn-sm" type="submit">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2 style="font-size:1.1rem">Yangi dastur</h2>
        <form method="post" style="max-width:560px">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label>Nomi</label><input name="name" required></div>
            <div class="form-group"><label>Vendor</label><input name="vendor"></div>
            <div class="form-group"><label>Kategoriya</label><input name="category"></div>
            <div class="form-group"><label>Tavsif</label><textarea name="description" rows="3"></textarea></div>
            <div class="form-group"><label>Qo‘llanilishi (vergul bilan)</label><input name="uses"></div>
            <div class="form-group"><label>Daraja</label><input name="level" value="Boshlang‘ich"></div>
            <button class="btn btn-primary" type="submit">Qo‘shish</button>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
