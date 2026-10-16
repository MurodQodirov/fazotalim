<?php
require_once dirname(__DIR__) . '/includes/functions.php';

if (is_logged_in()) {
    redirect(base_url('index.php'));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Barcha maydonlarni to‘ldiring.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email noto‘g‘ri.';
    } elseif (strlen($password) < 6) {
        $error = 'Parol kamida 6 belgi bo‘lsin.';
    } elseif ($password !== $password2) {
        $error = 'Parollar mos kelmadi.';
    } else {
        $users = load_json('users.json');
        foreach ($users as $u) {
            if (strcasecmp($u['email'], $email) === 0) {
                $error = 'Bu email band.';
                break;
            }
        }

        if ($error === '') {
            $users[] = [
                'id' => generate_id('stu_'),
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'student',
                'created_at' => date('Y-m-d H:i:s'),
            ];
            save_json('users.json', $users);
            flash('success', 'Ro‘yxatdan o‘tdingiz. Endi kiring.');
            redirect(base_url('pages/login.php'));
        }
    }
}

$page_title = "Ro‘yxatdan o‘tish";
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1>Ro‘yxatdan o‘tish</h1>

        <?php if ($error): ?>
            <div class="flash flash-error" style="border-radius:10px;margin-bottom:1rem;padding:0.75rem"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="name">Ism</label>
                <input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Parol</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password2">Parolni takrorlang</label>
                <input type="password" id="password2" name="password2" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Yaratish</button>
        </form>

        <p style="margin-top:1rem;text-align:center;color:var(--muted)">
            Akkount bormi? <a href="<?= base_url('pages/login.php') ?>">Kirish</a>
        </p>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
