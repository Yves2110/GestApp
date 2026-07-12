# CAHIER DES CHARGES — GESTAPP UNIVERSITY 3.0
**Version :** 3.0 | **Date :** 12 juillet 2026 | **Auteur :** Yves2110  
**Statut :** Document de référence technique et fonctionnel

---

## TABLE DES MATIÈRES

01. Présentation du projet  
02. Vision fonctionnelle  
03. Analyse complète de l'existant  
04. Principes de conception  
05. Règles générales de développement  
06. Design System complet  
07. Charte graphique  
08. Grille de mise en page  
09. Responsive  
10. Typographie  
11. Palette de couleurs  
12. Ombres  
13. Espacements  
14. Icônes  
15. Boutons  
16. Champs  
17. Tableaux  
18. Cards  
19. Badges  
20. Modales  
21. Notifications  
22. Breadcrumb  
23. Sidebar  
24. Header  
25. Footer  
26. Dashboard  
27. Gestion des activités  
28. Gestion des objectifs  
29. Gestion documentaire  
30. Analytics  
31. Calendrier  
32. Kanban  
33. Timeline  
34. Recherche  
35. Utilisateurs  
36. Administration  
37. Monitoring  
38. API  
39. Sécurité  
40. Performance  
41. Accessibilité  
42. Animations  
43. Architecture Front  
44. Architecture Backend  
45. Base de données  
46. Roadmap  
47. Règles de développement  
48. Checklist qualité  

---

## 01. PRÉSENTATION DU PROJET

### 1.1 Contexte général

GestApp University est une application web de gestion des activités universitaires développée en Laravel 11. Elle permet à une université de planifier, suivre et analyser ses activités pédagogiques, administratives et opérationnelles par service. La version 2.0 constitue la base existante, fonctionnelle mais présentant des lacunes significatives en termes d'expérience utilisateur, de cohérence visuelle et de solidité technique.

### 1.2 Objectif de la version 3.0

La version 3.0 vise une refonte complète de l'interface utilisateur, une modernisation de l'architecture technique, et l'ajout de nouvelles fonctionnalités à forte valeur ajoutée (kanban, calendrier, timeline, recherche avancée). Il ne s'agit pas d'un projet from scratch : la logique métier existante est conservée et enrichie.

### 1.3 Périmètre du document

Ce cahier des charges couvre :
- L'analyse critique de l'existant (v2.0)
- Les spécifications fonctionnelles et UX/UI de la v3.0
- Le design system complet
- L'architecture technique cible
- La roadmap de mise en œuvre

### 1.4 Parties prenantes

| Rôle | Responsabilité |
|------|----------------|
| SuperAdmin | Configuration globale, sécurité, audit |
| Président | Vision stratégique, analytics, reporting |
| Admin | Gestion opérationnelle des activités et objectifs |
| Service | Saisie et suivi des activités de son périmètre |
| Développeur principal | Implémentation, maintenance, évolutions |

---

## 02. VISION FONCTIONNELLE

### 2.1 Proposition de valeur v3.0

GestApp University 3.0 doit devenir la **plateforme de pilotage opérationnel** de référence pour l'université, offrant :

- Une **expérience utilisateur fluide et intuitive** réduisant la courbe d'apprentissage
- Des **vues multiples** des activités (liste, kanban, calendrier, timeline) pour s'adapter aux préférences de chaque utilisateur
- Un **moteur analytique enrichi** permettant des décisions éclairées en temps réel
- Une **architecture solide** facilitant la maintenance et les évolutions futures

### 2.2 Nouvelles fonctionnalités cibles

| Fonctionnalité | Priorité | Description |
|----------------|----------|-------------|
| Vue Kanban | HAUTE | Visualisation des activités par statut par glisser-déposer |
| Vue Calendrier | HAUTE | Planning mensuel/hebdomadaire des activités |
| Vue Timeline | MOYENNE | Gantt simplifié pour la vision temporelle |
| Recherche globale | HAUTE | Recherche full-text unifiée sur toutes les entités |
| Notifications internes | HAUTE | Alertes temps réel et système de badges |
| Workflow de validation | HAUTE | Circuit soumission → validation → rejet |
| Export PDF | MOYENNE | Rapports imprimables en plus du CSV |
| Mode sombre | BASSE | Thème dark pour confort utilisateur |
| API REST complète | MOYENNE | Ouverture à des intégrations tierces |
| Tests automatisés | HAUTE | Couverture PHPUnit ≥ 80% |

### 2.3 Objectifs mesurables

- Réduction du nombre de clics pour créer une activité : de 8 à 4 clics maximum
- Temps de chargement des pages : < 1,5 secondes
- Score Lighthouse Performance : ≥ 85
- Score Lighthouse Accessibilité : ≥ 90
- Couverture de tests : ≥ 80%
- Taux d'adoption utilisateur : mesurer via analytics embarqué

---

## 03. ANALYSE COMPLÈTE DE L'EXISTANT

### 3.1 Forces

- **Base Laravel solide** : Laravel 11 avec PHP 8.3 offre des fondations modernes et bien documentées.
- **Modèle de données cohérent** : Les entités principales (activities, objectives, under_objectives, services, periodes) sont bien définies et leurs relations correctement établies via Eloquent.
- **Système de rôles fonctionnel** : Les 4 rôles (SuperAdmin, Président, Admin, Service) avec permissions granulaires constituent une base de sécurité satisfaisante.
- **Journal d'audit présent** : La table `audit_logs` permet la traçabilité des actions sensibles.
- **Seeders complets** : La capacité à recréer un environnement de test réaliste avec `php artisan migrate:fresh --seed` est un atout pour le développement.
- **Module analytics existant** : Les graphiques et statistiques de base sont déjà en place dans `AnalyticsController`.
- **Export CSV fonctionnel** : La capacité d'export existe et constitue une bonne base à enrichir.
- **Design system embryonnaire** : Le fichier `university-theme.css` montre une intention de cohérence visuelle.

### 3.2 Faiblesses

- **Incohérence entre les versions de Bootstrap** : Mélange de Bootstrap 5.2.2 et 5.3.3 dans le projet, source de conflits CSS imprévisibles.
- **Dépendance aux CDN et ressources locales mélangées** : Instabilité en environnement sans internet, corrections récentes insuffisantes.
- **Absence de tests automatisés** : Aucune couverture PHPUnit ou Dusk, rendant toute refactorisation risquée.
- **Validation côté serveur incomplète** : Les contrôleurs manquent de Form Requests dédiées pour une validation robuste.
- **Pas de workflow de validation des activités** : Une activité créée est directement visible sans circuit de validation.
- **Exports limités au CSV** : Absence de PDF, de fichiers Excel natifs (.xlsx), de rapports imprimables.
- **Internationalisation absente** : L'application est entièrement en dur en français, sans infrastructure i18n.
- **Pas de notifications internes** : Les utilisateurs ne sont pas alertés des changements les concernant.
- **Relations Eloquent fragiles** : Des bugs récents (e.g. `under_objectives` → `under_objective`) révèlent un manque de tests et de conventions strictes.
- **Monitoring rudimentaire** : L'endpoint `/api/health` existe mais le monitoring n'est pas intégré à des outils professionnels.

### 3.3 Incohérences UX

- **Flux de création d'activité trop long** : Trop de champs sur une seule page sans étapes logiques, surchargeant l'utilisateur.
- **Absence de retour d'état clair** : Après création ou modification, le message de confirmation (via sweet-alert) disparaît sans trace dans l'interface.
- **Navigation non contextuelle** : La sidebar ne met pas en évidence la section active de manière persistante.
- **Filtrage des activités basique** : Le filtrage par rôle est côté serveur uniquement, sans possibilité de filtres additionnels côté client.
- **Pas de confirmation pour les suppressions critiques** : Certaines suppressions (services, objectifs) manquent de modale de confirmation robuste.
- **Formulaires non sauvegardés** : Perte des données saisies en cas de navigation accidentelle hors du formulaire.
- **Breadcrumb absent ou incohérent** : L'utilisateur ne sait pas toujours où il se trouve dans l'arborescence.
- **Actions en masse absentes** : Impossible de sélectionner plusieurs activités pour les modifier/supprimer ensemble.
- **Pagination non uniforme** : Certaines listes paginent, d'autres non, sans cohérence.

### 3.4 Incohérences UI

- **Mélange de styles** : Coexistence de classes Bootstrap native, de classes personnalisées du `university-theme.css` et de styles inline, créant des incohérences visuelles.
- **Typographie non unifiée** : Pas de scale typographique définie, tailles de polices hétérogènes entre les vues.
- **Couleurs non tokenisées** : Les couleurs sont codées en dur dans les fichiers CSS sans variables CSS globales, rendant les changements de thème impossibles.
- **Icônes mélangées** : Bootstrap Icons et Font Awesome cohabitent sans règle d'usage, entraînant des styles d'icônes hétérogènes.
- **Cards non uniformes** : Les cards du dashboard et des listes n'ont pas les mêmes ombres, arrondis ou padding.
- **Boutons incohérents** : Variantes, tailles et états (hover, focus, disabled) non standardisés entre les modules.
- **Responsive partiel** : Certaines vues (notamment les tableaux) débordent sur mobile.
- **Densité d'information mal calibrée** : Certaines pages sont trop chargées, d'autres trop vides.

### 3.5 Dette technique

