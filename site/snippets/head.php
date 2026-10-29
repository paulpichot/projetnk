<!DOCTYPE html>
<html lang="fr" itemscope itemtype="https://schema.org/WebPage">
<head>
    <?php snippet('seo/head'); ?>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php if($favicon = $site->favicon()->toFile()): ?>
		<link rel="shortcut icon" href="<?= $favicon->url() ?>" type="image/x-icon">
	<?php endif ?>
    <link rel="preload" href="<?= url('assets/main.min.css') ?>" as="style">
    <link rel="preload" href="<?= url('assets/fonts/space-grotesk-latin-wght-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?= url('assets/fonts/manrope-latin-wght-normal.woff2') ?>" as="font" type="font/woff2" crossorigin>
    <?= css([
        'assets/main.min.css',
    ]) ?>
</head>
<body>
