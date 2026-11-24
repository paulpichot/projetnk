<?php
$url = urlencode($page->url());
$instagram = $site->menusocials()->toStructure()->findBy('title', 'Instagram');
$labels = $page->parent()?->content();
?>
<div class="flex flex-wrap items-baseline gap-4 max-lg:gap-y-2.5">
    <p class="eyebrow"><?= $labels?->share_label()->or('Partager')->esc() ?? 'Partager' ?></p>
    <a class="underline" href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $url ?>" target="_blank" rel="noopener">LinkedIn</a>
    <?php if ($instagram): ?>
        <a class="underline" href="<?= $instagram->link()->toUrl() ?>" target="_blank" rel="noopener">Instagram</a>
    <?php endif ?>
    <a class="underline" href="https://www.facebook.com/sharer/sharer.php?u=<?= $url ?>" target="_blank" rel="noopener">Facebook</a>
    <button class="underline" type="button" data-copy="<?= $page->url() ?>"><?= $labels?->copy_label()->or('Copier le lien')->esc() ?? 'Copier le lien' ?></button>
</div>
