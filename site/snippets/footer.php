    </main>
    <footer class="footer" role="contentinfo">
        <div class="wrapper-wide footer__top">
            <a class="footer__logo" href="<?= $site->url() ?>" aria-label="<?= $site->title()->esc() ?>">
                <?php if ($logo = $site->logomark()->toFile()): ?>
                    <?= svg($logo) ?>
                <?php endif ?>
            </a>
            <nav class="footer__nav" aria-label="Navigation secondaire">
                <?php snippet('footer/menu-footer') ?>
                <?php snippet('footer/menu-socials') ?>
            </nav>
        </div>
        <div class="wrapper-wide footer__bottom">
            <p>© <?= date('Y') ?> <?= $site->title()->esc() ?></p>
            <?php snippet('menu-legal', ['class' => 'footer__legal']) ?>
        </div>
    </footer>
    <?php snippet('foot') ?>
