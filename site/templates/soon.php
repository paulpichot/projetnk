<?php snippet('head') ?>
<main class="soon" role="main">
    <div class="soon__content">
        <h1 class="soon__logo">
            <?php if ($logo = $site->logoblack()->toFile()): ?>
                <?= svg($logo) ?>
                <span class="sr-only"><?= $site->title()->esc() ?></span>
            <?php else: ?>
                <?= $site->title()->esc() ?>
            <?php endif ?>
        </h1>

        <div class="countdown" data-countdown="<?= date('c', $page->launch()->toDate()) ?>">
            <?php foreach (['days' => 'Jours', 'hours' => 'Heures', 'minutes' => 'Minutes', 'seconds' => 'Secondes'] as $unit => $label): ?>
                <p class="countdown__item">
                    <span class="countdown__value" data-unit="<?= $unit ?>">00</span>
                    <span class="eyebrow countdown__label"><?= $label ?></span>
                </p>
            <?php endforeach ?>
        </div>

        <p class="soon__text"><?= $page->text()->kti() ?></p>

        <?php snippet('components/newsletter', ['action' => $page->newsletter_action()->value(), 'mention' => false]) ?>
    </div>

    <footer class="soon__footer">
        <p>© <?= date('Y') ?> <?= $site->title()->esc() ?></p>
        <ul class="list-dots">
            <?php foreach ($site->menumobile()->toStructure() as $item): ?>
                <li><?php snippet('header/nav-link', ['item' => $item]) ?></li>
            <?php endforeach ?>
            <?php if ($page->email()->isNotEmpty()): ?>
                <li><?= Html::email($page->email()->value()) ?></li>
            <?php endif ?>
        </ul>
    </footer>
</main>
<?php snippet('foot') ?>
