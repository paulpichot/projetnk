<?php snippet('head') ?>
<main class="soon" role="main">
    <div class="soon__content">
        <h1>
            <img class="soon__logo" src="<?= url('assets/images/logo-nk.svg') ?>" alt="<?= $site->title()->esc() ?>" width="616" height="133">
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

        <?php snippet('components/newsletter', ['mention' => false]) ?>
    </div>

    <footer class="soon__footer">
        <p>© <?= date('Y') ?> <?= $site->title()->esc() ?></p>
        <?php if ($page->email()->isNotEmpty()): ?>
            <ul class="list-dots">
                <li><?= Html::email($page->email()->value()) ?></li>
            </ul>
        <?php endif ?>
    </footer>
</main>
<?php snippet('foot') ?>
