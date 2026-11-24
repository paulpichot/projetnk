<?php
/**
 * Formulaire newsletter, comme celui du countdown : champ et bouton accolés, soulignement corail au focus,
 * états d'envoi / succès / erreur et message sous le champ (src/js/custom/newsletter.js, route /newsletter)
 * @var bool $mention Afficher la mention RGPD (oui par défaut)
 * Libellés saisis sur la page d'accueil (onglet Contact)
 */
$home = $site->homePage();
$placeholder = $home->newsletter_placeholder()->or('Votre adresse e-mail')->esc('attr');
?>
<div>
    <form class="group peer flex" action="<?= url('newsletter') ?>" method="post" data-newsletter>
        <label class="sr-only" for="newsletter-email"><?= $placeholder ?></label>
        <input class="h-12 min-w-0 flex-1 bg-blanc bg-linear-to-r from-corail to-corail bg-size-[0_1.5px] bg-bottom-left bg-no-repeat px-4 text-encre outline-none transition-[background-size] duration-300 placeholder:text-encre/60 focus:bg-size-[100%_1.5px] group-data-[state=error]:from-corail-fonce group-data-[state=error]:to-corail-fonce group-data-[state=error]:bg-size-[100%_1.5px]" id="newsletter-email" type="email" name="email" placeholder="<?= $placeholder ?>" required>
        <button class="h-12 cursor-pointer border-[1.5px] border-corail bg-corail px-3 pt-0.5 font-heading font-bold whitespace-nowrap text-encre transition-colors duration-200 enabled:hover:border-corail-fonce enabled:hover:bg-corail-fonce enabled:hover:text-ivoire enabled:focus-visible:border-corail-fonce enabled:focus-visible:bg-corail-fonce enabled:focus-visible:text-ivoire disabled:cursor-default group-data-[state=sending]:opacity-60 group-data-[state=success]:border-encre group-data-[state=success]:bg-encre group-data-[state=success]:text-ivoire" type="submit" data-sending="Envoi…" data-success="Envoyé ✓"><?= $home->newsletter_button()->or('Je m’inscris')->esc() ?></button>
    </form>
    <p class="mt-2 text-xs/normal text-corail-fonce empty:hidden peer-data-[state=success]:text-encre/60 lg:text-sm/normal" aria-live="polite" data-newsletter-message></p>
    <?php if ($mention ?? true): ?>
        <p class="text-mention mt-[1.0625rem] max-w-[29.75rem] lg:mt-[1.5625rem] [&_a]:underline">
            <?= $home->newsletter_mention()->or('En vous inscrivant, vous acceptez de recevoir nos e-mails. Désinscription possible à tout moment en un clic.')->esc() ?>
            <?php if ($privacy = $site->privacy()->toPage()): ?>
                En savoir plus : <a href="<?= $privacy->url() ?>">politique de confidentialité</a>.
            <?php endif ?>
        </p>
    <?php endif ?>
</div>
