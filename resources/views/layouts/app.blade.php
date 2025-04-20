<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Document Management')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        body {
            padding-top: 4.5rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar-brand img {
            max-height: 30px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 1rem 0;
            margin-top: 2rem;
        }
        .preview-frame {
            border: 1px solid #ddd;
            min-height: 500px;
            width: 100%;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .template-card {
            transition: all 0.3s ease;
        }
        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .placeholder-badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        .document-actions {
            position: sticky;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            padding: 15px;
            border-top: 1px solid #ddd;
            z-index: 100;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo"> {{ config('app.name') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        @if(Auth::user()->hasRole('super_admin'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('templates*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                    <i class="fas fa-file-alt"></i> Templates
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('schools*') ? 'active' : '' }}" href="{{ route('schools.index') }}">
                                    <i class="fas fa-school"></i> Schools
                                </a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->hasRole('principal'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('processed*') ? 'active' : '' }}" href="{{ route('processed.index') }}">
                                    <i class="fas fa-file-word"></i> My Documents
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('templates*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                    <i class="fas fa-file-alt"></i> Available Templates
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="s">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="footer">
        <div class="container text-center">
            <span class="text-muted">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</span>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional scripts -->
    @yield('scripts')
</body>
</html>