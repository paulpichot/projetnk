<?php
/**
 * Données structurées (schema.org), sorties par seo/schemas avec le WebSite de kirby-seo :
 * l'association sur toutes les pages, et l'article sur les pages projet publiées
 */
use Spatie\SchemaOrg\Schema;

$socials = $site->menusocials()->toStructure()
    ->map(fn ($item) => $item->link()->value())
    ->filter(fn ($link) => str_starts_with($link, 'http'))
    ->values();

// Rattaché à la page : $site->schema() plante dans kirby-seo rc.1 (SchemaSingleton, page nulle)
$page->schema('NGO')
    ->name($site->title()->value())
    ->url($site->url())
    ->description($site->description()->value())
    ->logo(url('assets/favicon/apple-touch-icon.png'))
    ->sameAs(array_values($socials));

if ($page->intendedTemplate()->name() === 'project' && !$page->upcoming()->toBool()) {
    $image = $page->cover()->toFile() ?? $page->thumbnail()->toFile();
    $article = $page->schema('Article')
        ->headline($page->headline()->or($page->title())->value())
        ->description($page->lead()->excerpt(300))
        ->url($page->url())
        ->dateModified(date('c', $page->modified()))
        ->publisher(Schema::nGO()->name($site->title()->value())->url($site->url()));

    if ($page->published()->isNotEmpty()) {
        $article->datePublished(date('c', $page->published()->toDate()));
    }
    if ($page->author()->isNotEmpty()) {
        $article->author(Schema::person()->name($page->author()->value()));
    }
    if ($image) {
        $article->image($image->resize(1200)->url());
    }
}
