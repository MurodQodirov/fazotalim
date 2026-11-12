<?php
require_once dirname(__DIR__) . '/includes/functions.php';
$page_title = t('sec.loyihalash');
$extra_js = [
    'three.min.js',
    'architecture.js',
];
require dirname(__DIR__) . '/includes/header.php';
?>

<div class="container page-hero">
    <h1><?= e(t('sec.loyihalash')) ?></h1>
    <p><?= e(t('arch.lead')) ?></p>
</div>

<div class="container arch-layout">
    <div>
        <div class="arch-viewport">
            <div class="lab-toolbar">
                <button type="button" data-arch-view="iso" class="active">3D</button>
                <button type="button" data-arch-view="plan"><?= e(t('arch.plan')) ?></button>
                <button type="button" data-arch-view="front"><?= e(t('arch.front')) ?></button>
                <button type="button" data-arch-view="side"><?= e(t('arch.side')) ?></button>
            </div>
            <canvas id="archCanvas"></canvas>
        </div>
        <div class="theory-content" style="margin-top:1rem">
            <h2><?= e(t('arch.task')) ?></h2>
            <ol class="step-list">
                <li><?= e(t('arch.t1')) ?></li>
                <li><?= e(t('arch.t2')) ?></li>
            </ol>
        </div>
    </div>

    <div class="arch-views">
        <div class="arch-view-card">
            <h3><?= e(t('arch.plan.card')) ?></h3>
            <svg viewBox="0 0 200 140" aria-hidden="true">
                <rect width="200" height="140" fill="#153a6b"/>
                <rect x="40" y="30" width="120" height="80" fill="rgba(255,215,0,0.08)" stroke="#fff" stroke-width="1.5"/>
                <rect x="90" y="100" width="20" height="10" fill="none" stroke="#ffd700" stroke-width="1.25"/>
                <line x1="100" y1="110" x2="100" y2="130" stroke="#ffd700" stroke-width="4" opacity="0.8"/>
                <rect x="55" y="45" width="18" height="14" fill="none" stroke="#fff" stroke-width="1.25"/>
                <rect x="127" y="45" width="18" height="14" fill="none" stroke="#fff" stroke-width="1.25"/>
                <text x="100" y="18" text-anchor="middle" fill="#ffd700" font-size="9" font-family="IBM Plex Mono, monospace" letter-spacing="1">REJA (H)</text>
            </svg>
            <p style="color:var(--graphite);font-size:0.9rem;margin:0.5rem 0 0"><?= e(t('arch.plan.p')) ?></p>
        </div>
        <div class="arch-view-card">
            <h3><?= e(t('arch.front.card')) ?></h3>
            <svg viewBox="0 0 200 140" aria-hidden="true">
                <rect width="200" height="140" fill="#153a6b"/>
                <polygon points="30,70 100,25 170,70" fill="rgba(255,215,0,0.08)" stroke="#fff" stroke-width="1.5"/>
                <rect x="45" y="70" width="110" height="50" fill="rgba(255,255,255,0.06)" stroke="#fff" stroke-width="1.5"/>
                <rect x="90" y="85" width="20" height="35" fill="none" stroke="#ffd700" stroke-width="1.25"/>
                <rect x="55" y="82" width="22" height="18" fill="none" stroke="#fff" stroke-width="1.25"/>
                <rect x="123" y="82" width="22" height="18" fill="none" stroke="#fff" stroke-width="1.25"/>
                <text x="100" y="16" text-anchor="middle" fill="#ffd700" font-size="9" font-family="IBM Plex Mono, monospace" letter-spacing="1">FASAD (V)</text>
            </svg>
            <p style="color:var(--graphite);font-size:0.9rem;margin:0.5rem 0 0"><?= e(t('arch.front.p')) ?></p>
        </div>
        <div class="arch-view-card">
            <h3><?= e(t('arch.side.card')) ?></h3>
            <svg viewBox="0 0 200 140" aria-hidden="true">
                <rect width="200" height="140" fill="#153a6b"/>
                <polygon points="55,70 100,25 145,70" fill="rgba(255,215,0,0.08)" stroke="#fff" stroke-width="1.5"/>
                <rect x="65" y="70" width="70" height="50" fill="rgba(255,255,255,0.06)" stroke="#fff" stroke-width="1.5"/>
                <rect x="88" y="82" width="24" height="18" fill="none" stroke="#fff" stroke-width="1.25"/>
                <text x="100" y="16" text-anchor="middle" fill="#ffd700" font-size="9" font-family="IBM Plex Mono, monospace" letter-spacing="1">YON (W)</text>
            </svg>
            <p style="color:var(--graphite);font-size:0.9rem;margin:0.5rem 0 0"><?= e(t('arch.side.p')) ?></p>
        </div>
        <div class="arch-view-card">
            <h3><?= e(t('arch.link')) ?></h3>
            <ul class="info-list">
                <li><span><?= e(t('arch.plan.short')) ?></span><span>H</span></li>
                <li><span><?= e(t('arch.front.short')) ?></span><span>V</span></li>
                <li><span><?= e(t('arch.side.short')) ?></span><span>W</span></li>
            </ul>
            <p style="color:var(--graphite);font-size:0.9rem;margin-top:0.75rem">
                <a href="<?= base_url('pages/theory.php?id=topic-08') ?>"><?= e(t('arch.read')) ?></a>
            </p>
        </div>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/footer.php'; ?>
