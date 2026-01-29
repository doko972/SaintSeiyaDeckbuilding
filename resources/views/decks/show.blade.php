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
                radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255, 255, 255, 0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {

            0%,
            100% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }
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
           DECK HEADER
        ======================================== */
        .deck-header {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            overflow: hidden;
        }

        .deck-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #7C3AED, #6366F1, #7C3AED);
        }

        .deck-badge-active {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #22C55E, #16A34A);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.4);
        }

        /* ========================================
           ACTION BUTTONS
        ======================================== */
        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .action-btn.back {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-color: rgba(255, 255, 255, 0.2);
            padding: 2rem 3rem;
        }

        .action-btn.back:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .action-btn.edit {
            background: rgba(251, 191, 36, 0.2);
            color: #FBBF24;
            border-color: rgba(251, 191, 36, 0.3);
            padding: 8px 48px;
        }

        .action-btn.edit:hover {
            background: rgba(251, 191, 36, 0.3);
            border-color: rgba(251, 191, 36, 0.5);
            transform: translateY(-2px);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
            border-color: rgba(239, 68, 68, 0.3);
            padding: 8px 48px;
        }

        .action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.3);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-2px);
        }

        /* ========================================
           CARTES (HOLO EFFECT)
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

            0%,
            100% {
                box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
            }

            50% {
                box-shadow: 0 0 35px rgba(255, 215, 0, 0.6);
            }
        }

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
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5));
        }

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
        }

        .card-mini-holo {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.1) 45%,
                    rgba(255, 255, 255, 0.3) 50%,
                    rgba(255, 255, 255, 0.1) 55%,
                    transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .holo-card-mini:hover .card-mini-holo {
            opacity: 1;
            animation: holoShine 0.8s ease;
        }

        @keyframes holoShine {
            0% {
                transform: translateX(-100%) rotate(25deg);
            }

            100% {
                transform: translateX(100%) rotate(25deg);
            }
        }

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

        .stat-hp .mini-stat-value {
            color: #EF4444;
        }

        .stat-def .mini-stat-value {
            color: #3B82F6;
        }

        .stat-pwr .mini-stat-value {
            color: #F59E0B;
        }

        .stat-cost .mini-stat-value {
            color: #A78BFA;
        }

        /* ========================================
           SECTION TITLE
        ======================================== */
        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1.5rem;
        }

        .section-title svg {
            width: 24px;
            height: 24px;
            color: #A78BFA;
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
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-3xl font-bold text-white">{{ $deck->name }}</h1>
                        @if ($deck->is_active)
                            <span class="deck-badge-active">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Actif
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-400">Détails et composition du deck</p>
                </div>
                <a href="{{ route('decks.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour aux decks
                </a>
            </div>

            <!-- Deck Header -->
            <div class="deck-header mb-8">
                <div class="flex flex-wrap justify-between items-start gap-6">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white mb-2 flex items-center gap-3">
                            <span class="text-3xl">🎴</span>
                            {{ $deck->name }}
                        </h2>
                        @if ($deck->description)
                            <p class="text-gray-400 max-w-2xl">{{ $deck->description }}</p>
                        @else
                            <p class="text-gray-500 italic">Aucune description</p>
                        @endif
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('decks.edit', $deck) }}" class="action-btn edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Modifier
                        </a>
                        <form action="{{ route('decks.destroy', $deck) }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce deck ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="stat-card" style="--accent-color: #A78BFA;">
                    <div class="text-3xl font-bold text-purple-400">{{ $deck->cards->sum('pivot.quantity') }}</div>
                    <div class="text-sm text-gray-400 mt-1">Cartes totales</div>
                </div>
                <div class="stat-card" style="--accent-color: #818CF8;">
                    <div class="text-3xl font-bold text-indigo-400">{{ $deck->cards->count() }}</div>
                    <div class="text-sm text-gray-400 mt-1">Cartes uniques</div>
                </div>
                <div class="stat-card" style="--accent-color: #FBBF24;">
                    <div class="text-3xl font-bold text-yellow-400">
                        {{ $deck->cards->sum(fn($c) => $c->cost * $c->pivot->quantity) }}</div>
                    <div class="text-sm text-gray-400 mt-1">Coût total</div>
                </div>
                <div class="stat-card" style="--accent-color: #34D399;">
                    <div class="text-3xl font-bold text-green-400">
                        {{ $deck->cards->count() > 0 ? round($deck->cards->avg('power')) : 0 }}</div>
                    <div class="text-sm text-gray-400 mt-1">Puissance moy.</div>
                </div>
            </div>

            <!-- Cartes du deck -->
            @if ($deck->cards->isEmpty())
                <!-- État vide -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-12 text-center">
                    <div class="text-7xl mb-6">🃏</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Deck vide</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Ajoutez des cartes à ce deck pour pouvoir affronter vos adversaires !
                    </p>
                    <a href="{{ route('decks.edit', $deck) }}"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier le deck
                    </a>
                </div>
            @else
                <!-- Titre section -->
                <h3 class="section-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Cartes du deck
                </h3>

                <!-- Grille des cartes -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                    @foreach ($deck->cards as $card)
                        <a href="{{ route('cards.show', $card) }}" class="block">
                            <div class="holo-card-mini rarity-{{ $card->rarity }}"
                                style="--color1: {{ $card->faction->color_primary ?? '#6366f1' }}; --color2: {{ $card->faction->color_secondary ?? '#8b5cf6' }};">

                                <!-- Image -->
                                <div class="card-mini-image">
                                    @if ($card->image_primary)
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

                                    <!-- Overlay Holo -->
                                    <div class="card-mini-holo"></div>
                                </div>

                                <!-- Infos -->
                                <div class="card-mini-info">
                                    <h3 class="card-mini-name">{{ $card->name }}</h3>
                                    <p class="card-mini-faction">
                                        <span class="faction-dot"
                                            style="background: {{ $card->faction->color_primary ?? '#6366f1' }};"></span>
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
            @endif

        </div>
    </div>
</x-app-layout>
