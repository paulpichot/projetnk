<?php

// À copier en site/config/newsletter.php sur chaque environnement (non versionné)
// Utilisé par la route /newsletter (site/plugins/newsletter)
return [
    'brevo_key' => '',
    'brevo_list' => 0,     // identifiant de la liste Brevo (Contacts > Listes)
    'brevo_template' => 0, // identifiant du modèle d'e-mail « double opt-in » (Campagnes > Modèles)
    // 'brevo_redirect' => 'https://…', // page après confirmation (par défaut : page « Inscription confirmée »)
    'debug' => false, // true : affiche l’erreur Brevo sous le champ
];
