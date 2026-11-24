<?php
/**
 * Nous soutenir, et ses copies privées (gabarit support-private, support-private.php) : sur une copie, chaque champ
 * laissé vide reprend celui de la page Nous soutenir
 * @var \Kirby\Cms\Page $page
 */
$source = $page->intendedTemplate()->name() === 'support-private' ? $site->children()->template('support')->first() : null;
$field = fn (string $key) => $page->content()->get($key)->or($source?->content()->get($key));

// Campagne du formulaire de don RGive (plugin rgive)
$campaign = $page->rgiveCampaign();
?>
<?php snippet('header') ?>

<?php /* Haut de page : photo pleine largeur et formulaire de don RGive (format Embed : élément data-rgive-src remplacé par
         le formulaire par embed.js, chargé dans head.php), calé à droite du contenu (448px).
         RGive impose 370px de large au moins au formulaire : bord à bord sous la photo sur mobile */ ?>
<?php $hero = $field('hero_image')->toFile() ?>
<?php if ($hero || $campaign): ?>
    <section class="relative overflow-hidden lg:flex lg:min-h-[44.5rem] lg:items-center lg:py-22">
        <?php if ($hero): ?>
            <img class="block aspect-[375/240] h-auto w-full object-cover motion-safe:in-[.js]:invisible lg:absolute lg:inset-0 lg:aspect-auto lg:h-full" src="<?= $hero->resize(1440)->url() ?>" srcset="<?= $hero->srcset('full') ?>" sizes="100vw" alt="<?= $hero->alt()->esc('attr') ?>" data-photo>
        <?php endif ?>
        <?php if ($campaign): ?>
            <div class="wrapper relative flex justify-end max-lg:px-0">
                <div class="w-full lg:w-[28rem]" data-reveal="none">
                    <?php snippet('components/rgive-goal', compact('field')) ?>
                    <div class="rgive-embed" data-rgive-src="<?= esc($campaign) ?>" data-rgive-script="<?= esc(rtrim($site->rgive_url()->toUrl(), '/')) ?>/assets/embed.js"></div>
                </div>
            </div>
        <?php endif ?>
    </section>
<?php endif ?>

