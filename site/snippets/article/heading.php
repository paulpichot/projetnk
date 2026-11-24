<?php
$level = $block->level()->or('h2')->value();
$class = match ($level) {
    'h2'    => 'title-h2 max-lg:leading-9',
    'h3'    => 'text-h3 max-lg:text-lg/6.5',
    default => null,
};
?>
<<?= $level ?><?= attr(['class' => $class], ' ') ?>><?= $block->text() ?></<?= $level ?>>
