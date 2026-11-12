</main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <strong>FazoTa'lim</strong>
                <p><?= e(t('footer.tagline')) ?></p>
                <p class="footer-author"><?= e(t('author')) ?>: <?= e(SITE_AUTHOR) ?></p>
                <p class="footer-author"><?= e(t('supervisor')) ?>: <?= e(supervisor_name()) ?></p>
            </div>
            <?php
            $footerSections = course_sections();
            $footerMid = array_slice($footerSections, 0, 7);
            $footerEnd = array_slice($footerSections, 7);
            ?>
            <div>
                <h4><?= e(t('nav.sections')) ?></h4>
                <ul>
                    <?php foreach ($footerMid as $s): ?>
                        <li><a href="<?= base_url($s['file']) ?>"><?= e($s['n']) ?>. <?= e($s['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h4><?= e(t('footer.materials')) ?></h4>
                <ul>
                    <?php foreach ($footerEnd as $s): ?>
                        <li><a href="<?= base_url($s['file']) ?>"><?= e($s['n']) ?>. <?= e($s['title']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= base_url('pages/software.php') ?>"><?= e(t('nav.software')) ?></a></li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© <?= date('Y') ?> FazoTa'lim · <?= e(SITE_AUTHOR) ?> · <?= e(supervisor_name()) ?></span>
        </div>
    </footer>

    <script src="<?= asset('js/main.js') ?>?v=9"></script>
    <?php if (!empty($extra_js)): ?>
        <?php foreach ((array)$extra_js as $js): ?>
            <?php if (str_starts_with($js, 'http')): ?>
                <script src="<?= e($js) ?>"></script>
            <?php else: ?>
                <script src="<?= asset('js/' . $js) ?>?v=9"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