<section class="pt-14 pb-14 lg:pt-[10.5625rem] lg:pb-32">
    <div class="wrapper">
        <h1 class="eyebrow"><?= $page->title()->esc() ?></h1>
        <p class="title-h2 mt-8 max-w-[62.5rem] leading-[2.125rem] lg:leading-[2.875rem]"><?= $field('headline')->esc() ?></p>

        <?php /* Blocs photo et texte, la photo une fois à gauche, une fois à droite (506px chacun, 64px d'écart) */ ?>
        <?php if (($reasons = $field('reasons')->toStructure())->isNotEmpty()): ?>
            <div class="mt-14 flex flex-col gap-14 lg:mt-52 lg:gap-[4.875rem]">
                <?php foreach ($reasons as $reason): ?>
                    <div class="grid grid-cols-1 items-center gap-6 lg:grid-cols-2 lg:gap-16">
                        <?php if ($image = $reason->image()->toFile()): ?>
                            <img class="block aspect-[506/340] h-auto w-full object-cover <?= $reason->indexOf() % 2 ? 'lg:order-2' : '' ?>" src="<?= $image->crop(1012, 680)->url() ?>" alt="<?= $image->alt()->esc('attr') ?>" loading="lazy" data-reveal="fade">
                        <?php endif ?>
                        <div>
                            <h2 class="text-lead max-lg:[&_br]:hidden"><?= $reason->title()->kti() ?></h2>
                            <p class="mt-4 text-lg lg:mt-8"><?= $reason->text()->kti() ?></p>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <?php /* Suite des blocs, même style qu'un bloc photo et texte, en pleine largeur et sans photo */ ?>
        <?php if ($field('actions_title')->isNotEmpty()): ?>
            <div class="mt-14 lg:mt-[4.875rem]">
                <h2 class="text-lead"><?= $field('actions_title')->esc() ?></h2>
                <div class="mt-4 text-lg lg:mt-8 [&_ul]:list-disc [&_ul]:pl-[1.2em] [&>*+*]:mt-4">
                    <?= $field('actions')->value() ?>
                    <?= $field('actions_text')->kt() ?>
                </div>
            </div>
        <?php endif ?>

        <?php /* Répartition des dépenses : anneau (part de chaque poste, étiquettes à mi-arc) et tableau, dans la couleur
                 choisie pour chaque poste */ ?>
        <?php if (($expenses = $field('expenses')->toStructure())->isNotEmpty()): ?>
            <?php
            // Couleur choisie pour chaque poste (hexadécimale), sinon corail, sable, ardoise dans l'ordre
            $palette = ['#FF8257', '#F0E2CC', '#377379'];
            $color = fn ($item) => preg_match('/^#[0-9a-f]{3,8}$/i', $item->color()->value()) ? $item->color()->value() : $palette[$item->indexOf() % 3];
            $percent = fn ($value) => str_replace('.', ',', (string) round((float) $value, 1)) . "\u{00A0}%";
            $total = array_sum(array_map(fn ($item) => (float) $item['percent'], $expenses->toArray())) ?: 100;
            $circumference = 2 * M_PI * 97;
            $offset = 0;
            ?>
            <div class="mt-10 grid grid-cols-1 gap-4 border-t-[1.5px] border-encre pt-10 lg:mt-16 lg:grid-cols-[20.125rem_minmax(0,1fr)] lg:gap-0 lg:pt-16">
                <h2 class="eyebrow"><?= $field('expenses_label')->or('Répartition de nos dépenses')->esc() ?></h2>
                <p class="max-w-[35rem]"><?= $field('expenses_text')->kti() ?></p>
            </div>
            <div class="mt-12 grid grid-cols-1 items-center gap-10 lg:mt-36 lg:grid-cols-[22rem_minmax(0,1fr)] lg:gap-0" data-reveal="fade">
                <div class="relative mx-auto size-60 lg:mx-0 lg:ml-10">
                    <svg class="size-full -rotate-90" viewBox="0 0 240 240" aria-hidden="true">
                        <?php foreach ($expenses as $item): ?>
                            <?php $length = $circumference * (float) $item->percent()->value() / $total ?>
                            <circle class="fill-none" style="stroke: <?= $color($item) ?>" cx="120" cy="120" r="97" stroke-width="46" stroke-dasharray="<?= round($length, 2) ?> <?= round($circumference - $length, 2) ?>" stroke-dashoffset="<?= round(-$offset, 2) ?>"></circle>
                            <?php $offset += $length ?>
                        <?php endforeach ?>
                    </svg>
                    <?php $angle = 0 ?>
                    <?php foreach ($expenses as $item): ?>
                        <?php
                        $share = (float) $item->percent()->value() / $total * 360;
                        $middle = deg2rad($angle + $share / 2);
                        $angle += $share;
                        ?>
                        <span class="absolute -translate-1/2 text-label font-semibold whitespace-nowrap" style="left: <?= round(120 + 145 * sin($middle), 1) ?>px; top: <?= round(120 - 145 * cos($middle), 1) ?>px" aria-hidden="true"><?= $percent($item->percent()->value()) ?></span>
                    <?php endforeach ?>
                    <p class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="font-heading text-[2rem]/[1.1] font-bold"><?= $field('expenses_total')->esc() ?></span>
                        <span class="mt-1 text-small text-encre/70"><?= $field('expenses_total_label')->esc() ?></span>
                    </p>
                </div>
                <ul>
                    <?php foreach ($expenses as $item): ?>
                        <li class="flex min-h-19 items-center gap-4 border-t-[1.5px] border-encre py-3">
                            <span class="size-3 shrink-0" style="background-color: <?= $color($item) ?>" aria-hidden="true"></span>
                            <span class="flex-1">
                                <span class="block font-semibold"><?= $item->label()->esc() ?></span>
                                <span class="block text-small text-encre/70"><?= $item->detail()->esc() ?></span>
                            </span>
                            <span class="font-bold whitespace-nowrap"><?= $item->amount()->esc() ?></span>
                            <span class="w-16 text-right text-encre/70 whitespace-nowrap max-lg:hidden"><?= $percent($item->percent()->value()) ?></span>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>
    </div>
