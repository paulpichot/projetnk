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
