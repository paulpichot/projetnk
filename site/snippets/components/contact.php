<?php
/**
 * Contact et newsletter (sans apparition au scroll), contenu saisi sur la page d'accueil
 * @var string|null $background 'ivoire' pour un fond ivoire (sable par défaut)
 */
$home = $site->homePage();
?>
<section class="relative <?= ($background ?? null) === 'ivoire' ? 'bg-ivoire' : 'bg-sable' ?> pt-12 pb-14 max-lg:[&_br]:hidden lg:pt-22 lg:pb-[5.4375rem]" data-reveal="none">
    <div class="wrapper-wide grid grid-cols-[minmax(0,1fr)] gap-y-14 lg:grid-cols-[45rem_1fr] lg:gap-y-0">
        <div>
            <h2 class="eyebrow"><?= $home->contact_label()->esc() ?></h2>
            <p class="title-h2 mt-4 lg:mt-6"><?= $home->contact_title()->esc() ?></p>
            <div class="mt-4 mb-[1.0625rem] lg:mt-[1.375rem] lg:mb-6"><?= $home->contact_text()->kt() ?></div>
            <?php if ($link = $home->contact_link()->toUrl()): ?>
                <a class="btn-outline" href="<?= $link ?>"><?= $home->contact_button()->or('Nous écrire')->esc() ?></a>
            <?php endif ?>
        </div>
        <div id="newsletter">
            <h2 class="eyebrow"><?= $home->newsletter_label()->esc() ?></h2>
            <p class="title-h2 mt-4 lg:mt-6"><?= $home->newsletter_title()->esc() ?></p>
            <div class="mt-4 mb-[1.0625rem] lg:mt-[1.375rem] lg:mb-6"><?= $home->newsletter_text()->kt() ?></div>
            <?php snippet('components/newsletter') ?>
        </div>
    </div>
</section>
