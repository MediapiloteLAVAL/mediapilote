#!/bin/bash

#
# Script de déploiement automatique du thème Aiko by Mediapilote
# 
# Ce script permet de créer et déployer une nouvelle version du thème
# avec mise à jour automatique du serveur de distribution
#
# Usage: ./deploy-theme.sh [version]
# Exemple: ./deploy-theme.sh 1.0.1
#

set -e

# Configuration
THEME_NAME="mediapilote"
THEME_DIR="$(pwd)"
BUILD_DIR="$THEME_DIR/build"
RELEASES_DIR="$THEME_DIR/releases"
SERVER_PATH="/path/to/your/update/server" # À modifier selon votre serveur

# Couleurs pour les messages
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonctions utilitaires
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

success() {
    echo -e "${GREEN}✓${NC} $1"
}

warning() {
    echo -e "${YELLOW}⚠${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
    exit 1
}

# Vérifier les prérequis
check_prerequisites() {
    log "Vérification des prérequis..."
    
    # Vérifier si nous sommes dans le bon répertoire
    if [[ ! -f "style.css" ]] || [[ ! -f "functions.php" ]]; then
        error "Ce script doit être exécuté depuis le répertoire racine du thème"
    fi
    
    # Vérifier si git est installé
    if ! command -v git &> /dev/null; then
        error "Git n'est pas installé"
    fi
    
    # Vérifier si zip est installé
    if ! command -v zip &> /dev/null; then
        error "L'utilitaire zip n'est pas installé"
    fi
    
    success "Prérequis validés"
}

# Obtenir la version
get_version() {
    if [[ -n "$1" ]]; then
        VERSION="$1"
    else
        # Lire la version depuis style.css
        VERSION=$(grep "Version:" style.css | head -1 | sed 's/.*Version: *//' | sed 's/ *$//')
        if [[ -z "$VERSION" ]]; then
            error "Impossible de détecter la version dans style.css"
        fi
    fi
    
    log "Version à déployer: $VERSION"
}

# Mettre à jour la version dans les fichiers
update_version_files() {
    log "Mise à jour des fichiers de version..."
    
    # Mettre à jour style.css
    sed -i.bak "s/Version: .*/Version: $VERSION/" style.css
    
    # Mettre à jour le fichier de configuration
    sed -i.bak "s/'current_version' => '[^']*'/'current_version' => '$VERSION'/" inc/update-config.php
    
    # Mettre à jour le serveur de mise à jour
    sed -i.bak "s/define('CURRENT_VERSION', '[^']*');/define('CURRENT_VERSION', '$VERSION');/" update-server.php
    sed -i.bak "s/define('THEME_VERSION', '[^']*');/define('THEME_VERSION', '$VERSION');/" download.php
    
    # Supprimer les fichiers de sauvegarde
    rm -f style.css.bak inc/update-config.php.bak update-server.php.bak download.php.bak
    
    success "Fichiers de version mis à jour"
}

# Créer le répertoire de build
prepare_build() {
    log "Préparation du build..."
    
    # Nettoyer le répertoire de build s'il existe
    if [[ -d "$BUILD_DIR" ]]; then
        rm -rf "$BUILD_DIR"
    fi
    
    # Créer les répertoires
    mkdir -p "$BUILD_DIR"
    mkdir -p "$RELEASES_DIR"
    
    success "Répertoires préparés"
}

# Copier les fichiers du thème
copy_theme_files() {
    log "Copie des fichiers du thème..."
    
    # Créer le répertoire du thème dans build
    THEME_BUILD_DIR="$BUILD_DIR/$THEME_NAME"
    mkdir -p "$THEME_BUILD_DIR"
    
    # Copier tous les fichiers sauf ceux à exclure
    rsync -av \
        --exclude='.git' \
        --exclude='.gitignore' \
        --exclude='node_modules' \
        --exclude='build' \
        --exclude='releases' \
        --exclude='*.log' \
        --exclude='deploy-theme.sh' \
        --exclude='update-server.php' \
        --exclude='download.php' \
        --exclude='.DS_Store' \
        --exclude='gulpfile.js' \
        --exclude='package.json' \
        --exclude='package-lock.json' \
        "$THEME_DIR/" "$THEME_BUILD_DIR/"
    
    success "Fichiers copiés vers $THEME_BUILD_DIR"
}

