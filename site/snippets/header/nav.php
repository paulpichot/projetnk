<?php
/**
 * Menu principal. Desktop : liens en segments encadrés (le fond noir du lien actif glisse au survol, nav.js).
 * Mobile : panneau plein écran ouvert par le bouton du header (classe is-menu-open sur <html>, menu.js).
 */
$linkClass = implode(' ', [
    'nav__link flex items-center font-heading font-medium',
    'lg:h-[calc(3rem-3px)] lg:px-[0.8125rem] lg:aria-[current=page]:bg-encre lg:aria-[current=page]:text-ivoire',
    'group-data-indicator/nav:relative group-data-indicator/nav:transition-colors group-data-indicator/nav:duration-150',
    'group-data-indicator/nav:aria-[current=page]:bg-transparent group-data-indicator/nav:aria-[current=page]:text-inherit',
    'group-data-indicator/nav:data-highlighted:text-ivoire group-data-indicator/nav:data-highlighted:delay-40',
    'max-lg:justify-between max-lg:border-b-[1.5px] max-lg:border-encre max-lg:pt-[1.1875rem] max-lg:pb-[1.125rem] max-lg:text-[1.75rem]/9',
    "max-lg:aria-[current=page]:pr-[0.9375rem] max-lg:aria-[current=page]:pl-3 max-lg:aria-[current=page]:after:size-6 max-lg:aria-[current=page]:after:bg-[url(/assets/icons/arrow-right.svg)] max-lg:aria-[current=page]:after:bg-contain max-lg:aria-[current=page]:after:bg-center max-lg:aria-[current=page]:after:bg-no-repeat max-lg:aria-[current=page]:after:content-['']",
]);
?>
<nav class="nav hidden lg:flex lg:flex-wrap lg:motion-safe:in-[.intro]:invisible max-lg:in-[.is-menu-open]:fixed max-lg:in-[.is-menu-open]:inset-0 max-lg:in-[.is-menu-open]:flex max-lg:in-[.is-menu-open]:flex-col max-lg:in-[.is-menu-open]:bg-ivoire max-lg:in-[.is-menu-open]:px-(--page-margin) max-lg:in-[.is-menu-open]:pt-22 max-lg:in-[.is-menu-open]:pb-[1.9375rem]" id="nav" aria-label="Navigation principale">
    <?php if ($site->menuprimary()->isNotEmpty()): ?>
        <div class="nav__list group/nav flex data-indicator:relative max-lg:flex-col lg:border-[1.5px] lg:border-encre">
            <?php foreach ($site->menuprimary()->toStructure() as $item): ?>
                <?php snippet('header/nav-link', ['item' => $item, 'class' => $linkClass]) ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if ($cta = $site->menucta()->toStructure()->first()): ?>
        <?php /* Desktop : au survol, le bouton s'élargit vers la marge de droite (marge négative égale), sans pousser le menu */ ?>
        <?php snippet('header/nav-link', ['item' => $cta, 'class' => 'nav__cta btn in-[.is-support]:not-data-animated:hidden max-lg:mt-auto max-lg:w-full max-lg:justify-center lg:transition-[background-color,border-color,color,padding,margin] lg:duration-300 lg:ease-out lg:hover:-mr-4 lg:hover:px-5 lg:focus-visible:-mr-4 lg:focus-visible:px-5']) ?>
    <?php endif ?>
    <?php /* Mobile : réseaux sociaux (liens externes de « Nous suivre ») sous le bouton */ ?>
    <?php /* Liens externes seulement (« # » seul : lien en attente, laissé tel quel) */ ?>
    <?php $href = fn ($item) => $item->link()->value() === '#' ? '#' : $item->link()->toUrl() ?>
    <?php $socials = $site->menusocials()->toStructure()->filter(fn ($item) => !str_starts_with($href($item) ?? '', $site->url())) ?>
    <?php if ($socials->isNotEmpty()): ?>
        <ul class="hidden max-lg:mt-6 max-lg:flex max-lg:justify-center max-lg:gap-6 max-lg:text-small max-lg:font-medium">
            <?php foreach ($socials as $item): ?>
                <?php $external = str_starts_with($href($item), 'http') ?>
                <li><a <?= attr(['href' => $href($item), 'target' => $external ? '_blank' : null, 'rel' => $external ? 'noopener' : null]) ?>><?= $item->title()->esc() ?></a></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
    <?php if ($site->menumobile()->isNotEmpty()): ?>
        <ul class="list-dots hidden max-lg:mt-[1.3125rem] max-lg:block max-lg:text-xs max-lg:text-encre/70">
            <?php foreach ($site->menumobile()->toStructure() as $item): ?>
                <li><?php snippet('header/nav-link', ['item' => $item]) ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</nav>
