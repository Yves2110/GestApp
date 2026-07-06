# ACCÈS SEEDERS - GESTAPP UNIVERSITY
**Comptes de test pour l'application**  
**Date:** 6 Juillet 2026

---

## 🔑 **ACCÈS UTILISATEURS PRÉDÉFINIS**

### **1. SUPER ADMINISTRATEUR**
- **Email:** `ismaelyveskabore@gmail.com`
- **Mot de passe:** `password`
- **Rôle:** SuperAdmin (role_id: 1)
- **Service:** Service 1
- **Nom:** KABORE Ismael Yves
- **Téléphone:** 77634303

**Accès complet à toutes les fonctionnalités:**
- ✅ Dashboard principal
- ✅ Dashboard analytique
- ✅ Monitoring système
- ✅ Gestion des services
- ✅ Gestion des utilisateurs
- ✅ Tous les exports

---

### **2. PRÉSIDENT**
- **Email:** `president@gmail.com`
- **Mot de passe:** `password`
- **Rôle:** President (role_id: 2)
- **Service:** Service 2
- **Nom:** President President
- **Téléphone:** 70000001

**Accès stratégique:**
- ✅ Dashboard principal
- ✅ Dashboard analytique
- ✅ Exports globaux
- ❌ Monitoring système
- ❌ Gestion des services
- ❌ Gestion des utilisateurs

---

### **3. ADMINISTRATEUR**
- **Email:** `deps@gmail.com`
- **Mot de passe:** `password`
- **Rôle:** Admin (role_id: 3)
- **Service:** Service 3
- **Nom:** DEPS DEPS
- **Téléphone:** 70000000

**Accès opérationnel:**
- ✅ Dashboard principal
- ✅ Dashboard analytique
- ✅ Gestion des activités
- ✅ Exports de service
- ❌ Monitoring système
- ❌ Gestion des services

---

## 🚀 **COMMENT UTILISER LES SEEDERS**

### **Étape 1: Lancer les seeders**
```bash
# Dans votre terminal, dans le dossier GestApp
php artisan db:seed
```

### **Étape 2: Accéder à l'application**
1. **URL:** http://127.0.0.1:8000
2. **Cliquez sur "Se connecter"
3. **Utilisez un des comptes ci-dessus**

### **Étape 3: Tester selon le rôle**
- **SuperAdmin:** Testez toutes les fonctionnalités
- **President:** Testez dashboard analytique et exports
- **Admin:** Testez gestion des activités

---

## 📊 **HIÉRARCHIE DES PERMISSIONS**

| Rôle | ID | Dashboard | Analytique | Monitoring | Services | Utilisateurs | Exports |
|------|----|-----------|------------|------------|----------|-------------|---------|
| SuperAdmin | 1 | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| President | 2 | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ |
| Admin | 3 | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ |
| Service | 4 | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ |

---

## 🎯 **SCÉNARIOS DE TEST RECOMMANDÉS**

### **Test 1: SuperAdmin (Accès complet)**
1. **Connexion** avec `ismaelyveskabore@gmail.com`
2. **Dashboard principal** - Vérifiez les KPIs
3. **Dashboard analytique** - Testez les graphiques
4. **Monitoring** - Vérifiez l'état du système
5. **Gestion activités** - Créez une activité
6. **Exports** - Testez l'export CSV

### **Test 2: President (Vue stratégique)**
1. **Connexion** avec `president@gmail.com`
2. **Dashboard analytique** - Consultez les statistiques
3. **Exports** - Exportez les rapports globaux
4. **Vérifiez** que monitoring/services sont inaccessibles

### **Test 3: Admin (Gestion opérationnelle)**
1. **Connexion** avec `deps@gmail.com`
2. **Gestion activités** - Créez/modifiez des activités
3. **Dashboard** - Consultez vos statistiques de service
4. **Exports** - Exportez les données de votre service

---

## 🔧 **INFORMATIONS TECHNIQUES**

### **Structure des rôles**
```php
// RoleSeeder.php
1 => 'SuperAdmin'  // Accès complet
2 => 'President'   // Vue stratégique
3 => 'Admin'       // Gestion opérationnelle
4 => 'Service'     // Gestion de service uniquement
```

### **Services disponibles**
```php
// ServiceSeeder.php
1 => 'Service 1'
2 => 'Service 2'
3 => 'Service 3'
4 => 'Service 4'
5 => 'Service 5'
```

### **Périodes académiques**
```php
// PeriodeSeeder.php
1 => 'Semestre 1'
2 => 'Semestre 2'
3 => 'Trimestre 1'
4 => 'Trimestre 2'
5 => 'Trimestre 3'
6 => 'Trimestre 4'
```

---

## ⚠️ **NOTES IMPORTANTES**

### **Sécurité**
- **Mot de passe par défaut:** `password` (à changer en production)
- **Emails de test:** Utilisez les emails fournis
- **Ne pas utiliser** ces comptes en production

### **Base de données**
- **Les seeders créent** les utilisateurs de base
- **Services et périodes** sont aussi créés
- **Objectifs et activités** peuvent être créés via l'interface

### **Développement**
- **Pour réinitialiser:** `php artisan migrate:fresh --seed`
- **Pour ajouter des utilisateurs:** Créer de nouveaux seeders
- **Pour modifier les accès:** Éditer les seeders existants

---

## 🎉 **PRÊT À TESTER !**

1. **Lancez les seeders:** `php artisan db:seed`
2. **Connectez-vous** avec les comptes ci-dessus
3. **Testez toutes les fonctionnalités** selon les rôles
4. **Vérifiez la responsivité** sur mobile/desktop
5. **Testez les exports** et le dashboard analytique

L'application est maintenant **complètement fonctionnelle** avec des données de test réalistes ! 🚀

---

*Accès créés le 6 Juillet 2026*  
*GestApp University Management System*  
*Prêt pour tests complets*
