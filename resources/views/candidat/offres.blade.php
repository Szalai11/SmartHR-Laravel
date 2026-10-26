<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartHR - Offres d'emploi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#2563EB', 'primary-hover': '#1D4ED8' }
                }
            }
        }
    </script>
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-brain text-primary text-2xl"></i>
                <span class="font-bold text-xl text-gray-800">SmartHR</span>
            </div>
            <div class="space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:text-primary">Mon espace</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-primary hover:underline">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover">Inscription</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Offres d'emploi</h1>
            <p class="text-gray-600 mb-8">Découvrez nos opportunités et rejoignez notre équipe</p>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($offres as $offre)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $offre->titre }}</h2>
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">{{ $offre->type_contrat }}</span>
                        </div>
                        <div class="flex items-center text-gray-500 text-sm space-x-4 mb-3">
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $offre->lieu ?? 'Lomé, Togo' }}</span>
                            <span><i class="fas fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}</span>
                        </div>
                        <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ Str::limit($offre->description, 120) }}</p>
                        <a href="{{ route('offres.show', $offre->id) }}" class="block text-center bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary-hover transition">
                            <i class="fas fa-paper-plane mr-2"></i>Postuler
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12 bg-white rounded-xl">
                    <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Aucune offre disponible pour le moment</p>
                </div>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>