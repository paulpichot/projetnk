<blockquote class="mt-7 mb-6 pl-8 lg:my-[2.1875rem]">
    <p class="font-heading text-[1.875rem]/[2.25rem] font-bold"><?= $block->text() ?></p>
    <?php if ($block->citation()->isNotEmpty()): ?>
        <footer class="mt-3.5 text-[0.875rem] text-encre/70"><?= $block->citation() ?></footer>
    <?php endif ?>
</blockquote>
