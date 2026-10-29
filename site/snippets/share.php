<?php
$url = urlencode($page->url());
$instagram = $site->menusocials()->toStructure()->findBy('title', 'Instagram');
?>
<div class="share">
    <p class="eyebrow">Partager</p>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $url ?>" target="_blank" rel="noopener">LinkedIn</a>
    <?php if ($instagram): ?>
        <a href="<?= $instagram->link()->toUrl() ?>" target="_blank" rel="noopener">Instagram</a>
    <?php endif ?>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $url ?>" target="_blank" rel="noopener">Facebook</a>
    <button type="button" data-copy="<?= $page->url() ?>">Copier le lien</button>
</div>