| Catégorie | Description | Impact |
|-----------|-------------|--------|
| Tests | Absence totale de tests automatisés | Critique |
| Bootstrap | Double version (5.2.2 / 5.3.3) | Élevé |
| Form Requests | Validation inline dans les contrôleurs | Élevé |
| Eloquent | Relations mal nommées, pas de conventions strictes | Élevé |
| CSS | Pas de variables CSS, styles inline, redondances | Moyen |
| i18n | Chaînes de texte en dur | Moyen |
| API | Routes API non documentées, pas de versioning | Moyen |
| Sécurité | Pas de politique CSRF explicite sur les routes API | Élevé |
| Performance | Pas de cache sur les requêtes analytiques lourdes | Moyen |
| CI/CD | Pas de pipeline d'intégration continue | Moyen |

### 3.6 Priorités de correction

**CRITIQUE (à traiter avant tout développement v3.0)**
1. Unifier Bootstrap sur une seule version (5.3.x)
2. Mettre en place les Form Requests pour tous les contrôleurs
3. Standardiser les noms de relations Eloquent
4. Ajouter la protection CSRF sur toutes les routes sensibles

**HAUTE (sprint 1)**
5. Implémenter les tests unitaires sur les modèles critiques
6. Créer les variables CSS globales (tokens de design)
7. Unifier les icônes sur Bootstrap Icons uniquement
8. Ajouter les confirmations de suppression systématiques

**MOYENNE (sprint 2 et suivants)**
9. Workflow de validation des activités
10. Système de notifications internes
11. Export PDF
12. Recherche globale

---

## 04. PRINCIPES DE CONCEPTION

### 4.1 Clarté avant tout

Chaque écran doit répondre à une question principale. L'utilisateur doit comprendre sans formation préalable ce qu'il peut faire sur la page courante.

### 4.2 Progressivité

Les formulaires complexes sont découpés en étapes logiques (stepper). L'information est révélée progressivement selon les besoins de l'utilisateur (progressive disclosure).

### 4.3 Cohérence systémique

Un composant doit se comporter de manière identique partout dans l'application. Les mêmes actions produisent les mêmes résultats visuels et fonctionnels.

### 4.4 Feedback immédiat

Toute action utilisateur déclenche un retour visuel dans les 100ms. Les opérations longues affichent un indicateur de progression. Les succès et erreurs sont communiqués de manière distincte et persistante.

### 4.5 Accessibilité native

L'accessibilité n'est pas une option : contraste WCAG AA minimum, navigation au clavier complète, attributs ARIA sur tous les composants interactifs.

### 4.6 Performance perçue

Le squelette de la page (skeleton screens) est affiché pendant le chargement des données. Les interactions critiques ne doivent jamais bloquer l'interface.

### 4.7 Mobile-first

La conception part de la version mobile et s'enrichit pour les écrans plus grands. Aucun élément ne doit être inaccessible sur un écran de 375px de large.

---

## 05. RÈGLES GÉNÉRALES DE DÉVELOPPEMENT

### 5.1 Conventions de nommage

- **PHP/Laravel** : PSR-12 strict. Classes en PascalCase, méthodes en camelCase, variables en snake_case.
- **Base de données** : tables en snake_case au pluriel, clés étrangères sous la forme `{table_singulier}_id`.
- **CSS** : méthodologie BEM pour les classes personnalisées (`block__element--modifier`).
- **JavaScript** : camelCase pour les variables et fonctions, PascalCase pour les classes.
- **Fichiers Blade** : kebab-case (`activity-form.blade.php`).

### 5.2 Structure des commits

Format Conventional Commits :
```
feat(activities): add kanban view with drag and drop
fix(auth): resolve throttle not resetting after successful login
refactor(objectives): rename under_objectives relation to underObjectives
docs(api): add OpenAPI spec for activities endpoint
test(activities): add unit tests for ActivityController store method
```

### 5.3 Branches Git

```
main          ← production stable
develop       ← intégration des features
feature/*     ← développement de fonctionnalités
fix/*         ← corrections de bugs
release/*     ← préparation des versions
hotfix/*      ← corrections urgentes sur main
```

### 5.4 Code review

- Toute PR doit être revue par au moins une autre personne avant merge
- Les tests doivent passer en CI avant tout merge
- La couverture de code ne doit pas descendre en dessous de 80%

### 5.5 Documentation

- Chaque contrôleur doit avoir un bloc PHPDoc décrivant sa responsabilité
- Les routes API doivent être documentées via OpenAPI/Swagger
- Les choix d'architecture non évidents doivent être expliqués en commentaire

---

## 06. DESIGN SYSTEM COMPLET

Le design system de GestApp University 3.0 est un ensemble de règles, composants et tokens qui garantissent la cohérence visuelle et fonctionnelle de l'application. Il se matérialise par :

- Un fichier de tokens CSS (`resources/css/tokens.css`)
- Un fichier de composants de base (`resources/css/components.css`)
- Un fichier de layouts (`resources/css/layouts.css`)
- Un fichier utilitaire (`resources/css/utilities.css`)
- Une bibliothèque de composants Blade (`resources/views/components/`)

### 6.1 Principes du design system

- **Single source of truth** : Toute valeur de design (couleur, espacement, ombre) est définie une seule fois comme token CSS.
- **Composants atomiques** : Les composants complexes sont construits à partir de composants simples.
- **Documentation vivante** : Une page `/styleguide` accessible en développement affiche tous les composants.
- **Versionné** : Le design system suit le versionning sémantique indépendamment de l'application.

---

## 07. CHARTE GRAPHIQUE

### 7.1 Identité visuelle

GestApp University 3.0 adopte une identité **institutionnelle moderne** : sobre, professionnelle, inspirant confiance. L'esthétique s'inspire des meilleures pratiques des plateformes SaaS de gestion (Linear, Notion, monday.com) tout en conservant une identité universitaire.

### 7.2 Logo et marque

- Le logo de l'université est affiché dans la sidebar en version complète (avec texte) et en icône seule (sidebar réduite).
- Espace de protection minimum autour du logo : 16px de tous côtés.
- Ne pas déformer, recolorer ou ombrer le logo.

### 7.3 Ton visuel général

- **Fond principal** : blanc cassé ou gris très clair (pas de blanc pur)
- **Accentuation** : bleu universitaire profond
- **Alertes et statuts** : palette sémantique (vert, orange, rouge, bleu)
- **Typographie** : fonctionnelle, lisible, hiérarchisée

---

## 08. GRILLE DE MISE EN PAGE

### 8.1 Structure globale

```
┌─────────────────────────────────────────────┐
│  HEADER (hauteur fixe : 64px)               │
├──────────┬──────────────────────────────────┤
│          │                                  │
│ SIDEBAR  │  ZONE DE CONTENU PRINCIPALE     │
│ (240px)  │  (flex: 1, overflow-y: auto)    │
│          │                                  │
│          │  ┌──────────────────────────┐   │
│          │  │  PAGE HEADER (breadcrumb │   │
│          │  │  + titre + actions)      │   │
│          │  └──────────────────────────┘   │
│          │                                  │
│          │  ┌──────────────────────────┐   │
│          │  │  CONTENU (padding 24px)  │   │
│          │  └──────────────────────────┘   │
│          │                                  │
└──────────┴──────────────────────────────────┘
```

### 8.2 Grille de contenu

- Grille 12 colonnes avec gouttières de 24px
- Largeur maximale du contenu : 1440px
- Padding latéral du contenu : 24px (desktop), 16px (tablet), 12px (mobile)

### 8.3 Zones prédéfinies

| Zone | Colonnes | Usage |
|------|----------|-------|
| Full width | 12 | Tableaux, listes complètes |
| 3/4 + 1/4 | 9+3 | Contenu principal + sidebar contextuelle |
| 2/3 + 1/3 | 8+4 | Formulaire + aide contextuelle |
| 1/2 + 1/2 | 6+6 | Comparatifs, split views |
| KPIs | 3+3+3+3 | Cartes de métriques |

---

## 09. RESPONSIVE

### 9.1 Breakpoints

| Nom | Largeur minimale | Cible |
|-----|-----------------|-------|
| xs | 0px | Téléphones portrait |
| sm | 576px | Téléphones paysage |
| md | 768px | Tablettes |
| lg | 992px | Desktop compact |
| xl | 1200px | Desktop standard |
| xxl | 1400px | Grand écran |

### 9.2 Comportements responsive

**Sidebar**
- ≥ lg : visible, 240px de large
- md : réductible en icônes (64px) avec tooltip
- < md : masquée, déclenchée par hamburger, overlay complet

**Header**
- ≥ md : affichage complet (logo, recherche, notifications, profil)
- < md : logo + hamburger + icône notification (recherche en overlay)

**Tableaux**
- ≥ lg : toutes les colonnes visibles
- md : colonnes secondaires masquées, scroll horizontal disponible
- < md : vue card empilée à la place du tableau

**Dashboard KPIs**
- ≥ lg : 4 cartes en ligne
- md : 2 × 2
- < md : 1 colonne

### 9.3 Règles générales

- Touch targets minimum : 44px × 44px sur mobile
- Pas de hover-only interactions (doivent avoir un équivalent tap)
- Police minimum sur mobile : 14px
- Images et médias : `max-width: 100%` systématique

---

## 10. TYPOGRAPHIE

### 10.1 Familles de polices

```css
:root {
  --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  --font-mono: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
}
```

**Inter** est chargée via Google Fonts avec les variantes : 300, 400, 500, 600, 700.

### 10.2 Scale typographique

