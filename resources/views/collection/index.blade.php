<x-app-layout>
    <style>
        /* ========================================
           FOND COSMOS
        ======================================== */
        .cosmos-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(ellipse at 20% 80%, rgba(120, 0, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 0, 100, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(0, 100, 255, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #0a0a1a 0%, #1a0a2a 50%, #0a1a2a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* ========================================
           STAT CARDS
        ======================================== */
        .stat-card {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.25rem;
            text-align: center;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), transparent);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            border-color: var(--accent-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* ========================================
           PROGRESS BAR
        ======================================== */
        .progress-container {
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .progress-bar-bg {
            height: 12px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 6px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #7C3AED, #A78BFA, #FFD700);
            border-radius: 6px;
            transition: width 1s ease;
            box-shadow: 0 0 10px rgba(167, 139, 250, 0.5);
        }

        /* ========================================
           CARTES COLLECTION (HOLO EFFECT)
        ======================================== */
        .holo-card-mini {
            position: relative;
            background: linear-gradient(145deg, var(--color1, #333), var(--color2, #555));
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .holo-card-mini:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* Effet holo par rareté */
        .holo-card-mini.rarity-common {
            border-color: rgba(156, 163, 175, 0.3);
        }

        .holo-card-mini.rarity-rare {
            border-color: rgba(59, 130, 246, 0.5);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }

        .holo-card-mini.rarity-epic {
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
        }

        .holo-card-mini.rarity-legendary {
            border-color: rgba(255, 215, 0, 0.6);
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.4);
            animation: legendaryPulse 2s ease-in-out infinite;
        }

        @keyframes legendaryPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.3); }
            50% { box-shadow: 0 0 35px rgba(255, 215, 0, 0.6); }
        }

        .holo-card-mini:hover.rarity-rare {
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
        }

        .holo-card-mini:hover.rarity-epic {
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.4);
        }

        .holo-card-mini:hover.rarity-legendary {
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.5);
        }

        /* Image de la carte */
        .card-mini-image {
            position: relative;
            height: 180px;
            width: 100%;
            overflow: hidden;
        }

        .card-mini-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .holo-card-mini:hover .card-mini-image img {
            transform: scale(1.1);
        }

        .card-mini-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            background: linear-gradient(135deg, rgba(0,0,0,0.3), rgba(0,0,0,0.5));
        }

        /* Badge quantité */
        .card-mini-quantity {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: #FFD700;
            font-weight: 800;
            font-size: 0.85rem;
            padding: 4px 10px;
            border-radius: 20px;
            border: 1px solid rgba(255, 215, 0, 0.5);
        }

        /* Badge rareté */
        .card-mini-rarity {
            position: absolute;
            bottom: 10px;
            left: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rarity-common {
            background: linear-gradient(135deg, #6B7280, #9CA3AF);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
        }

        .rarity-rare {
            background: linear-gradient(135deg, #2563EB, #3B82F6);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }

        .rarity-epic {
            background: linear-gradient(135deg, #7C3AED, #A78BFA);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(168, 85, 247, 0.5);
        }

        .rarity-legendary {
            background: linear-gradient(135deg, #F59E0B, #FFD700);
            color: #1a1a2e;
            padding: 3px 8px;
            border-radius: 4px;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.6);
            animation: legendaryBadge 1.5s ease-in-out infinite;
        }

        @keyframes legendaryBadge {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 215, 0, 0.5); }
            50% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.8); }
        }

        /* Overlay holo */
        .card-mini-holo {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                transparent 0%,
                rgba(255, 255, 255, 0.1) 45%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0.1) 55%,
                transparent 100%
            );
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .holo-card-mini:hover .card-mini-holo {
            opacity: 1;
            animation: holoShine 0.8s ease;
        }

        @keyframes holoShine {
            0% { transform: translateX(-100%) rotate(25deg); }
            100% { transform: translateX(100%) rotate(25deg); }
        }

        /* Infos de la carte */
        .card-mini-info {
            padding: 1rem;
            background: rgba(0, 0, 0, 0.6);
        }

        .card-mini-name {
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-mini-faction {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.75rem;
        }

        .faction-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Stats de la carte */
        .card-mini-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .mini-stat {
            text-align: center;
            padding: 6px 4px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
        }

        .mini-stat-value {
            display: block;
            font-size: 0.9rem;
            font-weight: 700;
        }

        .mini-stat-label {
            display: block;
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
        }

        .stat-hp .mini-stat-value { color: #EF4444; }
        .stat-def .mini-stat-value { color: #3B82F6; }
        .stat-pwr .mini-stat-value { color: #F59E0B; }
        .stat-cost .mini-stat-value { color: #A78BFA; }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 640px) {
            .card-mini-image {
                height: 150px;
            }

            .card-mini-stats {
                grid-template-columns: repeat(4, 1fr);
                gap: 2px;
            }

            .mini-stat {
                padding: 4px 2px;
            }

            .mini-stat-value {
                font-size: 0.8rem;
            }
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        <!-- Fond Cosmos -->
        <div class="cosmos-bg">
            <div class="stars"></div>
        </div>

        <!-- Contenu -->
        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            <!-- Header de page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <span class="text-4xl">🎴</span>
                        Ma Collection
                    </h1>
                    <p class="text-gray-400 mt-1">Toutes vos cartes obtenues</p>
                </div>
                <a href="{{ route('shop.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Boutique
                </a>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="stat-card" style="--accent-color: #A78BFA;">
                    <div class="text-3xl font-bold text-purple-400">{{ $stats['unique_cards'] }}</div>
                    <div class="text-sm text-gray-400 mt-1">Cartes uniques</div>
                </div>
                <div class="stat-card" style="--accent-color: #818CF8;">
                    <div class="text-3xl font-bold text-indigo-400">{{ $stats['total_cards'] }}</div>
                    <div class="text-sm text-gray-400 mt-1">Total de cartes</div>
                </div>
                <div class="stat-card" style="--accent-color: #34D399;">
                    <div class="text-3xl font-bold text-green-400">{{ $stats['completion'] }}%</div>
                    <div class="text-sm text-gray-400 mt-1">Complétion</div>
                </div>
                <div class="stat-card" style="--accent-color: #FFD700;">
                    <div class="text-3xl font-bold text-yellow-400">{{ $stats['by_rarity']['legendary'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400 mt-1">Légendaires</div>
                </div>
                <div class="stat-card" style="--accent-color: #A78BFA;">
                    <div class="text-3xl font-bold text-purple-500">{{ $stats['by_rarity']['epic'] ?? 0 }}</div>
                    <div class="text-sm text-gray-400 mt-1">Épiques</div>
                </div>
            </div>

            <!-- Barre de progression -->
            <div class="progress-container mb-8">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-semibold text-white flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Progression de la collection
                    </span>
                    <span class="text-sm text-gray-400">
                        <span class="text-purple-400 font-bold">{{ $stats['unique_cards'] }}</span> 
                        / {{ $stats['total_available'] }} cartes
                    </span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: {{ $stats['completion'] }}%"></div>
                </div>
            </div>

            <!-- Collection -->
            @if($collection->isEmpty())
                <!-- État vide -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-12 text-center">
                    <div class="text-7xl mb-6">📭</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Collection vide</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Achetez des boosters dans la boutique pour obtenir vos premières cartes et commencer votre collection !
                    </p>
                    <a href="{{ route('shop.index') }}" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Aller à la boutique
                    </a>
                </div>
            @else
                <!-- Grille des cartes -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 rounded-3xl">
                    @foreach($collection as $card)
                        <a href="{{ route('collection.show', $card) }}" class="block">
                            <div class="holo-card-mini rarity-{{ $card->rarity }}"
                                 style="--color1: {{ $card->faction->color_primary ?? '#6366f1' }}; --color2: {{ $card->faction->color_secondary ?? '#8b5cf6' }};">
                                
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
                                        <span class="faction-dot" style="background: {{ $card->faction->color_primary ?? '#6366f1' }};"></span>
                                        {{ $card->faction->name ?? 'Sans faction' }}
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
                    @endforeach
                </div>

                <!-- Pagination si nécessaire -->
                @if(method_exists($collection, 'links'))
                    <div class="mt-8">
                        {{ $collection->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>