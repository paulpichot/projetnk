# 🚀 Starter Kirby Professionnel

> Un starter Kirby CMS moderne et polyvalent pour PME, associations et organisations

[![Kirby CMS](https://img.shields.io/badge/Kirby-4.0-ff6600.svg)](https://getkirby.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.0-38bdf8.svg)](https://tailwindcss.com)
[![Vite](https://img.shields.io/badge/Vite-5.0-646cff.svg)](https://vitejs.dev)

## ✨ Présentation

Ce starter vous permet de créer rapidement un site web professionnel pour votre organisation. Il est conçu pour s'adapter facilement aux besoins des **PME**, **associations**, **ONG** et autres types d'organisations.

### 🎯 Pourquoi ce starter ?

- **Polyvalent** : S'adapte à différents types d'organisations
- **Prêt à l'emploi** : Configuration optimisée out-of-the-box
- **Moderne** : Technologies actuelles et performantes
- **Extensible** : Architecture modulaire et flexible
- **Accessible** : Respect des standards d'accessibilité

## 🛠️ Technologies

- **[Kirby CMS 4](https://getkirby.com)** - Système de gestion de contenu flexible
- **[Tailwind CSS 3](https://tailwindcss.com)** - Framework CSS utilitaire
- **[Vite 5](https://vitejs.dev)** - Build tool moderne et rapide
- **PHP 8.1+** - Backend robuste
- **JavaScript ES6+** - Interactivité moderne

## 🚀 Installation rapide

### 1. Cloner le projet
```bash
git clone https://github.com/votre-username/starter-kirby-pro mon-site
cd mon-site
```

### 2. Installation automatique
```bash
chmod +x install.sh
./install.sh
```

### 3. Démarrage
```bash
# Serveur de développement
php -S localhost:8000 kirby/router.php

# Build assets (nouveau terminal)
npm run dev
```

Votre site sera accessible sur `http://localhost:8000`

## 📚 Documentation

- **[📖 Guide de démarrage](README-STARTER.md)** - Installation détaillée et utilisation
- **[🎨 Guide de personnalisation](CUSTOMIZATION.md)** - Adaptation par type d'organisation
- **[📁 Structure du projet](PROJECT_INDEX.md)** - Organisation des fichiers

## 🏢 Types d'organisations supportés

### PME/Entreprises
- Présentation de services
- Portfolio clients
- Équipe commerciale
- Formulaires de devis

### Associations
- Missions et actions
- Gestion de bénévoles
- Appels aux dons
- Événements

### ONG
- Projets internationaux
- Rapports d'activité
- Transparence financière
- Témoignages terrain

## 📋 Fonctionnalités incluses

### ✅ Pages type
- [x] Accueil modulaire
- [x] À propos adaptatif
- [x] Services/Activités
- [x] Équipe/Membres
- [x] Contact avec formulaire
- [x] Blog/Actualités

### ✅ Gestion de contenu
- [x] Système de blocs modulaires
- [x] Gestion d'équipe complète
- [x] Galeries d'images optimisées
- [x] SEO intégré
- [x] Multi-langues ready

### ✅ Performance
- [x] Images optimisées automatiquement
- [x] CSS/JS minifiés
- [x] Cache intelligent
- [x] Lazy loading
- [x] Core Web Vitals optimisés

## 🎨 Exemples de personnalisation

### Pour une PME tech
```bash
# Configuration rapide
Organization_type: "enterprise"
Primary_color: "#2563eb"
Sector: "technology"
```

### Pour une association caritative
```bash
# Configuration rapide
Organization_type: "association"
Primary_color: "#dc2626"
Sector: "social"
```

## 🔧 Configuration

### Variables d'environnement
```env
# .env
KIRBY_DEBUG=false
ORGANIZATION_TYPE=enterprise
CONTACT_EMAIL=contact@monorganisation.com
ANALYTICS_ID=GA_TRACKING_ID
```

### Personnalisation rapide
1. **Informations** : Éditez `content/site.fr.txt`
2. **Couleurs** : Modifiez `tailwind.config.js`
3. **Contenu** : Utilisez le panel admin `/panel`

## 📱 Responsive & Accessibilité

- ✅ Mobile-first design
- ✅ Contraste WCAG AA
- ✅ Navigation clavier
- ✅ Screen readers friendly
- ✅ Progressive enhancement

## 🚀 Déploiement

### Hébergements compatibles
- **Shared hosting** : PHP 8.1+ requis
- **VPS** : Ubuntu/Debian recommandé
- **Cloud** : DigitalOcean, Linode, AWS

### Build de production
```bash
npm run build
```

## 🤝 Contribution

Les contributions sont les bienvenues ! 

1. Fork le projet
2. Créez votre branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. Commitez vos changements (`git commit -m 'Ajout nouvelle fonctionnalité'`)
4. Push la branche (`git push origin feature/nouvelle-fonctionnalite`)
5. Ouvrez une Pull Request

## 📄 Licence

Ce projet est sous licence MIT - voir le fichier [LICENSE](LICENSE) pour plus de détails.

## 🆘 Support

- 📖 [Documentation Kirby](https://getkirby.com/docs)
- 💬 [Forum Kirby](https://forum.getkirby.com)
- 🐛 [Issues GitHub](https://github.com/votre-username/starter-kirby-pro/issues)

## 🙏 Remerciements

- [Kirby CMS](https://getkirby.com) pour cet excellent CMS
- [Tailwind CSS](https://tailwindcss.com) pour le framework CSS
- [Vite](https://vitejs.dev) pour l'excellent build tool

---

**Créé avec ❤️ pour simplifier la création de sites professionnels**