<?php
/**
 * Carte projet
 * @var \Kirby\Cms\Page $project
 */
$upcoming = $project->upcoming()->toBool();
?>
<article class="project-card">
    <?php if ($image = $project->thumbnail()->toFile()): ?>
        <img class="project-card__image" src="<?= $image->crop(602, 432)->url() ?>" alt="" loading="lazy">
    <?php else: ?>
        <div class="project-card__image"></div>
    <?php endif ?>
    <div class="project-card__body">
        <h3 class="tag project-card__tag">
            <?php if ($upcoming): ?>
                Coming soon
            <?php else: ?>
                <a href="<?= $project->url() ?>"><?= $project->title()->esc() ?></a>
            <?php endif ?>
        </h3>
        <p class="tag tag--outline"><?= $project->period()->esc() ?></p>
        <p class="project-card__text"><?= $project->excerpt()->kti() ?></p>
        <?php if ($partner = $project->partner()->toFile()): ?>
            <img class="project-card__partner" src="<?= $partner->url() ?>" alt="<?= $partner->alt()->esc('attr') ?>">
        <?php endif ?>
    </div>
</article>
