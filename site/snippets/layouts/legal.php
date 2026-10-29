<?php snippet('header') ?>

<div class="wrapper legal">
    <header class="legal__header">
        <h1 class="title-h1"><?= $page->title()->esc() ?></h1>
        <?php if ($page->updated()->isNotEmpty()): ?>
            <p class="legal__date">Dernière mise à jour : <?= $page->updated()->toDate('d MMMM y') ?></p>
        <?php endif ?>
        <p class="text-body-lg legal__intro"><?= $page->intro()->kti() ?></p>
    </header>

    <nav class="legal__nav" aria-label="Informations légales">
        <p class="eyebrow">Informations légales</p>
        <?php snippet('menu-legal', ['class' => 'legal__links', 'linkClass' => 'legal__link']) ?>
    </nav>

    <div class="legal__body">
        <?= $slot ?>
        <div class="legal__text"><?= $page->text()->kt() ?></div>
    </div>
</div>

<?php snippet('footer') ?>
