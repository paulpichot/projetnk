<?php snippet('header') ?>

<section class="section--sable projects">
    <div class="wrapper-cards">
        <h1 class="eyebrow"><?= $page->title()->esc() ?></h1>
        <div class="projects__grid">
            <?php foreach ($page->children()->listed()->filterBy('upcoming', '!=', 'true') as $project): ?>
                <?php snippet('components/project-card', ['project' => $project]) ?>
            <?php endforeach ?>
            <?php if ($page->upcoming_title()->isNotEmpty()): ?>
                <aside class="upcoming">
                    <p class="eyebrow"><?= $page->upcoming_label()->esc() ?></p>
                    <h2 class="text-lead upcoming__title"><?= $page->upcoming_title()->esc() ?></h2>
                    <p class="upcoming__text"><?= $page->upcoming_text()->kti() ?></p>
                    <?php if ($link = $page->upcoming_link()->toUrl()): ?>
                        <a class="btn btn--outline upcoming__button" href="<?= $link ?>"><?= $page->upcoming_button()->esc() ?></a>
                    <?php endif ?>
                </aside>
            <?php endif ?>
        </div>
    </div>
</section>

<section class="projects-hero">
    <?php if ($image = $page->hero_image()->toFile()): ?>
        <img class="projects-hero__image" src="<?= $image->resize(2880)->url() ?>" alt="">
    <?php endif ?>
    <div class="wrapper projects-hero__list">
        <?php foreach ($page->statements()->toStructure() as $statement): ?>
            <p class="projects-hero__item"><?= $statement->text()->kti() ?></p>
        <?php endforeach ?>
    </div>
</section>

<?php snippet('footer') ?>
