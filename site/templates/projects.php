<?php snippet('header') ?>

<section class="bg-sable py-12 lg:py-[5.5rem]">
    <div class="wrapper-cards">
        <h1 class="eyebrow"><?= $page->title()->esc() ?></h1>
        <div class="mt-6 grid grid-cols-[minmax(0,1fr)] gap-4 lg:mt-16 lg:grid-cols-2 lg:gap-3">
            <?php foreach ($page->children()->listed()->filterBy('upcoming', '!=', 'true') as $project): ?>
                <?php snippet('components/project-card', ['project' => $project]) ?>
            <?php endforeach ?>
            <?php if ($page->upcoming_title()->isNotEmpty()): ?>
                <?php /* Encart « Prochainement » (les retours à la ligne forcés ne valent que pour le desktop) */ ?>
                <aside class="flex flex-col items-start bg-ivoire px-(--page-margin) py-6 lg:p-8" data-reveal="box">
                    <p class="eyebrow"><?= $page->upcoming_label()->esc() ?></p>
                    <h2 class="text-lead mt-3"><?= $page->upcoming_title()->esc() ?></h2>
                    <p class="mt-3 mb-6 max-lg:[&_br]:hidden"><?= $page->upcoming_text()->kti() ?></p>
                    <?php if ($link = $page->upcoming_link()->toUrl()): ?>
                        <a class="btn-outline mt-auto" href="<?= $link ?>"><?= $page->upcoming_button()->esc() ?></a>
                    <?php endif ?>
                </aside>
            <?php endif ?>
        </div>
    </div>
</section>

<?php /* Missions sur fond ardoise : cadre blanc à gauche (862px de large en desktop), photo imprimée à droite,
         inclinée, passant sous le cadre. Desktop : tirage de 33,82 % de la largeur, centré à 73,96 % du bord
         gauche et à mi-hauteur. Mobile : au-dessus du cadre, qui en recouvre le bas */ ?>
<?php $image = $page->hero_image()->toFile() ?>
<section class="relative overflow-hidden bg-ardoise pt-14 pb-14 lg:py-[14.75rem]">
    <?php if ($image): ?>
        <div class="relative mx-auto -mb-16 w-[62%] max-w-xs rotate-[12.7deg] lg:absolute lg:top-1/2 lg:left-[73.96%] lg:m-0 lg:w-[33.82%] lg:max-w-none lg:-translate-x-1/2 lg:-translate-y-1/2" aria-hidden="true">
            <img class="block h-auto w-full" src="<?= $image->resize(1000)->url() ?>" width="<?= $image->width() ?>" height="<?= $image->height() ?>" alt="" loading="lazy">
        </div>
    <?php endif ?>
    <div class="wrapper-cards relative">
        <div class="bg-blanc p-3 lg:w-[53.875rem] lg:p-5" data-reveal="box">
            <h2 class="eyebrow max-lg:pt-2 max-lg:pl-1"><?= $page->statements_label()->or('Nos missions')->esc() ?></h2>
            <div class="mt-5 flex flex-col gap-3 lg:mt-8 lg:gap-4">
                <?php foreach ($page->statements()->toStructure() as $statement): ?>
                    <p class="bg-ivoire p-6 text-card-sm font-semibold lg:text-card-lg"><?= $statement->text()->kti() ?></p>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>

<?php snippet('components/contact', ['background' => 'ivoire']) ?>

<?php snippet('footer') ?>