| Token | Taille | Line-height | Poids | Usage |
|-------|--------|-------------|-------|-------|
| `--text-xs` | 11px | 1.5 | 400 | Labels discrets, légendes |
| `--text-sm` | 13px | 1.5 | 400 | Texte secondaire, métadonnées |
| `--text-base` | 14px | 1.6 | 400 | Corps de texte standard |
| `--text-md` | 16px | 1.5 | 400/500 | Texte mis en avant |
| `--text-lg` | 18px | 1.4 | 500/600 | Titres de section |
| `--text-xl` | 20px | 1.3 | 600 | Titres de page |
| `--text-2xl` | 24px | 1.25 | 600/700 | Titres principaux |
| `--text-3xl` | 30px | 1.2 | 700 | Grandes métriques dashboard |

### 10.3 Règles typographiques

- Longueur de ligne optimale : 60–80 caractères pour le texte courant
- Ne jamais utiliser `font-weight` inférieur à 400 pour le texte courant
- Les nombres dans les tableaux s'affichent en `font-variant-numeric: tabular-nums`
- Les codes et identifiants utilisent `--font-mono`

---

## 11. PALETTE DE COULEURS

### 11.1 Couleurs primaires (Bleu universitaire)

```css
:root {
  --color-primary-50:  #eff6ff;
  --color-primary-100: #dbeafe;
  --color-primary-200: #bfdbfe;
  --color-primary-300: #93c5fd;
  --color-primary-400: #60a5fa;
  --color-primary-500: #3b82f6;
  --color-primary-600: #2563eb;  /* Couleur principale */
  --color-primary-700: #1d4ed8;
  --color-primary-800: #1e40af;
  --color-primary-900: #1e3a8a;
  --color-primary-950: #172554;
}
```

### 11.2 Couleurs neutres (Gris)

```css
:root {
  --color-neutral-0:   #ffffff;
  --color-neutral-50:  #f8fafc;
  --color-neutral-100: #f1f5f9;
  --color-neutral-200: #e2e8f0;
  --color-neutral-300: #cbd5e1;
  --color-neutral-400: #94a3b8;
  --color-neutral-500: #64748b;
  --color-neutral-600: #475569;
  --color-neutral-700: #334155;
  --color-neutral-800: #1e293b;
  --color-neutral-900: #0f172a;
}
```

### 11.3 Couleurs sémantiques

```css
:root {
  /* Succès */
  --color-success-50:  #f0fdf4;
  --color-success-500: #22c55e;
  --color-success-600: #16a34a;
  --color-success-700: #15803d;

  /* Avertissement */
  --color-warning-50:  #fffbeb;
  --color-warning-500: #f59e0b;
  --color-warning-600: #d97706;
  --color-warning-700: #b45309;

  /* Danger/Erreur */
  --color-danger-50:  #fef2f2;
  --color-danger-500: #ef4444;
  --color-danger-600: #dc2626;
  --color-danger-700: #b91c1c;

  /* Info */
  --color-info-50:  #eff6ff;
  --color-info-500: #3b82f6;
  --color-info-600: #2563eb;
  --color-info-700: #1d4ed8;
}
```

### 11.4 Couleurs de statut des activités

```css
:root {
  --status-planned:    #8b5cf6;  /* Violet — planifié */
  --status-ongoing:    #f59e0b;  /* Orange — en cours */
  --status-done:       #22c55e;  /* Vert — réalisé */
  --status-cancelled:  #ef4444;  /* Rouge — annulé */
  --status-postponed:  #94a3b8;  /* Gris — reporté */
}
```

### 11.5 Tokens d'application

```css
:root {
  --bg-app:          var(--color-neutral-50);
  --bg-surface:      var(--color-neutral-0);
  --bg-elevated:     var(--color-neutral-0);
  --bg-sunken:       var(--color-neutral-100);
  
  --text-primary:    var(--color-neutral-900);
  --text-secondary:  var(--color-neutral-600);
  --text-muted:      var(--color-neutral-400);
  --text-inverse:    var(--color-neutral-0);
  
  --border-default:  var(--color-neutral-200);
  --border-strong:   var(--color-neutral-300);
  --border-focus:    var(--color-primary-600);
  
  --accent:          var(--color-primary-600);
  --accent-hover:    var(--color-primary-700);
  --accent-light:    var(--color-primary-50);
}
```

### 11.6 Accessibilité des couleurs

Tous les couples texte/fond doivent respecter WCAG AA (ratio ≥ 4.5:1 pour texte normal, ≥ 3:1 pour grand texte). Ratios validés :

- Texte primaire sur fond app : > 12:1 ✓
- Texte secondaire sur fond surface : > 4.6:1 ✓
- Texte blanc sur primary-600 : > 4.5:1 ✓
- Badge de statut : texte foncé sur fond clair toujours vérifié ✓

---

## 12. OMBRES

```css
:root {
  --shadow-xs:  0 1px 2px 0 rgba(0, 0, 0, 0.05);
  --shadow-sm:  0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
  --shadow-md:  0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
  --shadow-lg:  0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
  --shadow-xl:  0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
  --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  --shadow-inner: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);
  
  /* Ombre colorée pour éléments actifs */
  --shadow-primary: 0 4px 14px 0 rgba(37, 99, 235, 0.25);
}
```

Usage :
- `xs` : éléments très discrets (inputs)
- `sm` : cards de base
- `md` : cards hover, dropdowns
- `lg` : modales, sidepanels
- `xl` : popovers, tooltips riches
- `2xl` : modales centrées importantes

---

## 13. ESPACEMENTS

### 13.1 Échelle d'espacement (base 4px)

```css
:root {
  --space-0:   0px;
  --space-1:   4px;
  --space-2:   8px;
  --space-3:   12px;
  --space-4:   16px;
  --space-5:   20px;
  --space-6:   24px;
  --space-8:   32px;
  --space-10:  40px;
  --space-12:  48px;
  --space-16:  64px;
  --space-20:  80px;
  --space-24:  96px;
}
```

### 13.2 Règles d'espacement par composant

| Composant | Padding interne | Marge externe |
|-----------|----------------|---------------|
| Card | 24px | 0 (géré par la grille) |
| Bouton small | 6px 12px | — |
| Bouton medium | 8px 16px | — |
| Bouton large | 10px 20px | — |
| Input | 8px 12px | 0 0 16px 0 |
| Cellule de tableau | 12px 16px | — |
| Section de page | 24px (top) | 32px (bottom) |
| Sidebar item | 8px 12px | 2px (vertical) |

---

## 14. ICÔNES

### 14.1 Bibliothèque unique : Bootstrap Icons 1.11+

La version 3.0 utilise **exclusivement Bootstrap Icons**. Font Awesome est supprimé pour éliminer les conflits et alléger le bundle. Bootstrap Icons est chargé en local (fichier SVG sprite ou webfont selon les performances mesurées).

### 14.2 Tailles standard

| Contexte | Taille |
|----------|--------|
| Inline dans texte | 1em (suit la taille du texte) |
| Bouton icon-only | 20px |
| Sidebar navigation | 20px |
| Header actions | 20px |
| Card icône décorative | 32px |
| Empty state | 64px |
| Illustration | 96–128px |

### 14.3 Mapping des icônes par module

| Module | Icône principale |
|--------|-----------------|
| Dashboard | `bi-speedometer2` |
| Activités | `bi-list-task` |
| Objectifs | `bi-bullseye` |
| Services | `bi-building` |
| Analytics | `bi-bar-chart-line` |
| Documents | `bi-file-earmark-text` |
| Calendrier | `bi-calendar3` |
| Kanban | `bi-kanban` |
| Timeline | `bi-diagram-3` |
| Utilisateurs | `bi-people` |
| Paramètres | `bi-gear` |
| Audit | `bi-shield-check` |
| Notifications | `bi-bell` |
| Recherche | `bi-search` |
| Export | `bi-download` |

---

## 15. BOUTONS

### 15.1 Variantes

```html
<!-- Primaire : action principale, une seule par vue -->
<button class="btn btn-primary">Créer une activité</button>

<!-- Secondaire : actions secondaires -->
<button class="btn btn-secondary">Annuler</button>

<!-- Outline : actions moins prioritaires -->
<button class="btn btn-outline-primary">Exporter</button>

<!-- Ghost : actions très discrètes -->
<button class="btn btn-ghost">Voir les détails</button>

<!-- Danger : actions destructives -->
<button class="btn btn-danger">Supprimer</button>

<!-- Lien : navigation inline -->
<button class="btn btn-link">Voir tout</button>
```

### 15.2 Tailles

| Classe | Padding | Font-size | Usage |
|--------|---------|-----------|-------|
| `btn-sm` | 6px 12px | 13px | Actions dans les tableaux |
| `btn` (défaut) | 8px 16px | 14px | Usage standard |
| `btn-lg` | 10px 20px | 16px | CTA principal de page |

### 15.3 États

- **Default** : couleur définie par la variante
- **Hover** : assombrir de 10%, curseur pointer
- **Focus** : outline de 2px offset 2px avec `--border-focus`
- **Active/Pressed** : assombrir de 15%, légère translation vers le bas (1px)
- **Disabled** : opacité 50%, `cursor: not-allowed`, sans effet au survol
- **Loading** : spinner inline à gauche du label, pointer-events désactivés

### 15.4 Boutons avec icône

