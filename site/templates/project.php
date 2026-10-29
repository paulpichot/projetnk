<?php snippet('header') ?>

<article class="article">
    <div class="wrapper">
        <nav class="eyebrow article__breadcrumb" aria-label="Fil d’Ariane">
            <a href="<?= $page->parent()->url() ?>"><?= $page->parent()->title()->esc() ?></a>
            <span aria-hidden="true">/</span>
            <span aria-current="page"><?= $page->title()->esc() ?></span>
        </nav>

        <div class="article__tags">
            <a class="btn article__back" href="<?= $page->parent()->url() ?>"><?= svg('assets/icons/chevron-left.svg') ?>Retour</a>
            <p class="tag"><?= $page->title()->esc() ?></p>
            <p class="tag tag--outline"><?= $page->period()->esc() ?></p>
        </div>

        <h1 class="title-h1 article__title"><?= $page->headline()->esc() ?></h1>

        <?php $tool = $page->tool()->toObject() ?>
        <?php if ($link = $tool->link()->toUrl()): ?>
            <aside class="tool">
                <div>
                    <p class="eyebrow tool__label"><?= $tool->label()->esc() ?></p>
                    <p class="text-h3 tool__text"><?= $tool->text()->kti() ?></p>
                </div>
                <a class="btn tool__button" href="<?= $link ?>"><?= $tool->button()->esc() ?></a>
            </aside>
        <?php endif ?>

        <p class="article__lead"><?= $page->lead()->kti() ?></p>

        <div class="article__meta">
            <?php if ($photo = $page->author_photo()->toFile()): ?>
                <img class="article__avatar" src="<?= $photo->crop(80)->url() ?>" alt="">
            <?php else: ?>
                <span class="article__avatar"></span>
            <?php endif ?>
            <p class="article__author">Par <?= $page->author()->esc() ?></p>
            <p class="article__date">
                Publié le <?= $page->published()->toDate('d MMMM y') ?> · <?= $page->reading_time()->esc() ?> min de lecture
            </p>
        </div>

        <?php if ($cover = $page->cover()->toFile()): ?>
            <?php snippet('figure', ['image' => $cover, 'caption' => $cover->caption()]) ?>
        <?php endif ?>

        <div class="article__body">
            <?php foreach ($page->text()->toBlocks() as $block): ?>
                <?php snippet('article/' . $block->type(), ['block' => $block]) ?>
            <?php endforeach ?>
        </div>

        <footer class="article__footer">
            <?php if ($page->tags()->isNotEmpty()): ?>
                <ul class="article__keywords">
                    <?php foreach ($page->tags()->split() as $tag): ?>
                        <li class="tag tag--light"><?= esc($tag) ?></li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
            <?php snippet('share') ?>
        </footer>
    </div>
</article>

<?php if (($others = $page->siblings(false)->listed()->limit(2))->isNotEmpty()): ?>
    <section class="projects projects--related">
        <div class="wrapper-cards">
            <h2 class="eyebrow">Autres projets</h2>
            <div class="projects__grid">
                <?php foreach ($others as $project): ?>
                    <?php snippet('components/project-card', ['project' => $project]) ?>
                <?php endforeach ?>
            </div>
        </div>
    </section>
<?php endif ?>

<?php snippet('footer') ?>
