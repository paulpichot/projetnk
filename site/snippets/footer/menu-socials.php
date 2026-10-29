<?php if ($site->menusocials()->isNotEmpty()): ?>
    <div>
        <p class="eyebrow">Nous suivre</p>
        <ul class="footer__links">
            <?php foreach ($site->menusocials()->toStructure() as $item): ?>
                <li><a href="<?= $item->link()->toUrl() ?>" target="_blank" rel="noopener"><?= $item->title()->esc() ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
