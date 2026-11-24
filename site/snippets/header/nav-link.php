<?php
/**
 * Lien d'un item de menu (structure fields/menu-primary : title, link vers une page ou une adresse externe)
 * @var \Kirby\Cms\StructureObject $item
 * @var string|null $class
 */
$target = $item->link()->toPage();
$href = $item->link()->toUrl();

// Lien vers la plateforme de don RGive (Site > Identité) : ouvre la Capsule par-dessus la page (classe rgive-button,
// overlay.js) ; nouvel onglet si le script n'est pas chargé
$rgive = $site->rgive_url()->toUrl();
$capsule = $rgive && $href && str_starts_with($href, rtrim($rgive, '/') . '/');

// Couleur du haut de la page visée quand elle diffère de l'ivoire (fond de l'écran d'arrivée en partant vers elle, intro.js)
$background = $target?->background() === 'sable' ? 'sable' : null;
?>
<a <?= attr([
    'class' => trim(($class ?? '') . ($capsule ? ' rgive-button' : '')) ?: null,
    'href' => $href,
    'target' => $capsule ? '_blank' : null,
    'rel' => $capsule ? 'noopener' : null,
    'aria-current' => $target && $target->isOpen() ? 'page' : null,
    'data-background' => $background,
]) ?>><?= $item->title()->esc() ?></a>
