<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="GestApp - Système de gestion des activités universitaires">
    <meta name="keywords" content="université, gestion, activités, administratif">
    <meta name="author" content="GestApp University System">
    
    <title>@yield('title', 'GestApp - Gestion Universitaire')</title>
    
    <!-- University Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts - Inter for better readability -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/css/university-theme.css', 'resources/js/app.js'])
    
    <!-- Sweet Alert -->
    @if(function_exists('sweetAlert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endif
    
    @stack('styles')
</head>

<body class="d-flex flex-column h-100">
    <!-- Accessibility Skip Link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-university-primary text-white px-4 py-2 rounded-md">
        Passer au contenu principal
    </a>

    <!-- University Header -->
    @auth
        <header class="university-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h3 mb-0">
                            <i class="bi bi-mortarboard-fill me-2"></i>
                            GestApp - Gestion Universitaire
                        </h1>
                        <p class="mb-0 opacity-75">Plateforme de gestion des activités académiques</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-flex align-items-center justify-content-md-end gap-3">
                            <span class="text-white">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                            </span>
                            <span class="university-badge">
                                @switch(Auth::user()->role_id)
                                    @case(1)
                                        SuperAdmin
                                        @break
                                    @case(2)
                                        Président
                                        @break
                                    @case(3)
                                        Admin
                                        @break
                                    @case(4)
                                        Service
                                        @break
                                    @default
                                        Utilisateur
                                @endswitch
                            </span>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    @endauth

    <!-- Navigation -->
    @auth
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('Home') ? 'active' : '' }}" href="{{ route('Home') }}">
                                <i class="bi bi-house-door me-1"></i>
                                Tableau de bord
                            </a>
                        </li>
                        
                        @if(Auth::user()->role_id <= 3)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}" href="{{ route('analytics') }}">
                                <i class="bi bi-graph-up me-1"></i>
                                Analytique
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-bullseye me-1"></i>
                                Objectifs
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('Objective') }}">
                                        <i class="bi bi-list-check me-2"></i>
                                        Gestion des objectifs
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('Under_Objective') }}">
                                        <i class="bi bi-diagram-3 me-2"></i>
                                        Sous-objectifs
                                    </a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('Activites') ? 'active' : '' }}" href="{{ route('Activites') }}">
                                <i class="bi bi-activity me-1"></i>
                                Activités
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('Guide') ? 'active' : '' }}" href="{{ route('Guide') }}">
                                <i class="bi bi-file-earmark-text me-1"></i>
                                Guides
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('trimestre') ? 'active' : '' }}" href="{{ route('trimestre') }}">
                                <i class="bi bi-calendar3 me-1"></i>
                                Périodes
                            </a>
                        </li>
                        
                        @if(Auth::user()->role_id <= 2)
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('services') ? 'active' : '' }}" href="{{ route('services') }}">
                                    <i class="bi bi-building me-1"></i>
                                    Services
                                </a>
                            </li>
                        @endif
                    </ul>
                    
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-gear me-1"></i>
                                Administration
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(Auth::user()->role_id <= 3)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('export.config') }}">
                                            <i class="bi bi-download me-2"></i>
                                            Exporter les données
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->role_id == 1)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('register') }}">
                                            <i class="bi bi-person-plus me-2"></i>
                                            Nouvel utilisateur
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#" onclick="window.print()">
                                        <i class="bi bi-printer me-2"></i>
                                        Imprimer la page
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                                        <i class="bi bi-question-circle me-2"></i>
                                        Aide
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    <!-- Main Content -->
    <main id="main-content" class="flex-shrink-0">
        <div class="container py-4">
            <!-- Flash Messages -->
            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session()->get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- University Footer -->
    <footer class="bg-university-gray-800 text-white mt-auto py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="mb-2">GestApp - Système de Gestion Universitaire</h6>
                    <p class="mb-0 text-university-gray-400">
                        Plateforme moderne pour la gestion des activités académiques et administratives
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0 text-university-gray-400">
                        &copy; {{ date('Y') }} GestApp. Tous droits réservés.
                    </p>
                    <small class="text-university-gray-500">
                        Version {{ config('app.version', '2.0.0') }} • 
                        Propulsé par Laravel {{ app()->version() }}
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-question-circle me-2"></i>
                        Centre d'Aide - GestApp
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-university-primary mb-3">
                                <i class="bi bi-book me-1"></i>
                                Guides Rapides
                            </h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <i class="bi bi-file-text me-2 text-university-secondary"></i>
                                        Créer un objectif
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <i class="bi bi-file-text me-2 text-university-secondary"></i>
                                        Gérer les activités
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="text-decoration-none">
                                        <i class="bi bi-file-text me-2 text-university-secondary"></i>
                                        Consulter les rapports
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-university-primary mb-3">
                                <i class="bi bi-telephone me-1"></i>
                                Support Technique
                            </h6>
                            <p class="text-muted small">
                                Pour toute assistance technique, contactez :
                            </p>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-envelope me-2"></i>support@university.edu</li>
                                <li><i class="bi bi-telephone me-2"></i>Extension 1234</li>
                                <li><i class="bi bi-clock me-2"></i>Lun-Ven, 8h-17h</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-primary">
                        <i class="bi bi-download me-1"></i>
                        Télécharger le manuel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-hide flash messages after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Print functionality
        function printPage() {
            window.print();
        }

        // Accessibility: Focus management for modals
        document.addEventListener('shown.bs.modal', function (event) {
            event.target.querySelector('.btn-close, .btn-primary').focus();
        });
    </script>

    @stack('scripts')
</body>
</html>
