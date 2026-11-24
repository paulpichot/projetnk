<?php

use Kirby\Cms\Page;
use Kirby\Content\Field;

/**
 * Projet : description et image de partage par défaut (kirby-seo), tirées du chapô et de l'image principale
 * (ou du visuel de la carte) tant que les champs de l'onglet SEO sont vides ; un projet « à venir » n'a pas
 * encore de contenu et reste hors des moteurs de recherche et du sitemap
 */
class ProjectPage extends Page
{
    public function metaDefaults(string|null $lang = null): array
    {
        $defaults = [
            'metaDescription' => new Field($this, 'metaDescription', $this->lead()->or($this->headline())->excerpt(160)),
        ];

        if ($image = $this->cover()->toFile() ?? $this->thumbnail()->toFile()) {
            $defaults['ogImage'] = new Field($this, 'ogImage', $image->filename());
        }

        if ($this->upcoming()->toBool()) {
            // Balise robots fournie telle quelle (kirby-seo rc.1 ne la génère pas) ; robotsIndex vide = faux,
            // ce qui retire la page du sitemap sans produire de balise parasite
            $defaults['robots'] = ['name' => 'robots', 'content' => 'noindex'];
            $defaults['robotsIndex'] = new Field($this, 'robotsIndex', '');
        }

        return $defaults;
    }
}
