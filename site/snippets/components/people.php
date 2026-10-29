<?php
/**
 * Liste de personnes sur fond blanc (conseil scientifique), répartie en deux colonnes
 * @var \Kirby\Content\Field $title
 * @var \Kirby\Cms\Structure $people éléments avec name et role
 */
?>
<div class="people">
    <h2 class="eyebrow"><?= $title->esc() ?></h2>
    <div class="people__columns">
        <?php foreach ($people->chunk((int)ceil($people->count() / 2)) as $column): ?>
            <ul class="people__list">
                <?php foreach ($column as $person): ?>
                    <li>
                        <p class="person__name"><?= $person->name()->esc() ?></p>
                        <p class="person__role"><?= $person->role()->kti() ?></p>
                    </li>
                <?php endforeach ?>
            </ul>
        <?php endforeach ?>
    </div>
</div>
