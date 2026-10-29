<?php snippet('layouts/legal', slots: true) ?>
    <form class="cookies" data-cookies>
        <div class="cookies__panel">
            <?php foreach ($page->categories()->toStructure() as $category): ?>
                <div class="cookies__item">
                    <div>
                        <p class="cookies__title"><?= $category->title()->esc() ?></p>
                        <p class="cookies__text"><?= $category->text()->esc() ?></p>
                    </div>
                    <?php if ($category->type()->value() === 'essentials'): ?>
                        <p class="cookies__always">Toujours actifs</p>
                    <?php else: ?>
                        <input class="switch" type="checkbox" name="<?= $category->type()->esc('attr') ?>" aria-label="<?= $category->title()->esc('attr') ?>">
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
        <div class="cookies__actions">
            <button class="btn" type="submit">Enregistrer mes choix</button>
            <button class="btn btn--outline" type="button" data-consent="all">Tout accepter</button>
            <button class="btn btn--outline" type="button" data-consent="none">Tout refuser</button>
        </div>
    </form>
<?php endsnippet() ?>
