<?php
/**
 * Carte membre (équipe, bureau)
 * @var \Kirby\Cms\StructureObject $member champs photo, name, role et label (optionnel)
 */
?>
<figure class="member">
    <?php if ($photo = $member->photo()->toFile()): ?>
        <img class="member__image" src="<?= $photo->resize(696)->url() ?>" alt="<?= $member->name()->esc('attr') ?>" loading="lazy">
    <?php endif ?>
    <figcaption class="member__caption">
        <?php if ($member->label()->isNotEmpty()): ?>
            <p class="eyebrow member__label"><?= $member->label()->esc() ?></p>
        <?php endif ?>
        <p class="member__name"><?= $member->name()->esc() ?></p>
        <p class="member__role"><?= $member->role()->kti() ?></p>
    </figcaption>
</figure>
