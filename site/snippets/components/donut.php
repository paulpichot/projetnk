<?php
/**
 * Anneau de répartition (part de chaque élément en %)
 * @var \Kirby\Cms\Structure $items Éléments avec un champ share
 * @var \Kirby\Content\Field $total Montant affiché au centre
 * @var \Kirby\Content\Field $period
 */
$radius = 97;
$circumference = 2 * M_PI * $radius;
$start = 0;
?>
<figure class="donut">
    <svg viewBox="-160 -160 320 320" aria-hidden="true">
        <?php foreach ($items as $item): ?>
            <?php
            $share = $item->share()->toFloat();
            $angle = deg2rad(($start + $share / 2) * 3.6);
            ?>
            <circle class="donut__segment" r="<?= $radius ?>" pathLength="100" stroke-dasharray="<?= $share ?> 100" stroke-dashoffset="<?= -$start ?>" transform="rotate(-90)"/>
            <text class="donut__label" x="<?= round(146 * sin($angle), 1) ?>" y="<?= round(-146 * cos($angle), 1) ?>"><?= str_replace('.', ',', $share) ?> %</text>
            <?php $start += $share ?>
        <?php endforeach ?>
    </svg>
    <figcaption class="donut__total">
        <strong><?= $total->esc() ?></strong>
        <?= $period->esc() ?>
    </figcaption>
</figure>
