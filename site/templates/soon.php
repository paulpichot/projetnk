<?php snippet('head') ?>
<main class="flex min-h-svh flex-col px-(--page-margin) pt-24 pb-[1.4375rem]" role="main">
    <div class="mx-auto w-full max-w-[20.9375rem]">
        <h1 class="[&_svg]:mx-auto [&_svg]:block [&_svg]:h-auto [&_svg]:w-[15.0625rem]">
            <?php if ($logo = $site->logoblack()->toFile()): ?>
                <?= svg($logo) ?>
                <span class="sr-only"><?= $site->title()->esc() ?></span>
            <?php else: ?>
                <?= $site->title()->esc() ?>
            <?php endif ?>
        </h1>

        <?php /* Compte à rebours : 4 colonnes de 86px séparées par un filet */ ?>
        <div class="-mx-2.5 mt-13 grid grid-cols-[repeat(4,1fr)]" data-countdown="<?= date('c', $page->launch()->toDate()) ?>">
            <?php foreach (['days' => 'Jours', 'hours' => 'Heures', 'minutes' => 'Minutes', 'seconds' => 'Secondes'] as $unit => $label): ?>
                <p class="flex flex-col items-center not-first:border-l-[1.5px] not-first:border-sable">
                    <span class="font-heading text-[2rem]/none font-bold" data-unit="<?= $unit ?>">00</span>
                    <span class="eyebrow mt-[0.5625rem] text-encre/60"><?= $label ?></span>
                </p>
            <?php endforeach ?>
        </div>

        <p class="mt-[3.6875rem] mb-5 text-center"><?= $page->text()->kti() ?></p>

        <?php snippet('components/newsletter', ['mention' => false]) ?>
    </div>

    <footer class="mt-auto text-center text-xs text-encre/60">
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
