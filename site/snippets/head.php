<!DOCTYPE html>
<html lang="fr" class="scroll-smooth max-lg:[&.is-menu-open]:overflow-hidden" itemscope itemtype="https://schema.org/WebPage">
<head>
    <script>
        document.documentElement.classList.add('js');
        <?php if ($page->isHomePage()): ?>
        // Écran d'arrivée de l'accueil (intro.js) : une seule fois par visite
        try { sessionStorage.getItem('intro-vue') || document.documentElement.classList.add('intro'); } catch (e) {}
        <?php endif ?>
    </script>
    <?php snippet('seo/head'); ?>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (in_array($page->intendedTemplate()->name(), ['support-private', 'confirmation'])): ?>
        <meta name="robots" content="noindex, nofollow">
    <?php endif ?>
    <?php /* Site prévu en clair uniquement : pas d'assombrissement automatique (mode sombre forcé de Chrome Android,
             Samsung Internet…) ; barre du navigateur mobile teintée en ivoire */ ?>
    <meta name="color-scheme" content="only light">
    <meta name="theme-color" content="#FFF7EB">
    <?php /* Favicons tirés du logo NK du pied de page (assets/favicon) ; un favicon choisi dans le panel les remplace */ ?>
    <?php if ($favicon = $site->favicon()->toFile()): ?>
        <link rel="icon" href="<?= $favicon->url() ?>">
    <?php else: ?>
        <link rel="icon" href="<?= url('assets/favicon/favicon.ico') ?>" sizes="16x16 32x32 48x48">
        <link rel="apple-touch-icon" href="<?= url('assets/favicon/apple-touch-icon.png') ?>">
        <?php /* Identifiants envoyés avec la requête : le manifeste reste accessible derrière la protection par mot de passe */ ?>
        <link rel="manifest" href="<?= url('assets/favicon/manifest.json') ?>" crossorigin="use-credentials">
    <?php endif ?>
    <link rel="preload" href="<?= url('assets/fonts/space-grotesk-latin-wght-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= url('assets/fonts/manrope-latin-wght-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <?= vite()->css('index.css') ?>
    <?php /* RGive : Capsule (overlay.js, boutons rgive-button du menu) sur toutes les pages. Le script du formulaire
             intégré (embed.js) est chargé par la page qui le contient (rgive-embed.js), à chaque arrivée sur elle */ ?>
    <?php if ($rgive = $site->rgive_url()->toUrl()): ?>
        <script src="<?= esc(rtrim($rgive, '/')) ?>/assets/overlay.js" async></script>
    <?php endif ?>
</head>
<?php /* Fond de la page (plugin background) : sable en haut de Projets sur desktop ; au changement de page, un voile
         passe d'une couleur à l'autre (navigation.js) */ ?>
<?php /* is-support : page de don (Nous soutenir et ses copies privées), où le bouton « Faire un don » du menu est masqué */ ?>
<body class="<?= $page->background() === 'sable' ? 'bg-ivoire lg:bg-sable' : 'bg-ivoire' ?><?= in_array($page->intendedTemplate()->name(), ['support', 'support-private']) ? ' is-support' : '' ?> font-body text-base text-encre antialiased">
