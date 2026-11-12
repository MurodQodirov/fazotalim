<?php
require_once __DIR__ . '/includes/functions.php';
$page_title = t('nav.home');
$body_class = "is-home";
$extra_js = [
    'three.min.js',
    'geometry.js',
    'hero-3d.js',
];
require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <div class="container">
        <div class="sheet">
            <div class="hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow"><?= e(t('author')) ?>: <?= e(SITE_AUTHOR) ?></p>
                    <p class="eyebrow eyebrow-sub"><?= e(t('supervisor')) ?>: <?= e(supervisor_name()) ?></p>
                    <h1><?= e(t('hero.title')) ?></h1>
                    <p class="hero-lead">
                        <?= e(t('hero.lead')) ?>
                    </p>
                    <div class="axis-chip" aria-hidden="true">
                        <svg viewBox="0 0 88 88" width="54" height="54">
                            <line x1="16" y1="72" x2="78" y2="72" stroke="currentColor"/>
                            <line x1="16" y1="72" x2="16" y2="14" stroke="currentColor"/>
                            <line x1="16" y1="72" x2="52" y2="40" stroke="#d4a017"/>
                            <polygon points="78,72 70,68 70,76" fill="currentColor"/>
                            <polygon points="16,14 12,22 20,22" fill="currentColor"/>
                            <polygon points="52,40 46,46 54,48" fill="#d4a017"/>
                            <text x="80" y="70" font-size="10">X</text>
                            <text x="8" y="12" font-size="10">Z</text>
                            <text x="54" y="38" font-size="10" fill="#d4a017">Y</text>
                        </svg>
                        <span><?= e(t('hero.axes')) ?></span>
                    </div>
                    <div class="hero-actions">
                        <a class="btn btn-primary" href="<?= base_url('pages/lab.php') ?>"><?= e(t('hero.lab')) ?></a>
                        <a class="btn btn-ghost" href="<?= base_url('pages/solids.php') ?>"><?= e(t('nav.shapes')) ?></a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-stage">
                        <svg class="iso-wire" viewBox="0 0 200 160" aria-hidden="true">
                            <g fill="none" stroke="rgba(255,215,0,.45)" stroke-width="1">
                                <path d="M40 110 L100 80 L160 110 L100 140 Z"/>
                                <path d="M40 110 L40 50 L100 20 L100 80"/>
                                <path d="M160 110 L160 50 L100 20"/>
                                <path d="M40 50 L100 80 L160 50" stroke-dasharray="3 4"/>
                            </g>
                        </svg>
                        <canvas id="heroCanvas" aria-label="<?= e(t('hero.canvas')) ?>"></canvas>
                        <div class="shape-tag" id="heroShapeTag"><?= e(t('shape.cube')) ?></div>
                    </div>
                    <div class="hero-views">
                        <div class="hero-proj">
                            <span><?= e(t('view.front')) ?></span>
                            <svg id="heroFront" viewBox="0 0 120 90"></svg>
                        </div>
                        <div class="hero-proj">
                            <span><?= e(t('view.top')) ?></span>
                            <svg id="heroTop" viewBox="0 0 120 90"></svg>
                        </div>
                        <div class="hero-proj">
                            <span><?= e(t('view.side')) ?></span>
                            <svg id="heroSide" viewBox="0 0 120 90"></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="shape-ribbon">
    <div class="container shape-ribbon-inner">
        <p><?= e(t('ribbon.title')) ?></p>
        <div class="shape-orbit">
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><path d="M16 26 L32 16 L48 26 V42 L32 52 L16 42 Z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M16 26 L32 36 L48 26 M32 36 V52" fill="none" stroke="currentColor" stroke-width="1.4"/></svg>
                <span><?= e(t('shape.cube')) ?></span>
            </a>
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><path d="M12 48 L32 12 L52 48 Z" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M32 12 V48" stroke="currentColor" stroke-width="1.2" stroke-dasharray="2 3"/></svg>
                <span><?= e(t('shape.pyramid')) ?></span>
            </a>
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><ellipse cx="32" cy="46" rx="16" ry="6" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M16 46 L32 12 L48 46" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                <span><?= e(t('shape.cone')) ?></span>
            </a>
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><ellipse cx="32" cy="16" rx="14" ry="6" fill="none" stroke="currentColor" stroke-width="1.6"/><ellipse cx="32" cy="48" rx="14" ry="6" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M18 16 V48 M46 16 V48" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                <span><?= e(t('shape.cylinder')) ?></span>
            </a>
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><ellipse cx="32" cy="32" rx="18" ry="18" fill="none" stroke="currentColor" stroke-width="1.6"/><ellipse cx="32" cy="32" rx="18" ry="7" fill="none" stroke="currentColor" stroke-width="1.2"/><path d="M32 14 V50" stroke="currentColor" stroke-width="1.2" stroke-dasharray="2 3"/></svg>
                <span><?= e(t('shape.sphere')) ?></span>
            </a>
            <a href="<?= base_url('pages/solids.php') ?>" class="orbit-item">
                <svg viewBox="0 0 64 64"><path d="M18 44 L32 16 L46 44" fill="none" stroke="currentColor" stroke-width="1.6"/><path d="M18 44 H46" stroke="currentColor" stroke-width="1.6"/><path d="M22 44 V36 H42 V44" fill="none" stroke="currentColor" stroke-width="1.3"/></svg>
                <span><?= e(t('shape.prism')) ?></span>
            </a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <p class="kicker"><?= e(t('kicker')) ?></p>
                <h2><?= e(t('home.sections')) ?></h2>
            </div>
        </div>
        <div class="card-grid">
            <?php foreach (course_sections() as $s): ?>
            <a class="module-card" href="<?= base_url($s['file']) ?>">
                <span class="module-icon"><?= e($s['n']) ?></span>
                <h3><?= e($s['title']) ?></h3>
                <p><?= e($s['lead']) ?></p>
                <span class="module-glyph"><?= section_glyph($s['id']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
