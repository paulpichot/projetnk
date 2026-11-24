<?php if ($image = $block->image()->toFile()): ?>
    <?php snippet('figure', ['image' => $image, 'caption' => $block->caption()->or($image->caption()), 'class' => 'my-9 lg:my-11']) ?>
<?php endif ?>
