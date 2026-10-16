<?php
$adminPage = basename($_SERVER['PHP_SELF'] ?? '');
?>
<nav class="admin-nav">
    <a href="<?= base_url('admin/index.php') ?>" class="<?= $adminPage === 'index.php' ? 'active' : '' ?>">Dashboard</a>
    <a href="<?= base_url('admin/users.php') ?>" class="<?= $adminPage === 'users.php' ? 'active' : '' ?>">Foydalanuvchilar</a>
    <a href="<?= base_url('admin/topics.php') ?>" class="<?= $adminPage === 'topics.php' ? 'active' : '' ?>">Mavzular</a>
    <a href="<?= base_url('admin/videos.php') ?>" class="<?= $adminPage === 'videos.php' ? 'active' : '' ?>">Video darsliklar</a>
    <a href="<?= base_url('admin/tests.php') ?>" class="<?= in_array($adminPage, ['tests.php', 'test-form.php'], true) ? 'active' : '' ?>">Testlar</a>
    <a href="<?= base_url('admin/results.php') ?>" class="<?= $adminPage === 'results.php' ? 'active' : '' ?>">Natijalar</a>
    <a href="<?= base_url('admin/software.php') ?>" class="<?= $adminPage === 'software.php' ? 'active' : '' ?>">Dasturlar</a>
    <a href="<?= base_url('index.php') ?>">← Saytga</a>
</nav>
