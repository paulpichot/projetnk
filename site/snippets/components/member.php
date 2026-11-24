<?php
/**
 * Carte membre (équipe, bureau) ; au survol, la photo zoome légèrement dans son cadre.
 * Mobile : une ligne par membre, petite photo à gauche et texte aligné sur son bas
 * @var \Kirby\Cms\StructureObject $member champs photo, name, role et label (optionnel)
 */
?>
<figure class="group max-lg:flex max-lg:items-end max-lg:gap-4" data-reveal="fade">
    <?php if ($photo = $member->photo()->toFile()): ?>
        <div class="overflow-hidden max-lg:w-30 max-lg:shrink-0">
            <img class="block aspect-[120/144] w-full object-cover transition-transform duration-800 ease-[cubic-bezier(0.22,1,0.36,1)] group-hover:scale-[1.04] lg:aspect-[202/240]" src="<?= $photo->resize(480)->url() ?>" srcset="<?= $photo->srcset('member') ?>" sizes="(min-width: 1024px) 240px, 144px" alt="<?= $member->name()->esc('attr') ?>" loading="lazy">
        </div>
    <?php endif ?>
    <figcaption class="lg:mt-4">
        <?php if ($member->label()->isNotEmpty()): ?>
            <p class="eyebrow mb-1.5"><?= $member->label()->esc() ?></p>
        <?php endif ?>
        <p class="font-heading text-name font-medium"><?= $member->name()->esc() ?></p>
        <p class="mt-1.5 text-small max-lg:[&_br]:hidden"><?= $member->role()->kti() ?></p>
    </figcaption>
</figure>
