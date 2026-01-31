<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">

        <!-- Fond -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Messages -->
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/20 backdrop-blur-md border border-red-500/50 text-red-200 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Contenu principal -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8 text-center">

                <!-- Titre -->
                <div class="mb-8">
                    <div class="text-6xl mb-4">🎴</div>
                    <h1 class="text-4xl font-bold text-white mb-3">
                        Premier Tirage Gratuit
                    </h1>
                    <p class="text-xl text-gray-300">
                        Bienvenue, jeune Chevalier ! Tirez vos {{ $drawCount }} premieres cartes de base.
                    </p>
                </div>

                <!-- Info box -->
                <div class="bg-purple-500/20 backdrop-blur-sm rounded-xl border border-purple-500/30 p-6 mb-8">
                    <div class="flex items-center justify-center gap-4 text-purple-200">
                        <span class="text-4xl">🎁</span>
                        <div class="text-left">
                            <p class="font-bold text-lg">Tirage offert</p>
                            <p class="text-purple-300">Vous allez recevoir <strong class="text-white">{{ $drawCount }} cartes</strong> pour completer votre collection !</p>
                        </div>
                    </div>
                </div>

                <!-- Animation de cartes -->
                <div class="flex justify-center gap-2 mb-8">
                    @for($i = 0; $i < $drawCount; $i++)
                        <div class="w-16 h-24 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg transform hover:scale-110 transition-all duration-300 flex items-center justify-center text-2xl animate-pulse" style="animation-delay: {{ $i * 0.1 }}s;">
                            ?
                        </div>
                    @endfor
                </div>

                <!-- Bouton de tirage -->
                <form method="POST" action="{{ route('first-draw.draw') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-3 px-12 py-5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-xl rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                        <span class="text-3xl">🎲</span>
                        Tirer mes {{ $drawCount }} cartes !
                    </button>
                </form>

                <p class="mt-6 text-sm text-gray-400">
                    Ce tirage est gratuit et ne peut etre effectue qu'une seule fois.
                </p>

            </div>

        </div>
    </div>

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(0.95); }
        }
    </style>
</x-app-layout>
