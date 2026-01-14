<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Boostrap ICONS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <!-- CSS perso -->
    <link href="{{asset('style.css')}}" rel="stylesheet" type="text/css" />

    <!-- Redirection des pages -->
    @yield('redirect')
</head>

<body id="page-top">
    <!-- Messages Flash -->
    @section('Messages_flash')
    @if(session()->has('etat'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{session()->get('etat')}}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    @endif
    @show

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="sidebar navbar-nav" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div class="sidebar-brand-text mx-3">PrésEnsas</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0" style="border-top: 1px solid rgba(255,255,255,0.15);">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider" style="border-top: 1px solid rgba(255,255,255,0.15);">

            @auth
                @if(Auth::user()->type === 'admin')
                    <!-- Heading - Admin Section -->
                    <div class="sidebar-heading">
                        <i class="bi bi-shield-check me-2"></i>Administration
                    </div>

                    <!-- Nav Item - Students -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.etudiants') }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Étudiants</span>
                        </a>
                    </li>

                    <!-- Nav Item - Teachers -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.enseignants') }}">
                            <i class="bi bi-person-badge-fill"></i>
                            <span>Enseignants</span>
                        </a>
                    </li>

                    <!-- Nav Item - Modules -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.modules') }}">
                            <i class="bi bi-book-fill"></i>
                            <span>Modules</span>
                        </a>
                    </li>

                    <!-- Nav Item - Users Management -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="bi bi-person-check"></i>
                            <span>Gestion Utilisateurs</span>
                        </a>
                    </li>

                    <!-- Nav Item - Classes -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.groupes.index') }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Classes</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider" style="border-top: 1px solid rgba(255,255,255,0.15);">

                    <!-- Heading - Account -->
                    <div class="sidebar-heading">
                        <i class="bi bi-person-circle me-2"></i>Compte
                    </div>

                    <!-- Nav Item - Change Password -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.account.edit') }}">
                            <i class="bi bi-key"></i>
                            <span>Changer Mot de passe</span>
                        </a>
                    </li>

                    <!-- Nav Item - Profile -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.profil') }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <!-- Nav Item - Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @elseif(Auth::user()->type === 'enseignant')
                    <!-- Heading - Enseignant Section -->
                    <div class="sidebar-heading">
                        <i class="bi bi-book me-2"></i>Enseignant
                    </div>

                    <!-- Nav Item - Modules -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.modules.index') }}">
                            <i class="bi bi-book-fill"></i>
                            <span>Mes Modules</span>
                        </a>
                    </li>

                    <!-- Nav Item - Seances -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.showSeance') }}">
                            <i class="bi bi-calendar-event"></i>
                            <span>Mes Séances</span>
                        </a>
                    </li>

                    <!-- Nav Item - Students -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.etudiants') }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Étudiants</span>
                        </a>
                    </li>

                    <!-- Nav Item - Absences -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.absences.liste') }}">
                            <i class="bi bi-file-earmark-check"></i>
                            <span>Validations</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider" style="border-top: 1px solid rgba(255,255,255,0.15);">


                    <!-- Heading - Account -->
                    <div class="sidebar-heading">
                        <i class="bi bi-person-circle me-2"></i>Compte
                    </div>

                    <!-- Nav Item - Change Password -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.account.edit') }}">
                            <i class="bi bi-key"></i>
                            <span>Changer Mot de passe</span>
                        </a>
                    </li>

                    <!-- Nav Item - Profile -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('enseignant.profil') }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <!-- Nav Item - Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @elseif(Auth::user()->type === 'etudiant')
                    <!-- Heading - Etudiant Section -->
                    <div class="sidebar-heading">
                        <i class="bi bi-mortarboard me-2"></i>Étudiant
                    </div>

                    <!-- Nav Item - Mes Cours -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.home') }}#cours">
                            <i class="bi bi-book-fill"></i>
                            <span>Mes Cours</span>
                        </a>
                    </li>

                    <!-- Nav Item - Absences -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.absences.liste') }}">
                            <i class="bi bi-file-earmark-medical"></i>
                            <span>Mes Absences</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider" style="border-top: 1px solid rgba(255,255,255,0.15);">

                    <!-- Heading - Account -->
                    <div class="sidebar-heading">
                        <i class="bi bi-person-circle me-2"></i>Compte
                    </div>

                    <!-- Nav Item - Change Password -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.account.edit') }}">
                            <i class="bi bi-key"></i>
                            <span>Changer Mot de passe</span>
                        </a>
                    </li>

                    <!-- Nav Item - Profile -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('etudiant.profil') }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <!-- Nav Item - Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @elseif(Auth::user()->type === 'gestionnaire')
                    <!-- Heading - Gestionnaire Section -->
                    <div class="sidebar-heading">
                        <i class="bi bi-gear me-2"></i>Gestion
                    </div>

                    <!-- Nav Item - Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gestionnaire.home') }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <hr class="sidebar-divider" style="border-top: 1px solid rgba(255,255,255,0.15);">

                    <!-- Heading - Account -->
                    <div class="sidebar-heading">
                        <i class="bi bi-person-circle me-2"></i>Compte
                    </div>

                    <!-- Nav Item - Change Password -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gestionnaire.account.edit') }}">
                            <i class="bi bi-key"></i>
                            <span>Changer Mot de passe</span>
                        </a>
                    </li>

                    <!-- Nav Item - Profile -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('gestionnaire.profil') }}">
                            <i class="bi bi-person"></i>
                            <span>Profil</span>
                        </a>
                    </li>

                    <!-- Nav Item - Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @else
                    <!-- Heading - Interface Section (Non-Admin, Non-Enseignant) -->
                    <div class="sidebar-heading">
                        Interface
                    </div>

                    <!-- Nav Item - Logout -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Déconnexion</span>
                        </a>
                    </li>
                @endif
            @endauth


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="topbar mb-4 static-top"
                    style="@auth @if(Auth::user()->type === 'admin') display: none; @endif @endauth">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="bi bi-list"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Rechercher..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <button class="btn btn-primary" type="button">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @auth
                                    <span
                                        class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nom ?? 'Utilisateur' }}</span>
                                @endauth
                                <img class="img-profile rounded-circle"
                                    src="https://ui-avatars.com/api/?name={{ Auth::user()->nom ?? 'User' }}&background=random"
                                    style="height: 2rem; width: 2rem;">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person me-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="bi bi-box-arrow-right me-2 text-gray-400"></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('contents')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>&copy; Présensas {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Bootstrap Bundle avec Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>