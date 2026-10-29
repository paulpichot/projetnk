<?php snippet('head') ?>
    <header class="header" role="banner">
        <div class="wrapper-wide header__inner">
            <a class="header__logo" href="<?= $site->url() ?>" aria-label="<?= $site->title()->esc() ?>">
                <?php if ($logo = $site->logoblack()->toFile()): ?>
                    <?= svg($logo) ?>
                <?php else: ?>
                    <?= $site->title()->esc() ?>
                <?php endif ?>
            </a>
            <button class="header__toggle" type="button" aria-expanded="false" aria-controls="nav" aria-label="Ouvrir le menu">
                <?= svg('assets/icons/menu.svg') ?>
                <?= svg('assets/icons/close.svg') ?>
            </button>
            <?php snippet('header/nav') ?>
        </div>
    </header>
    <main id="content" role="main">
