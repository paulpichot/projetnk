<?php
/**
 * Lien d'un item de menu (structure fields/menu-primary)
 * @var \Kirby\Cms\StructureObject $item
 * @var string|null $class
 */
$target = $item->linkType()->value() === 'page' ? $item->pageLink()->toPage() : null;
?>
<a <?= attr([
    'class' => $class ?? null,
    'href' => $target ? $target->url() : $item->customUrl()->value(),
    'aria-current' => $target && $target->isOpen() ? 'page' : null,
]) ?>><?= $item->title()->esc() ?></a>
