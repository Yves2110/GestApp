# DEBRIEF - GestApp University

## 1. Vue d'ensemble

**GestApp University** est une application web de gestion des activités universitaires. Elle permet à une université de planifier, suivre et analyser ses activités pédagogiques, administratives et opérationnelles par service.

- **Nom du projet :** GestApp
- **Version :** 2.0
- **Framework :** Laravel 11 (PHP 8.3)
- **Front-end :** Blade, Bootstrap 5, Vite, Bootstrap Icons, Font Awesome
- **Base de données :** MySQL / MariaDB (configurée via `config/database.php` et `.env`)
- **Auteur / développeur principal :** Yves2110

---

## 2. Objectifs du projet

- Centraliser le suivi des activités de chaque service.
- Structurer les activités autour d'objectifs et de sous-objectifs stratégiques.
- Offrir un tableau de bord et un module analytique avec graphiques.
- Permettre l'export de données (CSV) pour le reporting.
- Sécuriser l'accès par rôles et permissions.
- Tracer les actions importantes dans un journal d'audit.

---

## 3. Stack technique

### Back-end

- **Laravel 11.42+**
- **PHP 8.3**
- **Laravel Sanctum** (authentification API)
- **realrashid/sweet-alert** (notifications)
- **Eloquent ORM** avec eager loading

### Front-end

- **Blade** (moteur de templates Laravel)
- **Bootstrap 5.2.2 / 5.3.3**
- **Bootstrap Icons**
- **Vite** (build des assets CSS/JS)
- **Université-thème CSS** personnalisé (`resources/css/university-theme.css`)

### Base de données

- **MySQL / MariaDB**
- Migrations et seeders complets pour recréer un environnement de test réaliste.

---

## 4. Architecture et dossiers clés

| Dossier / Fichier | Rôle |
|-------------------|------|
| `app/Http/Controllers/` | Contrôleurs métier (Activités, Objectifs, Analytique, Export, Paramètres, etc.) |
| `app/Models/` | Modèles Eloquent (User, Activities, Objective, service, Periode, ...) |
| `app/Http/Middleware/` | Middleware d'authentification et de contrôle des rôles |
| `database/migrations/` | Structure de la base de données |
| `database/seeders/` | Données de test (utilisateurs, rôles, services, objectifs, activités, TDR, rapports, variables) |
| `resources/views/` | Templates Blade (`layouts/app.blade.php`, pages, composants) |
| `resources/css/university-theme.css` | Design system institutionnel |
| `public/assets/` | Bibliothèques locales (Bootstrap, icônes, images, scripts) |
| `routes/web.php` | Routes applicatives principales |
| `routes/api.php` | Routes API |

---

## 5. Modèle de données

Les entités principales et leurs relations :

### Utilisateurs, rôles et services

- **roles** : `SuperAdmin`, `president`, `admin`, `services`
- **services** : `Developper`, `PRESIDENT`, `D.E.P.S`, `D.S.I`, `D.A.F`
- **users** : rattachés à un `role` et un `service`
- **permissions** : permissions granulaires attachées aux rôles

### Gestion stratégique

- **objectives** : objectifs globaux
- **under_objectives** : sous-objectifs liés à un objectif parent

### Gestion opérationnelle

- **activities** : activités liées à un service, un objectif, un sous-objectif et une période
- **periodes** : trimestres / semestres (T1, T2, T3, T4)
- **tdrs** : Termes de référence d'une activité
- **rapports** : rapports d'activité
- **activity_variables** : variables d'activité (nombre de participants, formateurs, jours, lieu)
- **final_statuses** : statut final de réalisation d'une activité

### Documents et audit

- **guides** : documents téléversés (procédures, modèles, politiques)
- **audit_logs** : journal des actions sensibles

---

## 6. Rôles et permissions

| Rôle | Niveau d'accès |
|------|----------------|
| **SuperAdmin** | Accès total : utilisateurs, permissions, audit, services, monitoring |
| **Président** | Dashboard stratégique, analytics, exports, vue des objectifs et services |
| **Admin** | Gestion complète des activités, objectifs, sous-objectifs, guides, exports |
| **Service** | Création et consultation des activités de son propre service |

Les permissions concrètes sont définies dans `database/seeders/PermissionSeeder.php` :

- `activities.view`, `activities.create`, `activities.update`, `activities.delete`
- `objectives.view`, `objectives.create`, `objectives.update`, `objectives.delete`
- `services.view`, `services.manage`
- `analytics.view`, `exports.manage`
- `guides.view`, `guides.manage`
- `users.manage`, `audit.view`

---

## 7. Modules et fonctionnalités détaillées

### 7.1 Authentification

- Page de connexion sécurisée (`routes/web.php` route `/` -> `AuthController@index`)
- Connexion avec email et mot de passe
- Limite de tentatives (throttle) pour éviter le brute-force
- Déconnexion (`/logout`)
- Création de compte réservée au SuperAdmin (`/registration`)
- Champs `is_active` et `must_reset_password` pour la gestion des comptes

### 7.2 Tableau de bord

- Route : `/home`
- Contrôleur : `HomeController`
- Affichage des KPIs principaux :
  - Nombre d'objectifs globaux
  - Nombre de sous-objectifs
  - Nombre d'activités
  - Nombre de services
- Actions rapides vers Objectifs, Activités, Sous-objectifs, Guides
- Liste des activités récentes

### 7.3 Gestion des objectifs et sous-objectifs

- **Objectifs** (`/objective`)
  - Liste paginée avec relation vers les sous-objectifs
  - Création, modification, suppression
- **Sous-objectifs** (`/under_objective`)
  - Création rattachée à un objectif parent
  - Suppression

