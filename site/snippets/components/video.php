<?php
/**
 * Vidéo chargée au clic sur le bouton lecture
 * @var string $url Lien YouTube ou Vimeo
 * @var \Kirby\Cms\File|null $poster
 * @var string|null $label
 * @var string|null $class
 */
?>
<div class="<?= trim('video ' . ($class ?? '')) ?>" data-video>
    <?php if ($poster ?? null): ?>
        <img class="video__poster" src="<?= $poster->resize(2640)->url() ?>" alt="" loading="lazy">
    <?php endif ?>
    <?php if ($label ?? null): ?>
        <p class="video__label"><?= esc($label) ?></p>
    <?php endif ?>
    <button class="video__play" type="button" aria-label="Lire la vidéo"><?= svg('assets/icons/play.svg') ?></button>
    <template><?= video($url, ['youtube' => ['autoplay' => 1], 'vimeo' => ['autoplay' => 1]]) ?></template>
</div>
