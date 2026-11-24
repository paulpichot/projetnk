<?php snippet('header') ?>

<?php /* Écran d'arrivée (intro.js, desktop) : couvre le site, seuls le logo et le menu de l'en-tête passent au-dessus */ ?>
<div class="intro-screen fixed inset-0 z-1 hidden bg-ivoire lg:motion-safe:in-[.intro]:block" aria-hidden="true"></div>

<?php /* Invitation à défiler (hero.js) : apparaît après la frappe du titre quand « Qui sommes-nous » n'est pas à l'écran,
         s'efface au premier défilement */ ?>
<a class="hero__scroll invisible fixed inset-x-0 bottom-6 z-1 mx-auto grid size-12 place-items-center rounded-full border-[1.5px] border-encre bg-ivoire *:w-4 *:rotate-90 lg:bottom-8 lg:size-14 lg:*:w-[1.125rem]" href="#qui-sommes-nous" aria-label="Défiler jusqu’à « <?= $page->about_label()->esc('attr') ?> »" data-no-swup>
    <?= svg('assets/icons/arrow-right.svg') ?>
</a>

<?php /* Hero épinglé au scroll (hero.js) : les sections suivantes (relative) passent par-dessus.
         Sur téléphone, l'image est un peu plus haute que son format d'origine, recadrée sur les côtés */ ?>
<section class="hero">
    <div class="wrapper">
        <h1 class="hero__title title-display relative z-1 pt-[3.5625rem] in-[.js]:invisible lg:pt-[10.6875rem]"><?= $page->hero_title()->kti() ?></h1>
    </div>
    <?php if ($image = $page->hero_image()->toFile()): ?>
        <?php /* Sous 768px : image mobile dédiée (recadrage du centre de l'illustration), posée juste sous le titre ;
                 à défaut, l'image principale recadrée.
                 Desktop : l'image remonte sous le titre, son ciel ivoire se fondant dans la page (haut fondu en transparence).
                 Remontée calée sur la plus haute cheminée (15,33 % de la largeur sous le haut de l'image), posée à 6,94 % de
                 la largeur sous le titre (100px à 1440px) */ ?>
        <?php $mobile = $page->hero_image_mobile()->toFile() ?>
        <picture class="block lg:-mt-[8.39%] <?= $mobile ? 'max-md:mt-0 md:mt-10' : 'mt-10' ?>">
            <?php if ($mobile): ?>
                <source media="(max-width: 767px)" srcset="<?= $mobile->resize(750)->url() ?>">
            <?php endif ?>
            <img class="block h-auto w-full mask-[linear-gradient(to_bottom,transparent,#000_6%)] motion-safe:in-[.js]:invisible <?= $mobile ? '' : 'max-md:aspect-[3/2] max-md:object-cover' ?>" src="<?= $image->resize(1440)->url() ?>" srcset="<?= $image->srcset('full') ?>" sizes="100vw" alt="<?= $image->alt()->esc('attr') ?>">
        </picture>
    <?php endif ?>
</section>

<?php /* Qui sommes-nous (les retours à la ligne forcés ne valent que pour le desktop) */ ?>
<section class="relative bg-ivoire py-14 max-lg:[&_br]:hidden lg:py-30" id="qui-sommes-nous">
    <div class="wrapper grid grid-cols-1 lg:grid-cols-[17.25rem_1fr]">
        <?php /* Sur-titre et premier paragraphe animés dès l'arrivée sur la page, les paragraphes suivants au scroll */ ?>
        <h2 class="eyebrow" data-reveal="load"><?= $page->about_label()->esc() ?></h2>
        <div class="text-lead max-lg:mt-4 max-lg:max-w-[20.1875rem] [&_p+p]:mt-6 lg:[&_p+p]:mt-8"><?= preg_replace('/<p>/', '<p data-reveal="load">', $page->about_text()->kt(), 1) ?></div>
        <h3 class="eyebrow col-span-full mt-10 mb-[1.4375rem] lg:mt-22 lg:mb-11"><?= $page->actions_label()->kti() ?></h3>
        <div class="col-span-full grid grid-cols-1 gap-y-8 lg:grid-cols-3 lg:gap-x-[5.875rem] lg:gap-y-0" data-reveal-group>
            <?php foreach ($page->actions()->toStructure() as $action): ?>
                <?php snippet('components/step', ['number' => str_pad($action->indexOf() + 1, 2, '0', STR_PAD_LEFT), 'text' => $action->text()]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

<?php /* NK : Natural Killers (illustration des deux cellules qui flottent au scroll, parallax.js) */ ?>
<section class="relative overflow-hidden bg-sable pt-34 pb-44 lg:pt-70 lg:pb-[19.375rem]">
    <?php /* Desktop : 1049.5px de large, décalées de 95px à droite du centre ; mobile : hauteur de la section, centrées */ ?>
    <div class="absolute inset-y-0 left-1/2 aspect-[1050/653] h-full -translate-x-1/2 lg:bottom-auto lg:left-[calc(50%-32.796875rem+5.921875rem)] lg:h-auto lg:w-[65.59375rem] lg:translate-x-0" aria-hidden="true">
        <img class="absolute top-0 left-0 h-auto w-[54.38%]" src="<?= url('assets/images/nk-cellule-n.svg') ?>" alt="" data-parallax="80" data-parallax-rotate="5">
        <img class="absolute top-[18.68%] left-[45.14%] h-auto w-[54.86%]" src="<?= url('assets/images/nk-cellule-n.svg') ?>" alt="" data-parallax="-80" data-parallax-rotate="-4" data-parallax-angle="180">
    </div>
    <div class="wrapper relative flex flex-col lg:flex-row lg:justify-between">
        <div class="lg:w-[24.875rem]" data-parallax="40">
            <h2 class="text-h3"><?= $page->nk_title()->esc() ?></h2>
            <p class="text-body-lg mt-4"><?= $page->nk_text()->kti() ?></p>
        </div>
        <p class="text-body-lg mt-[0.6875rem] font-bold lg:mt-0 lg:w-[24.875rem]" data-parallax="-40"><?= $page->nk_highlight()->kti() ?></p>
    </div>
</section>

<?php /* Équipe, bureau */ ?>
<section class="relative bg-ivoire py-14 lg:py-30">
    <div class="wrapper">
        <div class="text-lead max-lg:max-w-[20.1875rem] max-lg:[&_br]:hidden"><?= $page->team_text()->kt() ?></div>

        <h2 class="eyebrow mt-14 mb-6 lg:mt-16 lg:mb-8"><?= $page->team_label()->esc() ?></h2>
        <div class="grid grid-cols-1 items-start gap-4 lg:grid-cols-5">
            <?php foreach ($page->team()->toStructure() as $member): ?>
                <?php snippet('components/member', ['member' => $member]) ?>
            <?php endforeach ?>
        </div>

        <div class="mt-12 grid grid-cols-1 items-start gap-4 lg:mt-20 lg:grid-cols-5">
            <h2 class="eyebrow mb-2 lg:col-span-2 lg:mb-0"><?= $page->board_label()->esc() ?></h2>
            <?php foreach ($page->board()->toStructure() as $member): ?>
                <?php snippet('components/member', ['member' => $member]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

<?php /* Conseil scientifique */ ?>
<section class="relative bg-ardoise py-5 text-blanc lg:py-22">
    <div class="wrapper">
        <?php snippet('components/people', ['title' => $page->council_label(), 'people' => $page->council()->toStructure()]) ?>
    </div>
</section>

<?php snippet('components/contact') ?>

<?php snippet('footer') ?>
