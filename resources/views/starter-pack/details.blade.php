<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails du Starter Pack') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
        <!-- Fond Sanctuaire -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Retour -->
            <div class="mb-6">
                <a href="{{ route('starter-pack.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-lg hover:bg-white/20 transition">
                    ← Retour à la sélection
                </a>
            </div>

            <!-- Titre -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8 mb-8 text-center">
                <h3 class="text-4xl font-bold text-white mb-3">🎁 Contenu du Starter Pack</h3>
                <p class="text-xl text-gray-300">
                    Tout ce que vous recevez pour démarrer votre aventure !
                </p>
            </div>

            <!-- Grille principale -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                
                <!-- Composition du deck -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                    <h4 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>📦</span> Composition du deck
                    </h4>
                    
                    <div class="space-y-4">
                        @foreach($starterDetails['cards'] as $cardInfo)
                            <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-white/10 p-4 flex items-center justify-between hover:bg-white/10 transition">
                                <div>
                                    <h5 class="font-bold text-white text-lg">
                                        {{ $cardInfo['name'] }}
                                    </h5>
                                    <span class="text-sm px-2 py-1 rounded capitalize
                                        @if($cardInfo['rarity'] === 'rare') bg-blue-500/20 text-blue-300 border border-blue-500/30
                                        @else bg-gray-500/20 text-gray-300 border border-gray-500/30
                                        @endif">
                                        {{ $cardInfo['rarity'] }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-4xl font-bold text-amber-400">
                                        ×{{ $cardInfo['quantity'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-6 p-4 bg-gradient-to-r from-amber-500/20 to-orange-500/20 backdrop-blur-sm rounded-xl border border-amber-500/30">
                            <div class="flex justify-between items-center">
                                <strong class="text-white text-lg">Total de cartes</strong>
                                <strong class="text-4xl text-amber-400">{{ $starterDetails['total_cards'] }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monnaie et avantages -->
                <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-6">
                    <h4 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                        <span>💰</span> Bonus de démarrage
                    </h4>
                    
                    <div class="space-y-4">
                        <div class="p-6 bg-gradient-to-br from-yellow-500/20 to-amber-600/20 backdrop-blur-sm rounded-xl border border-yellow-500/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h5 class="font-bold text-white text-xl mb-1">Coins de départ</h5>
                                    <p class="text-sm text-gray-300">
                                        Pour acheter des boosters dans la boutique
                                    </p>
                                </div>
                                <span class="text-5xl font-bold text-yellow-400">
                                    {{ $starterDetails['coins'] }} 💰
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6 bg-white/5 backdrop-blur-sm rounded-xl border border-white/10">
                            <h5 class="font-bold text-white text-lg mb-3 flex items-center gap-2">
                                <span>🎯</span> Que faire avec vos coins ?
                            </h5>
                            <ul class="space-y-2 text-gray-300">
                                <li class="flex items-start gap-2">
                                    <span class="text-green-400 mt-1">✓</span>
                                    <span>Acheter des boosters dans la boutique</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-400 mt-1">✓</span>
                                    <span>Obtenir de nouvelles cartes rares et légendaires</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-400 mt-1">✓</span>
                                    <span>Améliorer votre collection</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="text-green-400 mt-1">✓</span>
                                    <span>Construire des decks plus puissants</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu des Bronzes -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8 mb-6">
                <h4 class="text-2xl font-bold text-white mb-6 text-center flex items-center justify-center gap-2">
                    <span>⚔️</span> Chevaliers de Bronze disponibles
                </h4>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($bronzeCards as $bronze)
                        <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-white/10 p-4 text-center hover:bg-white/10 hover:border-amber-500/50 transition group">
                            <div class="w-full aspect-square bg-gradient-to-br rounded-xl flex items-center justify-center mb-3 overflow-hidden"
                                 style="background: linear-gradient(135deg, {{ $bronze->faction->color_primary }}, {{ $bronze->faction->color_secondary }});">
                                @if($bronze->image_primary)
                                    <img src="{{ Storage::url($bronze->image_primary) }}" 
                                         alt="{{ $bronze->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <span class="text-5xl opacity-50">⚔️</span>
                                @endif
                            </div>
                            
                            <h5 class="font-bold text-white mb-1 text-sm">
                                {{ $bronze->name }}
                            </h5>
                            <p class="text-xs text-gray-400 capitalize">
                                <span class="inline-block px-2 py-1 rounded tag-{{ $bronze->element }}">
                                    {{ $bronze->element }}
                                </span>
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Conseils -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8 mb-6">
                <h4 class="text-2xl font-bold text-white mb-6 text-center flex items-center justify-center gap-2">
                    <span>💡</span> Conseils pour bien débuter
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-gradient-to-br from-green-500/10 to-emerald-600/10 backdrop-blur-sm rounded-xl border border-green-500/30 p-6">
                        <div class="text-4xl mb-3">🎯</div>
                        <h5 class="font-bold text-green-400 text-lg mb-2">
                            1. Choisissez votre style
                        </h5>
                        <p class="text-gray-300 text-sm">
                            Chaque Bronze a des forces uniques. Sélectionnez celui qui correspond à votre stratégie de combat.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-blue-500/10 to-indigo-600/10 backdrop-blur-sm rounded-xl border border-blue-500/30 p-6">
                        <div class="text-4xl mb-3">🛒</div>
                        <h5 class="font-bold text-blue-400 text-lg mb-2">
                            2. Complétez votre deck
                        </h5>
                        <p class="text-gray-300 text-sm">
                            Utilisez vos 500 coins pour acheter 2-3 boosters et renforcer immédiatement votre collection.
                        </p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-500/10 to-pink-600/10 backdrop-blur-sm rounded-xl border border-purple-500/30 p-6">
                        <div class="text-4xl mb-3">⚔️</div>
                        <h5 class="font-bold text-purple-400 text-lg mb-2">
                            3. Entrez en combat !
                        </h5>
                        <p class="text-gray-300 text-sm">
                            Testez votre deck en affrontant l'IA et gagnez plus de coins pour acheter d'autres boosters.
                        </p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="text-center">
                <a href="{{ route('starter-pack.index') }}" 
                   class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-lg font-bold rounded-xl hover:from-amber-400 hover:to-orange-500 transition transform hover:scale-105 shadow-lg shadow-amber-500/25">
                    ⚔️ Choisir mon Chevalier de Bronze
                </a>
            </div>

        </div>
    </div>

    <style>
        .tag-fire { color: #EF4444; }
        .tag-water { color: #3B82F6; }
        .tag-ice { color: #06B6D4; }
        .tag-thunder { color: #EAB308; }
        .tag-darkness { color: #9CA3AF; }
        .tag-light { color: #FCD34D; }
    </style>
</x-app-layout>