<?php

use Kirby\Cms\App as Kirby;
use Kirby\Http\Remote;
use Kirby\Http\Response;

/**
 * RGive : campagne de don affichée en formulaire intégré (format Embed) sur Nous soutenir et ses copies privées.
 * Lien saisi sur la page, sinon celui de Nous soutenir (copies privées), sinon celui du bouton « Faire un don » du menu
 * s'il mène à la plateforme RGive (Site > Identité)
 *
 * Bandeau d'objectif au-dessus du formulaire (snippet components/rgive-goal) : route /rgive/<page> qui renvoie en JSON
 * le nombre de contributeurs (API publique de RGive), l'objectif et la date de fin (lus sur la page de la campagne, que
 * l'API ne fournit pas). Appel côté serveur : l'API refuse les autres domaines et la page Nous soutenir est en cache
 * statique. Résultat gardé 5 minutes en cache ; valeur absente = null (le bandeau garde alors celle du Panel)
 */
Kirby::plugin('projetnk/rgive', [
    'options' => [
        'cache' => true,
    ],
    'pageMethods' => [
        'rgiveCampaign' => function (): ?string {
            if (!in_array($this->intendedTemplate()->name(), ['support', 'support-private'])) {
                return null;
            }

            $site = $this->site();
            $source = $site->children()->template('support')->first();
            if ($link = $this->rgive_campaign()->or($source?->rgive_campaign())->toUrl()) {
                return $link;
            }

            $platform = $site->rgive_url()->toUrl();
            $cta = $site->menucta()->toStructure()->first()?->link()->toUrl();

            return $platform && $cta && str_starts_with($cta, rtrim($platform, '/') . '/') ? $cta : null;
        },
    ],
    'routes' => [
        [
            'pattern' => 'rgive/(:all)',
            'action' => function (string $id) {
                $campaign = page($id)?->rgiveCampaign();
                if (!$campaign) {
                    return false;
                }

                $data = kirby()->cache('projetnk.rgive')->getOrSet(md5($campaign), function () use ($campaign) {
                    $html = (string) Remote::get($campaign, ['timeout' => 5])->content();
                    $match = fn (string $pattern) => preg_match($pattern, $html, $found) ? $found[1] : null;

                    $uuid = $match('/"campaignUuid":\s*"([0-9a-f-]{36})"/');
                    $goal = $match('/live-counter-contributors-goal[^>]*>([^<]+)</');
                    $deadline = $match('/data-countdown-deadline="(\d+)"/');
                    $counter = $uuid ? Remote::get(parse_url($campaign, PHP_URL_SCHEME) . '://' . parse_url($campaign, PHP_URL_HOST) . "/api/campaign-widgets-data/$uuid/", ['timeout' => 5])->json()['live_counter'] ?? null : null;

                    return [
                        'contributors' => $counter['total_contributors'] ?? null,
                        'goal' => (int) preg_replace('/\D/', '', (string) $goal) ?: null,
                        'deadline' => $deadline ? (int) $deadline * 1000 : null,
                    ];
                }, 5);

                return Response::json($data);
            },
        ],
    ],
]);
