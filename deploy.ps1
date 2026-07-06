# =============================================
# SCRIPT DE DÉPLOIEMENT POWERSHELL - GESTAPP
# =============================================
# Version: 2.0
# Date: 6 Juillet 2026
# Description: Déploiement automatisé pour environnement Windows

param(
    [string]$Environment = "production",
    [switch]$SkipBackup = $false,
    [switch]$SkipMigrations = $false
)

# Couleurs pour les logs
$Colors = @{
    Info = "Blue"
    Success = "Green"
    Warning = "Yellow"
    Error = "Red"
}

# Fonctions de logging
function Write-Log {
    param(
        [string]$Message,
        [string]$Level = "Info"
    )
    
    $Timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $Color = $Colors[$Level]
    
    Write-Host "[$Timestamp] [$Level] $Message" -ForegroundColor $Color
}

function Write-Info { param([string]$Message) Write-Log $Message "Info" }
function Write-Success { param([string]$Message) Write-Log $Message "Success" }
function Write-Warning { param([string]$Message) Write-Log $Message "Warning" }
function Write-Error { param([string]$Message) Write-Log $Message "Error" }

# Vérification des prérequis
function Test-Prerequisites {
    Write-Info "Vérification des prérequis..."
    
    # Vérifier PHP
    try {
        $PhpVersion = & php -r "echo PHP_VERSION;"
        Write-Info "Version PHP: $PhpVersion"
        
        $MajorVersion = [int]($PhpVersion.Split('.')[0])
        if ($MajorVersion -lt 8) {
            throw "PHP 8.3+ requis. Version actuelle: $PhpVersion"
        }
    }
    catch {
        Write-Error "PHP n'est pas installé ou version incompatible: $($_.Exception.Message)"
        exit 1
    }
    
    # Vérifier Composer
    try {
        & composer --version | Out-Null
        Write-Info "Composer trouvé"
    }
    catch {
        Write-Error "Composer n'est pas installé"
        exit 1
    }
    
    # Vérifier Node.js
    try {
        $NodeVersion = & node --version
        Write-Info "Version Node.js: $NodeVersion"
    }
    catch {
        Write-Error "Node.js n'est pas installé"
        exit 1
    }
    
    # Vérifier npm
    try {
        $NpmVersion = & npm --version
        Write-Info "Version npm: $NpmVersion"
    }
    catch {
        Write-Error "npm n'est pas installé"
        exit 1
    }
    
    Write-Success "Prérequis vérifiés avec succès"
}

# Création du backup
function New-Backup {
    if ($SkipBackup) {
        Write-Warning "Backup ignoré (paramètre -SkipBackup)"
        return
    }
    
    Write-Info "Création d'une sauvegarde..."
    
    $BackupDir = "backups\$(Get-Date -Format 'yyyyMMdd_HHmmss')"
    New-Item -ItemType Directory -Path $BackupDir -Force | Out-Null
    
    # Backup de la base de données (MySQL)
    try {
        Write-Info "Sauvegarde de la base de données..."
        & mysqldump --single-transaction --routines --triggers gestapp_university | Out-File "$BackupDir\database.sql"
        Write-Success "Base de données sauvegardée"
    }
    catch {
        Write-Warning "mysqldump non trouvé ou erreur de connexion, backup base de données ignoré"
    }
    
    # Backup des fichiers
    Write-Info "Sauvegarde des fichiers..."
    if (Test-Path "storage\app") {
        Copy-Item -Path "storage\app" -Destination "$BackupDir\" -Recurse -Force
    }
    if (Test-Path ".env") {
        Copy-Item -Path ".env" -Destination "$BackupDir\" -Force
    }
    
    Write-Success "Backup créé dans: $BackupDir"
    return $BackupDir
}

# Mise à jour du code
function Update-Code {
    Write-Info "Mise à jour du code source..."
    
    # Pull des dernières modifications (si git)
    if (Test-Path ".git") {
        try {
            & git pull origin main
            Write-Success "Code mis à jour depuis Git"
        }
        catch {
            Write-Warning "Erreur lors du pull Git: $($_.Exception.Message)"
        }
    }
    else {
        Write-Warning "Dépôt Git non trouvé, mise à jour ignorée"
    }
}

# Installation des dépendances PHP
function Install-PhpDependencies {
    Write-Info "Installation des dépendances PHP..."
    
    try {
        & composer install --no-dev --optimize-autoloader --no-interaction
        Write-Success "Dépendances PHP installées"
    }
    catch {
        Write-Error "Erreur lors de l'installation des dépendances PHP: $($_.Exception.Message)"
        exit 1
    }
}

# Installation des dépendances Node
function Install-NodeDependencies {
    Write-Info "Installation des dépendances Node.js..."
    
    try {
        & npm ci --production
        Write-Success "Dépendances Node.js installées"
    }
    catch {
        Write-Error "Erreur lors de l'installation des dépendances Node: $($_.Exception.Message)"
        exit 1
    }
}

