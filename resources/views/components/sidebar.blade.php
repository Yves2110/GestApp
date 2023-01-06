<nav>
    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <ul class="sidebar-nav" id="sidebar-nav">

            <li class="nav-item">
                <a class="nav-link " href=" {{ route('Guide') }} ">
                    <i class="bi bi-bookmark-heart"></i>
                    <span>Guide</span>
                </a>
            </li><!-- End Dashboard Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Services</span><i
                        class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href=" {{ route('services') }} ">
                            <i class="bi bi-plus-circle"></i><span>Ajouter</span>
                        </a>
                    </li>
                    @forelse ($services as $service)
                        <li>
                            <a href="components-accordion.html">
                                <i class="bi bi-circle"></i><span>{{ $service->service }}</span>
                            </a>
                        </li>

                    @empty
                        <span>Pas de Service</span>
                    @endforelse

                </ul>
            </li><!-- End Components Nav -->
            @include('sweetalert::alert')
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                    <i class="ri-newspaper-line"></i><span>Programme</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-card-list"></i><span>Activités</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-calendar-month"></i><span>Trimestres</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-calendar2-check"></i><span>Evaluation</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-plus-circle"></i><span>Ajouter</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Forms Nav -->

            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                    <i class="ri-settings-2-line"></i><span>Parametre</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>General Tables</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-circle"></i><span>Data Tables</span>
                        </a>
                    </li>
                </ul>
            </li><!-- End Tables Nav -->


            <li class="nav-item btn  bg-danger">
                <a class="nav-link collapsed" href=" {{ route('logout') }} ">
                    <i class="ri-logout-circle-r-line"></i>
                    <span>Déconnexion</span>
                </a>
            </li><!-- End Login Page Nav -->



        </ul>

    </aside><!-- End Sidebar-->

</nav>
