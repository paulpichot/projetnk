#!/bin/bash

echo "🚀 Transformation en Starter Kirby Professionnel"
echo "================================================"

# Couleurs pour l'affichage
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonction d'affichage
print_step() {
    echo -e "${BLUE}$1${NC}"
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

# Vérification que nous sommes dans un projet Kirby
if [ ! -f "index.php" ] || [ ! -d "kirby" ]; then
    print_error "Ce script doit être exécuté à la racine d'un projet Kirby"
    exit 1
fi

print_step "1. Nettoyage du contenu spécifique..."

# Supprimer le blueprint accordéon spécifique
if [ -f "site/blueprints/pages/accordion.yml" ]; then
    rm "site/blueprints/pages/accordion.yml"
    print_success "Blueprint accordion.yml supprimé"
fi

# Supprimer le contenu spécifique Paul Pichot
if [ -d "content/1_accueil" ]; then
    rm -rf "content/1_accueil"
    print_success "Contenu spécifique supprimé"
fi

# Supprimer les images spécifiques
if [ -d "content/_drafts" ]; then
    rm -rf "content/_drafts"
    print_success "Brouillons supprimés"
fi

print_step "2. Création de la structure générique..."

# Créer les dossiers de contenu d'exemple
mkdir -p "content/_exemple"
mkdir -p "content/1_accueil"
mkdir -p "content/2_a-propos"
mkdir -p "content/3_activites"
mkdir -p "content/4_equipe"
mkdir -p "content/5_contact"

print_success "Structure de contenu créée"

print_step "3. Installation des nouveaux blueprints..."

# Créer le dossier blueprints s'il n'existe pas
mkdir -p "site/blueprints/pages"
mkdir -p "site/blueprints/tabs"
mkdir -p "site/blueprints/fields"

# Note: Les blueprints seront ajoutés manuellement depuis les artifacts

print_success "Dossiers blueprints préparés"

print_step "4. Sauvegarde de la configuration actuelle..."

# Sauvegarder l'ancien site.txt
if [ -f "content/site.fr.txt" ]; then
    cp "content/site.fr.txt" "content/site.fr.txt.backup"
    print_success "Configuration sauvegardée"
fi

print_step "5. Nettoyage du cache..."

# Supprimer le cache
if [ -d "site/cache" ]; then
    rm -rf "site/cache/*"
    print_success "Cache nettoyé"
fi

# Supprimer les médias générés
if [ -d "media" ]; then
    rm -rf "media"
    print_success "Médias nettoyés"
fi

print_step "6. Mise à jour du README..."

# Sauvegarder l'ancien README
if [ -f "README.md" ]; then
    mv "README.md" "README-original.md"
    print_success "README original sauvegardé"
fi

print_step "7. Création des fichiers d'exemple..."

# Créer un fichier .env.example
cat > .env.example << 'EOF'
# Configuration Kirby
KIRBY_DEBUG=true
KIRBY_CACHE=false

# Type d'organisation
ORGANIZATION_TYPE=enterprise

# Analytics (optionnel)
ANALYTICS_ID=

# Contact
CONTACT_EMAIL=contact@monorganisation.com

# Sécurité
KIRBY_PANEL_INSTALL=true
EOF

print_success ".env.example créé"

# Créer un fichier de configuration d'exemple
cat > "content/_exemple/README.md" << 'EOF'
# Contenu d'exemple

Ce dossier contient des exemples de contenu pour vous aider à démarrer :

## Pages d'exemple
- Accueil avec blocs modulaires
- À propos d'une organisation
- Services/Activités
- Équipe avec membres
- Contact avec formulaire

## Comment utiliser
1. Copiez le contenu qui vous intéresse
2. Adaptez-le à votre organisation
3. Supprimez ce dossier `_exemple`

## Types d'organisation supportés
- PME/Entreprises
- Associations
- ONG
- Coopératives
- Fondations
EOF

print_success "Documentation d'exemple créée"

print_step "8. Finalisation..."

# Créer un script d'installation
cat > "install.sh" << 'EOF'
#!/bin/bash
echo "🚀 Installation du Starter Kirby Professionnel"
echo "=============================================="

# Installation des dépendances
echo "Installation des dépendances PHP..."
composer install

echo "Installation des dépendances JavaScript..."
npm install

echo "Configuration de l'environnement..."
if [ ! -f ".env" ]; then
    cp ".env.example" ".env"
    echo "Fichier .env créé"
fi

echo ""
echo "✅ Installation terminée !"
echo ""
echo "📋 Prochaines étapes :"
echo "1. Configurez votre .env"
echo "2. Lancez 'php -S localhost:8000 kirby/router.php'"
echo "3. Dans un autre terminal : 'npm run dev'"
echo "4. Allez sur http://localhost:8000/panel pour configurer"
EOF

chmod +x "install.sh"
print_success "Script d'installation créé"

echo ""
echo "🎉 Transformation terminée !"
echo ""
echo "📋 Actions à effectuer manuellement :"
echo "1. Copier les nouveaux blueprints depuis les artifacts"
echo "2. Remplacer content/site.fr.txt par la version générique"
echo "3. Créer le contenu d'exemple"
echo "4. Mettre à jour le README principal"
echo ""
echo "📚 Fichiers de référence :"
echo "- README-STARTER.md : Documentation du starter"
echo "- CUSTOMIZATION.md : Guide de personnalisation"
echo "- content/_exemple/ : Exemples de contenu"
echo ""
print_success "Le repository est maintenant prêt à devenir un starter !"