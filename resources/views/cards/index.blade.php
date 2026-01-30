<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
        <!-- Fond Sanctuaire -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt=""
            class="w-full h-full object-cover opacity-[0.10]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="flex justify-end items-center mb-3">
                    <a href="{{ route('admin.cards.create') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                        ➕ Nouvelle Carte
                    </a>
                </div>
            @endif

            <!-- Filtres -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-6 mb-8 border border-white/20">
                <form method="GET" action="{{ route('cards.index') }}" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <!-- Recherche -->
                    <div class="col-span-2 md:col-span-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white placeholder-gray-400 focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <!-- Faction -->
                    <select name="faction"
                        class="bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white focus:border-purple-500">
                        <option value="">Toutes les factions</option>
                        @foreach (\App\Models\Faction::orderBy('name')->get() as $faction)
                            <option value="{{ $faction->id }}"
                                {{ request('faction') == $faction->id ? 'selected' : '' }}>
                                {{ $faction->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Rareté -->
                    <select name="rarity"
                        class="bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white focus:border-purple-500">
                        <option value="">Toutes raretés</option>
                        <option value="common" {{ request('rarity') == 'common' ? 'selected' : '' }}>Commune</option>
                        <option value="rare" {{ request('rarity') == 'rare' ? 'selected' : '' }}>Rare</option>
                        <option value="epic" {{ request('rarity') == 'epic' ? 'selected' : '' }}>Épique</option>
                        <option value="legendary" {{ request('rarity') == 'legendary' ? 'selected' : '' }}>Légendaire</option>
                        <option value="mythic" {{ request('rarity') == 'mythic' ? 'selected' : '' }}>Mythique</option>
                    </select>

                    <!-- Armure -->
                    <select name="armor"
                        class="bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white focus:border-purple-500">
                        <option value="">Toutes armures</option>
                        <option value="bronze" {{ request('armor') == 'bronze' ? 'selected' : '' }}>Bronze</option>
                        <option value="silver" {{ request('armor') == 'silver' ? 'selected' : '' }}>Argent</option>
                        <option value="gold" {{ request('armor') == 'gold' ? 'selected' : '' }}>Or</option>
                        <option value="divine" {{ request('armor') == 'divine' ? 'selected' : '' }}>Divin</option>
                    </select>

                    <!-- Boutons -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            🔍 Filtrer
                        </button>
                        <a href="{{ route('cards.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ✖
                        </a>
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="flex flex-col gap-4 mb-6">
                <p class="text-gray-400">
                    <span class="text-white font-bold">{{ $cards->count() }}</span> carte(s) trouvée(s)
                </p>
                <div class="flex flex-wrap gap-2">
                    <span
                        class="px-3 py-1 bg-gray-600 text-gray-300 text-sm rounded-full">{{ \App\Models\Card::where('rarity', 'common')->count() }}
                        Communes</span>
                    <span
                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">{{ \App\Models\Card::where('rarity', 'rare')->count() }}
                        Rares</span>
                    <span
                        class="px-3 py-1 bg-purple-600 text-white text-sm rounded-full">{{ \App\Models\Card::where('rarity', 'epic')->count() }}
                        Épiques</span>
                    <span
                        class="px-3 py-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm rounded-full">{{ \App\Models\Card::where('rarity', 'legendary')->count() }}
                        Légendaires</span>
                    <span
                        class="px-3 py-1 bg-gradient-to-r from-pink-500 via-orange-400 to-cyan-400 text-white text-sm rounded-full">{{ \App\Models\Card::where('rarity', 'mythic')->count() }}
                        Mythiques</span>
                </div>
            </div>

            <!-- Grille de cartes -->
            @if ($cards->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-12 text-center border border-white/20">
                    <div class="text-6xl mb-4">🃏</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucune carte trouvée</h3>
                    <p class="text-gray-400">Modifiez vos filtres ou créez de nouvelles cartes.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach ($cards as $card)
                        <div class="card-grid-item group">
                            <a href="{{ route('cards.show', $card) }}" class="block">
                                <div class="holo-card-mini rarity-{{ $card->rarity }}"
                                    style="--color1: {{ $card->faction->color_primary }}; --color2: {{ $card->faction->color_secondary }};">

                                    <!-- Image -->
                                    <div class="card-mini-image">
                                        @if ($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}"
                                                alt="{{ $card->name }}">
                                        @else
                                            <div class="card-mini-placeholder">
                                                <span>🃏</span>
                                            </div>
                                        @endif

                                        <!-- Badge Rareté -->
                                        <div class="card-mini-rarity">
                                            @switch($card->rarity)
                                                @case('common')
                                                    <span class="rarity-common">Commune</span>
                                                @break

                                                @case('rare')
                                                    <span class="rarity-rare">Rare</span>
                                                @break

                                                @case('epic')
                                                    <span class="rarity-epic">Épique</span>
                                                @break

                                                @case('legendary')
                                                    <span class="rarity-legendary">Légendaire</span>
                                                @break

                                                @case('mythic')
                                                    <span class="rarity-mythic">Mythique</span>
                                                @break
                                            @endswitch
                                        </div>

                                        <!-- Badge Grade -->
                                        <div class="card-mini-grade">Gr. {{ $card->grade }}</div>

                                        <!-- Overlay Holo -->
                                        <div class="card-mini-holo"></div>
                                    </div>

                                    <!-- Infos -->
                                    <div class="card-mini-info">
                                        <h3 class="card-mini-name">{{ $card->name }}</h3>
                                        <p class="card-mini-faction">
                                            <span class="faction-dot"
                                                style="background: {{ $card->faction->color_primary }};"></span>
                                            {{ $card->faction->name }}
                                        </p>

                                        <!-- Tags -->
                                        <div class="card-mini-tags">
                                            <span class="tag-armor-{{ $card->armor_type }}">
                                                @switch($card->armor_type)
                                                    @case('bronze')
                                                        🥉
                                                    @break

                                                    @case('silver')
                                                        🥈
                                                    @break

                                                    @case('gold')
                                                        🥇
                                                    @break

                                                    @case('divine')
                                                        👑
                                                    @break
                                                @endswitch
                                                {{ ucfirst($card->armor_type) }}
                                            </span>
                                            <span class="tag-element-{{ $card->element }}">
                                                @switch($card->element)
                                                    @case('fire')
                                                        🔥
                                                    @break

                                                    @case('water')
                                                        💧
                                                    @break

                                                    @case('ice')
                                                        ❄️
                                                    @break

                                                    @case('thunder')
                                                        ⚡
                                                    @break

                                                    @case('darkness')
                                                        🌑
                                                    @break

                                                    @case('light')
                                                        ✨
                                                    @break
                                                @endswitch
                                            </span>
                                        </div>

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

                            <!-- Actions Admin -->
                            @if (auth()->user()->isAdmin())
                                <div class="card-mini-actions">
                                    <a href="{{ route('cards.show', $card) }}" class="action-btn action-view"
                                        title="Voir">
                                        👁️
                                    </a>
                                    <a href="{{ route('admin.cards.edit', $card) }}" class="action-btn action-edit"
                                        title="Modifier">
                                        ✏️
                                    </a>
                                    <form action="{{ route('admin.cards.destroy', $card) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Supprimer {{ $card->name }} ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-delete" title="Supprimer">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