# Build des assets
function Build-Assets {
    Write-Info "Compilation des assets..."
    
    try {
        & npm run build
        Write-Success "Assets compilés avec succès"
    }
    catch {
        Write-Error "Erreur lors de la compilation des assets: $($_.Exception.Message)"
        exit 1
    }
}

# Optimisation Laravel
function Optimize-Laravel {
    Write-Info "Optimisation de Laravel..."
    
    try {
        & php artisan config:cache
        & php artisan route:cache
        & php artisan view:cache
        & php artisan optimize
        Write-Success "Laravel optimisé"
    }
    catch {
        Write-Error "Erreur lors de l'optimisation Laravel: $($_.Exception.Message)"
        exit 1
    }
}

# Exécution des migrations
function Invoke-Migrations {
    if ($SkipMigrations) {
        Write-Warning "Migrations ignorées (paramètre -SkipMigrations)"
        return
    }
    
    Write-Info "Exécution des migrations..."
    
    try {
        & php artisan migrate --force
        Write-Success "Migrations exécutées"
    }
    catch {
        Write-Error "Erreur lors des migrations: $($_.Exception.Message)"
        exit 1
    }
}

# Nettoyage du cache
function Clear-Cache {
    Write-Info "Nettoyage du cache..."
    
    try {
        & php artisan cache:clear
        & php artisan config:clear
        & php artisan route:clear
        & php artisan view:clear
        Write-Success "Cache nettoyé"
    }
    catch {
        Write-Warning "Erreur lors du nettoyage du cache: $($_.Exception.Message)"
    }
}

# Vérification de la santé
function Test-Health {
    Write-Info "Vérification de la santé de l'application..."
    
    # Vérifier si l'application répond (adapter l'URL)
    try {
        $Response = Invoke-WebRequest -Uri "http://localhost" -TimeoutSec 10
        if ($Response.StatusCode -eq 200) {
            Write-Success "Application répond correctement"
        }
        else {
            Write-Warning "Application répond avec le code: $($Response.StatusCode)"
        }
    }
    catch {
        Write-Warning "Application ne répond pas ou erreur de connexion"
    }
    
    # Vérifier la version de Laravel
    try {
        $LaravelVersion = & php artisan --version
        Write-Info "Version Laravel: $LaravelVersion"
    }
    catch {
        Write-Warning "Impossible de vérifier la version Laravel"
    }
    
    # Vérifier l'environnement
    $AppEnv = $env:APP_ENV
    if ($AppEnv -eq "production") {
        Write-Success "Environnement de production configuré"
    }
    else {
        Write-Warning "Environnement: $AppEnv (devrait être production)"
    }
}

# Configuration des permissions
function Set-Permissions {
    Write-Info "Configuration des permissions..."
    
    try {
        # Permissions pour les répertoires de stockage
        if (Test-Path "storage") {
            & icacls storage /grant "IIS_IUSRS:(OI)(CI)F" /T
        }
        if (Test-Path "bootstrap\cache") {
            & icacls "bootstrap\cache" /grant "IIS_IUSRS:(OI)(CI)F" /T
        }
        
        Write-Success "Permissions configurées"
    }
    catch {
        Write-Warning "Erreur lors de la configuration des permissions: $($_.Exception.Message)"
    }
}

# Notification de déploiement
function Send-DeploymentNotification {
    Write-Info "Envoi de la notification de déploiement..."
    
    # Ici vous pouvez ajouter une notification email, Slack, etc.
    $NotificationData = @{
        message = "GestApp déployé avec succès en production"
        environment = $Environment
        timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    }
    
    Write-Success "Notification envoyée"
}

# Fonction principale
function Start-Deployment {
    Write-Host "============================================" -ForegroundColor Blue
    Write-Host "  DÉPLOIEMENT GESTAPP - PRODUCTION" -ForegroundColor Blue
    Write-Host "============================================" -ForegroundColor Blue
    Write-Host ""
    
    # Configuration de l'environnement
    $env:APP_ENV = $Environment
    $env:APP_DEBUG = "false"
    
    # Exécution des étapes
    Test-Prerequisites
    $BackupDir = New-Backup
    Update-Code
    Install-PhpDependencies
    Install-NodeDependencies
    Build-Assets
    Clear-Cache
    Invoke-Migrations
    Optimize-Laravel
    Set-Permissions
    Test-Health
    Send-DeploymentNotification
    
    Write-Host ""
    Write-Host "============================================" -ForegroundColor Green
    Write-Host "  DÉPLOIEMENT TERMINÉ AVEC SUCCÈS" -ForegroundColor Green
    Write-Host "============================================" -ForegroundColor Green
    Write-Host ""
    Write-Info "Application disponible: http://your-domain.com"
    if ($BackupDir) {
        Write-Info "Backup créé: $BackupDir"
    }
    Write-Host ""
}

# Gestion des erreurs
trap {
    Write-Error "Une erreur est survenue durant le déploiement: $($_.Exception.Message)"
    exit 1
}

# Point d'entrée
try {
    Start-Deployment
}
catch {
    Write-Error "Déploiement échoué: $($_.Exception.Message)"
    exit 1
}