Contrôleur : `ObjectiveController`

### 7.4 Gestion des activités

Route principale : `/activites`
Contrôleur : `ActivitiesController`

Fonctionnalités :

- Liste des activités avec pagination
- Filtrage selon le rôle (un utilisateur Service ne voit que les activités de son service)
- Création d'une activité avec :
  - Service concerné
  - Objectif et sous-objectif
  - Période (trimestre)
  - Libellé, indicateur, cible
  - Budget (`price`) et source de financement
  - Structure exécutrice
  - Statut et commentaire
- Modification / suppression
- Gestion des documents rattachés :
  - **TDR** (Termes de référence)
  - **Rapports**
  - **Variables d'activité** (participants, formateurs, jours, lieu)
  - **Statut final** (libellé, numéro de marché)

### 7.5 Gestion des services

- Routes : `/service` (liste) et `/ajoutservice` (création)
- Contrôleur : `HomeController`
- CRUD des services (réservé au SuperAdmin)
- Chaque activité est obligatoirement liée à un service

### 7.6 Gestion des périodes académiques

- Route : `/trimestre`
- Contrôleur : `PeriodeController`
- Visualisation des périodes (T1, T2, T3, T4)

### 7.7 Guides et documents

- Routes : `/Guide` (liste), `/guide` (upload), `/delete/{id}` (suppression)
- Contrôleur : `GuideController`
- Téléversement de documents de procédure
- Téléchargement via le dossier `public/docs/`

### 7.8 Analytique

- Route principale : `/analytics`
- Contrôleur : `AnalyticsController`

Fonctionnalités :

- Statistiques globales (objectifs, activités, services, utilisateurs, budget)
- Graphiques interactifs :
  - Répartition des activités par service
  - Répartition des statuts d'activités
  - Budget par service
  - Évolution mensuelle
- Top 5 des services les plus actifs
- Top 5 des objectifs les plus performants
- API interne pour les données graphiques
- Export Excel (`/analytics/export`)
- Rapport de performance (`/analytics/performance`)

### 7.9 Exports

- Route : `/export`
- Contrôleur : `ExportController`

Formats et contenus :

- Export CSV des activités
- Export CSV des statistiques globales
- Export CSV du rapport de performance
- API JSON des données d'export (`/api/export/data`)

### 7.10 Paramètres et administration

Accès réservé au SuperAdmin (`middleware('superadmin')`).

- **Paramètres généraux** (`/parametres`)
- **Gestion des utilisateurs** (`/parametres/utilisateurs`)
  - Activation / désactivation d'un compte
  - Réinitialisation des identifiants
- **Gestion des permissions par rôle** (`/parametres/permissions`)
- **Journal d'audit** (`/parametres/audit`)
  - Historique des actions sensibles

### 7.11 Monitoring système

- Route : `/monitoring`
- Contrôleur : `MonitoringController`

Métriques surveillées :

- Santé de l'application (`/api/health`)
- Métriques de performance (`/api/metrics`)
- Logs applicatifs (`/api/logs`)

---

## 8. Données de test et comptes par défaut

Lancer la commande suivante pour réinitialiser la base avec les données de test :

```bash
php artisan migrate:fresh --seed
```

### Comptes utilisateurs créés par les seeders

| Rôle | Email | Mot de passe | Service |
|------|-------|--------------|---------|
| SuperAdmin | `ismaelyveskabore@gmail.com` | `password` | Developper |
| Président | `president@gmail.com` | `password` | PRESIDENT |
| Admin | `deps@gmail.com` | `password` | D.E.P.S |
| Service (D.S.I) | `service@gmail.com` | `password` | D.S.I |

### Seeders exécutés

- `RoleSeeder`
- `ServiceSeeder`
- `AdminSeeder`
- `SuperAdminSeeder`
- `PresidentSeeder`
- `PeriodeSeeder`
- `PermissionSeeder`
- `ServiceUserSeeder`
- `ObjectiveSeeder`
- `UnderObjectiveSeeder`
- `ActivitySeeder`

Les seeders génèrent des objectifs, sous-objectifs, activités, TDR, rapports, variables d'activité et statuts finaux réalistes pour faciliter les tests.

---

## 9. Commandes principales

```bash
# Lancer le serveur de développement
php artisan serve

# Migrer et seeder
php artisan migrate:fresh --seed

# Vider le cache des vues
php artisan view:clear

# Vider le cache de configuration
php artisan config:clear

# Compiler les assets front-end
npm run build
# ou
npm run dev
```

---

## 10. État et améliorations récentes

### Corrections récentes

- Ajout du CSS Bootstrap manquant dans le layout principal (`resources/views/layouts/app.blade.php`).
- Passage des ressources Bootstrap et Bootstrap Icons en local pour fiabilité.
- Correction du nom de relation Eloquent `under_objectives` -> `under_objective` dans `ObjectiveController` et `ActivitiesController`.
- Correction de l'enregistrement d'un service (`label` au lieu de `service`) dans `HomeController`.
- Refonte du formulaire de connexion (`resources/views/auth/login.blade.php`) avec un affichage centré et responsive.
- Nettoyage des tirets longs dans les vues et suppression des liens de scripts incorrects.

### Axes d'amélioration possibles

- Créer une page dédiée par service avec vue détaillée.
- Ajouter un workflow de validation des activités (soumission -> validation -> rejet).
- Mettre en place des notifications par email ou alertes internes.
- Ajouter des tests automatisés (PHPUnit / Laravel Dusk).
- Internationalisation complète (actuellement principalement en français).

---

## 11. Contacts et support

- **Développeur :** Yves2110
- **Application :** GestApp University
- **Licence :** MIT

---

*Document généré le 12 juillet 2026.*
