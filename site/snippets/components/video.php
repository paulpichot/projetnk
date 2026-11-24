<?php
/**
 * Vidéo chargée au clic sur le bouton lecture
 * @var string $url Lien YouTube ou Vimeo
 * @var \Kirby\Cms\File|null $poster
 * @var string|null $label
 * @var bool|null $large Grand format (fond sable, bouton encre)
 */
$large ??= false;
?>
<div class="relative grid place-items-center <?= $large ? 'aspect-[375/188] bg-sable lg:aspect-[1320/810]' : 'aspect-[335/188] bg-ivoire lg:aspect-[562/428]' ?> [&_iframe]:absolute [&_iframe]:inset-0 [&_iframe]:size-full [&_iframe]:object-cover" data-video>
    <?php if ($poster ?? null): ?>
        <img class="absolute inset-0 size-full object-cover" src="<?= $poster->resize(1280)->url() ?>" srcset="<?= $poster->srcset('poster') ?>" sizes="<?= $large ? '(min-width: 1024px) 135vw, 120vw' : '(min-width: 1024px) 40vw, 100vw' ?>" alt="" loading="lazy">
    <?php endif ?>
    <?php if ($label ?? null): ?>
        <p class="absolute bottom-3 left-3 rounded-md border-[1.5px] border-corail bg-ivoire px-3 py-2 font-semibold lg:top-[3.125rem] lg:bottom-auto lg:left-15"><?= esc($label) ?></p>
    <?php endif ?>
    <button class="video__play relative grid size-16 place-items-center rounded-full pl-1 text-ivoire <?= $large ? 'bg-encre *:w-[1.875rem] lg:size-28' : 'bg-ardoise *:w-4.5 lg:size-22 lg:*:w-6' ?>" type="button" aria-label="Lire la vidéo"><?= svg('assets/icons/play.svg') ?></button>
    <?php /* Lecture lancée dès l'insertion du lecteur au clic (autorisation autoplay de l'iframe, lecture intégrée sur iOS).
             YouTube en mode confidentialité renforcée (youtube-nocookie.com) : pas de cookies de suivi, donc pas de consentement à demander */ ?>
    <?php $url = preg_replace('~^https?://(?:www\.)?(?:youtube\.com/watch\?v=|youtu\.be/)([\w-]+).*$~', 'https://www.youtube-nocookie.com/embed/$1', $url) ?>
    <template><?= video($url, ['youtube' => ['autoplay' => 1, 'playsinline' => 1], 'vimeo' => ['autoplay' => 1, 'playsinline' => 1]], ['allow' => 'autoplay; fullscreen; encrypted-media; picture-in-picture']) ?></template>
</div>
