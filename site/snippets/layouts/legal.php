<?php snippet('header') ?>

<?php /* Grille : navigation à gauche (296px) sur deux rangées, en-tête puis contenu à droite ; une colonne en mobile.
         Pages sobres : textes affichés tels quels, sans apparition au scroll (data-reveal="none") */ ?>
<div data-reveal="none" class="wrapper grid grid-cols-[minmax(0,1fr)] content-start pt-[2.5625rem] pb-14 lg:grid-cols-[18.5rem_minmax(0,1fr)] lg:pt-22 lg:pb-30">
    <header class="lg:col-start-2 lg:row-start-1">
        <h1 class="title-h1"><?= $page->title()->esc() ?></h1>
        <?php if ($page->updated()->isNotEmpty()): ?>
            <p class="mt-[0.9375rem] text-xs text-encre/70 lg:mt-[1.0625rem]">Dernière mise à jour : <?= $page->updated()->toDate('d MMMM y') ?></p>
        <?php endif ?>
        <p class="text-body-lg mt-4 max-lg:text-base/[1.625rem]"><?= $page->intro()->kti() ?></p>
    </header>

    <nav class="max-lg:mt-8 lg:col-start-1 lg:row-span-2 lg:row-start-1" aria-label="Informations légales">
        <p class="eyebrow"><?= $site->menulegal_label()->or('Informations légales')->esc() ?></p>
        <?php snippet('menu-legal', [
            'class' => 'mt-[1.0625rem] flex flex-col items-start max-lg:items-stretch',
            'linkClass' => 'block px-3 py-2 text-small/6 aria-[current=page]:outline-[1.5px] aria-[current=page]:outline-encre',
        ]) ?>
    </nav>

    <div class="lg:col-start-2 lg:row-start-2">
        <?= $slot ?>
        <div class="[&_h2]:mt-[3.5625rem] [&_h2]:font-heading [&_h2]:text-[1.125rem]/6 [&_h2]:font-bold lg:[&_h2]:mt-[4.5625rem] lg:[&_h2]:text-[1.4375rem]/[1.3] [&_p]:mt-[0.8125rem] lg:[&_p]:mt-[0.6875rem]"><?= $page->text()->kt() ?></div>
    </div>
</div>

<?php snippet('footer') ?>
