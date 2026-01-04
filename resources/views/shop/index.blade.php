<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Boutique') }}
            </h2>
            <div class="flex items-center gap-2 bg-yellow-500/20 border border-yellow-500/50 px-4 py-2 rounded-lg">
                <span class="text-2xl">🪙</span>
                <span class="text-xl font-bold text-yellow-400">{{ number_format($user->coins) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Intro -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 mb-8 text-center">
                <h3 class="text-2xl font-bold text-white mb-2">🎴 Achetez des Boosters</h3>
                <p class="text-gray-400">Ouvrez des boosters pour obtenir de nouvelles cartes et compléter votre collection !</p>
            </div>

            <!-- Boosters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach($boosters as $booster)
                    <div class="booster-card group">
                        <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 border-white/10 overflow-hidden transition-all duration-300 hover:border-purple-500/50 hover:shadow-2xl hover:shadow-purple-500/20 hover:transform hover:-translate-y-2">
                            <!-- Header coloré -->
                            <div class="p-8 bg-gradient-to-br {{ $booster['color'] }} text-white text-center relative overflow-hidden">
                                <!-- Effet brillant -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                
                                <div class="text-6xl mb-3 transform group-hover:scale-110 transition-transform duration-300">{{ $booster['icon'] }}</div>
                                <h3 class="text-xl font-bold relative z-10">{{ $booster['name'] }}</h3>
                            </div>

                            <!-- Contenu -->
                            <div class="p-5">
                                <p class="text-gray-400 text-sm mb-4">{{ $booster['description'] }}</p>

                                <!-- Taux de drop -->
                                <div class="bg-black/30 rounded-lg p-3 mb-4 border border-white/10">
                                    <p class="text-xs font-semibold text-gray-500 mb-2 uppercase tracking-wider">Chances d'obtention</p>
                                    @foreach($booster['rates'] as $rate)
                                        <p class="text-xs text-gray-400">• {{ $rate }}</p>
                                    @endforeach
                                </div>

                                <!-- Prix et bouton -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-2xl">🪙</span>
                                        <span class="text-2xl font-bold text-yellow-400">{{ number_format($booster['price']) }}</span>
                                    </div>

                                    <form action="{{ route('shop.buy', $booster['id']) }}" method="POST">
                                        @csrf
                                        @if($user->coins >= $booster['price'])
                                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-105 shadow-lg shadow-purple-500/25">
                                                Acheter
                                            </button>
                                        @else
                                            <button type="button" disabled class="px-5 py-2 bg-gray-700 text-gray-500 font-bold rounded-lg cursor-not-allowed">
                                                Insuffisant
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Comment gagner des pièces -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6">
                <h3 class="text-xl font-bold text-white mb-6 text-center">💰 Comment gagner des pièces ?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-5 text-center">
                        <div class="text-4xl mb-3">🏆</div>
                        <h4 class="font-bold text-green-400 text-lg">Victoire en combat</h4>
                        <p class="text-green-300/80 text-2xl font-bold mt-2">+100 🪙</p>
                    </div>
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-5 text-center">
                        <div class="text-4xl mb-3">⚔️</div>
                        <h4 class="font-bold text-blue-400 text-lg">Défaite en combat</h4>
                        <p class="text-blue-300/80 text-2xl font-bold mt-2">+25 🪙</p>
                    </div>
                    <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-5 text-center">
                        <div class="text-4xl mb-3">🎁</div>
                        <h4 class="font-bold text-purple-400 text-lg">Bonus quotidien</h4>
                        <p class="text-purple-300/80 text-sm mt-2">Bientôt disponible !</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>