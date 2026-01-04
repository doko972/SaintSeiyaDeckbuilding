<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('En attente d\'un adversaire...') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
        
        <!-- Fond Sanctuaire -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden text-center">
                
                <div class="p-8 bg-gradient-to-r from-yellow-600 to-orange-600">
                    <div class="text-6xl mb-4 animate-bounce">⏳</div>
                    <h1 class="text-2xl font-bold text-white">En attente d'un adversaire</h1>
                </div>

                <div class="p-8">
                    <!-- Animation de recherche -->
                    <div class="flex justify-center items-center gap-2 mb-8">
                        <div class="w-4 h-4 rounded-full bg-yellow-500 animate-pulse"></div>
                        <div class="w-4 h-4 rounded-full bg-yellow-500 animate-pulse" style="animation-delay: 0.2s;"></div>
                        <div class="w-4 h-4 rounded-full bg-yellow-500 animate-pulse" style="animation-delay: 0.4s;"></div>
                    </div>

                    <!-- Infos de la partie -->
                    <div class="bg-black/30 rounded-xl p-6 mb-8">
                        <div class="text-gray-400 text-sm mb-2">Votre deck</div>
                        <div class="text-xl font-bold text-white">{{ $battle->player1Deck->name }}</div>
                        <div class="text-gray-500 text-sm mt-1">
                            Créé {{ $battle->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <p class="text-gray-400 mb-8">
                        Partagez cette page avec un ami pour qu'il puisse vous rejoindre !
                    </p>

                    <!-- Bouton annuler -->
                    <form action="{{ route('pvp.cancel', $battle) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-8 py-3 bg-red-600/20 border border-red-600/50 text-red-400 font-bold rounded-xl hover:bg-red-600/30 transition">
                            ❌ Annuler la partie
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Auto-refresh pour vérifier si un adversaire a rejoint -->
    <script>
        const checkInterval = setInterval(async () => {
            try {
                const response = await fetch(window.location.href, {
                    headers: { 'Accept': 'text/html' }
                });
                
                // Si redirection, suivre
                if (response.redirected) {
                    window.location.href = response.url;
                    clearInterval(checkInterval);
                }
            } catch (e) {
                console.error('Check failed:', e);
            }
        }, 3000); // Vérifier toutes les 3 secondes
    </script>
</x-app-layout>