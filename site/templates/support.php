<?php
snippet('header');

$format = fn ($value) => number_format((float) $value, 0, ',', ' ');
$count = $page->contributions()->toInt();
$goal = max($page->goal()->toInt(), 1);
$next = max($page->next_goal()->toInt(), 1);
$days = max(0, (int) ceil(($page->end_date()->toDate() - time()) / 86400));
?>

<section class="support-intro">
    <div class="wrapper">
        <h1 class="eyebrow"><?= $page->title()->esc() ?></h1>
        <p class="title-h2 support-intro__title"><?= $page->headline()->esc() ?></p>
    </div>
</section>

<section class="section--sable campaign">
    <div class="wrapper campaign__grid">
        <div class="campaign__main">
            <?php snippet('components/video', ['url' => $page->video()->value(), 'poster' => $page->video_poster()->toFile()]) ?>
            <div class="campaign__org">
                <?php if ($logo = $site->logomark()->toFile()): ?>
                    <span class="campaign__logo"><?= svg($logo) ?></span>
                <?php endif ?>
                <div>
                    <p class="campaign__name"><?= $site->title()->esc() ?></p>
                    <p class="campaign__theme"><?= $page->theme()->esc() ?></p>
                </div>
                <div class="campaign__actions">
                    <a class="campaign__button" href="<?= $page->share_url()->toUrl() ?>">Partager</a>
                    <a class="campaign__button" href="<?= $page->follow_url()->toUrl() ?>">Suivre</a>
                </div>
            </div>
        </div>

        <div class="campaign__panel">
            <p class="campaign__total">
                <strong class="campaign__count"><?= $format($count) ?></strong>
                contributions sur <?= $format($goal) ?>
            </p>
            <p class="campaign__funded">Financé à <?= $format(floor($count / $goal * 100)) ?> %</p>

            <p class="campaign__next">
                <span class="campaign__badge">★ Prochain objectif</span>
                <?= $format($next) ?> contributions !
            </p>
            <progress class="campaign__progress" max="<?= $next ?>" value="<?= min($count, $next) ?>"></progress>
            <p class="campaign__ratio"><?= $format($count) ?> / <?= $format($next) ?> contributions</p>

            <dl class="campaign__stats">
                <div>
                    <dt><?= $format($page->contributors()->toInt()) ?></dt>
                    <dd>contributeur·rices</dd>
                </div>
                <div>
                    <dt><?= $days ?></dt>
                    <dd>jours restants</dd>
                </div>
            </dl>

            <p class="campaign__minimum"><?= $page->minimum()->esc() ?></p>
            <a class="btn campaign__cta" href="<?= $page->donate_url()->toUrl() ?>">Contribuer</a>
            <p class="campaign__tax"><?= $page->tax()->esc() ?></p>
            <a class="campaign__secure" href="<?= $page->secure_url()->toUrl() ?>"><?= $page->secure()->esc() ?></a>
        </div>
    </div>
</section>

<section class="budget">
    <div class="wrapper">
        <div class="split">
            <h2 class="eyebrow"><?= $page->budget_label()->esc() ?></h2>
            <p class="budget__text"><?= $page->budget_text()->kti() ?></p>
        </div>
        <div class="budget__detail">
            <?php snippet('components/donut', ['items' => $items = $page->budget()->toStructure(), 'total' => $page->budget_total(), 'period' => $page->budget_period()]) ?>
            <table class="budget__table">
                <?php foreach ($items as $item): ?>
                    <tr>
                        <th scope="row">
                            <?= $item->title()->esc() ?>
                            <span><?= $item->text()->esc() ?></span>
                        </th>
                        <td><?= $item->amount()->esc() ?></td>
                        <td><?= str_replace('.', ',', $item->share()->toFloat()) ?> %</td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>
</section>

<section class="faq">
    <div class="wrapper">
        <h2 class="eyebrow">FAQ</h2>
        <div class="faq__list">
            <?php foreach ($page->faq()->toStructure() as $item): ?>
                <details class="faq__item">
                    <summary class="faq__question"><?= $item->question()->esc() ?><?= svg('assets/icons/arrow-right.svg') ?></summary>
                    <div class="faq__answer"><?= $item->answer()->kt() ?></div>
                </details>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="funders">
    <div class="wrapper funders__grid">
        <h2 class="eyebrow funders__label"><?= $page->funders_label()->esc() ?></h2>
        <div>
            <p class="funders__text"><?= $page->funders_text()->kti() ?></p>
            <div class="funders__logos">
                <?php foreach ($page->funders()->toFiles() as $logo): ?>
                    <img src="<?= $logo->url() ?>" alt="<?= $logo->alt()->or($logo->name())->esc('attr') ?>">
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>

<?php if ($page->feature_show()->toBool()): ?>
    <section class="feature">
        <div class="wrapper">
            <h2 class="title-h2"><?= $page->feature_title()->esc() ?></h2>
            <p class="text-body-lg feature__text"><?= $page->feature_text()->kti() ?></p>
        </div>
        <div class="wrapper-wide">
            <?php snippet('components/video', [
                'url' => $page->feature_video()->value(),
                'poster' => $page->feature_poster()->toFile(),
                'label' => $page->feature_label()->value(),
                'class' => 'video--large',
            ]) ?>
        </div>
    </section>
<?php endif ?>

<?php snippet('footer') ?>
