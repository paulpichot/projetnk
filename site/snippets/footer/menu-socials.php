<?php if ($site->menusocials()->isNotEmpty()): ?>
    <div>
        <p class="eyebrow"><?= $site->menusocials_label()->or('Nous suivre')->esc() ?></p>
        <ul class="mt-[0.3125rem] [&_a]:link-underline [&_a]:text-small [&_a]:leading-[1.875rem] [&_a]:font-medium">
            <?php foreach ($site->menusocials()->toStructure() as $item): ?>
                <?php
                // Réseaux sociaux dans un nouvel onglet ; liens du site (« /#newsletter », ancre conservée) dans la page
                // (« # » seul, lien en attente, laissé tel quel)
                $link = $item->link()->value() === '#' ? '#' : $item->link()->toUrl();
                $external = str_starts_with($link, 'http') && !str_starts_with($link, $site->url());
                ?>
                <li><a <?= attr(['href' => $link, 'target' => $external ? '_blank' : null, 'rel' => $external ? 'noopener' : null]) ?>><?= $item->title()->esc() ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>
