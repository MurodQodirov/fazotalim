<?php
require_once dirname(__DIR__) . '/includes/functions.php';
require_admin();

$users = load_json('users.json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = $_POST['id'] ?? '';
    if ($id === current_user()['id']) {
        flash('error', 'O‘zingizni o‘chira olmaysiz.');
    } else {
        $users = array_values(array_filter($users, fn($u) => $u['id'] !== $id));
        save_json('users.json', $users);
        flash('success', 'Foydalanuvchi o‘chirildi.');
    }
    redirect(base_url('admin/users.php'));
}

$page_title = "Foydalanuvchilar";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container admin-layout">
    <?php require __DIR__ . '/_nav.php'; ?>
    <div class="admin-content">
        <div class="admin-actions">
            <h1 style="margin:0;font-size:1.4rem">Foydalanuvchilar</h1>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Ism</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Sana</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= e($u['name']) ?></td>
                            <td><?= e($u['email']) ?></td>
                            <td><?= e($u['role']) ?></td>
                            <td><?= e(format_date($u['created_at'] ?? '')) ?></td>
                            <td>
                                <?php if ($u['id'] !== current_user()['id']): ?>
                                    <form method="post" style="display:inline" onsubmit="return confirm('O‘chirasizmi?')">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= e($u['id']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">O‘chirish</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color:var(--muted)">Siz</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
