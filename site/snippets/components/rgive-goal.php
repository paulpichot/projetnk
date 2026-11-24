<?php
/**
 * Bandeau d'objectif collé au-dessus du formulaire de don RGive, dans son style : jours restants, prochain objectif et
 * barre de progression. Même largeur et même padding latéral que la carte du formulaire (370px, 24px) ; recouvre son
 * haut (marge et padding haut, 48px) pour n'en faire qu'une, avec les mêmes coins arrondis (32px).
 * Chiffres de la campagne RGive (route /rgive/<page>, src/js/custom/rgive-goal.js), sinon ceux du Panel (onglet
 * « Don et accroche ») ; masqué tant qu'aucun objectif n'est connu
 * @var \Kirby\Cms\Page $page
 * @var \Closure $field Champ de la page, sinon de Nous soutenir (support.php)
 */
$goal = $field('rgive_goal')->toInt();
$count = $field('rgive_contributors')->toInt();
$deadline = $field('rgive_deadline')->toTimestamp();
?>
<div class="relative z-1 mx-auto -mb-12 w-[23.125rem] max-w-full rounded-t-[2rem] bg-blanc px-6 pt-8 pb-8 text-encre" data-rgive-goal="<?= url('rgive/' . $page->id()) ?>" data-deadline="<?= $deadline ? $deadline * 1000 : '' ?>"<?= $goal ? '' : ' hidden' ?>>
    <p class="text-base/[1.2] font-semibold">Il nous reste <span data-goal-days>…</span> jours<br>pour atteindre notre objectif&nbsp;!</p>
    <p class="mt-1 text-[0.6875rem]">Chaque don compte. Même un petit montant peut aider.</p>
    <p class="mt-4 flex items-center gap-2 text-xs">
        <span class="bg-encre px-1.5 py-0.5 text-[0.625rem] text-blanc">★ Prochain objectif</span>
        <span><span data-goal-target><?= $goal ?></span> contributions&nbsp;!</span>
    </p>
    <div class="mt-2 h-1.5 bg-sable" role="meter" aria-valuemin="0" aria-valuemax="<?= $goal ?>" aria-valuenow="<?= $count ?>" aria-label="Contributions">
        <div class="h-full bg-corail" style="width: <?= $goal ? min(100, $count / $goal * 100) : 0 ?>%" data-goal-bar></div>
    </div>
    <p class="mt-2 text-right text-[0.6875rem]"><span data-goal-count><?= $count ?></span> / <span data-goal-target><?= $goal ?></span> contributions</p>
</div>
