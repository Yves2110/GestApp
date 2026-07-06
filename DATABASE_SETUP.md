# CONFIGURATION BASE DE DONNÉES - GESTAPP
**Guide complet pour la mise en place de la base de données**  
**Date:** 6 Juillet 2026

---

## 🚨 **PROBLÈME ACTUEL**

La base de données `gestapp_university` et l'utilisateur `gestapp_user` n'existent pas encore. Il faut les créer avant de pouvoir lancer l'application.

---

## 📋 **ÉTAPES DE CONFIGURATION**

### **ÉTAPE 1: Créer la base de données MySQL**

#### Option A: Avec phpMyAdmin (recommandé)
1. **Ouvrez phpMyAdmin** (http://localhost/phpmyadmin)
2. **Cliquez sur "Nouvelle base de données"**
3. **Nom:** `gestapp_university`
4. **Interclassement:** `utf8mb4_unicode_ci`
5. **Cliquez sur "Créer"**

#### Option B: Avec MySQL en ligne de commande
```sql
-- Connectez-vous à MySQL en tant que root
mysql -u root -p

-- Créez la base de données
CREATE DATABASE gestapp_university CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Créez l'utilisateur
CREATE USER 'gestapp_user'@'localhost' IDENTIFIED BY '';

-- Donnez les permissions
GRANT ALL PRIVILEGES ON gestapp_university.* TO 'gestapp_user'@'localhost';

-- Appliquez les changements
FLUSH PRIVILEGES;

-- Quittez
EXIT;
```

#### Option C: Avec MySQL Workbench
1. **Ouvrez MySQL Workbench**
2. **Connectez-vous en tant que root**
3. **Exécutez la requête:**
```sql
CREATE DATABASE gestapp_university CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'gestapp_user'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON gestapp_university.* TO 'gestapp_user'@'localhost';
FLUSH PRIVILEGES;
```

---

### **ÉTAPE 2: Vérifier la configuration**

Le fichier `.env` est déjà configuré:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestapp_university
DB_USERNAME=gestapp_user
DB_PASSWORD=
```

---

### **ÉTAPE 3: Lancer les migrations**

Une fois la base de données créée, exécutez:

```bash
# Dans le dossier GestApp
php artisan migrate:fresh --seed
```

Cette commande va:
- ✅ **Créer toutes les tables** (15 migrations)
- ✅ **Insérer les données de base** (seeders)
- ✅ **Créer les utilisateurs de test**

---

## 📊 **TABLES QUI SERONT CRÉÉES**

### **Tables principales:**
- `users` - Utilisateurs du système
- `roles` - Rôles (SuperAdmin, President, Admin, Service)
- `services` - Services universitaires
- `activities` - Activités (table principale)
- `objectives` - Objectifs globaux
- `under_objectives` - Sous-objectifs
- `periodes` - Périodes académiques

### **Tables système:**
- `password_resets` - Réinitialisation mots de passe
- `failed_jobs` - Jobs échoués
- `personal_access_tokens` - Tokens API
- `migrations` - Historique des migrations

### **Tables additionnelles:**
- `guides` - Documents guides
- `tdrs` - Tableaux de bord
- `activity_variables` - Variables d'activités
- `rapports` - Rapports
- `final_statuses` - Statuts finaux

---

## 🔧 **DÉPANNAGE**

### **Erreur: Access denied**
**Solution:** Vérifiez que l'utilisateur `gestapp_user` a bien été créé avec les bonnes permissions.

### **Erreur: Database doesn't exist**
**Solution:** Assurez-vous que la base `gestapp_university` existe bien.

### **Erreur: Connection refused**
**Solution:** Vérifiez que MySQL est bien démarré:
```bash
# Windows
net start mysql

# ou via XAMPP/WAMP
# Démarrez le service MySQL
```

---

## ✅ **VÉRIFICATION POST-INSTALLATION**

Après avoir lancé `php artisan migrate:fresh --seed`, vérifiez:

### **1. Base de données**
```bash
php artisan migrate:status
```
Devrait afficher toutes les migrations comme "Run".

### **2. Connexion à l'application**
1. **Allez sur** http://127.0.0.1:8000
2. **Connectez-vous** avec les comptes seeders:
   - SuperAdmin: `ismaelyveskabore@gmail.com` / `password`
   - President: `president@gmail.com` / `password`
   - Admin: `deps@gmail.com` / `password`

### **3. Test des fonctionnalités**
- ✅ Dashboard principal visible
- ✅ Création d'activité possible
- ✅ Dashboard analytique accessible (Admin+)
- ✅ Monitoring accessible (SuperAdmin)

---

## 🎯 **RÉSUMÉ RAPIDE**

1. **Créez la base** `gestapp_university` dans phpMyAdmin
2. **Créez l'utilisateur** `gestapp_user` (sans mot de passe)
3. **Lancez** `php artisan migrate:fresh --seed`
4. **Connectez-vous** et testez l'application

---

## 🚨 **IMPORTANT**

- **Ne modifiez pas** le fichier `.env` (déjà configuré)
- **Utilisez bien** `migrate:fresh --seed` (pas seulement `migrate`)
- **Le mot de passe** de l'utilisateur MySQL est vide (comme configuré)
- **Sauvegardez** votre base de données après installation

---

*Guide créé le 6 Juillet 2026*  
*GestApp University Management System*  
*Configuration base de données*
