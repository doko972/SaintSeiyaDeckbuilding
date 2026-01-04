<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Saint Seiya Deckbuilding') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
        
        <!-- Image de fond (optionnel - décommenter si tu as une image) -->
        {{-- 
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/bg-sanctuary.jpg') }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900/90 via-purple-900/80 to-gray-900/90"></div>
        </div>
        --}}
        
        <!-- Fond dégradé (si pas d'image) -->
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 z-0"></div>

        <!-- Étoiles animées -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-1">
            <div class="stars"></div>
            <div class="stars2"></div>
            <div class="stars3"></div>
        </div>

        <!-- Constellations décoratives -->
        <div class="absolute top-10 right-10 z-5 opacity-20 hidden md:block">
            <img src="{{ asset('images/constellation.svg') }}" alt="" class="h-64 w-64">
        </div>
        <div class="absolute bottom-10 left-10 z-5 opacity-20 hidden md:block">
            <img src="{{ asset('images/constellation.svg') }}" alt="" class="h-48 w-48 rotate-180">
        </div>

        <!-- Logo -->
        <div class="relative z-10">
            <a href="/" class="flex flex-col items-center group">
                {{-- Si tu as un logo image : --}}
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Saint Seiya Deckbuilding" 
                         class="h-20 w-auto mb-2 transform group-hover:scale-110 transition-transform duration-300 drop-shadow-[0_0_15px_rgba(255,215,0,0.5)]">
                @else
                    <div class="text-6xl mb-2 transform group-hover:scale-110 transition-transform duration-300">⚔️</div>
                @endif
                
                <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-yellow-200 to-yellow-400">
                    Saint Seiya
                </h1>
                <p class="text-purple-300 text-sm tracking-widest uppercase">Deckbuilding</p>
            </a>
        </div>

        <!-- Contenu (formulaire) -->
        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-800 bg-opacity-80 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-xl border border-purple-500/30 relative z-10">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <p class="mt-8 text-purple-400 text-sm relative z-10">
            Brûle ton cosmos ! 🔥
        </p>
    </div>

    <style>
        .stars, .stars2, .stars3 {
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

        .stars3 {
            background: transparent url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle fill="%238B5CF6" cx="50" cy="50" r="1.2"/></svg>') repeat;
            background-size: 150px 150px;
            animation: animStar 150s linear infinite;
            opacity: 0.4;
        }

        @keyframes animStar {
            from { transform: translateY(0); }
            to { transform: translateY(-100%); }
        }
    </style>
</body>
</html>