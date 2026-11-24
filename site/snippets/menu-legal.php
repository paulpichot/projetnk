<?php
/**
 * Liens légaux (champ site menulegal)
 * @var string $class
 */
?>
<?php if ($site->menulegal()->isNotEmpty()): ?>
    <ul class="<?= $class ?>">
        <?php foreach ($site->menulegal()->toStructure() as $item): ?>
            <?php if ($link = $item->link()->toUrl()): ?>
                <li><a <?= attr(['class' => $linkClass ?? null, 'href' => $link, 'aria-current' => $item->link()->toPage()?->isActive() ? 'page' : null]) ?>><?= $item->title()->esc() ?></a></li>
            <?php endif ?>
        <?php endforeach ?>
    </ul>
<?php endif ?>
