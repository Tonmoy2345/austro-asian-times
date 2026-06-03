
    </div>
</main>

<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-info">
                <strong><?= e(SITE_NAME) ?></strong>
                <p>News for Northern Australia and Southeast Asia</p>
            </div>
            <div class="footer-links">
                <a href="<?= BASE_URL ?>/">Home</a>
                <a href="<?= BASE_URL ?>/feed/rss">RSS Feed</a>
                <a href="<?= BASE_URL ?>/feed/atom">Atom Feed</a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="<?= BASE_URL ?>/logout">Logout</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/login">Staff Login</a>
                <?php endif; ?>
            </div>
        </div>
        <p class="footer-copy">&copy; <?= date('Y') ?> <?= e(SITE_NAME) ?>. Built with PHP &amp; MySQL.</p>
    </div>
</footer>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