```html
<!-- Icône + texte -->
<button class="btn btn-primary">
  <i class="bi bi-plus-circle me-2"></i>Nouvelle activité
</button>

<!-- Icône seule (icon-only, toujours avec aria-label) -->
<button class="btn btn-ghost btn-icon" aria-label="Modifier">
  <i class="bi bi-pencil"></i>
</button>
```

### 15.5 Groupe de boutons

Les groupes de boutons (filter tabs, segmented controls) utilisent `btn-group` avec des `btn-outline` pour une apparence unifiée.

---

## 16. CHAMPS DE FORMULAIRE

### 16.1 Anatomie d'un champ

```
[Label obligatoire *]                    [Aide contextuelle ?]
[_____________________________________________]
[Message d'erreur ou de succès]
```

### 16.2 États des champs

- **Default** : bordure `--border-default`, fond blanc
- **Focus** : bordure `--border-focus` (2px), ombre inner légère
- **Filled** : comme default
- **Error** : bordure `--color-danger-600`, fond `--color-danger-50`, icône erreur
- **Success** : bordure `--color-success-600`, icône check
- **Disabled** : fond `--bg-sunken`, opacité 60%, `cursor: not-allowed`
- **Read-only** : fond `--bg-sunken`, sans bordure de focus

### 16.3 Types de champs

| Type | Composant | Usage |
|------|-----------|-------|
| Texte court | `<input type="text">` | Libellé, titre |
| Texte long | `<textarea>` | Description, commentaire |
| Nombre | `<input type="number">` | Budget, quantités |
| Date | `<input type="date">` + datepicker custom | Dates d'activité |
| Sélection unique | `<select>` stylé | Service, objectif, période |
| Sélection multiple | `<select multiple>` + multi-select custom | Permissions |
| Recherche | `<input>` + suggestions | Recherche d'entités liées |
| Fichier | Upload zone drag & drop | TDR, rapports, guides |
| Montant | Input avec préfixe/suffixe | Budget en FCFA |
| Switch | Toggle stylé | Activer/désactiver |

### 16.4 Formulaires multi-étapes (Stepper)

Pour la création d'activité, utiliser un stepper horizontal :

```
① Informations générales → ② Objectifs & Période → ③ Budget & Ressources → ④ Documents
```

- L'étape courante est en surbrillance
- Les étapes précédentes sont cochées (navigables)
- Les étapes suivantes sont désactivées visuellement
- Sauvegarde automatique du brouillon entre chaque étape

---

## 17. TABLEAUX

### 17.1 Anatomie d'un tableau

```
┌── [Titre du tableau] ─── [Recherche dans le tableau] ── [Filtres] ── [Export] ──┐
│ ☐  Colonne A ↕  Colonne B ↕  Colonne C ↕  Actions                               │
│ ── ─────────── ─────────── ─────────── ──────────────────────────────────────── │
│ ☐  Valeur A1   Valeur B1   Valeur C1   [Voir] [Modifier] [···]                 │
│ ☐  Valeur A2   Valeur B2   Valeur C2   [Voir] [Modifier] [···]                 │
└── [Pagination : Précédent  1 2 3 … 10  Suivant]  [Lignes par page : 25 ▼] ──────┘
```

### 17.2 Fonctionnalités des tableaux

- **Tri** : clic sur l'en-tête de colonne, indicateur de direction (↑↓)
- **Sélection multiple** : checkbox en première colonne, "Sélectionner tout"
- **Actions en masse** : barre d'actions flottante quand une sélection est active
- **Filtres** : panneau latéral ou dropdowns contextuels par colonne
- **Pagination** : 10/25/50/100 par page, navigation numérotée
- **Export** : bouton dans l'en-tête du tableau
- **Densité** : commutateur compact/standard/confortable
- **Colonnes** : option pour masquer/afficher des colonnes

### 17.3 Vue responsive du tableau

Sur mobile (< md), les tableaux passent automatiquement en vue "card list" où chaque ligne devient une carte avec les informations essentielles et un bouton "Voir tout".

### 17.4 État vide du tableau

Quand un tableau n'a aucun résultat, afficher une illustration, un message explicatif, et un CTA vers l'action principale (ex: "Créer votre première activité").

---

## 18. CARDS

### 18.1 Variantes de cards

**Card de base**
- Fond blanc, bordure `--border-default` ou ombre `--shadow-sm`
- Border-radius : 12px
- Padding : 24px

**Card cliquable**
- Comme la card de base + curseur pointer
- Hover : ombre `--shadow-md`, légère translation (−2px vertical)
- Transition : 200ms ease

**Card de métrique (KPI)**
- Icône colorée (48×48px)
- Valeur principale en `--text-3xl`
- Label en `--text-sm` couleur secondaire
- Tendance optionnelle (flèche + pourcentage)

**Card d'activité (Kanban)**
- Barre de couleur de statut à gauche (4px)
- Titre, service, date
- Badges de statut et période
- Assigné à + actions rapides

### 18.2 Card skeleton (loading)

Utiliser des squelettes animés (shimmer effect) pendant le chargement pour éviter les sauts de layout.

---

## 19. BADGES

### 19.1 Statuts d'activités

```html
<span class="badge badge-planned">Planifié</span>
<span class="badge badge-ongoing">En cours</span>
<span class="badge badge-done">Réalisé</span>
<span class="badge badge-cancelled">Annulé</span>
<span class="badge badge-postponed">Reporté</span>
```

### 19.2 Badges de rôle

```html
<span class="badge badge-role-superadmin">Super Admin</span>
<span class="badge badge-role-president">Président</span>
<span class="badge badge-role-admin">Admin</span>
<span class="badge badge-role-service">Service</span>
```

### 19.3 Badges de notification

Petits badges numériques rouges sur les icônes de navigation (max affichage : 99+).

### 19.4 Règles de badges

- Fond coloré clair (`-50` de la palette), texte coloré foncé (`-700`)
- Border-radius : 9999px (pilule) pour les statuts, 4px pour les catégories
- Taille de police : 11–12px, font-weight : 600
- Ne jamais utiliser de badge sans texte alternatif accessible

---

## 20. MODALES

### 20.1 Types de modales

**Modale de confirmation (destructive)**
- Titre court, icône warning/danger
- Description des conséquences
- Deux boutons : Annuler (secondaire) + Supprimer (danger)
- Fermeture par Escape et clic sur overlay

**Modale de formulaire court (≤ 5 champs)**
- Titre, formulaire, boutons Annuler/Valider
- Largeur : 480px maximum

**Modale de détail (lecture seule)**
- Affichage des informations d'une entité
- Boutons : Fermer + éventuellement Modifier

**Panneau latéral (Drawer)**
- Pour formulaires longs ou détails riches
- S'ouvre depuis la droite (480–600px de large)
- Ne bloque pas complètement le contexte sous-jacent

### 20.2 Règles des modales

- Toujours piéger le focus dans la modale ouverte (focus trap)
- L'ouverture et la fermeture sont animées (200ms)
- Le scroll de la page est bloqué pendant l'ouverture
- L'overlay est semi-transparent (`rgba(0,0,0,0.5)`)
- Toujours inclure un bouton de fermeture visible (×)
- Attributs ARIA : `role="dialog"`, `aria-modal="true"`, `aria-labelledby`

---

## 21. NOTIFICATIONS

### 21.1 Toast notifications (retours d'action)

Positionnées en bas à droite de l'écran, empilables, disparition automatique :

| Type | Durée | Icône |
|------|-------|-------|
| Succès | 4 secondes | `bi-check-circle-fill` (vert) |
| Erreur | 8 secondes ou manuelle | `bi-x-circle-fill` (rouge) |
| Avertissement | 6 secondes | `bi-exclamation-triangle-fill` (orange) |
| Info | 4 secondes | `bi-info-circle-fill` (bleu) |

### 21.2 Notifications internes (système)

Accessibles via la cloche dans le header. Types :

- Activité en attente de validation (pour les admins)
- Activité validée/rejetée (pour le service)
- Document uploadé dans son service
- Rappel d'échéance de période

Chaque notification a :
- Un titre court
- Une description optionnelle
- Un lien vers l'entité concernée
- Une date et un statut lu/non-lu

### 21.3 Bannières d'alerte (in-page)

Pour les messages importants contextuels (ex: formulaire incomplet, période fermée) :

```html
<div class="alert alert-warning" role="alert">
  <i class="bi bi-exclamation-triangle me-2"></i>
  La période T3 est en cours de clôture. Vous avez encore 5 jours pour soumettre vos activités.
</div>
```

---

## 22. BREADCRUMB

### 22.1 Implémentation

Le breadcrumb est affiché en haut de chaque page, sous le header, avant le titre de page.

```
Accueil > Activités > Modification > Atelier de formation en comptabilité
```

### 22.2 Règles

- Séparateur : `>` ou `/` (chevron en SVG)
- Le dernier élément est non cliquable et représente la page courante
- Maximum 4 niveaux (tronquer les niveaux intermédiaires si nécessaire avec `...`)
- Sur mobile, seuls les deux derniers niveaux sont affichés
- Attributs ARIA : `<nav aria-label="Fil d'Ariane">`, `aria-current="page"` sur le dernier item

---

## 23. SIDEBAR

### 23.1 Structure

