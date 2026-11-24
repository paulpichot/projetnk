<?php

use Kirby\Cms\Page;
use Kirby\Content\Field;

/**
 * Nous soutenir : l'accroche sert de description par défaut (kirby-seo)
 */
class SupportPage extends Page
{
    public function metaDefaults(string|null $lang = null): array
    {
        return [
            'metaDescription' => new Field($this, 'metaDescription', $this->headline()->excerpt(160)),
        ];
    }
}
