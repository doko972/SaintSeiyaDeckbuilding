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
           CARTES COLLECTION - DESIGN TCG PRO
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

        /* Badge niveau de fusion */
        .card-mini-fusion-level {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            font-weight: 800;
            font-size: 0.7rem;
            padding: 3px 8px;
            border-radius: 12px;
            border: 1px solid rgba(16, 185, 129, 0.5);
            z-index: 11;
            backdrop-filter: blur(4px);
            box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
        }

        .card-mini-fusion-level.high-level {
            background: linear-gradient(135deg, #F59E0B, #D97706);
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 12px rgba(245, 158, 11, 0.5);
        }

        .card-mini-fusion-level.max-level {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
            animation: maxLevelPulse 1.5s ease-in-out infinite;
        }

        @keyframes maxLevelPulse {
            0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.8); }
        }

        /* Stats boostées */
        .mini-stat-boosted .mini-stat-value {
            color: #34D399;
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

        /* Cartes non possédées (grisées) */
        .holo-card-mini.not-owned {
            filter: grayscale(100%) brightness(0.5);
            opacity: 0.6;
            border-color: rgba(100, 100, 100, 0.3) !important;
            box-shadow: none !important;
            animation: none !important;
        }

        .holo-card-mini.not-owned:hover {
            filter: grayscale(80%) brightness(0.6);
            opacity: 0.8;
            transform: translateY(-5px) scale(1.01);
        }

        .holo-card-mini.not-owned .card-mini-holo {
            display: none;
        }

        /* Badge "Non obtenue" */
        .card-not-owned-badge {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.85);
            color: #9CA3AF;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid rgba(156, 163, 175, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 15;
            backdrop-filter: blur(4px);
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
           RESPONSIVE
        ======================================== */
        @media (max-width: 640px) {
            .card-mini-stats {
                gap: 2px;
            }

            .mini-stat {
                padding: 3px 1px;
                border-radius: 4px;
            }

            .mini-stat-value {
                font-size: 0.7rem;
            }

            .mini-stat-label {
                font-size: 0.4rem;
            }

            .card-mini-name {
                font-size: 0.6rem;
                max-width: 70px;
            }

            .card-mini-faction {
                font-size: 0.4rem;
            }

            .card-mini-header {
                bottom: 42px;
                padding: 4px 6px;
            }

            .card-mini-cost {
                width: 22px;
                height: 22px;
                font-size: 0.7rem;
            }

            .card-mini-stats-wrapper {
                padding: 4px;
            }

            .card-mini-rarity span {
                font-size: 0.45rem;
                padding: 2px 5px;
            }

            .card-mini-quantity {
                font-size: 0.65rem;
                padding: 2px 6px;
            }

            .card-not-owned-badge {
                font-size: 0.5rem;
                padding: 4px 8px;
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
                    <p class="text-gray-400 mt-1">Toutes les cartes du jeu - Les cartes grisées ne sont pas encore dans votre collection</p>
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
            @if($allCards->isEmpty())
                <!-- État vide -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-12 text-center">
                    <div class="text-7xl mb-6">📭</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Aucune carte disponible</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Il n'y a pas encore de cartes dans le jeu.
                    </p>
                </div>
            @else
                <!-- Grille des cartes -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6 rounded-3xl">
                    @foreach($allCards as $card)
                        <a href="{{ route('collection.show', $card) }}" class="block">
                            <div class="holo-card-mini rarity-{{ $card->rarity }} {{ !$card->owned ? 'not-owned' : '' }}"
                                 style="--color1: {{ $card->faction->color_primary ?? '#6366f1' }}; --color2: {{ $card->faction->color_secondary ?? '#8b5cf6' }};">
                                <div class="card-mini-content">
                                    <!-- Image de fond -->
                                    <div class="card-mini-image">
                                        @if($card->image_primary)
                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                        @else
                                            <div class="card-mini-placeholder">🃏</div>
                                        @endif
                                    </div>

                                    <!-- Overlay dégradé -->
                                    <div class="card-mini-overlay"></div>

                                    <!-- Effet holo par rareté -->
                                    @if($card->owned && in_array($card->rarity, ['epic', 'legendary', 'mythic']))
                                        <div class="card-mini-holo-effect"></div>
                                    @endif

                                    <!-- Badge Niveau de Fusion -->
                                    @if($card->owned && $card->fusion_level > 1)
                                        <div class="card-mini-fusion-level {{ $card->fusion_level >= 7 ? ($card->fusion_level >= 10 ? 'max-level' : 'high-level') : '' }}"
                                             title="+{{ $card->bonus_percent }}% stats">
                                            +{{ $card->fusion_level - 1 }}
                                        </div>
                                    @endif

                                    <!-- Badge Quantité (déplacé si fusion level) -->
                                    @if($card->owned && $card->owned_quantity > 1)
                                        <div class="card-mini-quantity" style="{{ $card->fusion_level > 1 ? 'top: 36px;' : '' }}">
                                            x{{ $card->owned_quantity }}
                                        </div>
                                    @endif

                                    <!-- Badge "Non obtenue" -->
                                    @if(!$card->owned)
                                        <div class="card-not-owned-badge">Non obtenue</div>
                                    @endif

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
                                    @if($card->owned)
                                        <div class="card-mini-holo"></div>
                                    @endif

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
                                            <div class="mini-stat stat-hp {{ $card->owned && $card->fusion_level > 1 ? 'mini-stat-boosted' : '' }}">
                                                <span class="mini-stat-value">{{ $card->owned ? $card->boosted_hp : $card->health_points }}</span>
                                                <span class="mini-stat-label">PV</span>
                                            </div>
                                            <div class="mini-stat stat-def {{ $card->owned && $card->fusion_level > 1 ? 'mini-stat-boosted' : '' }}">
                                                <span class="mini-stat-value">{{ $card->owned ? $card->boosted_defense : $card->defense }}</span>
                                                <span class="mini-stat-label">DEF</span>
                                            </div>
                                            <div class="mini-stat stat-pwr {{ $card->owned && $card->fusion_level > 1 ? 'mini-stat-boosted' : '' }}">
                                                <span class="mini-stat-value">{{ $card->owned ? $card->boosted_power : $card->power }}</span>
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