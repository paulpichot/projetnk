<?php if ($site->menufooter()->isNotEmpty()): ?>
    <div>
        <p class="eyebrow"><?= $site->menufooter_label()->or('Pages')->esc() ?></p>
        <ul class="mt-[0.3125rem] [&_a]:link-underline [&_a]:text-small [&_a]:leading-[1.875rem] [&_a]:font-medium">
            <?php foreach ($site->menufooter()->toStructure() as $item): ?>
                <?php if ($link = $item->link()->toUrl()): ?>
                    <li><a href="<?= $link ?>"><?= $item->title()->esc() ?></a></li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
