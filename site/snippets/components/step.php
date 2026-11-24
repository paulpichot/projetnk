<?php
/**
 * Étape numérotée (numéro et filet corail, texte en dessous)
 * @var string $number ex. "01"
 * @var \Kirby\Content\Field $text
 */
?>
<article>
    <p class="eyebrow flex items-center gap-6 text-corail"><?= esc($number) ?><span class="h-[1.5px] flex-1 bg-current" data-reveal="line"></span></p>
    <p class="mt-6 text-card-sm font-semibold max-lg:max-w-70 lg:text-card-lg" data-reveal="text"><?= $text->kti() ?></p>
</article>
