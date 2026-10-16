<?php
require_once __DIR__ . '/functions.php';
$user = current_user();
$current = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? "FazoTa'lim") ?> — FazoTa'lim</title>
    <meta name="description" content="Chizma geometriyani o‘qitishda fazoviy tasavvurni rivojlantiruvchi interaktiv o‘quv platforma">
    <meta name="author" content="<?= e(SITE_AUTHOR) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=10">
    <?php if (!empty($extra_css)): ?>
        <?php foreach ((array)$extra_css as $css): ?>
            <link rel="stylesheet" href="<?= asset('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
        window.FAZO = {
            baseUrl: <?= json_encode(rtrim(base_url(), '/')) ?>,
            assetUrl: <?= json_encode(rtrim(asset(''), '/')) ?>
        };
    </script>
</head>
<body class="<?= e($body_class ?? '') ?>">
    <div class="bg-grid" aria-hidden="true"></div>
    <header class="site-header">
        <div class="container header-inner">
            <a href="<?= base_url('index.php') ?>" class="logo">
                <span class="logo-mark" aria-hidden="true">
                    <svg viewBox="0 0 40 40" width="34" height="34">
                        <rect x="3" y="3" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M8 28 V12 H20 L28 20 V28 Z" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M8 28 H28 M20 12 V28 M20 20 H28" stroke="currentColor" stroke-width="1.25"/>
                        <circle cx="14" cy="20" r="1.4" fill="currentColor"/>
                    </svg>
                </span>
                <span class="logo-text">FazoTa<em>'lim</em></span>
            </a>

            <nav class="main-nav" id="mainNav">
                <?php $activeSection = current_section_id(); ?>
                <div class="nav-drop" id="navDrop">
                    <button type="button" class="nav-drop-btn <?= $activeSection ? 'active' : '' ?>" id="navDropBtn" aria-expanded="false">Bo‘limlar</button>
                    <div class="nav-drop-menu">
                        <?php foreach (course_sections() as $s): ?>
                            <a href="<?= base_url($s['file']) ?>" class="<?= $activeSection === $s['id'] ? 'active' : '' ?>">
                                <span><?= e($s['n']) ?></span><?= e($s['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?= base_url('pages/solids.php') ?>" class="<?= in_array($current, ['solids.php', 'lab.php'], true) ? 'active' : '' ?>">Shakllar</a>
                <a href="<?= base_url('pages/course.php?id=tasvirlash') ?>" class="<?= $activeSection === 'tasvirlash' ? 'active' : '' ?>">Proyeksiyalar</a>
                <a href="<?= base_url('pages/videos.php') ?>" class="<?= $activeSection === 'videodarslar' ? 'active' : '' ?>">Videodarslar</a>
                <a href="<?= base_url('pages/tests.php') ?>" class="<?= $activeSection === 'testlar' ? 'active' : '' ?>">Testlar</a>
                <a href="<?= base_url('pages/software.php') ?>" class="<?= $current === 'software.php' ? 'active' : '' ?>">Dasturlar</a>
                <?php if ($user): ?>
                    <a href="<?= base_url('pages/profile.php') ?>" class="nav-user <?= $current === 'profile.php' ? 'active' : '' ?>">
                        <?= e($user['name']) ?>
                    </a>
                    <?php if (($user['role'] ?? '') === 'admin'): ?>
                        <a href="<?= base_url('admin/index.php') ?>" class="nav-admin">Admin</a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <div class="header-cta">
                <?php if ($user): ?>
                    <a href="<?= base_url('pages/logout.php') ?>" class="btn btn-ghost btn-sm">Chiqish</a>
                <?php else: ?>
                    <a href="<?= base_url('pages/login.php') ?>" class="btn btn-primary btn-sm">Kirish</a>
                <?php endif; ?>
                <button class="nav-toggle" id="navToggle" aria-label="Menyu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <?php $flash = get_flash(); if ($flash): ?>
        <div class="flash flash-<?= e($flash['type']) ?>" role="alert">
            <div class="container"><?= e($flash['message']) ?></div>
        </div>
    <?php endif; ?>

    <main class="site-main">
