<nav class="nav" id="nav" aria-label="Navigation principale">
    <?php if ($site->menuprimary()->isNotEmpty()): ?>
        <div class="nav__list">
            <?php foreach ($site->menuprimary()->toStructure() as $item): ?>
                <?php snippet('header/nav-link', ['item' => $item, 'class' => 'nav__link']) ?>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if ($cta = $site->menucta()->toStructure()->first()): ?>
        <?php snippet('header/nav-link', ['item' => $cta, 'class' => 'btn nav__cta']) ?>
    <?php endif ?>
    <?php if ($site->menumobile()->isNotEmpty()): ?>
        <ul class="nav__secondary list-dots">
            <?php foreach ($site->menumobile()->toStructure() as $item): ?>
                <li><?php snippet('header/nav-link', ['item' => $item]) ?></li>
            <?php endforeach ?>
        </ul>
    <?php endif ?>
</nav>
