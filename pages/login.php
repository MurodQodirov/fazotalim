<?php
require_once dirname(__DIR__) . '/includes/functions.php';

if (is_logged_in()) {
    redirect(base_url('index.php'));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $users = load_json('users.json');
    $found = null;

    foreach ($users as $u) {
        if (strcasecmp($u['email'], $email) === 0) {
            $found = $u;
            break;
        }
    }

    if ($found && password_verify($password, $found['password'])) {
        $_SESSION['user_id'] = $found['id'];
        flash('success', 'Xush kelibsiz, ' . $found['name'] . '!');
        if (($found['role'] ?? '') === 'admin') {
            redirect(base_url('admin/index.php'));
        }
        redirect(base_url('index.php'));
    }

    $error = 'Email yoki parol noto‘g‘ri.';
}

$page_title = "Kirish";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1>Tizimga kirish</h1>

        <?php if ($error): ?>
            <div class="flash flash-error" style="border-radius:10px;margin-bottom:1rem;padding:0.75rem"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Parol</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Kirish</button>
        </form>

        <p style="margin-top:1rem;text-align:center;color:var(--muted)">
            Akkount yo‘qmi? <a href="<?= base_url('pages/register.php') ?>">Ro‘yxatdan o‘tish</a>
        </p>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
