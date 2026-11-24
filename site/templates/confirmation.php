<?php snippet('header') ?>

<?php /* Mini-page ouverte après le clic sur le lien de l'e-mail de confirmation Brevo (double opt-in, plugin newsletter) :
         message centré dans la hauteur de l'écran */ ?>
<section class="grid min-h-[calc(100svh-12rem)] place-items-center py-14 text-center lg:py-22">
    <div class="wrapper">
        <h1 class="title-h1"><?= $page->heading()->or('Merci !')->esc() ?></h1>
        <p class="text-body-lg mx-auto mt-5 max-w-[36rem] text-balance lg:mt-6"><?= $page->text()->esc() ?></p>
        <a class="btn-outline mt-10 lg:mt-12" href="<?= $site->url() ?>"><?= $page->button()->or('Retour à l’accueil')->esc() ?></a>
    </div>
</section>

<?php snippet('footer') ?>
