<blockquote>
    <p><?= $block->text() ?></p>
    <?php if ($block->citation()->isNotEmpty()): ?>
        <footer><?= $block->citation() ?></footer>
    <?php endif ?>
</blockquote>
