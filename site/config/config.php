<?php

/**
 * Configuration minimale qui fonctionne + optimisations SEO de base
 */

return [
    'languages' => true,
    'languages.detect' => false,
    'debug' => true,
    'home' => 'accueil',
    'date.handler' => 'intl',
    'whoops' => true,
    'api' => [
        'allowInsecure' => true
    ],
    'thumbs' => [
        'quality' => 85,      // Sweet spot qualité/poids
        'format' => 'webp',   // WebP par défaut (-60% poids)
        'autoOrient' => true,
        'interlace' => true,  // JPEG progressif (perception vitesse)
        'sharpen' => 10,      // Netteté légère pour compenser compression
        
        // Driver optimisé (ImageMagick > GD si disponible)
        'driver' => function_exists('imagick') ? 'im' : 'gd',
        
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
    
    // Panel optimisé (si utilisé)
    'panel' => [
        'css' => 'panel.css',      // CSS panel minifié
        'js' => 'panel.js'         // JS panel minifié
    ],
    
    // Routes optimisées
    'routes' => [
        // Préchargement DNS pour domaines externes
        [
            'pattern' => '.*',
            'action' => function() {
                // Headers performance dans toutes les réponses
                kirby()->response()->header('X-Content-Type-Options', 'nosniff');
                kirby()->response()->header('X-Frame-Options', 'DENY');
                return kirby()->resolve();
            }
        ]
        ],
    
    'slugs' => [
        'language' => 'fr',
        'allowed' => 'a-z0-9',
    ],
    
    'hooks' => [
        'page.update:after' => function ($newPage, $oldPage) {
            if(empty($newPage->og_image()->value())) {
                $ogImage = $newPage->head_image()->toFile();
                if ($ogImage) {
                    $newPage->update(['og_image' => $ogImage->url()]);
                }
            }
            $now = date('Y-m-d H:i:s');
            $newPage->update([
                'modified_at' => $now,
            ]);
            $newPage->save();
        },
        
        'page.create:after' => function ($page) {
            try {
                kirby()->cache('pages')?->flush();
            } catch (Exception $e) {
                // Ignorer les erreurs en développement
            }
        },
    ]
];