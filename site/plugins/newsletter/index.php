<?php

use Kirby\Cms\App as Kirby;
use Kirby\Http\Remote;
use Kirby\Http\Response;
use Kirby\Toolkit\V;

/**
 * Inscription newsletter : POST /newsletter → API contacts Brevo
 * Clés dans .env : BREVO_KEY, BREVO_LIST
 */
Kirby::plugin('projetnk/newsletter', [
    'routes' => [
        [
            'pattern' => 'newsletter',
            'method' => 'POST',
            'action' => function () {
                $kirby = kirby();
                $email = trim(get('email', ''));
                $ok = false;
                $message = 'Cette adresse e-mail n’est pas valide.';
                $detail = null;

                if (V::email($email)) {
                    $message = 'Un souci technique nous empêche de vous inscrire, réessayez plus tard.';

                    try {
                        $response = Remote::request('https://api.brevo.com/v3/contacts', [
                            'method' => 'POST',
                            'headers' => [
                                'Accept: application/json',
                                'Content-Type: application/json',
                                'api-key: ' . env('BREVO_KEY'),
                            ],
                            'data' => json_encode([
                                'email' => $email,
                                'listIds' => [(int)env('BREVO_LIST')],
                                'updateEnabled' => true,
                            ]),
                        ]);

                        $ok = $response->code() >= 200 && $response->code() < 300;
                        $detail = $ok ? null : 'Erreur Brevo ' . $response->code() . ' : ' . ($response->json()['message'] ?? $response->content());
                    } catch (Exception $e) {
                        $detail = 'Brevo injoignable : ' . $e->getMessage();
                    }

                    if ($ok) {
                        $message = 'Merci, votre inscription est bien prise en compte.';
                    }
                }

                if ($detail) {
                    error_log('newsletter : ' . $detail);
                }

                if (str_contains($kirby->request()->header('Accept') ?? '', 'application/json')) {
                    return Response::json([
                        'ok' => $ok,
                        'message' => $message,
                        'detail' => option('debug') ? $detail : null,
                    ], $ok ? 200 : 400);
                }

                go(site()->url() . '#newsletter');
            }
        ]
    ]
]);
