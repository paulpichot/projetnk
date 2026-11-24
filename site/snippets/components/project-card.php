<?php
/**
 * Carte projet : toute la carte est cliquable, son lien (nommé par le titre) étant posé par-dessus le contenu.
 * Lien hors du titre : les animations d'apparition (reveal.js) déplacent le titre, qui ne pourrait plus porter
 * un lien étendu à toute la carte
 * Illustration teintée en corail (calque fondu en « éclaircir » : les traits foncés prennent la couleur, le blanc
 * reste blanc) ; au survol (ou focus du lien), la carte passe au corail et l'illustration retrouve ses traits encre,
 * sur fond ivoire (calque fondu en « produit » : le blanc prend la couleur, les traits restent foncés)
 * @var \Kirby\Cms\Page $project
 */
$upcoming = $project->upcoming()->toBool();
$hover = $upcoming ? '' : 'group-hover:bg-corail group-has-focus-visible:bg-corail';
?>
<article class="group relative grid min-h-[18.25rem] grid-cols-[minmax(0,1fr)] gap-2 bg-blanc p-2 transition-colors duration-300 lg:grid-cols-[18.8125rem_1fr] <?= $upcoming ? '' : 'hover:bg-corail has-focus-visible:bg-corail' ?>" data-reveal="box">
    <?php if ($image = $project->thumbnail()->toFile()): ?>
        <div class="relative isolate overflow-hidden">
            <img class="block aspect-[319/200] h-auto w-full object-cover lg:aspect-auto lg:h-full" src="<?= $image->crop(602, 552)->url() ?>" srcset="<?= $image->srcset('card') ?>" sizes="(min-width: 1024px) 301px, calc(100vw - 2.5rem)" alt="" loading="lazy">
            <span class="absolute inset-0 bg-corail mix-blend-lighten transition-opacity duration-300 <?= $upcoming ? '' : 'group-hover:opacity-0 group-has-focus-visible:opacity-0' ?>"></span>
            <?php if (!$upcoming): ?>
                <span class="absolute inset-0 bg-ivoire opacity-0 mix-blend-multiply transition-opacity duration-300 group-hover:opacity-100 group-has-focus-visible:opacity-100"></span>
            <?php endif ?>
        </div>
    <?php else: ?>
        <div class="block aspect-[319/200] h-auto w-full object-cover lg:aspect-auto lg:h-full"></div>
    <?php endif ?>
    <div class="flex flex-col items-start">
        <?php /* Titre : fond encre posé ligne par ligne (bord droit suivant le texte), puis la période. Fonds des lignes
                 débordant d'1px l'un sur l'autre et sous la période (relative : dessinée par-dessus) : aucun filet clair aux
                 jonctions quand elles tombent entre deux pixels */ ?>
        <h3 class="font-medium leading-9 text-ivoire lg:max-w-[15.5rem]"><span class="box-decoration-clone bg-encre px-2 py-2"><?= $upcoming ? 'Coming soon' : $project->title()->esc() ?></span></h3>
        <p class="relative inline-flex h-8 items-center border-[1.5px] border-encre bg-blanc px-2 text-xs text-encre transition-colors duration-300 <?= $hover ?>"><?= $project->period()->esc() ?></p>
        <p class="mt-3 px-2"><?= $project->excerpt()->kti() ?></p>
        <?php if ($partner = $project->partner()->toFile()): ?>
            <?php /* Logo calé en bas à droite, toujours bien séparé de la description */ ?>
            <img class="mt-auto mr-4 mb-3 w-26 self-end pt-8 box-content" src="<?= $partner->resize(208)->url() ?>" alt="<?= $partner->alt()->esc('attr') ?>">
        <?php endif ?>
    </div>
    <?php if (!$upcoming): ?>
        <a class="absolute inset-0 z-1" href="<?= $project->url() ?>" aria-label="<?= $project->title()->esc('attr') ?>"></a>
    <?php endif ?>
</article>
