<?php snippet('head') ?>
    <header class="header relative z-2 max-lg:in-[.is-menu-open]:sticky max-lg:in-[.is-menu-open]:top-0" role="banner">
        <div class="wrapper-wide flex flex-wrap items-center justify-between gap-4 py-3.5 lg:py-[2.1875rem]">
            <a class="header__logo block w-32 *:block *:h-auto *:w-full max-lg:relative max-lg:z-1 lg:w-[11.9375rem] lg:motion-safe:in-[.intro]:invisible" href="<?= $site->url() ?>" aria-label="<?= $site->title()->esc() ?>">
                <?php if ($logo = $site->logoblack()->toFile()): ?>
                    <?= svg($logo) ?>
                <?php else: ?>
                    <?= $site->title()->esc() ?>
                <?php endif ?>
            </a>
            <button class="header__toggle relative z-1 block p-1.5 *:block lg:hidden [&[aria-expanded=false]>svg+svg]:hidden [&[aria-expanded=true]>svg:first-child]:hidden" type="button" aria-expanded="false" aria-controls="nav" aria-label="Ouvrir le menu">
                <?= svg('assets/icons/menu.svg') ?>
                <?= svg('assets/icons/close.svg') ?>
            </button>
            <?php snippet('header/nav') ?>
        </div>
    </header>
    <main id="content" role="main">
