<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $faction->name }}
            </h2>
            <a href="{{ route('factions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

<div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
    <!-- Fond d'écran bannière Sanctuaire -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/baniere.webp') }}" 
             alt="" 
             class="w-full h-full object-cover opacity-[0.15]">
        <!-- Overlay dégradé pour lisibilité -->
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/70 via-gray-900/50 to-gray-900/90"></div>
    </div>
    
    <!-- Dégradé de couleurs de la faction -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-30"
         style="background: radial-gradient(ellipse at 20% 30%, {{ $faction->color_primary }}40 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, {{ $faction->color_secondary }}30 0%, transparent 50%);"></div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Header de la faction -->
            <div class="relative rounded-2xl overflow-hidden mb-8"
                 style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});">
                
                <!-- Image de fond -->
                @if($faction->image)
                    <img src="{{ Storage::url($faction->image) }}" 
                         alt="{{ $faction->name }}"
                         class="absolute inset-0 w-full h-full object-cover opacity-30">
                @endif

                <div class="relative z-10 p-8">
                    <div class="flex flex-wrap items-center gap-6">
                        <!-- Icône/Couleur -->
                        <div class="w-24 h-24 rounded-2xl border-4 border-white/30 shadow-2xl overflow-hidden"
                             style="background: linear-gradient(145deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});">
                            @if($faction->image)
                                <img src="{{ Storage::url($faction->image) }}" alt="{{ $faction->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="text-4xl">🏛️</span>
                                </div>
                            @endif
                        </div>

                        <!-- Infos -->
                        <div class="flex-1">
                            <h1 class="text-4xl font-bold text-white mb-2">{{ $faction->name }}</h1>
                            @if($faction->description)
                                <p class="text-white/80 text-lg">{{ $faction->description }}</p>
                            @endif
                        </div>

                        <!-- Stats -->
                        <div class="flex gap-4">
                            <div class="bg-black/30 backdrop-blur-sm rounded-xl px-6 py-4 text-center">
                                <div class="text-3xl font-bold text-white">{{ $faction->cards->count() }}</div>
                                <div class="text-white/70 text-sm">Cartes</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Admin -->
                    @if(auth()->user()->isAdmin())
                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('admin.factions.edit', $faction) }}" 
                               class="px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg hover:bg-yellow-600 transition">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('admin.factions.destroy', $faction) }}" method="POST"
                                  onsubmit="return confirm('Supprimer cette faction ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cartes de la faction -->
            <h3 class="text-2xl font-bold text-white mb-6">🃏 Cartes de cette faction</h3>

            @if($faction->cards->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-12 text-center">
                    <div class="text-6xl mb-4">🃏</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucune carte</h3>
                    <p class="text-gray-400">Cette faction n'a pas encore de cartes.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($faction->cards as $card)
                        <div class="card-grid-item group">
                            <a href="{{ route('cards.show', $card) }}" class="block">
                                <div class="holo-card-mini rarity-{{ $card->rarity }}"
                                     style="--color1: {{ $faction->color_primary }}; --color2: {{ $faction->color_secondary }};">
                                    
                                    <!-- Image -->
                                    <div class="card-mini-image">
                                        @if($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                        @else
                                            <div class="card-mini-placeholder">
                                                <span>🃏</span>
                                            </div>
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

                                        <!-- Badge Grade -->
                                        <div class="card-mini-grade">Gr. {{ $card->grade }}</div>

                                        <!-- Overlay Holo -->
                                        <div class="card-mini-holo"></div>
                                    </div>

                                    <!-- Infos -->
                                    <div class="card-mini-info">
                                        <h3 class="card-mini-name">{{ $card->name }}</h3>
                                        <p class="card-mini-faction">
                                            <span class="faction-dot" style="background: {{ $faction->color_primary }};"></span>
                                            {{ $faction->name }}
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