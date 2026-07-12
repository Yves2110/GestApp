<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="GestApp - Système de gestion des activités universitaires">
    <meta name="author" content="GestApp University System">

    <title>@yield('title', 'GestApp') — GestApp University</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Bootstrap 5 CSS & Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite: app.css + app.js -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body>
    <a href="#main-content" class="skip-link">Passer au contenu principal</a>

    @auth
    <div class="app-layout">

        {{-- ====== SIDEBAR ====== --}}
        <aside class="sidebar" id="sidebar" aria-label="Navigation principale">

            {{-- Brand --}}
            <a href="{{ route('Home') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <span class="sidebar-brand-text">GestApp</span>
            </a>

            {{-- Nav --}}
            <nav class="sidebar-nav">
                <ul class="list-unstyled mb-0">

                    {{-- PRINCIPAL --}}
                    <li class="sidebar-section-label">Principal</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('Home') }}"
                           class="sidebar-nav-link {{ request()->routeIs('Home') ? 'active' : '' }}"
                           data-label="Tableau de bord">
                            <i class="bi bi-house-door nav-icon"></i>
                            <span class="nav-label">Tableau de bord</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('Activites') }}"
                           class="sidebar-nav-link {{ request()->routeIs('Activites', 'activites.show', 'activites.edit') ? 'active' : '' }}"
                           data-label="Activités">
                            <i class="bi bi-lightning-charge nav-icon"></i>
                            <span class="nav-label">Activités</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('activites.kanban') }}"
                           class="sidebar-nav-link {{ request()->routeIs('activites.kanban') ? 'active' : '' }}"
                           data-label="Kanban">
                            <i class="bi bi-kanban nav-icon"></i>
                            <span class="nav-label">Kanban</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('activites.calendar') }}"
                           class="sidebar-nav-link {{ request()->routeIs('activites.calendar') ? 'active' : '' }}"
                           data-label="Calendrier">
                            <i class="bi bi-calendar3 nav-icon"></i>
                            <span class="nav-label">Calendrier</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('activites.timeline') }}"
                           class="sidebar-nav-link {{ request()->routeIs('activites.timeline') ? 'active' : '' }}"
                           data-label="Timeline">
                            <i class="bi bi-diagram-3 nav-icon"></i>
                            <span class="nav-label">Timeline</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('notifications.index') }}"
                           class="sidebar-nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
                           data-label="Notifications">
                            <i class="bi bi-bell nav-icon"></i>
                            <span class="nav-label">Notifications</span>
                            @if(isset($_unreadNotifCount) && $_unreadNotifCount > 0)
                                <span class="badge-count ms-auto" style="font-size:.6rem;min-width:1.2rem;height:1.2rem;padding:0 .25rem;border-radius:9999px;background:var(--clr-danger);color:#fff;display:flex;align-items:center;justify-content:center;">
                                    {{ $_unreadNotifCount > 9 ? '9+' : $_unreadNotifCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- PLANIFICATION --}}
                    <li class="sidebar-section-label">Planification</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('Objective') }}"
                           class="sidebar-nav-link {{ request()->routeIs('Objective') ? 'active' : '' }}"
                           data-label="Objectifs">
                            <i class="bi bi-bullseye nav-icon"></i>
                            <span class="nav-label">Objectifs</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('Under_Objective') }}"
                           class="sidebar-nav-link {{ request()->routeIs('Under_Objective') ? 'active' : '' }}"
                           data-label="Sous-objectifs">
                            <i class="bi bi-diagram-3 nav-icon"></i>
                            <span class="nav-label">Sous-objectifs</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('trimestre') }}"
                           class="sidebar-nav-link {{ request()->routeIs('trimestre') ? 'active' : '' }}"
                           data-label="Périodes">
                            <i class="bi bi-calendar3 nav-icon"></i>
                            <span class="nav-label">Périodes</span>
                        </a>
                    </li>

                    {{-- ANALYSES --}}
                    @if(Auth::user()->role_id <= 3)
                    <li class="sidebar-section-label">Analyses</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('analytics') }}"
                           class="sidebar-nav-link {{ request()->routeIs('analytics') || (request()->routeIs('analytics.*') && !request()->routeIs('analytics.performance')) ? 'active' : '' }}"
                           data-label="Analytique">
                            <i class="bi bi-graph-up-arrow nav-icon"></i>
                            <span class="nav-label">Analytique</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('analytics.performance') }}"
                           class="sidebar-nav-link {{ request()->routeIs('analytics.performance') ? 'active' : '' }}"
                           data-label="Performance">
                            <i class="bi bi-speedometer2 nav-icon"></i>
                            <span class="nav-label">Performance</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('export.config') }}"
                           class="sidebar-nav-link {{ request()->routeIs('export*') ? 'active' : '' }}"
                           data-label="Exports">
                            <i class="bi bi-download nav-icon"></i>
                            <span class="nav-label">Exports</span>
                        </a>
                    </li>
                    @endif

                    {{-- RESSOURCES --}}
                    <li class="sidebar-section-label">Ressources</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('Guide') }}"
                           class="sidebar-nav-link {{ request()->routeIs('Guide') ? 'active' : '' }}"
                           data-label="Guides">
                            <i class="bi bi-file-earmark-text nav-icon"></i>
                            <span class="nav-label">Guides</span>
                        </a>
                    </li>
                    @if(Auth::user()->role_id <= 2)
                    <li class="sidebar-nav-item">
                        <a href="{{ route('services') }}"
                           class="sidebar-nav-link {{ request()->routeIs('services') ? 'active' : '' }}"
                           data-label="Services">
                            <i class="bi bi-building nav-icon"></i>
                            <span class="nav-label">Services</span>
                        </a>
                    </li>
                    @endif

                    {{-- ADMINISTRATION --}}
                    @if(Auth::user()->role_id == 1)
                    <li class="sidebar-section-label">Administration</li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('settings.users') }}"
                           class="sidebar-nav-link {{ request()->routeIs('settings.users*') ? 'active' : '' }}"
                           data-label="Utilisateurs">
                            <i class="bi bi-people nav-icon"></i>
                            <span class="nav-label">Utilisateurs</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('settings.permissions') }}"
                           class="sidebar-nav-link {{ request()->routeIs('settings.permissions*') ? 'active' : '' }}"
                           data-label="Permissions">
                            <i class="bi bi-shield-lock nav-icon"></i>
                            <span class="nav-label">Permissions</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('settings.audit') }}"
                           class="sidebar-nav-link {{ request()->routeIs('settings.audit') ? 'active' : '' }}"
                           data-label="Audit">
                            <i class="bi bi-clock-history nav-icon"></i>
                            <span class="nav-label">Audit</span>
                        </a>
                    </li>
                    <li class="sidebar-nav-item">
                        <a href="{{ route('monitoring') }}"
                           class="sidebar-nav-link {{ request()->routeIs('monitoring*') ? 'active' : '' }}"
                           data-label="Monitoring">
                            <i class="bi bi-activity nav-icon"></i>
                            <span class="nav-label">Monitoring</span>
                        </a>
                    </li>
                    @endif

                </ul>
            </nav>

            {{-- User footer --}}
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                        <div class="sidebar-user-role">
                            @switch(Auth::user()->role_id)
                                @case(1) SuperAdmin @break
                                @case(2) Président @break
                                @case(3) Admin @break
                                @case(4) Service @break
                                @default Utilisateur
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- ====== MAIN AREA ====== --}}
        <div class="app-content" id="appContent">

            {{-- ====== HEADER ====== --}}
            <header class="app-header" role="banner">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Basculer la sidebar" aria-expanded="true">
                    <i class="bi bi-list"></i>
                </button>

                <ol class="header-breadcrumb" aria-label="Fil d'ariane">
                    <li class="breadcrumb-item"><a href="{{ route('Home') }}">Accueil</a></li>
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @endif
                </ol>

                <div class="header-spacer"></div>

                {{-- Recherche globale --}}
                <button id="globalSearchBtn"
                        class="btn btn-ghost btn-sm d-none d-md-flex align-items-center gap-2"
                        style="border:1px solid var(--border-color);border-radius:var(--border-radius);padding:.3rem .75rem;color:var(--clr-gray-500);min-width:200px;"
                        aria-label="Recherche globale (Ctrl+K)">
                    <i class="bi bi-search" style="font-size:.85rem;"></i>
                    <span class="text-sm" style="flex:1;text-align:left;">Rechercher…</span>
                    <kbd style="font-size:.65rem;background:var(--clr-gray-100);border:1px solid var(--border-color);border-radius:3px;padding:.1rem .3rem;">Ctrl K</kbd>
                </button>

                <div class="header-actions">
                    {{-- Notifications bell --}}
                    <a href="{{ route('notifications.index') }}"
                       class="btn btn-ghost btn-icon position-relative"
                       title="Notifications">
                        <i class="bi bi-bell"></i>
                        @if(isset($_unreadNotifCount) && $_unreadNotifCount > 0)
                            <span class="badge-count position-absolute"
                                  style="top:2px;right:2px;font-size:.6rem;min-width:1rem;height:1rem;padding:0 .2rem;">
                                {{ $_unreadNotifCount > 9 ? '9+' : $_unreadNotifCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Nouvel utilisateur (SuperAdmin) --}}
                    @if(Auth::user()->role_id == 1)
                    <a href="{{ route('register') }}" class="btn btn-sm btn-ghost" title="Nouvel utilisateur">
                        <i class="bi bi-person-plus"></i>
                        <span class="hide-mobile">Nouvel utilisateur</span>
                    </a>
                    @endif

                    {{-- User dropdown --}}
                    <div class="dropdown">
                        <button class="header-user-btn" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="header-avatar">
                                {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                            </div>
                            <span class="header-user-name">{{ Auth::user()->firstname }}</span>
                            <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width:200px;">
                            <li class="px-3 py-2">
                                <div class="fw-semi text-sm">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                                <div class="text-xs text-muted">{{ Auth::user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @if(Auth::user()->role_id == 1)
                            <li>
                                <a class="dropdown-item" href="{{ route('settings') }}">
                                    <i class="bi bi-gear me-2"></i>Paramètres
                                </a>
                            </li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="#" onclick="window.print(); return false;">
                                    <i class="bi bi-printer me-2"></i>Imprimer
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- ====== CONTENT ====== --}}
            <main id="main-content" class="app-main" tabindex="-1">

                {{-- Flash messages --}}
                @if(session('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i>
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="app-footer no-print">
                <span>&copy; {{ date('Y') }} GestApp University</span>
                <span>v{{ config('app.version', '3.0.0') }} &bull; Laravel {{ app()->version() }}</span>
            </footer>

        </div>{{-- /.app-content --}}
    </div>{{-- /.app-layout --}}

    @else
        {{-- Guest: juste le contenu --}}
        @yield('content')
    @endauth

    <!-- Bootstrap 5 JS Bundle -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Sidebar toggle (collapse / expand)
        (function () {
            const sidebar  = document.getElementById('sidebar');
            const toggle   = document.getElementById('sidebarToggle');
            const overlay  = document.getElementById('sidebarOverlay');
            if (!sidebar) return;

            const STORAGE_KEY = 'gestapp_sidebar_collapsed';
            const isMobile = () => window.innerWidth < 992;

            function applyState(collapsed) {
                if (isMobile()) {
                    sidebar.classList.toggle('mobile-open', !collapsed);
                } else {
                    sidebar.classList.toggle('collapsed', collapsed);
                    if (toggle) toggle.setAttribute('aria-expanded', String(!collapsed));
                    try { localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0'); } catch(_) {}
                }
            }

            // Restore on load
            if (!isMobile()) {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved === '1') sidebar.classList.add('collapsed');
            }

            if (toggle) {
                toggle.addEventListener('click', function () {
                    if (isMobile()) {
                        applyState(sidebar.classList.contains('mobile-open'));
                    } else {
                        applyState(!sidebar.classList.contains('collapsed'));
                    }
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function () {
                    applyState(true);
                });
            }
        })();

        // Bootstrap tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
            new bootstrap.Tooltip(el);
        });

        // Auto-dismiss flash alerts after 5s
        setTimeout(function () {
            document.querySelectorAll('.alert.fade.show').forEach(function (el) {
                bootstrap.Alert.getOrCreateInstance(el).close();
            });
        }, 5000);

        // Modal focus management
        document.addEventListener('shown.bs.modal', function (e) {
            const focusable = e.target.querySelector('[autofocus], .btn-close, .btn-primary');
            if (focusable) focusable.focus();
        });
    </script>

    {{-- Toast container --}}
    <div class="toast-container" id="toastContainer" aria-live="polite" aria-atomic="false"></div>

    @stack('scripts')
</body>
</html>
