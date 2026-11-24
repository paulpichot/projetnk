    </main>
    <footer class="bg-blanc" role="contentinfo">
        <div class="wrapper-wide flex flex-wrap justify-between gap-8 pt-10 max-lg:flex-col lg:pt-14">
            <a class="block w-[6.875rem] *:block *:h-auto *:w-full" href="<?= $site->url() ?>" aria-label="<?= $site->title()->esc() ?>">
                <?php if ($logo = $site->logomark()->toFile()): ?>
                    <?= svg($logo) ?>
                <?php endif ?>
            </a>
            <nav class="grid grid-cols-[10.6875rem_1fr] lg:grid-cols-[11.625rem_13.5rem]" aria-label="Navigation secondaire">
                <?php snippet('footer/menu-footer') ?>
                <?php snippet('footer/menu-socials') ?>
            </nav>
        </div>
        <div class="wrapper-wide flex flex-wrap justify-between gap-4 pt-13 pb-[1.9375rem] text-xs max-lg:flex-col lg:pb-[3.4375rem]">
            <p>© <?= date('Y') ?> <?= $site->title()->esc() ?></p>
            <?php snippet('menu-legal', ['class' => 'flex flex-wrap gap-5 max-lg:flex-col max-lg:gap-2', 'linkClass' => 'link-underline']) ?>
        </div>
    </footer>
    <?php snippet('foot') ?>
