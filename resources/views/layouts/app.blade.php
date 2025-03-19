<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ISI')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #2c3e50 !important;
        }
        .nav-link {
            color: #34495e !important;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            color: #3498db !important;
            transform: translateY(-2px);
        }
        .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .animate__animated {
            animation-duration: 0.5s;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">ISI</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">Tableau de bord</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">Utilisateurs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cours.index') }}">Cours</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('salles.index') }}">Salles</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('emargements.index') }}">Émargements</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.rapports.index') }}">Rapports</a>
                            </li>
                        @endif

                        @if(auth()->user()->isProfesseur())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('emargements.index') }}">Mes émargements</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        Notifications
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Déconnexion</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Institut Supérieur Informatique. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html> 