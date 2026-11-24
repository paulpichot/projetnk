<?php

use Kirby\Cms\App as Kirby;
use Kirby\Http\Response;

/**
 * Inscription à la newsletter (formulaire du snippet components/newsletter), reprise du countdown, en double opt-in :
 * Brevo envoie un e-mail de confirmation (modèle double opt-in) et n'ajoute l'adresse à la liste qu'après le clic sur
 * son lien, qui mène ensuite à la page de confirmation du site (gabarit confirmation).
 * Réponse JSON pour le formulaire en JS (src/js/custom/newsletter.js), retour à la page d'origine sinon.
 * Configuration dans site/config/newsletter.php (non versionné, voir newsletter.example.php).
 */
Kirby::plugin('projetnk/newsletter', [
    'routes' => [
        [
            'pattern' => 'newsletter',
            'method' => 'POST',
            'action' => function () {
                $file = kirby()->root('config') . '/newsletter.php';
                $config = is_readable($file) ? include $file : null;
                $email = filter_var(get('email'), FILTER_VALIDATE_EMAIL);
                $ok = false;
                $message = 'Cette adresse e-mail n’est pas valide.';
                $detail = null;

                if (!$config) {
                    $message = 'Un souci technique nous empêche de vous inscrire, réessayez plus tard.';
                    $detail = match (true) {
                        !is_file($file) => 'site/config/newsletter.php introuvable',
                        !is_readable($file) => 'site/config/newsletter.php illisible (droits du fichier)',
                        default => 'site/config/newsletter.php ne renvoie pas de tableau (return [ … ];)',
                    };
                } elseif (!is_array($config) || empty($config['brevo_key']) || empty($config['brevo_list']) || empty($config['brevo_template'])) {
                    $config = is_array($config) ? $config : [];
                    $message = 'Un souci technique nous empêche de vous inscrire, réessayez plus tard.';
                    $detail = 'site/config/newsletter.php incomplet : brevo_key, brevo_list et brevo_template sont requis';
                } elseif ($email) {
                    // Page ouverte après la confirmation : celle du site (gabarit confirmation), sinon l'accueil
                    $redirect = $config['brevo_redirect'] ?? site()->children()->template('confirmation')->first()?->url() ?? site()->url();
                    $curl = curl_init('https://api.brevo.com/v3/contacts/doubleOptinConfirmation');
                    curl_setopt_array($curl, [
                        CURLOPT_POST => true,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [
                            'accept: application/json',
                            'content-type: application/json',
                            'api-key: ' . $config['brevo_key'],
                        ],
                        CURLOPT_POSTFIELDS => json_encode([
                            'email' => $email,
                            'includeListIds' => [(int)$config['brevo_list']],
                            'templateId' => (int)($config['brevo_template'] ?? 0),
                            'redirectionUrl' => $redirect,
                        ]),
                    ]);
                    $body = curl_exec($curl);
                    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
                    $error = curl_error($curl);
                    curl_close($curl);

                    $ok = $status >= 200 && $status < 300;
                    $message = $ok ? 'Consultez votre boîte mail : nous venons de vous envoyer un message pour confirmer votre inscription.' : 'Un souci technique nous empêche de vous inscrire, réessayez plus tard.';
                    $detail = match (true) {
                        $ok => null,
                        $status === 0 => 'Brevo injoignable : ' . $error,
                        default => 'Erreur Brevo ' . $status . ' : ' . (json_decode($body, true)['message'] ?? $body),
                    };
                }

                if ($detail) {
                    error_log('newsletter : ' . $detail);

                    if ($config['debug'] ?? false) {
                        $message = $detail;
                    }
                }

                if (str_contains(kirby()->request()->header('Accept') ?? '', 'application/json')) {
                    return Response::json(['ok' => $ok, 'message' => $message, 'detail' => $detail], $ok ? 200 : 400);
                }

                return go(kirby()->request()->header('Referer') ?? site()->url());
            },
        ],
    ],
]);
