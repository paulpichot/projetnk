<?php
/**
 * Image légendée
 * @var \Kirby\Cms\File $image
 * @var \Kirby\Content\Field $caption
 * @var string|null $class classes du conteneur (marges)
 */
?>
<figure<?= attr(['class' => $class ?? null], ' ') ?>>
    <img class="block h-auto w-full" src="<?= $image->resize(1200)->url() ?>" srcset="<?= $image->srcset('content') ?>" sizes="(min-width: 1180px) 1076px, 100vw" alt="<?= $image->alt()->esc('attr') ?>" loading="lazy">
    <?php if ($caption->isNotEmpty()): ?>
        <figcaption class="mt-4 text-xs text-encre/70"><?= $caption->esc() ?></figcaption>
    <?php endif ?>
</figure>
