<?php if ($image = $block->image()->toFile()): ?>
    <?php snippet('figure', ['image' => $image, 'caption' => $block->caption()->or($image->caption())]) ?>
<?php endif ?>
