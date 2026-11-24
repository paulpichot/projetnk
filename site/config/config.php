<?php

/**
 * Configuration minimale qui fonctionne + optimisations SEO de base
 */

return [
    'languages' => true,
    'languages.detect' => false,
    // Production : erreurs masquées et pages indexables ; le local (projetnk.test) réactive le débogage
    // dans config.projetnk.test.php
    'debug' => false,
    'home' => 'accueil',
    'date.handler' => 'intl',
    'thumbs' => [
        'quality' => 85,      // Sweet spot qualité/poids
        'format' => 'webp',   // WebP par défaut (-60% poids)
        'autoOrient' => true,
        'interlace' => true,  // JPEG progressif (perception vitesse)
        'sharpen' => 10,      // Netteté légère pour compenser compression
        
        // Driver optimisé (ImageMagick > GD si disponible)
        'driver' => function_exists('imagick') ? 'im' : 'gd',
        
        // Jeux de tailles (srcset) : le navigateur choisit selon la largeur affichée (attribut sizes) et la densité
        // de l'écran ; aucune image n'est agrandie au-delà de l'original
        'srcsets' => [
            'full' => [720, 1080, 1440, 2160, 2880],     // pleine largeur (hero, photo Projets)
            'content' => [800, 1200, 1600, 2152],        // images d'article (1076px max)
            'poster' => [640, 960, 1280, 1920, 2640],    // aperçus vidéo
            'member' => [240, 480, 720],                 // photos de l'équipe (240px max)
            'card' => [
                '301w' => ['width' => 301, 'height' => 276, 'crop' => true],
                '602w' => ['width' => 602, 'height' => 552, 'crop' => true],
            ],
        ],

        // Presets ultra-optimisés par usage
        'presets' => [
            'hero' => [
                'width' => 1920,
                'height' => 1080,
                'quality' => 88,    // Qualité élevée pour hero
                'format' => 'webp',
                'sharpen' => 15
            ],
            'card' => [
                'width' => 800,
                'height' => 1000,
                'quality' => 82,    // Équilibre parfait
                'format' => 'webp',
                'sharpen' => 10
            ],
            'thumb' => [
                'width' => 400,
                'height' => 400,
                'quality' => 75,    // Plus compressé pour miniatures
                'format' => 'webp'
            ]
        ]
    ],
    
    // ===================================
    // CACHE STATIQUE ULTRARAPIDE
    // ===================================
    'cache' => [
        'pages' => [
            'active' => true,
            'type' => 'static',  // Plugin Staticache = 10x plus rapide
            'ignore' => function ($page) {
                // Ignorer seulement pages avec formulaires/dynamiques
                return in_array($page->template()->name(), [
                    'support-private', 'confirmation', // Pages non indexées : en-tête X-Robots-Tag envoyé à chaque affichage
                    'contact',  // Si formulaire
                    'search'    // Si recherche
                ]);
            }
        ],
        
        // Cache media très long (images ne changent pas)
        'media' => [
            'active' => true,
            'type' => 'file',
            'expires' => 60 * 24 * 365  // 1 an
        ]
    ],
    
    // ===================================
    // OPTIMISATIONS DIVERSES
    // ===================================
    
    // Désactiver fonctionnalités non utilisées
    'smartypants' => false,        // Si guillemets typographiques non utilisés
    
    // Markdown léger
    'markdown' => [
        'extra' => false,           // Plus léger
        'breaks' => true
    ],
    
    // SEO (kirby-seo) : les pages « Bientôt » et de confirmation restent hors du sitemap ; langue des balises Open Graph
    'tobimori.seo' => [
        'lang' => 'fr_FR',
        'sitemap' => [
            'excludeTemplates' => ['error', 'soon', 'success', 'support-private', 'confirmation'],
        ],
    ],

    'slugs' => [
        'language' => 'fr',
        'allowed' => 'a-z0-9',
    ],
    
    'hooks' => [
        // En-têtes de sécurité sur les pages (une route « .* » interceptait aussi robots.txt et sitemap.xml)
        'page.render:before' => function (string $contentType, array $data, Kirby\Cms\Page $page) {
            kirby()->response()->header('X-Content-Type-Options', 'nosniff');
            kirby()->response()->header('X-Frame-Options', 'DENY');
            // Pages privées (copies de Nous soutenir) et confirmation newsletter : jamais indexées
            if (in_array($page->intendedTemplate()->name(), ['support-private', 'confirmation'])) {
                kirby()->response()->header('X-Robots-Tag', 'noindex, nofollow');
            }
        },
    ]
];