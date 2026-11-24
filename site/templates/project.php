<?php snippet('header') ?>
<?php /* Libellés de l'article, modifiables sur la page Projets (onglet « Textes des articles ») */ ?>
<?php $labels = $page->parent()->content() ?>

<article class="relative bg-blanc pt-[4.75rem] pb-14 lg:static lg:pt-14 lg:pb-30">
    <div class="wrapper">
        <nav class="eyebrow flex flex-wrap gap-2" aria-label="Fil d’Ariane">
            <a href="<?= $page->parent()->url() ?>"><?= $page->parent()->title()->esc() ?></a>
            <span class="text-encre/70" aria-hidden="true">/</span>
            <span class="text-encre/70" aria-current="page"><?= $page->title()->esc() ?></span>
        </nav>

        <div class="mt-5 lg:relative lg:mt-8">
            <?php /* Bouton Retour : sur desktop, dans la marge à gauche des étiquettes, il reste à l'écran pendant la lecture
                     (collant dans une colonne aussi haute que l'article) ; sur mobile, en haut de l'article */ ?>
            <div class="max-lg:contents lg:absolute lg:inset-y-0 lg:right-[calc(100%+1.25rem)]">
                <a class="btn absolute top-0 left-0 gap-2 lg:sticky lg:top-8" href="<?= $page->parent()->url() ?>"><?= svg('assets/icons/chevron-left.svg') ?><?= $labels->back_label()->or('Retour')->esc() ?></a>
            </div>

            <?php /* Titre sur une ligne en desktop ; en mobile, fond posé ligne par ligne comme sur les cartes, période juste dessous */ ?>
            <div class="flex flex-col items-start lg:flex-row lg:items-stretch">
                <p class="font-medium leading-9 text-ivoire lg:inline-flex lg:h-12 lg:items-center lg:bg-encre lg:px-4 lg:leading-7"><span class="box-decoration-clone bg-encre px-2 py-2 lg:bg-transparent lg:p-0"><?= $page->title()->esc() ?></span></p>
                <p class="relative inline-flex h-8 items-center border-[1.5px] border-encre bg-blanc px-2 text-xs text-encre lg:h-12 lg:px-4"><?= $page->period()->esc() ?></p>
            </div>

            <h1 class="title-h1 mt-[1.375rem] lg:mt-8"><?= $page->headline()->esc() ?></h1>

            <?php $tool = $page->tool()->toObject() ?>
            <?php if ($link = $tool->link()->toUrl()): ?>
                <?php /* Encart outil, ex. cartographie participative (les retours à la ligne forcés ne valent que pour le desktop) */ ?>
                <aside class="mt-8 flex flex-col items-start justify-between gap-6 bg-encre px-(--page-margin) py-8 text-ivoire lg:flex-row lg:items-center lg:gap-8 lg:px-10">
                    <div>
                        <p class="eyebrow text-corail"><?= $tool->label()->esc() ?></p>
                        <p class="text-h3 mt-2 max-lg:[&_br]:hidden"><?= $tool->text()->kti() ?></p>
                    </div>
                    <a class="btn max-lg:h-auto max-lg:min-h-12 max-lg:pt-3 max-lg:pb-3 max-lg:leading-5 max-lg:whitespace-normal" href="<?= $link ?>"><?= $tool->button()->esc() ?></a>
                </aside>
            <?php endif ?>

            <?php /* Chapeau, auteur et date : même largeur que le texte de l'article (800px) */ ?>
            <p class="mt-5 max-w-200 text-lg/7 font-light max-lg:max-w-[20.1875rem] lg:mt-8 lg:text-[1.375rem]/[2.125rem]"><?= $page->lead()->kti() ?></p>

            <div class="mt-4 mb-8 grid max-w-200 grid-cols-[auto_1fr] items-center gap-x-4 lg:mt-8 lg:mb-16 lg:flex lg:gap-4">
                <?php if ($photo = $page->author_photo()->toFile()): ?>
                    <img class="row-span-2 size-10 rounded-full bg-sable" src="<?= $photo->crop(80)->url() ?>" alt="">
                <?php else: ?>
                    <span class="row-span-2 size-10 rounded-full bg-sable"></span>
                <?php endif ?>
                <p class="text-[0.875rem] font-medium"><?= $labels->author_prefix()->or('Par')->esc() ?> <?= $page->author()->esc() ?></p>
                <p class="text-xs text-encre/70 lg:ml-auto">
                    Publié le <?= $page->published()->toDate('d MMMM y') ?> · <?= $page->reading_time()->esc() ?> min de lecture
                </p>
            </div>

            <?php if ($cover = $page->cover()->toFile()): ?>
                <?php snippet('figure', ['image' => $cover, 'caption' => $cover->caption()]) ?>
            <?php endif ?>

            <?php /* Contenu libre (blocs) : listes à puces corail issues de l'éditeur ; affiché tel quel, sans apparition au scroll.
                     Textes limités à 800px de large, images et encadrés sur toute la largeur */ ?>
            <div class="mt-8 space-y-5 [&>:not(figure,aside)]:max-w-200 text-base/6.5 lg:mt-16 lg:space-y-7 lg:text-lg [&_li]:relative [&_li]:pl-6 [&_li]:before:absolute [&_li]:before:top-3 [&_li]:before:left-0 [&_li]:before:size-1.5 [&_li]:before:rounded-full [&_li]:before:bg-corail [&_li+li]:mt-3" data-reveal="none">
                <?php foreach ($page->text()->toBlocks() as $block): ?>
                    <?php snippet('article/' . $block->type(), ['block' => $block]) ?>
                <?php endforeach ?>
            </div>

            <footer class="mt-8 flex flex-wrap items-center justify-between gap-2 max-lg:border-t-[1.5px] max-lg:border-encre max-lg:pt-6 lg:mt-16 lg:gap-6">
                <?php if ($page->tags()->isNotEmpty()): ?>
                    <ul class="flex flex-wrap gap-2">
                        <?php foreach ($page->tags()->split() as $tag): ?>
                            <li class="inline-flex h-[1.625rem] items-center bg-ivoire px-1.5 text-xs text-encre"><?= esc($tag) ?></li>
                        <?php endforeach ?>
                    </ul>
                <?php endif ?>
                <?php snippet('share') ?>
            </footer>
        </div>
    </div>
</article>

<?php if (($others = $page->siblings(false)->listed()->limit(2))->isNotEmpty()): ?>
    <section class="py-10 lg:pt-[4.5rem] lg:pb-30">
        <div class="wrapper-cards">
            <h2 class="eyebrow"><?= $labels->others_label()->or('Autres projets')->esc() ?></h2>
            <div class="mt-8 grid grid-cols-[minmax(0,1fr)] gap-4 lg:grid-cols-2 lg:gap-3">
                <?php foreach ($others as $project): ?>
                    <?php snippet('components/project-card', ['project' => $project]) ?>
                <?php endforeach ?>
            </div>
        </div>
    </section>
<?php endif ?>

<?php snippet('footer') ?>
