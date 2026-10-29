<?php
/**
 * Formulaire newsletter
 * @var bool $mention Afficher la mention RGPD (oui par défaut)
 */
?>
<div class="newsletter">
    <form class="newsletter__form" action="<?= url('newsletter') ?>" method="post" data-newsletter>
        <label class="sr-only" for="newsletter-email">Votre adresse e-mail</label>
        <input class="newsletter__input" id="newsletter-email" type="email" name="email" placeholder="Votre adresse e-mail" required>
        <button class="btn" type="submit" data-sending="Envoi…" data-success="Inscrit ✓">Je m’inscris</button>
    </form>
    <p class="newsletter__message" aria-live="polite" data-newsletter-message></p>
    <?php if ($mention ?? true): ?>
        <p class="text-mention newsletter__mention">
            En vous inscrivant, vous acceptez de recevoir nos e-mails. Désinscription possible à tout moment en un clic.
            <?php if ($privacy = $site->privacy()->toPage()): ?>
                En savoir plus : <a href="<?= $privacy->url() ?>">politique de confidentialité</a>.
            <?php endif ?>
        </p>
    <?php endif ?>
</div>
