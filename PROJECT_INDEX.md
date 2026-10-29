# Index du projet

Ce document liste les dossiers et fichiers principaux du projet, avec une brève description pour chacun. Il sert à faciliter la compréhension rapide du projet par toute IA ou développeur.

## Racine

- `README.md` : Présentation du projet et instructions de base.
- `composer.json` / `composer.lock` : Dépendances PHP et configuration du projet backend.
- `package.json` / `package-lock.json` / `yarn.lock` : Dépendances JavaScript et outils frontend.
- `index.php` : Point d'entrée principal de l'application Kirby.
- `.htaccess` : Configuration serveur Apache.
- `tailwind.config.js` / `vite.config.js` : Configurations Tailwind CSS et Vite.
- `favicon.ico` / `favicon.svg` : Icônes du site.

## Dossiers principaux

- `assets/` : Fichiers statiques générés (CSS, JS, images, etc.).
- `content/` : Contenus éditoriaux du site (pages, articles, etc.).
- `kirby/` : Code source du CMS Kirby (core).
- `media/` : Fichiers médias générés par Kirby (images, thumbs, etc.).
- `node_modules/` : Dépendances JS installées (générées automatiquement).
- `site/` : Code spécifique au projet (voir détail ci-dessous).
- `src/` : Sources frontend (CSS, JS non compilés).
- `vendor/` : Dépendances PHP installées (générées automatiquement).

## Détail du dossier `site/`

- `accounts/` : Comptes utilisateurs (admin, etc.).
- `api/` : Extensions d’API personnalisées.
- `blueprints/` : Schémas de structure pour le panel Kirby (pages, utilisateurs, etc.).
- `cache/` : Fichiers de cache générés.
- `config/` : Fichiers de configuration du projet.
- `controllers/` : Contrôleurs PHP pour la logique des pages.
- `languages/` : Fichiers de traduction.
- `plugins/` : Plugins Kirby personnalisés ou installés.
- `sessions/` : Sessions utilisateurs.
- `snippets/` : Fragments de vues réutilisables (PHP).
- `templates/` : Templates principaux des pages (PHP).
- `utils.php` : Fonctions utilitaires globales.

## Détail du dossier `src/`

- `css/` : Sources CSS (ex : Tailwind).
- `js/` : Sources JavaScript personnalisées.

---

Pour toute question, se référer au README ou aux fichiers de documentation du projet.
