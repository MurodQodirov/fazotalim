<?php
require_once __DIR__ . '/functions.php';
$user = current_user();
$current = basename($_SERVER['PHP_SELF'] ?? '');
?>
<!DOCTYPE html>
<html lang="<?= current_lang() === 'ru' ? 'ru' : 'uz' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? "FazoTa'lim") ?> — FazoTa'lim</title>
    <meta name="description" content="<?= e(t('meta.description')) ?>">
    <meta name="author" content="<?= e(SITE_AUTHOR) ?>, <?= e(supervisor_name()) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=14">
    <?php if (!empty($extra_css)): ?>
        <?php foreach ((array)$extra_css as $css): ?>
            <link rel="stylesheet" href="<?= asset('css/' . $css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <script>
        window.FAZO = {
            baseUrl: <?= json_encode(rtrim(base_url(), '/')) ?>,
            assetUrl: <?= json_encode(rtrim(asset(''), '/')) ?>,
            lang: <?= json_encode(current_lang()) ?>,
            shapes: {
                cube: <?= json_encode(t('shape.cube')) ?>,
                pyramid: <?= json_encode(t('shape.pyramid')) ?>,
                cone: <?= json_encode(t('shape.cone')) ?>,
                cylinder: <?= json_encode(t('shape.cylinder')) ?>,
                prism: <?= json_encode(t('shape.prism')) ?>,
                sphere: <?= json_encode(t('shape.sphere')) ?>
            }
        };
    </script>
</head>
<body class="<?= e($body_class ?? '') ?>">
    <div class="bg-grid" aria-hidden="true"></div>
    <div class="geo-field" aria-hidden="true">
        <svg class="geo-solid s1" viewBox="0 0 80 80"><path d="M14 30 L40 14 L66 30 V52 L40 68 L14 52 Z" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M14 30 L40 46 L66 30 M40 46 V68" fill="none" stroke="currentColor" stroke-width="1.2"/></svg>
        <svg class="geo-solid s2 gold" viewBox="0 0 80 80"><path d="M16 58 L40 14 L64 58 Z" fill="none" stroke="currentColor" stroke-width="1.4"/><path d="M40 14 V58" stroke="currentColor" stroke-width="1.1" stroke-dasharray="3 3"/></svg>
        <svg class="geo-solid s3" viewBox="0 0 80 80"><ellipse cx="40" cy="58" rx="20" ry="8" fill="none" stroke="currentColor" stroke-width="1.3"/><path d="M20 58 L40 12 L60 58" fill="none" stroke="currentColor" stroke-width="1.3"/></svg>
        <svg class="geo-solid s4 gold" viewBox="0 0 80 80"><ellipse cx="40" cy="40" rx="22" ry="22" fill="none" stroke="currentColor" stroke-width="1.3"/><ellipse cx="40" cy="40" rx="22" ry="8" fill="none" stroke="currentColor" stroke-width="1"/><path d="M40 18 V62" stroke-dasharray="2 3" stroke="currentColor"/></svg>
        <svg class="geo-solid s5" viewBox="0 0 80 80"><path d="M12 42 H68 M22 42 V22 H44 M22 42 V62 H44" fill="none" stroke="currentColor" stroke-width="1.3"/><circle cx="44" cy="22" r="2.2" fill="currentColor"/><circle cx="44" cy="62" r="2.2" fill="currentColor"/></svg>
        <svg class="geo-solid s6 gold" viewBox="0 0 80 80"><path d="M14 54 C22 18, 34 62, 42 30 S58 18, 66 48" fill="none" stroke="currentColor" stroke-width="1.5"/></svg>
    </div>
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
                    <button type="button" class="nav-drop-btn <?= $activeSection ? 'active' : '' ?>" id="navDropBtn" aria-expanded="false"><?= e(t('nav.sections')) ?></button>
                    <div class="nav-drop-menu">
                        <?php foreach (course_sections() as $s): ?>
                            <a href="<?= base_url($s['file']) ?>" class="<?= $activeSection === $s['id'] ? 'active' : '' ?>">
                                <span><?= e($s['n']) ?></span><?= e($s['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="<?= base_url('pages/solids.php') ?>" class="<?= in_array($current, ['solids.php', 'lab.php'], true) ? 'active' : '' ?>"><?= e(t('nav.shapes')) ?></a>
                <a href="<?= base_url('pages/course.php?id=tasvirlash') ?>" class="<?= $activeSection === 'tasvirlash' ? 'active' : '' ?>"><?= e(t('nav.projections')) ?></a>
                <a href="<?= base_url('pages/videos.php') ?>" class="<?= $activeSection === 'videodarslar' ? 'active' : '' ?>"><?= e(t('nav.videos')) ?></a>
                <a href="<?= base_url('pages/tests.php') ?>" class="<?= $activeSection === 'testlar' ? 'active' : '' ?>"><?= e(t('nav.tests')) ?></a>
                <a href="<?= base_url('pages/software.php') ?>" class="<?= $current === 'software.php' ? 'active' : '' ?>"><?= e(t('nav.software')) ?></a>
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
                <nav class="lang-switch" aria-label="Language">
                    <a href="<?= e(lang_url('uz')) ?>" class="<?= current_lang() === 'uz' ? 'active' : '' ?>">UZ</a>
                    <a href="<?= e(lang_url('ru')) ?>" class="<?= current_lang() === 'ru' ? 'active' : '' ?>">RU</a>
                </nav>
                <?php if ($user): ?>
                    <a href="<?= base_url('pages/logout.php') ?>" class="btn btn-ghost btn-sm"><?= e(t('nav.logout')) ?></a>
                <?php else: ?>
                    <a href="<?= base_url('pages/login.php') ?>" class="btn btn-primary btn-sm"><?= e(t('nav.login')) ?></a>
                <?php endif; ?>
                <button class="nav-toggle" id="navToggle" aria-label="<?= e(t('nav.menu')) ?>" aria-expanded="false">
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