# Compiler les assets si nécessaire
compile_assets() {
    log "Compilation des assets..."
    
    # Vérifier s'il y a un package.json (pour npm/gulp)
    if [[ -f "package.json" ]]; then
        warning "Compilation des assets détectée, mais ignorée dans ce script simple"
        warning "Vous pouvez ajouter ici vos commandes de build (npm run build, gulp build, etc.)"
        # npm install --production
        # npm run build
        # gulp build
    fi
    
    success "Assets traités"
}

# Créer l'archive ZIP
create_archive() {
    log "Création de l'archive ZIP..."
    
    ARCHIVE_NAME="$THEME_NAME-v$VERSION.zip"
    ARCHIVE_PATH="$RELEASES_DIR/$ARCHIVE_NAME"
    
    cd "$BUILD_DIR"
    zip -r "$ARCHIVE_PATH" "$THEME_NAME/" -q
    cd "$THEME_DIR"
    
    success "Archive créée: $ARCHIVE_PATH"
}

# Générer les checksums
generate_checksums() {
    log "Génération des checksums..."
    
    ARCHIVE_NAME="$THEME_NAME-v$VERSION.zip"
    ARCHIVE_PATH="$RELEASES_DIR/$ARCHIVE_NAME"
    
    cd "$RELEASES_DIR"
    
    # MD5
    md5sum "$ARCHIVE_NAME" > "$ARCHIVE_NAME.md5"
    
    # SHA256
    sha256sum "$ARCHIVE_NAME" > "$ARCHIVE_NAME.sha256"
    
    cd "$THEME_DIR"
    
    success "Checksums générés"
}

# Commit Git
git_commit() {
    log "Commit Git..."
    
    # Vérifier s'il y a des changements
    if git diff --quiet && git diff --staged --quiet; then
        warning "Aucun changement à commiter"
    else
        git add -A
        git commit -m "Version $VERSION - Déploiement automatique"
        git tag "v$VERSION"
        success "Commit effectué avec le tag v$VERSION"
    fi
}

# Déployer vers le serveur (optionnel)
deploy_to_server() {
    if [[ -n "$SERVER_PATH" ]] && [[ "$SERVER_PATH" != "/path/to/your/update/server" ]]; then
        log "Déploiement vers le serveur..."
        
        # Copier les fichiers du serveur de mise à jour
        scp update-server.php download.php "$SERVER_PATH/"
        
        # Copier l'archive
        scp "$RELEASES_DIR/$THEME_NAME-v$VERSION.zip" "$SERVER_PATH/releases/"
        
        success "Déployé vers le serveur"
    else
        warning "Chemin du serveur non configuré - déploiement manuel requis"
    fi
}

# Nettoyer les anciens builds
cleanup() {
    log "Nettoyage..."
    
    # Supprimer le répertoire de build
    rm -rf "$BUILD_DIR"
    
    # Garder seulement les 5 dernières releases
    cd "$RELEASES_DIR"
    ls -t *.zip 2>/dev/null | tail -n +6 | xargs -r rm
    ls -t *.md5 2>/dev/null | tail -n +6 | xargs -r rm
    ls -t *.sha256 2>/dev/null | tail -n +6 | xargs -r rm
    cd "$THEME_DIR"
    
    success "Nettoyage terminé"
}

# Afficher le résumé
show_summary() {
    echo ""
    echo "=========================================="
    echo "  DÉPLOIEMENT TERMINÉ"
    echo "=========================================="
    echo "Thème: $THEME_NAME"
    echo "Version: $VERSION"
    echo "Archive: $RELEASES_DIR/$THEME_NAME-v$VERSION.zip"
    echo ""
    echo "Prochaines étapes:"
    echo "1. Vérifiez l'archive générée"
    echo "2. Testez la mise à jour sur un site de développement"
    echo "3. Déployez les fichiers serveur si nécessaire"
    echo "4. Communiquez la nouvelle version à vos clients"
    echo ""
}

# Script principal
main() {
    log "Début du déploiement du thème $THEME_NAME"
    
    check_prerequisites
    get_version "$1"
    update_version_files
    prepare_build
    copy_theme_files
    compile_assets
    create_archive
    generate_checksums
    git_commit
    deploy_to_server
    cleanup
    show_summary
    
    success "Déploiement terminé avec succès!"
}

# Exécuter le script principal
main "$@"