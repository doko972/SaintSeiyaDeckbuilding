<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
        <!-- Fond Sanctuaire -->
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500/20 backdrop-blur-md border border-green-500/50 text-green-200 rounded-xl">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/20 backdrop-blur-md border border-red-500/50 text-red-200 rounded-xl">
                    ❌ {{ session('error') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-6 p-4 bg-blue-500/20 backdrop-blur-md border border-blue-500/50 text-blue-200 rounded-xl">
                    ℹ️ {{ session('info') }}
                </div>
            @endif

            <!-- Introduction -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8 mb-8">
                <div class="text-center mb-6">
                    <h3 class="text-4xl font-bold text-white mb-3">
                        Bienvenue dans Saint Seiya Deckbuilding !
                    </h3>
                    <p class="text-xl text-gray-300">
                        Commencez votre aventure en choisissant votre héros
                    </p>
                </div>

                <div class="bg-white/5 backdrop-blur-sm rounded-xl border border-white/10 p-6 mb-4">
                    <h4 class="text-lg font-bold text-white mb-4">📦 Votre Starter Pack contient :</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 text-gray-300">
                            <span class="text-2xl">⚔️</span>
                            <div>
                                <strong class="text-amber-400">1 Chevalier de Bronze Rare</strong>
                                <p class="text-sm text-gray-400">Celui que vous allez choisir</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-300">
                            <span class="text-2xl">👥</span>
                            <div>
                                <strong class="text-gray-400">{{ $starterDetails['cards'][1]['quantity'] }} Soldats du Sanctuaire</strong>
                                <p class="text-sm text-gray-500">Common</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-300">
                            <span class="text-2xl">🛡️</span>
                            <div>
                                <strong class="text-gray-400">{{ $starterDetails['cards'][2]['quantity'] }} Gardes d'Argent</strong>
                                <p class="text-sm text-gray-500">Common</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 text-gray-300">
                            <span class="text-2xl">✨</span>
                            <div>
                                <strong class="text-gray-400">{{ $starterDetails['cards'][3]['quantity'] }} Cartes de Soutien</strong>
                                <p class="text-sm text-gray-500">Common</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-white/10 flex justify-between items-center">
                        <div class="text-white">
                            <span class="text-gray-400">Total :</span>
                            <strong class="text-2xl ml-2">{{ $starterDetails['total_cards'] }} cartes</strong>
                        </div>
                        <div class="text-white">
                            <span class="text-gray-400">Bonus :</span>
                            <strong class="text-2xl text-yellow-400 ml-2">{{ $starterDetails['coins'] }} 💰</strong>
                        </div>
                    </div>
                </div>

                <div class="text-center text-sm text-red-400 bg-red-500/10 border border-red-500/30 rounded-lg p-3">
                    ⚠️ <strong>Ce choix est définitif.</strong> Choisissez avec soin !
                </div>
            </div>

            <!-- Sélection des Bronzes -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8">
                <h3 class="text-2xl font-bold text-white mb-6 text-center">
                    ⚔️ Sélectionnez votre héros :
                </h3>

                @if($bronzeCards->isEmpty())
                    <div class="p-6 bg-yellow-500/20 backdrop-blur-md border border-yellow-500/50 text-yellow-200 rounded-xl text-center">
                        Aucun Chevalier de Bronze disponible. Veuillez contacter un administrateur.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                        @foreach($bronzeCards as $bronze)
                            <form method="POST" action="{{ route('starter-pack.select') }}" 
                                  onsubmit="return confirm('⚠️ Êtes-vous sûr de choisir {{ $bronze->name }} ?\n\nCe choix est définitif et ne pourra pas être modifié.')">
                                @csrf
                                <input type="hidden" name="bronze_card_id" value="{{ $bronze->id }}">
                                
                                <button type="submit" class="w-full text-left">
                                    <!-- Carte avec effet holo -->
                                    <div class="card-wrapper interactive">
                                        <div class="holo-card rarity-{{ $bronze->rarity }}" 
                                             style="--color1: {{ $bronze->faction->color_primary }}; --color2: {{ $bronze->faction->color_secondary }}; height: 450px;">
                                            
                                            <!-- Effets holographiques -->
                                            <div class="holo-overlay"></div>
                                            <div class="holo-sparkle"></div>
                                            <div class="holo-shine"></div>
                                            
                                            <!-- Contenu de la carte -->
                                            <div class="card-content">
                                                
                                                <!-- Header -->
                                                <div class="card-header">
                                                    <div class="card-title">
                                                        <h4 class="card-name">{{ $bronze->name }}</h4>
                                                        <p class="card-faction">{{ $bronze->faction->name }}</p>
                                                    </div>
                                                    <div class="card-cost">
                                                        <span>{{ $bronze->cost }}</span>
                                                    </div>
                                                </div>

                                                <!-- Image -->
                                                <div class="card-image-container" style="flex: 1;">
                                                    @if($bronze->image_primary)
                                                        <img src="{{ Storage::url($bronze->image_primary) }}" 
                                                             alt="{{ $bronze->name }}" 
                                                             class="card-image">
                                                    @else
                                                        <div class="card-image-placeholder">
                                                            <span>⚔️</span>
                                                        </div>
                                                    @endif

                                                    <!-- Badges -->
                                                    <div class="card-badge card-badge-grade">
                                                        Grade {{ $bronze->grade }}
                                                    </div>
                                                    <div class="card-badge card-badge-rarity">
                                                        {{ ucfirst($bronze->rarity) }}
                                                    </div>
                                                </div>

                                                <!-- Tags -->
                                                <div class="card-tags">
                                                    <span class="card-tag tag-{{ $bronze->armor_type }}">
                                                        {{ ucfirst($bronze->armor_type) }}
                                                    </span>
                                                    <span class="card-tag tag-{{ $bronze->element }}">
                                                        {{ ucfirst($bronze->element) }}
                                                    </span>
                                                </div>

                                                <!-- Stats -->
                                                <div class="card-stats">
                                                    <div class="stat stat-hp">
                                                        <span class="stat-value">{{ $bronze->health_points }}</span>
                                                        <span class="stat-label">HP</span>
                                                    </div>
                                                    <div class="stat stat-end">
                                                        <span class="stat-value">{{ $bronze->endurance }}</span>
                                                        <span class="stat-label">END</span>
                                                    </div>
                                                    <div class="stat stat-def">
                                                        <span class="stat-value">{{ $bronze->defense }}</span>
                                                        <span class="stat-label">DEF</span>
                                                    </div>
                                                    <div class="stat stat-pwr">
                                                        <span class="stat-value">{{ $bronze->power }}</span>
                                                        <span class="stat-label">PWR</span>
                                                    </div>
                                                    <div class="stat stat-cos">
                                                        <span class="stat-value">{{ $bronze->cosmos }}</span>
                                                        <span class="stat-label">COS</span>
                                                    </div>
                                                </div>

                                                <!-- Capacité passive -->
                                                @if($bronze->passive_ability_name)
                                                    <div class="card-passive">
                                                        <div class="passive-label">Capacité Passive</div>
                                                        <div class="passive-name">{{ $bronze->passive_ability_name }}</div>
                                                        <p class="passive-desc">{{ $bronze->passive_ability_description }}</p>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </form>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Lien vers détails -->
            <div class="mt-8 text-center">
                <a href="{{ route('starter-pack.details') }}" 
                   class="inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-lg hover:bg-white/20 transition">
                    📖 En savoir plus sur le Starter Pack
                </a>
            </div>

        </div>
    </div>

    <style>
        /* Ajout d'un effet de pulsation pour attirer l'attention */
        .holo-card {
            cursor: pointer;
        }
        
        .holo-card:hover {
            transform: translateY(-10px) scale(1.02);
        }
        
        button[type="submit"] {
            display: block;
            width: 100%;
        }
    </style>
</x-app-layout>