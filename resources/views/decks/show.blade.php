<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $deck->name }}
                @if($deck->is_active)
                    <span class="ml-2 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">ACTIF</span>
                @endif
            </h2>
            <a href="{{ route('decks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header du deck -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 mb-8">
                <div class="flex flex-wrap justify-between items-start gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">{{ $deck->name }}</h1>
                        @if($deck->description)
                            <p class="text-gray-400">{{ $deck->description }}</p>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('decks.edit', $deck) }}" class="px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg hover:bg-yellow-600 transition">
                            ✏️ Modifier
                        </a>
                        <form action="{{ route('decks.destroy', $deck) }}" method="POST"
                              onsubmit="return confirm('Supprimer ce deck ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-purple-400">{{ $deck->cards->sum('pivot.quantity') }}</div>
                    <div class="text-sm text-gray-400">Cartes totales</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-indigo-400">{{ $deck->cards->count() }}</div>
                    <div class="text-sm text-gray-400">Cartes uniques</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-400">{{ $deck->cards->sum(fn($c) => $c->cost * $c->pivot->quantity) }}</div>
                    <div class="text-sm text-gray-400">Coût total</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-green-400">{{ $deck->cards->count() > 0 ? round($deck->cards->avg('power')) : 0 }}</div>
                    <div class="text-sm text-gray-400">Puissance moy.</div>
                </div>
            </div>

            <!-- Cartes du deck -->
            @if($deck->cards->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-12 text-center">
                    <div class="text-6xl mb-4">🃏</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Deck vide</h3>
                    <p class="text-gray-400 mb-6">Ajoutez des cartes à ce deck pour pouvoir jouer !</p>
                    <a href="{{ route('decks.edit', $deck) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition">
                        ✏️ Modifier le deck
                    </a>
                </div>
            @else
                <h3 class="text-xl font-bold text-white mb-4">Cartes du deck</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($deck->cards as $card)
                        <div class="card-grid-item group">
                            <a href="{{ route('cards.show', $card) }}" class="block">
                                <div class="holo-card-mini rarity-{{ $card->rarity }}"
                                     style="--color1: {{ $card->faction->color_primary }}; --color2: {{ $card->faction->color_secondary }};">
                                    
                                    <!-- Image -->
                                    <div class="card-mini-image">
                                        @if($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                        @else
                                            <div class="card-mini-placeholder">
                                                <span>🃏</span>
                                            </div>
                                        @endif

                                        <!-- Badge Quantité -->
                                        <div class="card-mini-quantity">x{{ $card->pivot->quantity }}</div>

                                        <!-- Badge Rareté -->
                                        <div class="card-mini-rarity">
                                            @switch($card->rarity)
                                                @case('common') <span class="rarity-common">Commune</span> @break
                                                @case('rare') <span class="rarity-rare">Rare</span> @break
                                                @case('epic') <span class="rarity-epic">Épique</span> @break
                                                @case('legendary') <span class="rarity-legendary">Légendaire</span> @break
                                            @endswitch
                                        </div>

                                        <!-- Overlay Holo -->
                                        <div class="card-mini-holo"></div>
                                    </div>

                                    <!-- Infos -->
                                    <div class="card-mini-info">
                                        <h3 class="card-mini-name">{{ $card->name }}</h3>
                                        <p class="card-mini-faction">
                                            <span class="faction-dot" style="background: {{ $card->faction->color_primary }};"></span>
                                            {{ $card->faction->name }}
                                        </p>

                                        <!-- Stats -->
                                        <div class="card-mini-stats">
                                            <div class="mini-stat stat-hp">
                                                <span class="mini-stat-value">{{ $card->health_points }}</span>
                                                <span class="mini-stat-label">PV</span>
                                            </div>
                                            <div class="mini-stat stat-def">
                                                <span class="mini-stat-value">{{ $card->defense }}</span>
                                                <span class="mini-stat-label">DEF</span>
                                            </div>
                                            <div class="mini-stat stat-pwr">
                                                <span class="mini-stat-value">{{ $card->power }}</span>
                                                <span class="mini-stat-label">PWR</span>
                                            </div>
                                            <div class="mini-stat stat-cost">
                                                <span class="mini-stat-value">{{ $card->cost }}</span>
                                                <span class="mini-stat-label">Coût</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>