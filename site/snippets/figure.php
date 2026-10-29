<?php
/**
 * Image légendée
 * @var \Kirby\Cms\File $image
 * @var \Kirby\Content\Field $caption
 */
?>
<figure class="figure">
    <img src="<?= $image->resize(2152)->url() ?>" alt="<?= $image->alt()->esc('attr') ?>" loading="lazy">
    <?php if ($caption->isNotEmpty()): ?>
        <figcaption class="figure__caption"><?= $caption->esc() ?></figcaption>
    <?php endif ?>
</figure>
