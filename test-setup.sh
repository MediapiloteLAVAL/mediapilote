#!/bin/bash

#
# Script de test rapide du système de mise à jour
# 
# Ce script simule un déploiement complet en local pour tester le système
#

# Configuration
THEME_DIR="$(pwd)"
TEST_VERSION="1.0.1"

echo "🧪 Test rapide du système de mise à jour"
echo "========================================"

# 1. Vérifier la structure des fichiers
echo "1. Vérification des fichiers..."

FILES_TO_CHECK=(
    "inc/theme-updater.php"
    "inc/update-config.php" 
    "update-server.php"
    "download.php"
    "deploy-theme.sh"
    "test-updater.php"
)

for file in "${FILES_TO_CHECK[@]}"; do
    if [[ -f "$file" ]]; then
        echo "   ✅ $file"
    else
        echo "   ❌ $file (manquant)"
    fi
done

# 2. Créer le dossier releases s'il n'existe pas
echo ""
echo "2. Préparation des dossiers..."
mkdir -p releases
echo "   ✅ Dossier releases/ créé"

# 3. Test de création d'archive (simulation)
echo ""
echo "3. Test de création d'archive..."
ARCHIVE_NAME="mediapilote-v$TEST_VERSION.zip"

# Créer une archive de test simple
zip -r "releases/$ARCHIVE_NAME" . \
    -x "*.git*" \
    -x "node_modules/*" \
    -x "releases/*" \
    -x "*.log" \
    -q

if [[ -f "releases/$ARCHIVE_NAME" ]]; then
    echo "   ✅ Archive créée: releases/$ARCHIVE_NAME"
    
    # Afficher la taille
    size=$(du -h "releases/$ARCHIVE_NAME" | cut -f1)
    echo "   📦 Taille: $size"
else
    echo "   ❌ Erreur lors de la création de l'archive"
fi

# 4. Test des checksums
echo ""
echo "4. Test des checksums..."
cd releases
if [[ -f "$ARCHIVE_NAME" ]]; then
    md5sum "$ARCHIVE_NAME" > "$ARCHIVE_NAME.md5"
    sha256sum "$ARCHIVE_NAME" > "$ARCHIVE_NAME.sha256"
    echo "   ✅ Checksums générés"
else
    echo "   ❌ Archive non trouvée pour les checksums"
fi
cd "$THEME_DIR"

# 5. Vérifier la configuration
echo ""
echo "5. Vérification de la configuration..."

# Vérifier si les URLs de configuration sont personnalisées
config_file="inc/update-config.php"
if grep -q "votre-serveur.com" "$config_file"; then
    echo "   ⚠️  URL du serveur à personnaliser dans $config_file"
else
    echo "   ✅ Configuration du serveur personnalisée"
fi

if grep -q "votre_cle_secrete_unique_2024" "$config_file"; then
    echo "   ⚠️  Clé de licence à personnaliser dans $config_file"
else
    echo "   ✅ Clé de licence personnalisée"
fi

# 6. Test de syntaxe PHP
echo ""
echo "6. Test de syntaxe PHP..."

PHP_FILES=(
    "inc/theme-updater.php"
    "inc/update-config.php"
    "update-server.php"
    "download.php"
    "test-updater.php"
)

php_ok=true
for file in "${PHP_FILES[@]}"; do
    if [[ -f "$file" ]]; then
        if php -l "$file" > /dev/null 2>&1; then
            echo "   ✅ $file (syntaxe OK)"
        else
            echo "   ❌ $file (erreur de syntaxe)"
            php_ok=false
        fi
    fi
done

# 7. Résumé
echo ""
echo "========================================"
echo "RÉSUMÉ DU TEST"
echo "========================================"

if [[ "$php_ok" == true ]]; then
    echo "✅ Syntaxe PHP validée"
else
    echo "❌ Erreurs de syntaxe détectées"
fi

if [[ -f "releases/$ARCHIVE_NAME" ]]; then
    echo "✅ Génération d'archives fonctionnelle"
else
    echo "❌ Problème de génération d'archives"
fi

echo ""
echo "Prochaines étapes:"
echo "1. Personnalisez les URLs et clés dans inc/update-config.php"
echo "2. Uploadez update-server.php et download.php sur votre serveur"
echo "3. Testez avec: php test-updater.php"
echo "4. Déployez avec: ./deploy-theme.sh $TEST_VERSION"

echo ""
echo "📝 Consultez README-UPDATES.md pour les instructions complètes"

# Nettoyage optionnel
read -p "Supprimer l'archive de test? (y/N) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    rm -f "releases/$ARCHIVE_NAME"*
    echo "Archive de test supprimée"
fi