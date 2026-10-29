<?php
/**
 * Étape numérotée (numéro et filet corail, texte en dessous)
 * @var string $number ex. "01"
 * @var \Kirby\Content\Field $text
 */
?>
<article class="step">
    <p class="eyebrow step__number"><?= esc($number) ?></p>
    <p class="step__text"><?= $text->kti() ?></p>
</article>
