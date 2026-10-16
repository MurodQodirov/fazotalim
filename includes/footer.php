</main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <strong>FazoTa'lim</strong>
                <p>Chizma geometriya bo‘yicha interaktiv o‘quv platforma.</p>
                <p class="footer-author">Muallif: <?= e(SITE_AUTHOR) ?></p>
            </div>
            <?php
            $footerSections = course_sections();
            $footerMid = array_slice($footerSections, 0, 7);
            $footerEnd = array_slice($footerSections, 7);
            ?>
            <div>
                <h4>Bo‘limlar</h4>
                <ul>
                    <?php foreach ($footerMid as $s): ?>
                        <li><a href="<?= base_url($s['file']) ?>"><?= e($s['n']) ?>. <?= e($s['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h4>Materiallar</h4>
                <ul>
                    <?php foreach ($footerEnd as $s): ?>
                        <li><a href="<?= base_url($s['file']) ?>"><?= e($s['n']) ?>. <?= e($s['title']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="<?= base_url('pages/software.php') ?>">Dasturlar</a></li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© <?= date('Y') ?> FazoTa'lim · <?= e(SITE_AUTHOR) ?></span>
        </div>
    </footer>

    <script src="<?= asset('js/main.js') ?>?v=8"></script>
    <?php if (!empty($extra_js)): ?>
        <?php foreach ((array)$extra_js as $js): ?>
            <?php if (str_starts_with($js, 'http')): ?>
                <script src="<?= e($js) ?>"></script>
            <?php else: ?>
                <script src="<?= asset('js/' . $js) ?>?v=7"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
