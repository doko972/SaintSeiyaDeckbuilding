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
           CARTES - DESIGN TCG PRO
        ======================================== */
        .holo-card-mini {
            position: relative;
            background: #1a1a2e;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 3px solid rgba(255, 255, 255, 0.1);
            aspect-ratio: 2.5 / 4;
        }

        .holo-card-mini:hover {
            transform: translateY(-10px) scale(1.03);
        }

        /* Bordures par rareté */
        .holo-card-mini.rarity-common {
            border-color: #6B7280;
        }

        .holo-card-mini.rarity-rare {
            border-color: #3B82F6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        .holo-card-mini.rarity-epic {
            border-color: #8B5CF6;
            box-shadow: 0 0 25px rgba(139, 92, 246, 0.5);
        }

        .holo-card-mini.rarity-legendary {
            border-color: #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6), 0 0 60px rgba(255, 100, 0, 0.4);
            animation: legendaryPulse 2s ease-in-out infinite;
        }

        .holo-card-mini.rarity-mythic {
            border: 3px solid transparent;
            background: linear-gradient(#1a1a2e, #1a1a2e) padding-box,
                        linear-gradient(135deg, #FF006E, #8338EC, #3A86FF, #FF006E) border-box;
            box-shadow: 0 0 40px rgba(131, 56, 236, 0.6), 0 0 80px rgba(255, 0, 110, 0.4);
            animation: mythicPulse 3s ease-in-out infinite;
        }

        @keyframes legendaryPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.8), 0 0 60px rgba(255, 100, 0, 0.5); }
        }

        @keyframes mythicPulse {
            0%, 100% { box-shadow: 0 0 40px rgba(131, 56, 236, 0.6), 0 0 80px rgba(255, 0, 110, 0.4); }
            50% { box-shadow: 0 0 60px rgba(58, 134, 255, 0.8), 0 0 100px rgba(131, 56, 236, 0.6); }
        }

        .holo-card-mini:hover.rarity-rare {
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.4), 0 0 30px rgba(59, 130, 246, 0.3);
        }

        .holo-card-mini:hover.rarity-epic {
            box-shadow: 0 20px 40px rgba(168, 85, 247, 0.5), 0 0 35px rgba(139, 92, 246, 0.4);
        }

        .holo-card-mini:hover.rarity-legendary {
            box-shadow: 0 20px 40px rgba(255, 215, 0, 0.6), 0 0 50px rgba(255, 215, 0, 0.5);
        }

        .holo-card-mini:hover.rarity-mythic {
            box-shadow: 0 20px 40px rgba(255, 0, 110, 0.6), 0 0 60px rgba(131, 56, 236, 0.5);
        }

        /* Contenu de la carte */
        .card-mini-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Image de fond - pleine carte */
        .card-mini-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .card-mini-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            transition: transform 0.4s ease;
        }

        .holo-card-mini:hover .card-mini-image img {
            transform: scale(1.05);
        }

        .card-mini-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background: linear-gradient(180deg, var(--color1), var(--color2));
        }

        /* Overlay dégradé pour lisibilité */
        .card-mini-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                180deg,
                rgba(0, 0, 0, 0.5) 0%,
                transparent 20%,
                transparent 50%,
                rgba(0, 0, 0, 0.8) 100%
            );
            z-index: 2;
            pointer-events: none;
        }

        /* Badge quantité */
        .card-mini-quantity {
            position: absolute;
            top: 8px;
            left: 8px;
            background: rgba(0, 0, 0, 0.85);
            color: #FFD700;
            font-weight: 800;
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            border: 1px solid rgba(255, 215, 0, 0.5);
            z-index: 10;
            backdrop-filter: blur(4px);
        }

        /* Badge rareté - en haut à droite */
        .card-mini-rarity {
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
        }

        .card-mini-rarity span {
            display: block;
            font-size: 0.55rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 8px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
        }

        .rarity-badge-common {
            background: linear-gradient(135deg, #6B7280, #4B5563);
            color: white;
            border: 1px solid #9CA3AF;
        }

        .rarity-badge-rare {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: white;
            border: 1px solid #60A5FA;
        }

        .rarity-badge-epic {
            background: linear-gradient(135deg, #8B5CF6, #6D28D9);
            color: white;
            border: 1px solid #A78BFA;
        }

        .rarity-badge-legendary {
            background: linear-gradient(135deg, #FFD700, #FF8C00, #FF4500);
            color: white;
            border: 1px solid #FBBF24;
            animation: legendaryBadgePulse 1.5s ease-in-out infinite;
        }

        .rarity-badge-mythic {
            background: linear-gradient(135deg, #FF006E, #8338EC, #3A86FF);
            background-size: 200% 200%;
            color: white;
            border: 1px solid #FF006E;
            animation: mythicGradient 3s ease infinite;
        }

        @keyframes legendaryBadgePulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 215, 0, 0.6); }
            50% { box-shadow: 0 0 20px rgba(255, 215, 0, 1), 0 0 30px rgba(255, 140, 0, 0.6); }
        }

        @keyframes mythicGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Header avec nom et coût - en bas au-dessus des stats */
        .card-mini-header {
            position: absolute;
            bottom: 52px;
            left: 0;
            right: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 8px;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-mini-name {
            font-size: 0.7rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100px;
        }

        .card-mini-faction {
            font-size: 0.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1px;
        }

        .card-mini-cost {
            background: linear-gradient(145deg, var(--color1), var(--color2));
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            width: 26px;
            height: 26px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 900;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.3);
            flex-shrink: 0;
        }

        /* Stats en overlay - en bas */
        .card-mini-stats-wrapper {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 6px;
        }

        .card-mini-stats {
            display: flex;
            justify-content: space-between;
            gap: 4px;
        }

        .mini-stat {
            flex: 1;
            text-align: center;
            padding: 4px 2px;
            border-radius: 6px;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .mini-stat.stat-hp {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.85), rgba(153, 27, 27, 0.85));
            border-color: rgba(248, 113, 113, 0.3);
        }
        .mini-stat.stat-def {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.85), rgba(29, 78, 216, 0.85));
            border-color: rgba(96, 165, 250, 0.3);
        }
        .mini-stat.stat-pwr {
            background: linear-gradient(135deg, rgba(234, 88, 12, 0.85), rgba(194, 65, 12, 0.85));
            border-color: rgba(251, 146, 60, 0.3);
        }
        .mini-stat.stat-cos {
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.85), rgba(91, 33, 182, 0.85));
            border-color: rgba(167, 139, 250, 0.3);
        }

        .mini-stat-value {
            display: block;
            font-size: 0.8rem;
            font-weight: 900;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
            line-height: 1;
        }

        .mini-stat-label {
            display: block;
            font-size: 0.45rem;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        /* Overlay holo */
        .card-mini-holo {
            position: absolute;
            inset: 0;
            z-index: 5;
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

        /* Effets holo par rareté */
        .holo-card-mini.rarity-legendary .card-mini-holo-effect {
            position: absolute;
            inset: 0;
            z-index: 3;
            background:
                url("https://assets.codepen.io/13471/sparkles.gif"),
                linear-gradient(125deg,
                    rgba(255, 0, 132, 0.15) 15%,
                    rgba(252, 164, 0, 0.15) 30%,
                    rgba(255, 255, 0, 0.1) 40%,
                    rgba(0, 255, 138, 0.08) 60%,
                    rgba(0, 207, 255, 0.15) 70%,
                    rgba(204, 76, 250, 0.15) 85%
                );
            background-size: 160%;
            mix-blend-mode: color-dodge;
            pointer-events: none;
            opacity: 0.6;
            animation: holoSparkle 4s ease infinite;
        }

        .holo-card-mini.rarity-epic .card-mini-holo-effect {
            position: absolute;
            inset: 0;
            z-index: 3;
            background: linear-gradient(
                115deg,
                transparent 0%,
                rgba(139, 92, 246, 0.2) 25%,
                transparent 50%,
                rgba(168, 85, 247, 0.2) 75%,
                transparent 100%
            );
            mix-blend-mode: color-dodge;
            pointer-events: none;
            opacity: 0.5;
        }

        @keyframes holoSparkle {
            0%, 100% { opacity: 0.5; background-position: 50% 50%; }
            50% { opacity: 0.8; background-position: 60% 60%; }
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
                                <div class="card-mini-content">
                                    <!-- Image de fond -->
                                    <div class="card-mini-image">
                                        @if ($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                        @else
                                            <div class="card-mini-placeholder">🃏</div>
                                        @endif
                                    </div>

                                    <!-- Overlay dégradé -->
                                    <div class="card-mini-overlay"></div>

                                    <!-- Effet holo par rareté -->
                                    @if(in_array($card->rarity, ['epic', 'legendary', 'mythic']))
                                        <div class="card-mini-holo-effect"></div>
                                    @endif

                                    <!-- Badge Quantité -->
                                    <div class="card-mini-quantity">x{{ $card->pivot->quantity }}</div>

                                    <!-- Badge Rareté -->
                                    <div class="card-mini-rarity">
                                        @switch($card->rarity)
                                            @case('common') <span class="rarity-badge-common">Commune</span> @break
                                            @case('rare') <span class="rarity-badge-rare">Rare</span> @break
                                            @case('epic') <span class="rarity-badge-epic">Épique</span> @break
                                            @case('legendary') <span class="rarity-badge-legendary">Légendaire</span> @break
                                            @case('mythic') <span class="rarity-badge-mythic">Mythique</span> @break
                                        @endswitch
                                    </div>

                                    <!-- Overlay Holo (au hover) -->
                                    <div class="card-mini-holo"></div>

                                    <!-- Header avec nom et coût -->
                                    <div class="card-mini-header">
                                        <div>
                                            <div class="card-mini-name">{{ $card->name }}</div>
                                            <div class="card-mini-faction">{{ $card->faction->name ?? 'Sans faction' }}</div>
                                        </div>
                                        <div class="card-mini-cost">{{ $card->cost }}</div>
                                    </div>

                                    <!-- Stats en overlay -->
                                    <div class="card-mini-stats-wrapper">
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
                                            <div class="mini-stat stat-cos">
                                                <span class="mini-stat-value">{{ $card->cosmos }}</span>
                                                <span class="mini-stat-label">COS</span>
                                            </div>
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
