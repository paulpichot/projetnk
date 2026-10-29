<?php snippet('header') ?>

<section class="hero">
    <div class="wrapper">
        <h1 class="title-display hero__title"><?= $page->hero_title()->kti() ?></h1>
    </div>
    <?php if ($image = $page->hero_image()->toFile()): ?>
        <img class="hero__image" src="<?= $image->resize(2880)->url() ?>" alt="<?= $image->alt()->esc('attr') ?>">
    <?php endif ?>
</section>

<section class="about">
    <div class="wrapper about__grid">
        <h2 class="eyebrow"><?= $page->about_label()->esc() ?></h2>
        <div class="text-lead about__text"><?= $page->about_text()->kt() ?></div>
        <h3 class="eyebrow about__actions-label"><?= $page->actions_label()->kti() ?></h3>
        <div class="about__actions">
            <?php foreach ($page->actions()->toStructure() as $action): ?>
                <?php snippet('components/step', ['number' => str_pad($action->indexOf() + 1, 2, '0', STR_PAD_LEFT), 'text' => $action->text()]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="section--sable nk">
    <div class="wrapper nk__inner">
        <h2 class="text-h3"><?= $page->nk_title()->esc() ?></h2>
        <p class="text-body-lg nk__text"><?= $page->nk_text()->kti() ?></p>
    </div>
</section>

<section class="team">
    <div class="wrapper">
        <div class="text-lead team__text"><?= $page->team_text()->kt() ?></div>

        <h2 class="eyebrow team__label"><?= $page->team_label()->esc() ?></h2>
        <div class="team__grid">
            <?php foreach ($page->team()->toStructure() as $member): ?>
                <?php snippet('components/member', ['member' => $member]) ?>
            <?php endforeach ?>
        </div>

        <div class="team__grid team__board">
            <h2 class="eyebrow team__board-label"><?= $page->board_label()->esc() ?></h2>
            <?php foreach ($page->board()->toStructure() as $member): ?>
                <?php snippet('components/member', ['member' => $member]) ?>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="section--ardoise council">
    <div class="wrapper">
        <?php snippet('components/people', ['title' => $page->council_label(), 'people' => $page->council()->toStructure()]) ?>
    </div>
</section>

<section class="section--sable contact">
    <div class="wrapper-wide contact__grid">
        <div>
            <h2 class="eyebrow"><?= $page->contact_label()->esc() ?></h2>
            <p class="title-h2 contact__title"><?= $page->contact_title()->esc() ?></p>
            <div class="contact__text"><?= $page->contact_text()->kt() ?></div>
            <?php if ($link = $page->contact_link()->toUrl()): ?>
                <a class="btn btn--outline" href="<?= $link ?>">Nous écrire</a>
            <?php endif ?>
        </div>
        <div id="newsletter">
            <h2 class="eyebrow"><?= $page->newsletter_label()->esc() ?></h2>
            <p class="title-h2 contact__title"><?= $page->newsletter_title()->esc() ?></p>
            <div class="contact__text"><?= $page->newsletter_text()->kt() ?></div>
            <?php snippet('components/newsletter', ['action' => $page->newsletter_action()->value()]) ?>
        </div>
    </div>
</section>

<?php snippet('footer') ?>
