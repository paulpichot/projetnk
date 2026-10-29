<?php if ($site->menufooter()->isNotEmpty()): ?>
    <div>
        <p class="eyebrow">Pages</p>
        <ul class="footer__links">
            <?php foreach ($site->menufooter()->toStructure() as $item): ?>
                <?php if ($link = $item->link()->toPage()): ?>
                    <li><a href="<?= $link->url() ?>"><?= $item->title()->esc() ?></a></li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
