<?php

/**
 * Couleur du haut de chaque page : fond de <body> (head.php), fondu entre pages
 * et écran d'intro qui part vers cette page (data-background, header/nav-link.php)
 */
Kirby::plugin('projetnk/background', [
    'pageMethods' => [
        'background' => function (): string {
            return $this->intendedTemplate()->name() === 'projects' ? 'sable' : 'ivoire';
        },
    ],
]);
