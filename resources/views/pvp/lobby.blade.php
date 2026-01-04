<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Arena PvP') }}
            </h2>
            <a href="{{ route('game.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
        
        <!-- Fond Sanctuaire -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.15]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Messages flash -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-xl text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Header -->
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 mb-2">
                    ⚔️ Arena PvP
                </h1>
                <p class="text-gray-400">Affrontez d'autres joueurs en temps réel !</p>
            </div>

            <!-- Stats du joueur -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-gray-400 text-sm">Combats</div>
                </div>
                <div class="bg-green-500/20 backdrop-blur-md rounded-xl border border-green-500/30 p-4 text-center">
                    <div class="text-3xl font-bold text-green-400">{{ $stats['wins'] }}</div>
                    <div class="text-green-300/70 text-sm">Victoires</div>
                </div>
                <div class="bg-red-500/20 backdrop-blur-md rounded-xl border border-red-500/30 p-4 text-center">
                    <div class="text-3xl font-bold text-red-400">{{ $stats['losses'] }}</div>
                    <div class="text-red-300/70 text-sm">Défaites</div>
                </div>
                <div class="bg-purple-500/20 backdrop-blur-md rounded-xl border border-purple-500/30 p-4 text-center">
                    <div class="text-3xl font-bold text-purple-400">
                        {{ $stats['total'] > 0 ? round(($stats['wins'] / $stats['total']) * 100) : 0 }}%
                    </div>
                    <div class="text-purple-300/70 text-sm">Win Rate</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Créer une partie -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-green-600 to-emerald-600">
                        <h3 class="text-xl font-bold text-white">🎮 Créer une partie</h3>
                        <p class="text-white/70 text-sm">Créez une partie et attendez un adversaire</p>
                    </div>

                    <form action="{{ route('pvp.create') }}" method="POST" class="p-6">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-300 mb-3">Choisissez votre deck</label>
                            
                            @if($decks->isEmpty())
                                <div class="text-center py-8">
                                    <div class="text-4xl mb-2">📭</div>
                                    <p class="text-gray-400 mb-4">Vous n'avez pas encore de deck</p>
                                    <a href="{{ route('decks.create') }}" class="text-purple-400 hover:text-purple-300">
                                        Créer un deck →
                                    </a>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($decks as $deck)
                                        <label class="flex items-center gap-4 p-4 bg-white/5 border-2 border-white/10 rounded-xl cursor-pointer hover:border-green-500/50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-500/10">
                                            <input type="radio" name="deck_id" value="{{ $deck->id }}" class="w-5 h-5 text-green-600 bg-white/10 border-white/30" {{ $deck->is_active ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <div class="font-bold text-white">{{ $deck->name }}</div>
                                                <div class="text-sm text-gray-400">{{ $deck->cards_count }} cartes</div>
                                            </div>
                                            @if($deck->is_active)
                                                <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-bold rounded">ACTIF</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if($decks->isNotEmpty())
                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold text-lg rounded-xl hover:from-green-500 hover:to-emerald-500 transition transform hover:scale-[1.02]">
                                ⚔️ Créer une partie
                            </button>
                        @endif
                    </form>
                </div>

                <!-- Rejoindre une partie -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600">
                        <h3 class="text-xl font-bold text-white">🎯 Rejoindre une partie</h3>
                        <p class="text-white/70 text-sm">Parties en attente d'adversaire</p>
                    </div>

                    <div class="p-6">
                        @if($waitingBattles->isEmpty())
                            <div class="text-center py-8">
                                <div class="text-4xl mb-2">😴</div>
                                <p class="text-gray-400">Aucune partie en attente</p>
                                <p class="text-gray-500 text-sm mt-2">Créez la vôtre !</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($waitingBattles as $battle)
                                    <div class="p-4 bg-white/5 border border-white/10 rounded-xl">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                                    {{ strtoupper(substr($battle->player1->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-white">{{ $battle->player1->name }}</div>
                                                    <div class="text-sm text-gray-400">{{ $battle->player1Deck->name }}</div>
                                                </div>
                                            </div>
                                            <div class="text-gray-500 text-sm">
                                                {{ $battle->created_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        <!-- Formulaire pour rejoindre -->
                                        <form action="{{ route('pvp.join', $battle) }}" method="POST" class="flex gap-3">
                                            @csrf
                                            <select name="deck_id" required class="flex-1 bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm">
                                                @foreach($decks as $deck)
                                                    <option value="{{ $deck->id }}" {{ $deck->is_active ? 'selected' : '' }}>
                                                        {{ $deck->name }} ({{ $deck->cards_count }} cartes)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-500 transition">
                                                Rejoindre
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Joueurs en ligne (optionnel) -->
            <div class="mt-10 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600 to-pink-600">
                    <h3 class="text-xl font-bold text-white">👥 Joueurs récemment actifs</h3>
                </div>

                <div class="p-6">
                    @if($onlinePlayers->isEmpty())
                        <p class="text-gray-400 text-center">Aucun autre joueur actif pour le moment</p>
                    @else
                        <div class="flex flex-wrap gap-3">
                            @foreach($onlinePlayers as $player)
                                <div class="flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full">
                                    <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                                    <span class="text-white font-medium">{{ $player->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Auto-refresh pour voir les nouvelles parties -->
    <script>
        setTimeout(() => {
            window.location.reload();
        }, 30000); // Refresh toutes les 30 secondes
    </script>
</x-app-layout>