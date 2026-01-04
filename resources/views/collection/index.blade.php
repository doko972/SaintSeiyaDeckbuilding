<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ma Collection') }}
            </h2>
            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                🛒 Boutique
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-purple-400">{{ $stats['unique_cards'] }}</div>
                    <div class="text-sm text-gray-400">Cartes uniques</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-indigo-400">{{ $stats['total_cards'] }}</div>
                    <div class="text-sm text-gray-400">Total de cartes</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-green-400">{{ $stats['completion'] }}%</div>
                    <div class="text-sm text-gray-400">Complétion</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-yellow-400">{{ $stats['by_rarity']['legendary'] }}</div>
                    <div class="text-sm text-gray-400">Légendaires</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 text-center">
                    <div class="text-3xl font-bold text-purple-500">{{ $stats['by_rarity']['epic'] }}</div>
                    <div class="text-sm text-gray-400">Épiques</div>
                </div>
            </div>

            <!-- Barre de progression -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-4 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-300">Progression de la collection</span>
                    <span class="text-sm text-gray-400">{{ $stats['unique_cards'] }} / {{ $stats['total_available'] }}</span>
                </div>
                <div class="h-4 bg-gray-700 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full transition-all duration-500"
                         style="width: {{ $stats['completion'] }}%"></div>
                </div>
            </div>

            <!-- Collection -->
            @if($collection->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Collection vide</h3>
                    <p class="text-gray-400 mb-6">Achetez des boosters pour obtenir vos premières cartes !</p>
                    <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-105">
                        🛒 Aller à la boutique
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($collection as $card)
                        <div class="card-grid-item group">
                            <a href="{{ route('collection.show', $card) }}" class="block">
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
                                        @if($card->pivot->quantity > 1)
                                            <div class="card-mini-quantity">x{{ $card->pivot->quantity }}</div>
                                        @endif

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