```
┌──────────────────────────┐
│  [Logo université]        │
│  GestApp University       │
├──────────────────────────┤
│  [Avatar + Nom + Rôle]   │
├──────────────────────────┤
│  PRINCIPAL                │
│  ◎ Dashboard             │  ← actif
│  ○ Activités             │
│  ○ Objectifs             │
│  ○ Documents             │
│                          │
│  VUES                     │
│  ○ Calendrier            │
│  ○ Kanban                │
│  ○ Timeline              │
│                          │
│  ANALYSES                 │
│  ○ Analytics             │
│  ○ Exports               │
│                          │
│  ADMINISTRATION           │
│  ○ Utilisateurs          │  ← visible SuperAdmin/Admin
│  ○ Paramètres            │
│  ○ Audit                 │
│  ○ Monitoring            │
├──────────────────────────┤
│  [Réduire la sidebar ←]  │
└──────────────────────────┘
```

### 23.2 Comportements

- L'item actif est mis en surbrillance avec fond `--accent-light` et texte `--accent`
- Les groupes de navigation sont séparés par des labels de section discrets
- Les items cachés selon le rôle ne prennent pas de place (display: none)
- La sidebar peut être réduite (collapsed) avec persistance en localStorage
- En mode réduit, seules les icônes sont visibles avec des tooltips au survol
- Transition d'ouverture/fermeture : 250ms ease-in-out

---

## 24. HEADER

### 24.1 Contenu

```
[☰ Toggle sidebar]  [Logo compact]    [🔍 Recherche globale]    [🔔 2] [Notifications] [👤 Profil ▼]
```

### 24.2 Hauteur et style

- Hauteur fixe : 64px
- Fond blanc, bordure inférieure `--border-default`, ombre `--shadow-xs`
- Position sticky (reste en haut lors du scroll)
- z-index élevé (1000)

### 24.3 Barre de recherche globale

- Champ de recherche avec raccourci clavier (`Ctrl+K` / `Cmd+K`)
- Ouvre un modal de recherche globale (voir section 34)
- Placeholder : "Rechercher une activité, un objectif, un service…"

### 24.4 Menu profil (dropdown)

- Nom et email de l'utilisateur connecté
- Lien vers le profil
- Lien vers les préférences
- Séparateur
- Déconnexion (avec confirmation optionnelle)

---

## 25. FOOTER

Le footer applicatif est minimal dans une application de gestion :

- Position : en bas du contenu (pas de sticky)
- Contenu : version de l'application, année, liens légaux discrets
- Hauteur : 48px
- Typographie : `--text-xs`, couleur `--text-muted`

---

## 26. DASHBOARD

### 26.1 Structure de la page

**Zone 1 : KPIs principaux (4 cards)**
- Nombre d'activités totales (avec variation vs période précédente)
- Activités en cours
- Taux de réalisation (%)
- Budget total engagé

**Zone 2 : Actions rapides**
- Créer une activité
- Voir le kanban
- Accéder aux analytics
- Uploader un document

**Zone 3 : Vue globale par période**
- Progress bars par période (T1, T2, T3, T4)
- Pourcentage d'activités réalisées par période

**Zone 4 : Activités récentes**
- Tableau compact des 5 dernières activités modifiées
- Colonne : Service, Libellé, Statut, Date de mise à jour

**Zone 5 : Graphique de répartition**
- Donut chart : répartition des activités par statut
- Bar chart : top 5 services par nombre d'activités

**Zone 6 : Alertes et rappels**
- Activités en retard
- Périodes en voie de clôture
- Documents manquants

### 26.2 Dashboard par rôle

| Rôle | Zones visibles |
|------|----------------|
| SuperAdmin | Toutes + métriques système |
| Président | KPIs + analytics + objectifs stratégiques |
| Admin | Toutes les zones opérationnelles |
| Service | KPIs de son service + ses activités récentes |

---

## 27. GESTION DES ACTIVITÉS

### 27.1 Vue liste (tableau enrichi)

Colonnes : Service | Libellé | Objectif | Période | Statut | Budget | Actions

Filtres disponibles :
- Service (multi-select)
- Période (T1, T2, T3, T4)
- Statut (multi-select)
- Objectif
- Plage de budget
- Date de création

Tri disponible sur toutes les colonnes.

### 27.2 Formulaire de création (stepper 4 étapes)

**Étape 1 — Informations générales**
- Service concerné (obligatoire)
- Libellé de l'activité (obligatoire, max 255 chars)
- Description/Contexte (facultatif)
- Structure exécutrice

