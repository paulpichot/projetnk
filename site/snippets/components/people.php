<?php
/**
 * Liste de personnes sur fond blanc (conseil scientifique), répartie en deux colonnes, les personnes séparées par
 * un filet sable ; en mobile, une seule liste
 * @var \Kirby\Content\Field $title
 * @var \Kirby\Cms\Structure $people éléments avec name et role
 */
?>
<div class="bg-blanc px-(--page-margin) py-8 text-encre lg:px-25 lg:pt-22 lg:pb-[6.875rem]">
    <h2 class="eyebrow"><?= $title->esc() ?></h2>
    <div class="mt-11 grid grid-cols-1 gap-y-[1.375rem] lg:mt-[5.75rem] lg:grid-cols-2 lg:gap-x-[6.0625rem] lg:gap-y-0">
        <?php foreach ($people->chunk((int)ceil($people->count() / 2)) as $column): ?>
            <ul class="flex flex-col gap-[1.375rem] max-lg:last:[&>li:last-child]:border-0 max-lg:last:[&>li:last-child]:pb-0">
                <?php foreach ($column as $person): ?>
                    <li class="border-b-[1.5px] border-sable pb-6 lg:pb-[1.4375rem] lg:last:border-0 lg:last:pb-0" data-reveal="fade">
                        <p class="font-heading text-name font-medium"><?= $person->name()->esc() ?></p>
                        <p class="mt-[0.1875rem] text-small max-lg:[&_br]:hidden"><?= $person->role()->kti() ?></p>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endforeach ?>
    </div>
</div>