</section>

<section class="bg-blanc py-12 lg:pt-22 lg:pb-30">
    <div class="wrapper">
        <h2 class="eyebrow"><?= $field('faq_label')->or('FAQ')->esc() ?></h2>
        <?php /* Questions qui apparaissent l'une après l'autre ; réponses dépliées en douceur, flèche qui pivote (faq.js) ;
                 question ouverte sur fond ivoire, qui s'efface dès qu'on la referme ; la première ouverte d'emblée */ ?>
        <div class="mt-8" data-faq>
            <?php foreach ($field('faq')->toStructure() as $item): ?>
                <details class="group transition-colors duration-300 max-lg:border-t-[1.5px] max-lg:border-encre [&[open]:not([data-closing])]:bg-ivoire" data-reveal="box"<?= $item->indexOf() === 0 ? ' open' : '' ?>>
                    <summary class="group/question flex cursor-pointer list-none items-center justify-between gap-6 py-6 pr-7 pl-6 font-heading text-lg/[1.625rem] font-bold *:shrink-0 lg:py-[1.5625rem] lg:text-h3 [&::-webkit-details-marker]:hidden">
                        <?= $item->question()->esc() ?>
                        <?php /* Flèche qui pivote vers le bas une fois la réponse ouverte (faq.js) ; glisse au survol tant que la
                                 question est fermée */ ?>
                        <span class="transition-transform duration-300 ease-out group-[:not([open])]:group-hover/question:translate-x-1">
                            <?= svg('assets/icons/arrow-right.svg') ?>
                        </span>
                    </summary>
                    <div class="overflow-hidden">
                        <div class="pr-7 pb-[1.625rem] pl-6"><?= $item->answer()->kt() ?></div>
                    </div>
                </details>
            <?php endforeach ?>
        </div>
    </div>
</section>

<section class="bg-blanc py-12 lg:pt-22 lg:pb-30">
    <div class="wrapper grid grid-cols-1 lg:grid-cols-[14.5rem_minmax(0,1fr)]">
        <h2 class="eyebrow lg:w-min"><?= $field('funders_label')->esc() ?></h2>
        <div>
            <p class="lg:-mr-4 lg:font-heading lg:text-h3 lg:font-bold"><?= $field('funders_text')->kti() ?></p>
            <div class="mt-6 flex flex-wrap items-center gap-x-11 gap-y-6 lg:mt-[4.625rem] lg:gap-x-[5.8125rem]">
                <?php foreach ($field('funders')->toFiles() as $logo): ?>
                    <img class="h-[3.5625rem] w-auto lg:h-[7.0625rem]" src="<?= $logo->resize(null, 226)->url() ?>" alt="<?= $logo->alt()->or($logo->name())->esc('attr') ?>">
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>

<?php if ($field('feature_show')->toBool()): ?>
    <section class="py-12 lg:pt-22 lg:pb-15">
        <div class="wrapper">
            <h2 class="title-h2 max-lg:leading-[2.125rem]"><?= $field('feature_title')->esc() ?></h2>
            <p class="mt-[1.375rem] text-base/[1.625rem] lg:text-lg/[1.67]"><?= $field('feature_text')->kti() ?></p>
        </div>
        <?php /* Vidéo bord à bord en mobile (format 375/188 de la maquette) */ ?>
        <div class="wrapper-wide mt-10 max-lg:px-0 lg:mt-15">
            <?php snippet('components/video', [
                'url' => $field('feature_video')->value(),
                'poster' => $field('feature_poster')->toFile(),
                'label' => $field('feature_label')->value(),
                'large' => true,
            ]) ?>
        </div>
    </section>
<?php endif ?>

<?php snippet('components/contact') ?>

<?php snippet('footer') ?>