**Étape 2 — Cadre stratégique**
- Objectif parent (obligatoire)
- Sous-objectif (obligatoire, chargé dynamiquement selon l'objectif)
- Période (T1/T2/T3/T4) (obligatoire)
- Indicateur de performance
- Cible quantitative

**Étape 3 — Ressources**
- Budget prévisionnel (en FCFA)
- Source de financement
- Nombre de participants prévus
- Nombre de formateurs
- Nombre de jours
- Lieu de déroulement

**Étape 4 — Statut et finalisation**
- Statut initial (Planifié par défaut)
- Commentaire
- Soumission pour validation (si workflow activé)

### 27.3 Workflow de validation

```
[Création par Service]
         ↓
    [BROUILLON]
         ↓ (Soumettre)
   [EN ATTENTE DE VALIDATION]
         ↓
   ┌─────┴─────┐
[VALIDÉ]    [REJETÉ + motif]
     ↓
[PLANIFIÉ → EN COURS → RÉALISÉ]
```

### 27.4 Détail d'une activité

Page dédiée affichant :
- Toutes les informations générales
- Onglet TDR (téléchargement/upload)
- Onglet Rapports
- Onglet Variables
- Onglet Statut final
- Journal des modifications de l'activité
- Actions : Modifier | Dupliquer | Changer le statut | Exporter | Supprimer

---

## 28. GESTION DES OBJECTIFS

### 28.1 Vue hiérarchique

La page des objectifs affiche une vue arborescente :

```
▶ Objectif 1 : Améliorer la qualité pédagogique
  ├─ Sous-objectif 1.1 : Former les enseignants
  ├─ Sous-objectif 1.2 : Moderniser les curricula
  └─ Sous-objectif 1.3 : Évaluer les formations

▶ Objectif 2 : Optimiser la gestion administrative
  ├─ Sous-objectif 2.1 : Digitaliser les processus
  └─ Sous-objectif 2.2 : Former le personnel
```

### 28.2 Indicateurs par objectif

Pour chaque objectif, afficher :
- Nombre total d'activités associées
- Répartition par statut (mini donut ou progress)
- Budget total engagé
- Taux de réalisation global

### 28.3 Formulaires

- Objectif : Libellé (obligatoire), Description, Code optionnel
- Sous-objectif : Libellé (obligatoire), Objectif parent (obligatoire), Description

---

## 29. GESTION DOCUMENTAIRE

### 29.1 Types de documents gérés

| Type | Entité liée | Format accepté |
|------|-------------|----------------|
| Guide/Procédure | Global | PDF, DOCX |
| TDR (Termes de Référence) | Activité | PDF, DOCX |
| Rapport d'activité | Activité | PDF, DOCX, XLSX |
| Compte-rendu | Activité | PDF, DOCX |

### 29.2 Espace documentaire

- Liste des documents avec prévisualisation miniature (si PDF)
- Recherche par nom, type, service, période
- Téléchargement direct
- Remplacement d'une version (versioning simple)
- Statistiques : nombre de documents par service

### 29.3 Upload

- Drag & drop dans une zone dédiée
- Barre de progression pendant l'upload
- Validation : taille max 10 Mo, types autorisés vérifiés côté serveur
- Stockage dans `storage/app/documents/` (pas dans `public/`)
- Accès via route sécurisée avec vérification des permissions

### 29.4 Sécurité documentaire

- Aucun fichier n'est accessible directement via une URL publique
- Toute consultation passe par un contrôleur vérifiant les permissions
- Les noms de fichiers sont randomisés à l'upload (UUID)

---

## 30. ANALYTICS

### 30.1 Tableau de bord analytique

**Métriques globales**
- Total activités / par statut / par service
- Total budget / consommé / disponible
- Taux global de réalisation (%)
- Nombre d'objectifs couverts

**Graphiques interactifs**

| Graphique | Type | Dimensions |
|-----------|------|-----------|
| Activités par service | Bar chart horizontal | Service × Nombre |
| Répartition par statut | Donut chart | Statut × % |
| Budget par service | Bar chart | Service × Budget |
| Évolution mensuelle | Line chart | Mois × Activités |
| Réalisation vs Objectif | Gauge ou progress | % global |
| Heatmap d'activité | Heatmap calendrier | Jour × Intensité |

### 30.2 Filtres analytiques

- Plage de dates (année universitaire ou personnalisée)
- Service(s) sélectionné(s)
- Période(s) académique(s)
- Objectif(s)

### 30.3 Exports analytiques

- Export PDF du rapport complet (avec graphiques rendus)
- Export Excel avec données brutes + feuille de synthèse
- Export CSV (existant, à maintenir)

### 30.4 Performance

- Les données analytiques lourdes sont mises en cache (Laravel Cache, 15 minutes)
- Les graphiques sont rechargés à la demande (bouton refresh)
- Chargement asynchrone (skeleton pendant le calcul)

---

## 31. CALENDRIER

### 31.1 Vues disponibles

- Vue mensuelle (par défaut)
- Vue hebdomadaire
- Vue journalière
- Vue agenda (liste chronologique)

### 31.2 Affichage des événements

Chaque activité est représentée comme un événement avec :
- Couleur selon le statut
- Icône du service
- Libellé tronqué (max 30 chars)
- Clic → panneau de détail latéral

### 31.3 Interactions

- Navigation mois précédent/suivant/aujourd'hui
- Filtres : par service, par statut
- Drag & drop pour modifier les dates (avec confirmation)
- Création rapide d'activité depuis un créneau vide

### 31.4 Implémentation technique

Utiliser **FullCalendar.js** (version 6) alimenté par une API JSON interne.

---

## 32. KANBAN

### 32.1 Colonnes par défaut

```
│ BROUILLON │ EN ATTENTE │ PLANIFIÉ │ EN COURS │ RÉALISÉ │ ANNULÉ │
```

Les colonnes correspondent aux statuts des activités.

### 32.2 Carte kanban

Chaque carte affiche :
- Barre de couleur de service (gauche)
- Libellé de l'activité
- Service + Période (badges)
- Budget (discret)
- Indicateur de progression (si variables renseignées)
- Actions rapides (survol) : voir, modifier

### 32.3 Fonctionnalités

- Glisser-déposer entre colonnes (mise à jour du statut via API)
- Filtres par service, période, objectif
- Tri des cards dans une colonne (par date, budget, service)
- Compteur de cards par colonne
- Limite visuelle par colonne (configurable)

### 32.4 Implémentation technique

Utiliser **SortableJS** (léger, sans dépendances) pour le glisser-déposer avec sauvegarde AJAX.

---

## 33. TIMELINE

### 33.1 Description

La vue Timeline est un diagramme de Gantt simplifié, montrant les activités sur un axe temporel. Elle est accessible depuis le menu Vues.

### 33.2 Affichage

- Axe horizontal : temps (semaines ou mois selon le zoom)
- Axe vertical : services ou objectifs (groupés)
- Chaque activité = barre horizontale colorée selon le statut
- Zoom : semaine / mois / trimestre / année

### 33.3 Interactions

- Clic sur une barre → détail de l'activité
- Filtres par service, période, statut
- Export de la vue en PNG ou PDF

### 33.4 Implémentation technique

Utiliser **DHTMLX Gantt** (licence open source) ou une solution basée sur D3.js selon les contraintes de licence.

---

## 34. RECHERCHE GLOBALE

### 34.1 Déclenchement

- Raccourci clavier : `Ctrl+K` (Windows/Linux) / `Cmd+K` (macOS)
- Clic sur la barre de recherche dans le header
- Ouvre une modale centrée (largeur 640px)

### 34.2 Comportement

- La saisie démarre la recherche après 200ms de pause (debounce)
- Résultats groupés par type : Activités, Objectifs, Services, Documents
- Navigation aux flèches clavier dans les résultats
- Entrée pour ouvrir le premier résultat
- Escape pour fermer

### 34.3 Indexation

Les entités indexées :
- Activities (libellé, description, commentaire)
- Objectives + UnderObjectives (libellé, description)
- Services (nom)
- Guides (nom du fichier, description)

Implémentation back-end : recherche `LIKE` dans un premier temps, puis migration vers **Laravel Scout + Meilisearch** en v3.1 pour les performances.

### 34.4 Résultats

Chaque résultat affiche : icône du type, libellé, contexte (service, période), lien direct.

---

## 35. GESTION DES UTILISATEURS

### 35.1 Liste des utilisateurs

Colonnes : Avatar | Nom/Email | Rôle | Service | Statut | Dernière connexion | Actions

### 35.2 Fiche utilisateur

- Informations personnelles
- Rôle et service assignés
- Historique des connexions (5 dernières)
- Actions récentes dans le journal d'audit
- Activités créées
- Boutons : Modifier | Désactiver | Réinitialiser le mot de passe | Supprimer

### 35.3 Création d'un compte

Réservée au SuperAdmin. Champs :
- Prénom, Nom
- Email (unique, validé)
- Rôle (select)
- Service (select, peut être vide pour certains rôles)
- Mot de passe temporaire (généré automatiquement)
- Option "Forcer la réinitialisation au prochain login" (cochée par défaut)

### 35.4 Profil utilisateur

Page accessible par tous les utilisateurs pour modifier :
- Leur prénom et nom
- Leur photo de profil (optionnel)
- Leur mot de passe (avec confirmation de l'ancien)
- Leurs préférences d'affichage (densité des tableaux, sidebar réduite)

---

## 36. ADMINISTRATION

### 36.1 Paramètres généraux

| Paramètre | Type | Description |
|-----------|------|-------------|
| Nom de l'université | Texte | Affiché dans le header et les exports |
| Logo | Image | Upload, format PNG/SVG, max 500 Ko |
| Année universitaire courante | Texte | Ex: 2026-2027 |
| Email de contact | Email | Pour les notifications système |
| Fuseau horaire | Select | Pour l'horodatage correct |

### 36.2 Gestion des permissions

Interface de gestion des permissions par rôle :
- Matrice rôle × permission
- Cases à cocher
- Sauvegarde transactionnelle

### 36.3 Journal d'audit

Filtres : date, utilisateur, action, entité concernée

Colonnes : Date/Heure | Utilisateur | Action | Entité | Détails | IP

Actions tracées :
- Connexion / Déconnexion
- Création / Modification / Suppression d'entités
- Changement de rôle
- Réinitialisation de mot de passe
- Upload et suppression de documents
- Validation/Rejet d'activités

Export du journal en CSV.

---

## 37. MONITORING

### 37.1 Métriques surveillées

**Application**
- Santé globale (endpoint `/api/health`)
- Version de l'application
- Uptime
- Nombre de requêtes/heure (si disponible)

**Base de données**
- Connexion active
- Temps de réponse moyen des requêtes
- Nombre de requêtes lentes (> 1s)

**Système**
- Utilisation CPU (si accès serveur)
- Utilisation mémoire
- Espace disque

**Applicatif**
- Erreurs récentes (5xx)
- Jobs en échec (si file d'attente activée)
- Taille du répertoire de logs

### 37.2 Interface de monitoring

- Tableau de bord avec indicateurs RAG (Rouge / Amber / Vert)
- Graphiques d'évolution sur 24h / 7 jours
- Alertes configurables (seuil de déclenchement)

### 37.3 Intégration

En v3.0 : monitoring interne basique.
En v3.1 : intégration optionnelle avec **Laravel Telescope** ou **Sentry**.

---

## 38. API

### 38.1 Principes

- Architecture REST
- Versioning via le préfixe URL : `/api/v1/`
- Authentification par token Bearer (Laravel Sanctum)
- Format de réponse : JSON exclusivement
- Codes HTTP standards (200, 201, 204, 400, 401, 403, 404, 422, 500)

### 38.2 Endpoints principaux

| Méthode | Route | Description |
|---------|-------|-------------|
| GET | `/api/v1/activities` | Liste paginée des activités |
| POST | `/api/v1/activities` | Créer une activité |
| GET | `/api/v1/activities/{id}` | Détail d'une activité |
| PUT | `/api/v1/activities/{id}` | Modifier une activité |
| DELETE | `/api/v1/activities/{id}` | Supprimer une activité |
| GET | `/api/v1/objectives` | Liste des objectifs |
| GET | `/api/v1/analytics/summary` | Résumé analytique |
| GET | `/api/v1/analytics/chart-data` | Données pour graphiques |
| GET | `/api/v1/health` | Santé de l'API |

### 38.3 Format de réponse standard

```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "current_page": 1,
    "per_page": 25,
    "total": 150
  },
  "message": "Activité créée avec succès"
}
```

### 38.4 Gestion des erreurs

```json
{
  "success": false,
  "message": "Validation échouée",
  "errors": {
    "label": ["Le libellé est obligatoire"],
    "service_id": ["Le service sélectionné est invalide"]
  }
}
```

### 38.5 Documentation

L'API est documentée via **OpenAPI 3.0** (fichier `openapi.yaml` à la racine). Accessible via Swagger UI à `/api/docs` en mode développement.

### 38.6 Rate limiting

- Authentifiés : 1000 requêtes / heure
- Non authentifiés : 60 requêtes / heure
- Endpoints analytics : 100 requêtes / heure (cache recommandé)

---

## 39. SÉCURITÉ

### 39.1 Authentification

- Sessions Laravel sécurisées (cookie HttpOnly, SameSite=Strict)
- Throttle sur la connexion : 5 tentatives / minute, blocage 5 minutes
- Option 2FA (TOTP via Google Authenticator) — en v3.1
- Expiration de session configurable (par défaut : 120 minutes d'inactivité)
- Déconnexion forcée à distance (SuperAdmin)

### 39.2 Autorisation

- Middleware de rôles sur toutes les routes protégées
- Gates et Policies Laravel pour les actions sur les ressources
- Principe du moindre privilège : chaque rôle n'accède qu'à ce dont il a besoin
- Vérification de propriété : un utilisateur Service ne peut modifier que les activités de son service

### 39.3 Protection des données

- CSRF token sur tous les formulaires HTML
- Validation et sanitisation de toutes les entrées utilisateur
- Pas d'affichage brut de données non sanitisées (`{!! !!}` interdit sauf exception documentée)
- Mots de passe hashés en bcrypt (coût ≥ 12)
- Pas de données sensibles dans les logs

### 39.4 Sécurité des fichiers

- Upload de fichiers : validation du type MIME réel (pas uniquement l'extension)
- Stockage dans `storage/app/` (non public)
- Noms de fichiers UUID pour éviter la devinabilité
- Antivirus scan optionnel (webhook ClamAV en v3.1)

### 39.5 En-têtes de sécurité HTTP

Configurer dans le middleware :
```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: [politique restrictive adaptée]
Referrer-Policy: strict-origin-when-cross-origin
```

### 39.6 Dépendances

- Audit régulier avec `composer audit` et `npm audit`
- Mise à jour des dépendances dans les 30 jours après une alerte de sécurité
- Pas de dépendances dont la maintenance est abandonnée

---

## 40. PERFORMANCE

### 40.1 Cibles

| Métrique | Cible |
|----------|-------|
| Time to First Byte (TTFB) | < 200ms |
| First Contentful Paint (FCP) | < 1s |
| Largest Contentful Paint (LCP) | < 2.5s |
| Cumulative Layout Shift (CLS) | < 0.1 |
| Score Lighthouse Performance | ≥ 85 |

### 40.2 Optimisations Laravel

- Eager loading systématique (pas de N+1)
- Cache des requêtes analytiques lourdes (15 minutes)
- Pagination sur toutes les listes (pas de `->get()` sans limite)
- Index de base de données sur les colonnes fréquemment filtrées
- `php artisan optimize` en production
- OPcache activé sur le serveur PHP

### 40.3 Optimisations front-end

- Vite pour le build (minification, tree-shaking, code splitting)
- Images optimisées (WebP, lazy loading)
- Fonts en local avec `font-display: swap`
- CSS critique inline pour le premier rendu
- Pas de JavaScript bloquant le rendu

### 40.4 Cache

```php
// Configuration recommandée
CACHE_DRIVER=redis      // Redis si disponible, sinon file
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### 40.5 Queue

Les tâches lentes (envoi d'emails, génération de PDF, exports lourds) sont déléguées à une file d'attente Laravel.

---

## 41. ACCESSIBILITÉ

### 41.1 Niveau cible

WCAG 2.1 niveau AA.

### 41.2 Exigences techniques

**Navigation clavier**
- Tous les éléments interactifs sont accessibles au clavier (Tab, Shift+Tab)
- L'ordre de tabulation suit le flux logique de la page
- Le focus visible est toujours apparent (outline de 2px)
- Les raccourcis clavier sont documentés

**Lecteurs d'écran**
- Tous les éléments interactifs ont un label accessible (`aria-label` ou `aria-labelledby`)
- Les images informatives ont un attribut `alt` descriptif
- Les images décoratives ont `alt=""`
- Les statuts de formulaire sont annoncés via `aria-live`

**Couleurs et contrastes**
- Ratio minimum 4.5:1 pour le texte normal
- Ratio minimum 3:1 pour le grand texte et les éléments graphiques
- L'information n'est jamais véhiculée uniquement par la couleur

**Formulaires**
- Chaque champ a un `<label>` explicitement associé (`for`/`id`)
- Les erreurs de validation sont associées au champ via `aria-describedby`
- Les champs obligatoires sont indiqués visuellement ET via `aria-required`

### 41.3 Tests d'accessibilité

- Audit automatisé : intégrer **axe-core** dans le pipeline CI
- Audit manuel : checklist sur les 5 pages les plus utilisées avant chaque release
- Test avec lecteur d'écran (NVDA / VoiceOver) : authentification + création d'activité

---

## 42. ANIMATIONS

### 42.1 Principes

- Les animations doivent avoir un **but fonctionnel** : orienter l'attention, indiquer un changement d'état, donner un retour d'action.
- **Durée** : courte (150–350ms). Jamais plus de 500ms pour une action réactive.
- **Easing** : `ease-out` pour les apparitions, `ease-in` pour les disparitions, `ease-in-out` pour les transitions de position.
- **Préférence utilisateur** : respecter `prefers-reduced-motion: reduce` en supprimant toutes les animations non essentielles.

### 42.2 Catalogue d'animations

| Élément | Animation | Durée |
|---------|-----------|-------|
| Toast notification | Slide depuis le bas + fade in | 200ms |
| Modale | Scale 95%→100% + fade in | 200ms |
| Sidebar collapse | Width transition | 250ms |
| Dropdown | Fade in + translate Y -4px | 150ms |
| Card hover | Box-shadow + translate Y | 200ms |
| Bouton loading | Spinner rotate | Infini |
| Skeleton loading | Shimmer (gradient animé) | 1.5s infini |
| Page transition | Fade in | 150ms |

### 42.3 Implémentation CSS

```css
/* Respect de la préférence utilisateur */
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}
```

---

## 43. ARCHITECTURE FRONT-END

### 43.1 Technologie

- **Moteur de templates** : Blade (Laravel) — conservé
- **CSS** : Vanilla CSS avec variables CSS + Bootstrap 5.3 (une seule version)
- **JavaScript** : Vanilla JS ES2022+ (pas de framework JS lourd)
- **Build tool** : Vite 5
- **Icônes** : Bootstrap Icons (local)
- **Graphiques** : Chart.js 4
- **Calendrier** : FullCalendar.js 6
- **Kanban** : SortableJS
- **Éditeur riche (si nécessaire)** : Quill.js

### 43.2 Organisation des fichiers front-end

```
resources/
├── css/
│   ├── tokens.css          ← Variables CSS (design tokens)
│   ├── base.css            ← Reset, éléments HTML de base
│   ├── components/
│   │   ├── buttons.css
│   │   ├── forms.css
│   │   ├── tables.css
│   │   ├── cards.css
│   │   ├── modals.css
│   │   ├── badges.css
│   │   ├── sidebar.css
│   │   ├── header.css
│   │   └── notifications.css
│   ├── layouts/
│   │   ├── app.css         ← Layout principal
│   │   ├── dashboard.css
│   │   └── auth.css
│   └── utilities.css       ← Classes utilitaires custom
├── js/
│   ├── app.js              ← Point d'entrée principal
│   ├── modules/
│   │   ├── sidebar.js
│   │   ├── search.js
│   │   ├── notifications.js
│   │   ├── kanban.js
│   │   ├── calendar.js
│   │   ├── charts.js
│   │   └── file-upload.js
│   └── utils/
│       ├── api.js          ← Wrapper fetch pour les appels AJAX
│       ├── toast.js        ← Système de notifications toast
│       └── helpers.js
└── views/
    ├── components/
    │   ├── button.blade.php
    │   ├── card.blade.php
    │   ├── badge.blade.php
    │   ├── modal.blade.php
    │   ├── alert.blade.php
    │   └── ...
    └── layouts/
        ├── app.blade.php   ← Layout principal
        └── auth.blade.php  ← Layout d'authentification
```

### 43.3 Composants Blade

Chaque composant Blade doit :
- Accepter des props clairement définies
- Avoir des valeurs par défaut sensées
- Être documenté avec un bloc de commentaire
- Être démontré dans la page `/styleguide`

---

## 44. ARCHITECTURE BACK-END

### 44.1 Structure Laravel

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── AuthController.php
│   │   ├── Api/
│   │   │   ├── V1/
│   │   │   │   ├── ActivityController.php
│   │   │   │   ├── ObjectiveController.php
│   │   │   │   └── AnalyticsController.php
│   │   ├── ActivitiesController.php
│   │   ├── ObjectiveController.php
│   │   ├── AnalyticsController.php
│   │   ├── ExportController.php
│   │   ├── GuideController.php
│   │   ├── MonitoringController.php
│   │   └── ...
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   ├── CheckPermission.php
│   │   └── AuditLog.php
│   └── Requests/
│       ├── StoreActivityRequest.php
│       ├── UpdateActivityRequest.php
│       ├── StoreObjectiveRequest.php
│       └── ...
├── Models/
│   ├── User.php
│   ├── Activity.php
│   ├── Objective.php
│   ├── UnderObjective.php
│   ├── Service.php
│   ├── Periode.php
│   ├── Tdr.php
│   ├── Rapport.php
│   ├── ActivityVariable.php
│   ├── FinalStatus.php
│   ├── Guide.php
│   ├── AuditLog.php
│   └── Notification.php
├── Services/
│   ├── ActivityService.php   ← Logique métier des activités
│   ├── AnalyticsService.php  ← Calculs analytiques
│   ├── ExportService.php     ← Génération d'exports
│   ├── NotificationService.php
│   └── AuditService.php
├── Policies/
│   ├── ActivityPolicy.php
│   ├── ObjectivePolicy.php
│   └── GuidePolicy.php
└── Observers/
    └── ActivityObserver.php  ← Journal automatique des modifications
```

### 44.2 Couche Service

Extraire la logique métier complexe des contrôleurs vers des classes Service :

```php
// Bon : contrôleur léger
class ActivitiesController extends Controller
{
    public function store(StoreActivityRequest $request, ActivityService $service)
    {
        $activity = $service->create($request->validated(), auth()->user());
        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activité créée avec succès');
    }
}
```

### 44.3 Observers Eloquent

Utiliser les Observers pour l'audit automatique :

```php
class ActivityObserver
{
    public function updated(Activity $activity): void
    {
        AuditService::log('activity.updated', $activity, $activity->getDirty());
    }
}
```

---

## 45. BASE DE DONNÉES

### 45.1 Conventions

- Tables au pluriel, snake_case : `activities`, `under_objectives`, `audit_logs`
- Clés primaires : `id` (BIGINT UNSIGNED AUTO_INCREMENT)
- Clés étrangères : `{table_singulier}_id` (ex: `service_id`, `objective_id`)
- Dates : `created_at`, `updated_at` (via timestamps()), `deleted_at` pour le soft delete
- Soft delete sur : `activities`, `objectives`, `under_objectives`, `users`, `guides`

### 45.2 Index recommandés

```sql
-- Activities
INDEX idx_activities_service_id (service_id)
INDEX idx_activities_objective_id (objective_id)
INDEX idx_activities_periode_id (periode_id)
INDEX idx_activities_status (status)
INDEX idx_activities_created_at (created_at)

-- Audit logs
INDEX idx_audit_logs_user_id (user_id)
INDEX idx_audit_logs_created_at (created_at)
INDEX idx_audit_logs_auditable (auditable_type, auditable_id)
```

### 45.3 Nouvelles tables v3.0

**notifications**
```sql
CREATE TABLE notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  type VARCHAR(100) NOT NULL,
  title VARCHAR(255) NOT NULL,
  body TEXT NULL,
  data JSON NULL,
  url VARCHAR(500) NULL,
  read_at TIMESTAMP NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_notifications_user_read (user_id, read_at)
);
```

**activity_status_history**
```sql
CREATE TABLE activity_status_history (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  activity_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  from_status VARCHAR(50) NULL,
  to_status VARCHAR(50) NOT NULL,
  comment TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

**user_preferences**
```sql
CREATE TABLE user_preferences (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  sidebar_collapsed TINYINT(1) DEFAULT 0,
  table_density ENUM('compact', 'standard', 'comfortable') DEFAULT 'standard',
  default_view ENUM('list', 'kanban', 'calendar', 'timeline') DEFAULT 'list',
  notifications_email TINYINT(1) DEFAULT 1,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### 45.4 Migrations

Créer des migrations distinctes pour :
1. Chaque nouvelle table
2. Chaque modification de colonne existante (avec rollback)
3. Les index et les contraintes

Ne jamais modifier une migration déjà jouée en production. Créer une nouvelle migration à la place.

---

## 46. ROADMAP

### Phase 0 — Stabilisation (Semaine 1-2)
**Objectif : éliminer la dette technique bloquante**

- [ ] Unifier Bootstrap sur 5.3.x
- [ ] Supprimer Font Awesome, unifier sur Bootstrap Icons
- [ ] Créer les Form Requests pour tous les contrôleurs existants
- [ ] Standardiser les noms de relations Eloquent
- [ ] Ajouter les en-têtes de sécurité HTTP
- [ ] Configurer le stockage des documents hors public/
- [ ] Setup pipeline CI minimal (GitHub Actions : lint + tests)

### Phase 1 — Design System & Layout (Semaine 3-5)
**Objectif : nouveau look cohérent**

- [ ] Créer `tokens.css` avec toutes les variables CSS
- [ ] Refondre le layout (sidebar, header, structure de page)
- [ ] Créer les composants Blade (boutons, cards, badges, tableaux, modales)
- [ ] Page `/styleguide` pour documenter les composants
- [ ] Refonte du dashboard

### Phase 2 — Fonctionnalités cœur v3.0 (Semaine 6-10)
**Objectif : valeur ajoutée principale**

- [ ] Stepper de création d'activité
- [ ] Workflow de validation (statuts + transitions)
- [ ] Vue Kanban (SortableJS + API)
- [ ] Vue Calendrier (FullCalendar.js)
- [ ] Recherche globale (`Ctrl+K`)
- [ ] Notifications internes

### Phase 3 — Analytics & Export enrichis (Semaine 11-13)
**Objectif : pilotage amélioré**

- [ ] Dashboard analytique v2 (graphiques enrichis)
- [ ] Export PDF (rapports imprimables)
- [ ] Vue Timeline (Gantt simplifié)
- [ ] Filtres avancés sur toutes les listes

### Phase 4 — Qualité & Performance (Semaine 14-16)
**Objectif : solidité et adoption**

- [ ] Couverture de tests ≥ 80% (PHPUnit)
- [ ] Audit accessibilité WCAG AA
- [ ] Optimisations performance (cache, index, Lighthouse ≥ 85)
- [ ] Documentation API (OpenAPI)
- [ ] Formation utilisateurs + guide administrateur

### Phase 5 — Évolutions v3.1 (À planifier)
- 2FA (TOTP)
- Laravel Scout + Meilisearch
- Mode sombre
- Intégration monitoring externe (Sentry)
- Internationalisation (i18n)
- Application mobile (PWA)

---

## 47. RÈGLES DE DÉVELOPPEMENT

### 47.1 Avant chaque développement

1. Lire le cahier des charges de la fonctionnalité concernée
2. Vérifier qu'une issue / ticket est créé et assigné
3. Créer une branche `feature/{nom-fonctionnalité}` depuis `develop`
4. Vérifier que les tests existants passent avant de commencer

### 47.2 Pendant le développement

- Commiter fréquemment avec des messages clairs (Conventional Commits)
- Ne pas commiter de fichiers `.env`, `storage/`, `vendor/`, `node_modules/`
- Écrire les tests en même temps que le code (pas après)
- Tester manuellement sur mobile et desktop avant toute PR

### 47.3 Avant chaque PR

- [ ] Les tests passent (`php artisan test`)
- [ ] Le code est formatté (`./vendor/bin/pint`)
- [ ] Pas de `dd()`, `dump()`, `console.log()` oubliés
- [ ] La migration a un `down()` fonctionnel
- [ ] Les nouvelles routes sont protégées par les middlewares appropriés
- [ ] La PR décrit clairement ce qui a été fait et pourquoi

### 47.4 Déploiement

```bash
# Checklist de déploiement
php artisan down                      # Mode maintenance
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan up                        # Fin maintenance
```

### 47.5 Variables d'environnement obligatoires

Toujours documenter dans `.env.example` toute nouvelle variable d'environnement.

---

## 48. CHECKLIST QUALITÉ

### 48.1 Checklist fonctionnelle (par fonctionnalité)

- [ ] La fonctionnalité répond au besoin décrit dans le cahier des charges
- [ ] Les permissions par rôle sont correctement appliquées
- [ ] Les validations côté serveur sont exhaustives
- [ ] Les messages d'erreur sont clairs et actionnables
- [ ] Les confirmations pour les actions destructives sont en place
- [ ] Le journal d'audit trace les actions pertinentes
- [ ] La fonctionnalité est testée manuellement sur les 4 rôles

### 48.2 Checklist UX/UI

- [ ] Le composant est conforme au design system (tokens, icônes, typographie)
- [ ] Les états (hover, focus, disabled, error, empty) sont tous gérés
- [ ] Le breadcrumb est correct
- [ ] Les toasts de confirmation/erreur s'affichent correctement
- [ ] La vue est correcte sur desktop (1280px+), tablet (768px), mobile (375px)
- [ ] Le focus visible est apparent en navigation clavier

### 48.3 Checklist technique

- [ ] Pas de requête N+1 (vérifier avec Laravel Debugbar en développement)
- [ ] Pas de données sensibles dans les logs
- [ ] Les nouveaux modèles ont `$fillable` ou `$guarded` définis
- [ ] Les relations Eloquent sont nommées selon les conventions
- [ ] Les migrations ont un `up()` et un `down()` testés
- [ ] Les nouveaux endpoints API sont documentés

### 48.4 Checklist sécurité

- [ ] CSRF protégé sur toutes les routes POST/PUT/DELETE web
- [ ] Token Bearer requis sur toutes les routes API protégées
- [ ] Les uploads de fichiers valident le MIME type réel
- [ ] Pas d'injection SQL possible (toujours utiliser l'ORM ou les requêtes préparées)
- [ ] Pas de XSS possible (`{{ }}` Blade partout, `{!! !!}` justifié)
- [ ] Les rôles et permissions sont vérifiés dans les Policies, pas seulement dans les middlewares

### 48.5 Checklist accessibilité

- [ ] Ratio de contraste WCAG AA respecté
- [ ] Navigation au clavier complète et logique
- [ ] Labels associés à tous les champs de formulaire
- [ ] Messages d'erreur annoncés aux lecteurs d'écran
- [ ] Pas d'information véhiculée uniquement par la couleur

### 48.6 Checklist performance

- [ ] Eager loading sur toutes les relations utilisées
- [ ] Pagination sur toutes les listes
- [ ] Index de base de données sur les colonnes filtrées
- [ ] Les assets sont minifiés en production
- [ ] Score Lighthouse ≥ 85 sur la page concernée

---

## ANNEXES

### A. Glossaire

| Terme | Définition |
|-------|-----------|
| Activité | Unité de base de gestion : action planifiée par un service, liée à un objectif |
| TDR | Termes de Référence : document décrivant le cadre d'une activité |
| Service | Unité organisationnelle de l'université (DSI, DAF, DEPS, etc.) |
| Période | Division temporelle de l'année universitaire (T1 à T4) |
| Objectif | Orientation stratégique à laquelle se rattachent les activités |
| Sous-objectif | Déclinaison opérationnelle d'un objectif |
| Statut | État courant d'une activité dans son cycle de vie |
| FCFA | Franc CFA, devise utilisée pour les budgets |

### B. Contacts

- **Développeur principal :** Yves2110
- **Application :** GestApp University
- **Version du document :** 3.0
- **Date de rédaction :** 12 juillet 2026
- **Licence :** MIT

### C. Historique des versions du document

| Version | Date | Auteur | Changements |
|---------|------|--------|------------|
| 1.0 | — | Yves2110 | Création initiale (debrief v2.0) |
| 3.0 | 12/07/2026 | Yves2110 | Cahier des charges complet pour la refonte |

---

*Document de référence — GestApp University 3.0 — Toute modification doit être versionnée.*
