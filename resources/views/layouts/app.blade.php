<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SmartHR - RH')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2563EB',
                        'primary-hover': '#1D4ED8',
                        secondary: '#1F2937',
                        success: '#10B981',
                        danger: '#EF4444',
                        warning: '#F59E0B',
                    }
                }
            }
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/locales/fr.js'></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F9FAFB;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased">

    <nav class="bg-white shadow-md border-b border-gray-200 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        <i class="fas fa-brain text-primary text-2xl"></i>
                        <span class="font-bold text-xl text-secondary">SmartHR</span>
                        <span class="text-xs text-gray-500 ml-2">RH</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        @php $role = Auth::user()->role->nom ?? 'Employe'; @endphp

                        @if($role === 'Candidat')
                            <a href="{{ route('offres.publiques') }}" 
                               class="{{ request()->routeIs('offres.publiques') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                <i class="fas fa-briefcase mr-1"></i> Offres
                            </a>
                            <a href="{{ route('candidatures.mes') }}" 
                               class="{{ request()->routeIs('candidatures.mes') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                <i class="fas fa-file-alt mr-1"></i> Mes candidatures
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" 
                               class="{{ request()->routeIs('dashboard') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                <i class="fas fa-chart-line mr-1"></i> Dashboard
                            </a>

                            @if(in_array($role, ['Admin', 'RH']))
                                <a href="{{ route('employes.index') }}" 
                                   class="{{ request()->routeIs('employes.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                    <i class="fas fa-users mr-1"></i> Employés
                                </a>
                            @endif

                            <a href="{{ route('conges.index') }}" 
                               class="{{ request()->routeIs('conges.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                <i class="fas fa-umbrella-beach mr-1"></i> Congés
                            </a>

                            <a href="{{ route('calendrier') }}" 
                               class="{{ request()->routeIs('calendrier') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                <i class="fas fa-calendar-alt mr-1"></i> Calendrier
                            </a>

                            @if(in_array($role, ['Admin', 'RH']))
                                <a href="{{ route('offres.admin') }}" 
                                   class="{{ request()->routeIs('offres.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                    <i class="fas fa-briefcase mr-1"></i> Offres
                                </a>
                                <a href="{{ route('candidatures.admin') }}" 
                                   class="{{ request()->routeIs('candidatures.*') ? 'text-primary font-semibold border-b-2 border-primary pb-1' : 'text-gray-700 hover:text-primary transition' }}">
                                    <i class="fas fa-file-alt mr-1"></i> Candidatures
                                </a>
                            @endif
                        @endif

                        <!-- NOTIFICATIONS -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-700 hover:text-primary relative">
                                <i class="fas fa-bell"></i>
                                @php
                                    $unreadCount = Auth::user() ? Auth::user()->unreadNotifications()->count() : 0;
                                @endphp
                                @if($unreadCount > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg z-50" x-cloak>
                                <div class="p-3 border-b border-gray-100">
                                    <h3 class="font-semibold text-gray-800">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @php
                                        $notifications = Auth::user() ? Auth::user()->notifications()->take(10)->get() : collect();
                                    @endphp
                                    @forelse($notifications as $notif)
                                    <a href="{{ $notif->url }}" class="block p-3 hover:bg-gray-50 border-b border-gray-100 {{ $notif->is_read ? '' : 'bg-blue-50' }}">
                                        <p class="text-sm font-medium text-gray-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $notif->message }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </a>
                                    @empty
                                    <p class="p-3 text-center text-gray-500">Aucune notification</p>
                                    @endforelse
                                </div>
                                <div class="p-2 border-t border-gray-100 text-center">
                                    <a href="#" class="text-xs text-primary hover:underline" onclick="markAllAsRead(); return false;">Tout marquer comme lu</a>
                                </div>
                            </div>
                        </div>

                        <style>
                            [x-cloak] { display: none !important; }
                        </style>

                        <script>
                        function markAllAsRead() {
                            fetch('{{ route("notifications.mark-read") }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        }
                        </script>
                    @endauth
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="text-gray-700">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-gray-500 text-sm"></i>
                            </button>

                            <div id="userMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden z-50">
                                <a href="{{ route('profil') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i> Mon profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}" class="text-primary hover:underline">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
                            Inscription
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');

        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!userMenuBtn.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>