<?php

/**
 * Mode lancement (interrupteur dans le Panel, onglet Identité) : la page « Bientôt » et son compte à rebours remplacent
 * le site public ; toute autre page renvoie vers l'accueil, sauf les pages légales et la confirmation d'inscription à la
 * newsletter. Hors mode lancement, /bientot renvoie vers l'accueil. Les comptes connectés au Panel voient le vrai site.
 * Les routes des plugins (newsletter, rgive) et les fichiers (robots.txt, sitemap) ne sont pas concernés : seules les
 * pages sont détournées, le reste passe à la route suivante
 */

use Kirby\Cms\App as Kirby;
use Kirby\Http\Route;

Kirby::plugin('projetnk/launch', [
    'routes' => [
        [
            'pattern' => '(:all)',
            'method' => 'GET|HEAD',
            'action' => function (string $path = '') {
                $kirby = kirby();
                $soon = page('bientot');

                if ($soon && !$kirby->user()) {
                    if ($kirby->site()->launch_mode()->toBool()) {
                        if ($path === '') {
                            return $soon;
                        }
                        $target = page($path);
                        $open = ['legal', 'cookies', 'confirmation'];
                        if ($target && !in_array($target->intendedTemplate()->name(), $open)) {
                            return go('/', 302);
                        }
                    } elseif ($path === 'bientot') {
                        return go('/', 302);
                    }
                }

                Route::next();
            },
        ],
    ],
]);
