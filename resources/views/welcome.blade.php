<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Saint Seiya Deckbuilding - Construis ton deck et affronte tes adversaires avec les Chevaliers du Zodiaque !">

    <title>{{ config('app.name', 'Saint Seiya Deckbuilding') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-white">

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-gray-900/80 backdrop-blur-md border-b border-purple-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2">
                    @if(file_exists(public_path('images/logo.webp')))
                        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <span class="text-2xl">⚔️</span>
                    @endif
                    <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                        Saint Seiya
                    </span>
                </a>

                <!-- Auth Links -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 text-purple-300 hover:text-white transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-purple-300 hover:text-white transition">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-gray-900 font-bold rounded-lg hover:from-yellow-400 hover:to-orange-400 transition transform hover:scale-105">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            @if(file_exists(public_path('images/bg-sanctuary.webp')))
                <img src="{{ asset('images/bg-sanctuary.webp') }}" alt="" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/70 via-purple-900/60 to-gray-900"></div>
        </div>

        <!-- Étoiles animées -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-1">
            <div class="stars"></div>
            <div class="stars2"></div>
        </div>

        <!-- Contenu Hero -->
        <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
            <!-- Logo principal -->
            <div class="mb-6">
                @if(file_exists(public_path('images/logo.webp')))
                    <img src="{{ asset('images/logo.webp') }}" alt="Saint Seiya Deckbuilding" class="h-32 md:h-40 w-auto mx-auto drop-shadow-[0_0_30px_rgba(255,215,0,0.5)] animate-float">
                @else
                    <div class="text-8xl md:text-9xl animate-float">⚔️</div>
                @endif
            </div>

            <h1 class="text-5xl md:text-7xl font-black mb-4">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-yellow-200 to-yellow-400">
                    SAINT SEIYA
                </span>
            </h1>
            <h2 class="text-2xl md:text-4xl font-bold text-purple-300 mb-6 tracking-widest">
                DECKBUILDING
            </h2>

            <p class="text-lg md:text-xl text-gray-300 mb-8 max-w-2xl mx-auto">
                Construis ton deck de Chevaliers, maîtrise le Cosmos et affronte des adversaires dans des combats épiques !
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-gray-900 font-bold text-lg rounded-xl shadow-lg shadow-yellow-500/30 hover:from-yellow-400 hover:to-orange-400 transition transform hover:scale-105">
                        🎮 Accéder au jeu
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-500 text-gray-900 font-bold text-lg rounded-xl shadow-lg shadow-yellow-500/30 hover:from-yellow-400 hover:to-orange-400 transition transform hover:scale-105">
                        🛡️ Devenir Chevalier
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-gray-800 border border-purple-500 text-purple-300 font-bold text-lg rounded-xl hover:bg-purple-900/50 transition">
                        Se connecter
                    </a>
                @endauth
            </div>

            <!-- Scroll indicator -->
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- Section Factions -->
    <section class="py-20 bg-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">
                        Choisis ta Faction
                    </span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Chaque faction possède ses propres forces et stratégies. Quelle armure porteras-tu ?
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Chevaliers de Bronze -->
                <div class="group relative bg-gradient-to-br from-orange-900/40 to-orange-700/20 rounded-2xl p-6 border border-orange-500/30 hover:border-orange-400 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">🥉</div>
                        <h3 class="text-2xl font-bold text-orange-400 mb-2">Chevaliers de Bronze</h3>
                        <p class="text-gray-400">Les protecteurs d'Athéna. Leur détermination surpasse leur rang.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded">Seiya</span>
                            <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded">Shiryu</span>
                            <span class="px-2 py-1 bg-orange-500/20 text-orange-300 text-xs rounded">Hyōga</span>
                        </div>
                    </div>
                </div>

                <!-- Chevaliers d'Argent -->
                <div class="group relative bg-gradient-to-br from-gray-600/40 to-gray-500/20 rounded-2xl p-6 border border-gray-400/30 hover:border-gray-300 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-gray-400/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">🥈</div>
                        <h3 class="text-2xl font-bold text-gray-300 mb-2">Chevaliers d'Argent</h3>
                        <p class="text-gray-400">Les exécuteurs du Sanctuaire, redoutables et disciplinés.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded">Misty</span>
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded">Marin</span>
                            <span class="px-2 py-1 bg-gray-500/20 text-gray-300 text-xs rounded">Shaina</span>
                        </div>
                    </div>
                </div>

                <!-- Chevaliers d'Or -->
                <div class="group relative bg-gradient-to-br from-yellow-900/40 to-yellow-600/20 rounded-2xl p-6 border border-yellow-500/30 hover:border-yellow-400 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">🥇</div>
                        <h3 class="text-2xl font-bold text-yellow-400 mb-2">Chevaliers d'Or</h3>
                        <p class="text-gray-400">L'élite du Sanctuaire, gardiens des 12 maisons du Zodiaque.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 text-xs rounded">Aiolia</span>
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 text-xs rounded">Saga</span>
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-300 text-xs rounded">Mū</span>
                        </div>
                    </div>
                </div>

                <!-- Spectres d'Hadès -->
                <div class="group relative bg-gradient-to-br from-purple-900/40 to-purple-700/20 rounded-2xl p-6 border border-purple-500/30 hover:border-purple-400 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">💀</div>
                        <h3 class="text-2xl font-bold text-purple-400 mb-2">Spectres d'Hadès</h3>
                        <p class="text-gray-400">Les guerriers des Enfers, porteurs des Surplis maudits.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-purple-500/20 text-purple-300 text-xs rounded">Rhadamanthe</span>
                            <span class="px-2 py-1 bg-purple-500/20 text-purple-300 text-xs rounded">Minos</span>
                        </div>
                    </div>
                </div>

                <!-- Marinas de Poséidon -->
                <div class="group relative bg-gradient-to-br from-blue-900/40 to-blue-600/20 rounded-2xl p-6 border border-blue-500/30 hover:border-blue-400 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">🌊</div>
                        <h3 class="text-2xl font-bold text-blue-400 mb-2">Marinas de Poséidon</h3>
                        <p class="text-gray-400">Les guerriers des océans, maîtres des profondeurs.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded">Kanon</span>
                            <span class="px-2 py-1 bg-blue-500/20 text-blue-300 text-xs rounded">Sorrento</span>
                        </div>
                    </div>
                </div>

                <!-- Guerriers Divins d'Asgard -->
                <div class="group relative bg-gradient-to-br from-cyan-900/40 to-cyan-600/20 rounded-2xl p-6 border border-cyan-500/30 hover:border-cyan-400 transition-all duration-300 hover:transform hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <div class="text-5xl mb-4">❄️</div>
                        <h3 class="text-2xl font-bold text-cyan-400 mb-2">Guerriers d'Asgard</h3>
                        <p class="text-gray-400">Les protecteurs du royaume nordique, bénis par Odin.</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 bg-cyan-500/20 text-cyan-300 text-xs rounded">Siegfried</span>
                            <span class="px-2 py-1 bg-cyan-500/20 text-cyan-300 text-xs rounded">Hagen</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Gameplay -->
    <section class="py-20 bg-gradient-to-b from-gray-900 to-purple-900/30 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-400">
                        Comment Jouer
                    </span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Maîtrise les mécaniques du cosmos pour devenir le plus puissant des Chevaliers
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Étape 1 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-purple-500/30">
                        🃏
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">1. Collectionne</h3>
                    <p class="text-gray-400">Obtiens des cartes de Chevaliers avec leurs attaques uniques et capacités spéciales.</p>
                </div>

                <!-- Étape 2 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-yellow-500/30">
                        📚
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">2. Construis</h3>
                    <p class="text-gray-400">Crée ton deck en combinant stratégiquement tes meilleurs Chevaliers.</p>
                </div>

                <!-- Étape 3 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-red-500/30">
                        ⚔️
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">3. Combats</h3>
                    <p class="text-gray-400">Affronte des adversaires et utilise le Cosmos pour déchaîner tes attaques.</p>
                </div>

                <!-- Étape 4 -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-green-500/30">
                        🏆
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">4. Triomphe</h3>
                    <p class="text-gray-400">Remporte des victoires, gagne des récompenses et deviens une légende !</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Stats -->
    <section class="py-20 bg-gray-900 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">
                        Les Stats de Combat
                    </span>
                </h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Chaque carte possède des statistiques uniques qui définissent sa puissance au combat
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- PV -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-red-500/30">
                    <div class="text-4xl mb-2">❤️</div>
                    <h3 class="font-bold text-red-400">PV</h3>
                    <p class="text-xs text-gray-400 mt-1">Points de Vie</p>
                </div>

                <!-- Endurance -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-yellow-500/30">
                    <div class="text-4xl mb-2">⚡</div>
                    <h3 class="font-bold text-yellow-400">END</h3>
                    <p class="text-xs text-gray-400 mt-1">Endurance</p>
                </div>

                <!-- Défense -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-blue-500/30">
                    <div class="text-4xl mb-2">🛡️</div>
                    <h3 class="font-bold text-blue-400">DEF</h3>
                    <p class="text-xs text-gray-400 mt-1">Défense</p>
                </div>

                <!-- Puissance -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-orange-500/30">
                    <div class="text-4xl mb-2">💪</div>
                    <h3 class="font-bold text-orange-400">PWR</h3>
                    <p class="text-xs text-gray-400 mt-1">Puissance</p>
                </div>

                <!-- Cosmos -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-purple-500/30">
                    <div class="text-4xl mb-2">🌟</div>
                    <h3 class="font-bold text-purple-400">COS</h3>
                    <p class="text-xs text-gray-400 mt-1">Cosmos</p>
                </div>

                <!-- Coût -->
                <div class="bg-gray-800 rounded-xl p-4 text-center border border-green-500/30">
                    <div class="text-4xl mb-2">💎</div>
                    <h3 class="font-bold text-green-400">COÛT</h3>
                    <p class="text-xs text-gray-400 mt-1">Invocation</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA Final -->
    <section class="py-20 bg-gradient-to-b from-gray-900 via-purple-900/50 to-gray-900 relative overflow-hidden">
        <!-- Constellation décorative -->
        @if(file_exists(public_path('images/constellation.svg')))
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <img src="{{ asset('images/constellation.svg') }}" alt="" class="absolute top-10 left-10 w-64 h-64">
                <img src="{{ asset('images/constellation.svg') }}" alt="" class="absolute bottom-10 right-10 w-48 h-48 rotate-180">
            </div>
        @endif

        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-black mb-6">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-yellow-200 to-yellow-400">
                    Prêt à brûler ton Cosmos ?
                </span>
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Rejoins des milliers de Chevaliers et commence ton aventure dès maintenant !
            </p>

            @guest
                <a href="{{ route('register') }}" class="inline-block px-10 py-5 bg-gradient-to-r from-yellow-500 to-orange-500 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-yellow-500/30 hover:from-yellow-400 hover:to-orange-400 transition transform hover:scale-105">
                    🔥 Commencer l'aventure
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="inline-block px-10 py-5 bg-gradient-to-r from-yellow-500 to-orange-500 text-gray-900 font-black text-xl rounded-xl shadow-lg shadow-yellow-500/30 hover:from-yellow-400 hover:to-orange-400 transition transform hover:scale-105">
                    🎮 Accéder au jeu
                </a>
            @endguest
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-10 bg-gray-900 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-2">
                    @if(file_exists(public_path('images/logo.webp')))
                        <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="h-8 w-auto">
                    @else
                        <span class="text-2xl">⚔️</span>
                    @endif
                    <span class="font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-200">
                        Saint Seiya Deckbuilding
                    </span>
                </div>

                <div class="flex gap-6 text-gray-400">
                    <a href="#" class="hover:text-purple-400 transition">À propos</a>
                    <a href="#" class="hover:text-purple-400 transition">Règles</a>
                    <a href="#" class="hover:text-purple-400 transition">Contact</a>
                </div>

                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} - Fan Project
                </p>
            </div>
        </div>
    </footer>

    <!-- Styles pour les étoiles -->
    <style>
        .stars, .stars2 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            display: block;
        }

        .stars {
            background: transparent url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle fill="%23FFF" cx="50" cy="50" r="1"/></svg>') repeat;
            background-size: 50px 50px;
            animation: animStar 50s linear infinite;
            opacity: 0.5;
        }

        .stars2 {
            background: transparent url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle fill="%23FFD700" cx="50" cy="50" r="0.8"/></svg>') repeat;
            background-size: 100px 100px;
            animation: animStar 100s linear infinite;
            opacity: 0.3;
        }

        @keyframes animStar {
            from { transform: translateY(0); }
            to { transform: translateY(-100%); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</body>
</html>