#!/bin/bash

# =============================================
# SCRIPT DE DÉPLOIEMENT - GESTAPP UNIVERSITY
# =============================================
# Version: 2.0
# Date: 6 Juillet 2026
# Description: Déploiement automatisé pour environnement de production

set -e  # Arrêter le script en cas d'erreur

# Couleurs pour les logs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Fonctions de logging
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Vérification des prérequis
check_prerequisites() {
    log_info "Vérification des prérequis..."
    
    # Vérifier PHP
    if ! command -v php &> /dev/null; then
        log_error "PHP n'est pas installé"
        exit 1
    fi
    
    PHP_VERSION=$(php -r "echo PHP_VERSION;")
    log_info "Version PHP: $PHP_VERSION"
    
    if [[ $(echo "$PHP_VERSION" | cut -d. -f1) -lt 8 ]]; then
        log_error "PHP 8.3+ requis. Version actuelle: $PHP_VERSION"
        exit 1
    fi
    
    # Vérifier Composer
    if ! command -v composer &> /dev/null; then
        log_error "Composer n'est pas installé"
        exit 1
    fi
    
    # Vérifier Node.js
    if ! command -v node &> /dev/null; then
        log_error "Node.js n'est pas installé"
        exit 1
    fi
    
    # Vérifier npm
    if ! command -v npm &> /dev/null; then
        log_error "npm n'est pas installé"
        exit 1
    fi
    
    log_success "Prérequis vérifiés avec succès"
}

# Backup avant déploiement
create_backup() {
    log_info "Création d'une sauvegarde..."
    
    BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"
    mkdir -p "$BACKUP_DIR"
    
    # Backup de la base de données
    if command -v mysqldump &> /dev/null; then
        log_info "Sauvegarde de la base de données..."
        mysqldump --single-transaction --routines --triggers gestapp_university > "$BACKUP_DIR/database.sql"
        log_success "Base de données sauvegardée"
    else
        log_warning "mysqldump non trouvé, backup base de données ignoré"
    fi
    
    # Backup des fichiers
    log_info "Sauvegarde des fichiers..."
    cp -r storage/app "$BACKUP_DIR/"
    cp -r .env "$BACKUP_DIR/"
    
    log_success "Backup créé dans: $BACKUP_DIR"
}

# Mise à jour du code
update_code() {
    log_info "Mise à jour du code source..."
    
    # Pull des dernières modifications (si git)
    if [ -d ".git" ]; then
        git pull origin main
        log_success "Code mis à jour depuis Git"
    else
        log_warning "Dépôt Git non trouvé, mise à jour ignorée"
    fi
}

# Installation des dépendances PHP
install_php_dependencies() {
    log_info "Installation des dépendances PHP..."
    
    composer install --no-dev --optimize-autoloader --no-interaction
    
    log_success "Dépendances PHP installées"
}

# Installation des dépendances Node
install_node_dependencies() {
    log_info "Installation des dépendances Node.js..."
    
    npm ci --production
    log_success "Dépendances Node.js installées"
}

# Build des assets
build_assets() {
    log_info "Compilation des assets..."
    
    npm run build
    
    log_success "Assets compilés avec succès"
}

# Optimisation Laravel
optimize_laravel() {
    log_info "Optimisation de Laravel..."
    
    # Cache de configuration
    php artisan config:cache
    
    # Cache des routes
    php artisan route:cache
    
    # Cache des vues
    php artisan view:cache
    
    # Optimisation du chargement automatique
    php artisan optimize
    
    log_success "Laravel optimisé"
}

# Exécution des migrations
run_migrations() {
    log_info "Exécution des migrations..."
    
    php artisan migrate --force
    
    log_success "Migrations exécutées"
}

# Nettoyage du cache
clear_cache() {
    log_info "Nettoyage du cache..."
    
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    log_success "Cache nettoyé"
}

# Vérification de la santé
health_check() {
    log_info "Vérification de la santé de l'application..."
    
    # Vérifier si l'application répond
    if curl -f -s http://localhost > /dev/null; then
        log_success "Application répond correctement"
    else
        log_error "Application ne répond pas"
        exit 1
    fi
    
    # Vérifier la version de Laravel
    LARAVEL_VERSION=$(php artisan --version | grep -o 'Laravel Framework [0-9.]*' | cut -d' ' -f3)
    log_info "Version Laravel: $LARAVEL_VERSION"
    
    # Vérifier l'environnement
    if [ "$APP_ENV" = "production" ]; then
        log_success "Environnement de production configuré"
    else
        log_warning "Environnement: $APP_ENV (devrait être production)"
    fi
}

# Permissions
set_permissions() {
    log_info "Configuration des permissions..."
    
    # Permissions pour les répertoires de stockage
    chmod -R 775 storage bootstrap/cache
    
    # Permissions pour les fichiers
    chmod -R 644 storage/logs/*.log
    
    # Owner du groupe web (adapter selon votre serveur)
    # chown -R www-data:www-data storage bootstrap/cache
    
    log_success "Permissions configurées"
}

# Notification de déploiement
notify_deployment() {
    log_info "Envoi de la notification de déploiement..."
    
    # Ici vous pouvez ajouter une notification Slack, email, etc.
    # Exemple avec curl vers un webhook
    # curl -X POST -H 'Content-type: application/json' \
    #     --data '{"text":"GestApp déployé avec succès en production"}' \
    #     YOUR_SLACK_WEBHOOK_URL
    
    log_success "Notification envoyée"
}

# Fonction principale
main() {
    echo -e "${BLUE}============================================${NC}"
    echo -e "${BLUE}  DÉPLOIEMENT GESTAPP - PRODUCTION${NC}"
    echo -e "${BLUE}============================================${NC}"
    echo ""
    
    # Vérifier si on est en root
    if [ "$EUID" -eq 0 ]; then
        log_error "Ne pas exécuter ce script en root"
        exit 1
    fi
    
    # Variables d'environnement
    export APP_ENV=production
    export APP_DEBUG=false
    
    # Exécution des étapes
    check_prerequisites
    create_backup
    update_code
    install_php_dependencies
    install_node_dependencies
    build_assets
    clear_cache
    run_migrations
    optimize_laravel
    set_permissions
    health_check
    notify_deployment
    
    echo ""
    echo -e "${GREEN}============================================${NC}"
    echo -e "${GREEN}  DÉPLOIEMENT TERMINÉ AVEC SUCCÈS${NC}"
    echo -e "${GREEN}============================================${NC}"
    echo ""
    log_info "Application disponible: http://your-domain.com"
    log_info "Backup créé: $BACKUP_DIR"
    echo ""
}

# Gestion des erreurs
trap 'log_error "Une erreur est survenue durant le déploiement"; exit 1' ERR

# Point d'entrée
if [[ "${BASH_SOURCE[0]}" == "${0}" ]]; then
    main "$@"
fi
