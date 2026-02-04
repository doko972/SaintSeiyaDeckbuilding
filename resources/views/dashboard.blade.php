<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <!-- {{ __('Dashboard') }} -->
        </h2>
    </x-slot>

    <style>
        /* ========================================
           FOND COSMOS ANIMÉ
        ======================================== */
        .cosmos-bg {
            position: absolute;
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

        /* Étoiles scintillantes */
        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent),
                radial-gradient(2px 2px at 300px 150px, #fff, transparent),
                radial-gradient(1px 1px at 350px 60px, rgba(255,255,255,0.7), transparent),
                radial-gradient(2px 2px at 400px 200px, #fff, transparent);
            background-size: 450px 250px;
            animation: twinkle 5s ease-in-out infinite;
        }

        .stars-2 {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(1px 1px at 50px 80px, #fff, transparent),
                radial-gradient(2px 2px at 100px 150px, rgba(255,215,0,0.8), transparent),
                radial-gradient(1px 1px at 180px 30px, #fff, transparent),
                radial-gradient(2px 2px at 250px 180px, rgba(255,215,0,0.6), transparent),
                radial-gradient(1px 1px at 320px 100px, #fff, transparent);
            background-size: 400px 220px;
            animation: twinkle 7s ease-in-out infinite reverse;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Constellation en arrière-plan */
        .constellation {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 800 600'%3E%3Cpath d='M100,100 L200,150 L180,280 L250,350 L200,450 M200,150 L350,120 L400,200 L350,300 M400,200 L500,180 L550,250 L500,350 L400,380 M550,250 L650,200 L700,280 L650,380' stroke='%23FFD700' stroke-width='1' fill='none'/%3E%3Ccircle cx='100' cy='100' r='3' fill='%23FFD700'/%3E%3Ccircle cx='200' cy='150' r='4' fill='%23FFD700'/%3E%3Ccircle cx='180' cy='280' r='3' fill='%23FFD700'/%3E%3Ccircle cx='250' cy='350' r='3' fill='%23FFD700'/%3E%3Ccircle cx='200' cy='450' r='3' fill='%23FFD700'/%3E%3Ccircle cx='350' cy='120' r='4' fill='%23FFD700'/%3E%3Ccircle cx='400' cy='200' r='5' fill='%23FFD700'/%3E%3Ccircle cx='350' cy='300' r='3' fill='%23FFD700'/%3E%3Ccircle cx='500' cy='180' r='4' fill='%23FFD700'/%3E%3Ccircle cx='550' cy='250' r='4' fill='%23FFD700'/%3E%3Ccircle cx='500' cy='350' r='3' fill='%23FFD700'/%3E%3Ccircle cx='400' cy='380' r='3' fill='%23FFD700'/%3E%3Ccircle cx='650' cy='200' r='4' fill='%23FFD700'/%3E%3Ccircle cx='700' cy='280' r='5' fill='%23FFD700'/%3E%3Ccircle cx='650' cy='380' r='3' fill='%23FFD700'/%3E%3C/svg%3E");
            background-size: cover;
            animation: constellationFloat 30s ease-in-out infinite;
        }

        @keyframes constellationFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(2deg); }
        }

        /* ========================================
           RANG DU CHEVALIER
        ======================================== */
        .rank-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
        }

        .rank-bronze {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
            box-shadow: 0 0 20px rgba(205, 127, 50, 0.5);
        }

        .rank-argent {
            background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
            color: #1a1a2e;
            box-shadow: 0 0 20px rgba(192, 192, 192, 0.5);
        }

        .rank-or {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a2e;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            animation: goldenGlow 2s ease-in-out infinite;
        }

        .rank-divin {
            background: linear-gradient(135deg, #E0B0FF, #9400D3, #FFD700);
            color: white;
            box-shadow: 0 0 40px rgba(148, 0, 211, 0.6);
            animation: divineGlow 1.5s ease-in-out infinite;
        }

        @keyframes goldenGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.8); }
        }

        @keyframes divineGlow {
            0%, 100% { box-shadow: 0 0 30px rgba(148, 0, 211, 0.5); }
            50% { box-shadow: 0 0 50px rgba(255, 215, 0, 0.7); }
        }

        /* ========================================
           HERO BANNER
        ======================================== */
        .hero-banner {
            position: relative;
            background: linear-gradient(135deg, rgba(88, 28, 135, 0.8), rgba(124, 58, 237, 0.6), rgba(88, 28, 135, 0.8));
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 20px;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 215, 0, 0.1), transparent, rgba(255, 215, 0, 0.1), transparent);
            animation: rotateCosmos 10s linear infinite;
        }

        @keyframes rotateCosmos {
            100% { transform: rotate(360deg); }
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }

        /* ========================================
           COSMOS BAR (Barre d'expérience)
        ======================================== */
        .cosmos-bar-container {
            background: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 3px;
            border: 1px solid rgba(255, 215, 0, 0.3);
        }

        .cosmos-bar {
            height: 8px;
            border-radius: 8px;
            background: linear-gradient(90deg, #7C3AED, #A78BFA, #FFD700);
            box-shadow: 0 0 10px rgba(167, 139, 250, 0.5);
            transition: width 1s ease;
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
            padding: 1.5rem;
            text-align: center;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
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
            transform: translateY(-8px);
            border-color: var(--accent-color);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 30px rgba(var(--glow-rgb), 0.2);
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.2) rotate(10deg);
            filter: drop-shadow(0 0 10px var(--accent-color));
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            transition: all 0.4s ease;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent-color), var(--accent-color-light));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        /* ========================================
           ACTION CARDS (Boutons)
        ======================================== */
        .action-card {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem;
            border-radius: 16px;
            text-decoration: none;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .action-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .action-card:hover::before {
            opacity: 1;
        }

        .action-card:hover {
            transform: translateY(-5px) scale(1.02);
        }

        .action-card:hover .action-icon {
            animation: cosmosFlare 0.6s ease;
        }

        @keyframes cosmosFlare {
            0% { transform: scale(1); }
            50% { transform: scale(1.3); filter: brightness(1.5); }
            100% { transform: scale(1.1); }
        }

        .action-icon {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .action-label {
            font-weight: 600;
            color: white;
            font-size: 0.95rem;
        }

        /* ========================================
           WIN/LOSS RATIO
        ======================================== */
        .ratio-bar-container {
            display: flex;
            height: 12px;
            border-radius: 6px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.3);
        }

        .ratio-wins {
            background: linear-gradient(90deg, #10B981, #34D399);
            transition: width 1s ease;
        }

        .ratio-losses {
            background: linear-gradient(90deg, #EF4444, #F87171);
            transition: width 1s ease;
        }

        /* ========================================
           ZODIAC DECORATION
        ======================================== */
        .zodiac-ring {
            position: absolute;
            width: 300px;
            height: 300px;
            border: 1px solid rgba(255, 215, 0, 0.1);
            border-radius: 50%;
            animation: slowRotate 60s linear infinite;
        }

        .zodiac-ring-2 {
            width: 400px;
            height: 400px;
            animation-direction: reverse;
            animation-duration: 90s;
        }

        @keyframes slowRotate {
            100% { transform: rotate(360deg); }
        }

        /* ========================================
           RECENT CARDS SECTION
        ======================================== */
        .cards-scroll-container {
            display: flex;
            gap: 1rem;
            overflow-x: auto;
            overflow-y: visible;
            padding: 1rem 0.5rem 1rem 0.5rem;
            margin: -1rem -0.5rem;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.5) transparent;
            touch-action: pan-x;
            cursor: grab;
        }

        .cards-scroll-container:active {
            cursor: grabbing;
        }

        .cards-scroll-container::-webkit-scrollbar {
            height: 6px;
        }

        .cards-scroll-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .cards-scroll-container::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.5);
            border-radius: 3px;
        }

        .cards-scroll-container::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.7);
        }

        .recent-card {
            position: relative;
            width: 100px;
            height: 140px;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            background: linear-gradient(145deg, var(--card-color-1, #333), var(--card-color-2, #555));
            z-index: 1;
        }

        .recent-card:hover {
            transform: translateY(-10px) scale(1.1);
            border-color: rgba(255, 215, 0, 0.5);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 215, 0, 0.2);
            z-index: 10;
        }

        .recent-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recent-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 0.5rem;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        }

        .recent-card-name {
            font-size: 0.65rem;
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ========================================
           ADMIN SECTION
        ======================================== */
        .admin-section {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 16px;
            padding: 1.5rem;
        }

        .admin-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .admin-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-2px);
        }

        /* ========================================
           HERO STATS
        ======================================== */
        .hero-stats {
            display: flex;
            gap: 2rem;
        }

        .hero-stat {
            text-align: center;
        }

        .hero-stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .hero-stat-label {
            font-size: 0.875rem;
            color: #E9D5FF;
            margin-top: 0.25rem;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 768px) {
            .hero-banner {
                padding: 1.5rem !important;
            }

            .hero-banner > .relative > .flex {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .hero-banner h1 {
                font-size: 1.5rem !important;
            }

            .hero-banner .rank-badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
            }

            .hero-banner p {
                font-size: 0.9rem;
                margin-bottom: 1rem;
            }

            .hero-stats {
                display: flex;
                flex-direction: row;
                justify-content: center;
                gap: 1rem;
                background: rgba(0, 0, 0, 0.3);
                padding: 1rem;
                border-radius: 12px;
                margin-top: 0.5rem;
            }

            .hero-stat {
                flex: 1;
                min-width: 0;
            }

            .hero-stat-value {
                font-size: 1.2rem;
                flex-direction: column;
                gap: 0.2rem;
            }

            .hero-stat-label {
                font-size: 0.7rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-value {
                font-size: 2rem;
            }

            .action-card {
                padding: 1rem;
            }

            .action-icon {
                font-size: 2rem;
            }

            .cosmos-bar-container {
                max-width: 100%;
            }
        }

        @media (max-width: 480px) {
            .hero-banner {
                padding: 1rem !important;
            }

            .hero-banner h1 {
                font-size: 1.25rem !important;
            }

            .hero-stats {
                gap: 0.5rem;
                padding: 0.75rem;
            }

            .hero-stat-value {
                font-size: 1rem;
            }

            .hero-stat-label {
                font-size: 0.6rem;
            }
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        <!-- Fond Cosmos -->
        <div class="cosmos-bg">
            <div class="stars"></div>
            <div class="stars-2"></div>
            <div class="constellation"></div>
        </div>

        <!-- Anneaux du Zodiaque décoratifs -->
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none opacity-20">
            <div class="zodiac-ring"></div>
            <div class="zodiac-ring zodiac-ring-2"></div>
        </div>

        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

            <!-- ========================================
                 HERO BANNER
            ======================================== -->
            <div class="hero-banner p-8 mb-8">
                <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
                    <!-- Infos Joueur -->
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <h1 class="text-3xl md:text-4xl font-bold text-white">
                                Bienvenue, {{ auth()->user()->name }} !
                            </h1>
                            @php
                                $wins = auth()->user()->wins;
                                if ($wins >= 100) {
                                    $rank = ['name' => 'Chevalier Divin', 'class' => 'rank-divin', 'icon' => '👑'];
                                } elseif ($wins >= 50) {
                                    $rank = ['name' => 'Chevalier d\'Or', 'class' => 'rank-or', 'icon' => '🥇'];
                                } elseif ($wins >= 20) {
                                    $rank = ['name' => 'Chevalier d\'Argent', 'class' => 'rank-argent', 'icon' => '🥈'];
                                } else {
                                    $rank = ['name' => 'Chevalier de Bronze', 'class' => 'rank-bronze', 'icon' => '🥉'];
                                }
                            @endphp
                            <span class="rank-badge {{ $rank['class'] }}">
                                {{ $rank['icon'] }} {{ $rank['name'] }}
                            </span>
                        </div>
                        <p class="text-purple-200 text-lg mb-4">Brûle ton cosmos et deviens une légende !</p>
                        
                        <!-- Barre de progression vers prochain rang -->
                        @php
                            $nextRankWins = $wins >= 100 ? 100 : ($wins >= 50 ? 100 : ($wins >= 20 ? 50 : 20));
                            $prevRankWins = $wins >= 100 ? 100 : ($wins >= 50 ? 50 : ($wins >= 20 ? 20 : 0));
                            $progress = $wins >= 100 ? 100 : (($wins - $prevRankWins) / ($nextRankWins - $prevRankWins) * 100);
                        @endphp
                        <div class="max-w-md">
                            <div class="flex justify-between text-sm text-purple-300 mb-1">
                                <span>Cosmos</span>
                                <span>{{ $wins }}/{{ $nextRankWins }} victoires</span>
                            </div>
                            <div class="cosmos-bar-container">
                                <div class="cosmos-bar" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats principales -->
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="hero-stat-value text-yellow-400">
                                🪙 {{ number_format(auth()->user()->coins) }}
                            </div>
                            <div class="hero-stat-label">Pièces</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value text-green-400">
                                🏆 {{ auth()->user()->wins }}
                            </div>
                            <div class="hero-stat-label">Victoires</div>
                        </div>
                        <div class="hero-stat">
                            <div class="hero-stat-value text-red-400">
                                💀 {{ auth()->user()->losses }}
                            </div>
                            <div class="hero-stat-label">Défaites</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 RATIO VICTOIRES/DÉFAITES
            ======================================== -->
            @php
                $totalGames = auth()->user()->wins + auth()->user()->losses;
                $winRate = $totalGames > 0 ? (auth()->user()->wins / $totalGames * 100) : 50;
            @endphp
            @if($totalGames > 0)
            <div class="bg-white/5 backdrop-blur-md rounded-xl border border-white/10 p-4 mb-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-white font-semibold">📊 Ratio de combat</span>
                    <span class="text-sm">
                        <span class="text-green-400">{{ auth()->user()->wins }}V</span>
                        <span class="text-gray-400"> / </span>
                        <span class="text-red-400">{{ auth()->user()->losses }}D</span>
                        <span class="text-yellow-400 ml-2">({{ number_format($winRate, 1) }}%)</span>
                    </span>
                </div>
                <div class="ratio-bar-container">
                    <div class="ratio-wins" style="width: {{ $winRate }}%"></div>
                    <div class="ratio-losses" style="width: {{ 100 - $winRate }}%"></div>
                </div>
            </div>
            @endif

            <!-- ========================================
                 STATS CARDS
            ======================================== -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                <div class="stat-card" style="--accent-color: #818CF8; --accent-color-light: #A5B4FC; --glow-rgb: 129, 140, 248;">
                    <div class="stat-icon">🃏</div>
                    <div class="stat-value">{{ \App\Models\Card::count() }}</div>
                    <div class="stat-label">Cartes totales</div>
                </div>
                <div class="stat-card" style="--accent-color: #34D399; --accent-color-light: #6EE7B7; --glow-rgb: 52, 211, 153;">
                    <div class="stat-icon">📚</div>
                    <div class="stat-value">{{ auth()->user()->cards()->count() }}</div>
                    <div class="stat-label">Ma collection</div>
                </div>
                <div class="stat-card" style="--accent-color: #FBBF24; --accent-color-light: #FCD34D; --glow-rgb: 251, 191, 36;">
                    <div class="stat-icon">🎴</div>
                    <div class="stat-value">{{ auth()->user()->decks()->count() }}</div>
                    <div class="stat-label">Mes decks</div>
                </div>
                <div class="stat-card" style="--accent-color: #F87171; --accent-color-light: #FCA5A5; --glow-rgb: 248, 113, 113;">
                    <div class="stat-icon">⚔️</div>
                    <div class="stat-value">{{ auth()->user()->wins + auth()->user()->losses }}</div>
                    <div class="stat-label">Combats joués</div>
                </div>
            </div>

            <!-- ========================================
                 DERNIÈRES CARTES OBTENUES
            ======================================== -->
            @php
                $recentCards = auth()->user()->cards()->with('faction')->latest('user_cards.created_at')->take(6)->get();
            @endphp
            @if($recentCards->count() > 0)
            <div class="bg-white/5 backdrop-blur-md rounded-xl border border-white/10 p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        Dernières cartes obtenues
                    </h3>
                    <a href="{{ route('collection.index') }}" class="text-purple-400 hover:text-purple-300 text-sm transition">
                        Voir tout →
                    </a>
                </div>
                <div class="cards-scroll-container" id="recentCardsScroller">
                    @foreach($recentCards as $card)
                    <div class="recent-card flex-shrink-0" 
                         style="--card-color-1: {{ $card->faction->color_primary ?? '#333' }}; --card-color-2: {{ $card->faction->color_secondary ?? '#555' }};">
                        @if($card->image_primary)
                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">🃏</div>
                        @endif
                        <div class="recent-card-overlay">
                            <div class="recent-card-name">{{ $card->name }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- ========================================
                 ACTIONS RAPIDES
            ======================================== -->
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                Actions rapides
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <a href="{{ route('game.index') }}" class="action-card bg-gradient-to-br from-red-600/30 to-orange-600/30 border border-red-500/30 hover:border-red-400/60 hover:shadow-[0_0_30px_rgba(239,68,68,0.3)]">
                    <div class="action-icon">⚔️</div>
                    <div class="action-label">Combat</div>
                </a>
                <a href="{{ route('shop.index') }}" class="action-card bg-gradient-to-br from-yellow-600/30 to-amber-600/30 border border-yellow-500/30 hover:border-yellow-400/60 hover:shadow-[0_0_30px_rgba(251,191,36,0.3)]">
                    <div class="action-icon">🛒</div>
                    <div class="action-label">Boutique</div>
                </a>
                <a href="{{ route('collection.index') }}" class="action-card bg-gradient-to-br from-purple-600/30 to-pink-600/30 border border-purple-500/30 hover:border-purple-400/60 hover:shadow-[0_0_30px_rgba(168,85,247,0.3)]">
                    <div class="action-icon">📚</div>
                    <div class="action-label">Collection</div>
                </a>
                <a href="{{ route('decks.index') }}" class="action-card bg-gradient-to-br from-indigo-600/30 to-blue-600/30 border border-indigo-500/30 hover:border-indigo-400/60 hover:shadow-[0_0_30px_rgba(99,102,241,0.3)]">
                    <div class="action-icon">🎴</div>
                    <div class="action-label">Mes Decks</div>
                </a>
                <a href="{{ route('cards.index') }}" class="action-card bg-gradient-to-br from-emerald-600/30 to-green-600/30 border border-emerald-500/30 hover:border-emerald-400/60 hover:shadow-[0_0_30px_rgba(16,185,129,0.3)]">
                    <div class="action-icon">🃏</div>
                    <div class="action-label">Cartes</div>
                </a>
                <a href="{{ route('factions.index') }}" class="action-card bg-gradient-to-br from-cyan-600/30 to-teal-600/30 border border-cyan-500/30 hover:border-cyan-400/60 hover:shadow-[0_0_30px_rgba(6,182,212,0.3)]">
                    <div class="action-icon">🏛️</div>
                    <div class="action-label">Factions</div>
                </a>
            </div>

            <!-- ========================================
                 SECTION ADMIN
            ======================================== -->
            @if(auth()->user()->isAdmin())
            <div class="admin-section">
                <h4 class="text-lg font-bold text-red-400 mb-4 flex items-center gap-2">
                    ⚙️ Administration
                </h4>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.cards.create') }}" class="admin-btn">
                        <span>➕</span> Nouvelle carte
                    </a>
                    <a href="{{ route('admin.factions.create') }}" class="admin-btn">
                        <span>➕</span> Nouvelle faction
                    </a>
                    <a href="{{ route('admin.attacks.create') }}" class="admin-btn">
                        <span>➕</span> Nouvelle attaque
                    </a>
                    <a href="{{ route('admin.musics.index') }}" class="admin-btn">
                        <span>🎵</span> Gestion musiques
                    </a>
                    <a href="{{ route('admin.combos.index') }}" class="admin-btn">
                        <span>⚡</span> Gestion combos
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- ========================================
         MODAL BONUS QUOTIDIEN
    ======================================== -->
    <div id="dailyBonusModal" class="daily-bonus-modal">
        <div class="daily-bonus-content">
            <div class="daily-bonus-header">
                <h2>&#127922; Bonus Quotidien</h2>
                <p>Lancez le de pour gagner des pieces !</p>
            </div>

            <div class="dice-container" id="diceContainer">
                <div class="dice" id="dice">
                    <div class="dice-face front">1</div>
                    <div class="dice-face back">6</div>
                    <div class="dice-face right">3</div>
                    <div class="dice-face left">4</div>
                    <div class="dice-face top">2</div>
                    <div class="dice-face bottom">5</div>
                </div>
            </div>

            <div class="rewards-preview">
                <div class="reward-item"><span>&#9856;</span> = 100 po</div>
                <div class="reward-item"><span>&#9857;</span> = 200 po</div>
                <div class="reward-item"><span>&#9858;</span> = 300 po</div>
                <div class="reward-item"><span>&#9859;</span> = 400 po</div>
                <div class="reward-item"><span>&#9860;</span> = 500 po</div>
                <div class="reward-item"><span>&#9861;</span> = 600 po</div>
            </div>

            <button id="rollDiceBtn" class="roll-dice-btn" onclick="rollDice()">
                &#127922; Lancer le de !
            </button>

            <div id="resultContainer" class="result-container" style="display: none;">
                <div class="result-dice" id="resultDice"></div>
                <div class="result-coins" id="resultCoins"></div>
                <div class="result-balance" id="resultBalance"></div>
                <button class="close-modal-btn" onclick="closeDailyBonusModal()">
                    Super !
                </button>
            </div>
        </div>
    </div>

    <style>
        /* ========================================
           MODAL BONUS QUOTIDIEN
        ======================================== */
        .daily-bonus-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .daily-bonus-modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .daily-bonus-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(255, 215, 0, 0.4);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 450px;
            width: 90%;
            text-align: center;
            position: relative;
            box-shadow: 0 0 60px rgba(255, 215, 0, 0.2);
        }

        .daily-bonus-header h2 {
            font-size: 2rem;
            font-weight: 900;
            color: #FFD700;
            margin-bottom: 0.5rem;
        }

        .daily-bonus-header p {
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
        }

        /* ========================================
           DE 3D
        ======================================== */
        .dice-container {
            width: 120px;
            height: 120px;
            margin: 2rem auto;
            perspective: 600px;
            cursor: pointer;
        }

        .dice {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.1s ease;
        }

        .dice.rolling {
            animation: diceRoll 2s ease-out;
        }

        @keyframes diceRoll {
            0% { transform: rotateX(0) rotateY(0) rotateZ(0); }
            20% { transform: rotateX(180deg) rotateY(90deg) rotateZ(45deg); }
            40% { transform: rotateX(360deg) rotateY(180deg) rotateZ(90deg); }
            60% { transform: rotateX(540deg) rotateY(270deg) rotateZ(135deg); }
            80% { transform: rotateX(720deg) rotateY(360deg) rotateZ(180deg); }
            100% { transform: var(--final-rotation); }
        }

        .dice-face {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
            border: 3px solid #333;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 900;
            color: #1a1a3e;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.1);
        }

        .dice-face.front  { transform: translateZ(60px); }
        .dice-face.back   { transform: rotateY(180deg) translateZ(60px); }
        .dice-face.right  { transform: rotateY(90deg) translateZ(60px); }
        .dice-face.left   { transform: rotateY(-90deg) translateZ(60px); }
        .dice-face.top    { transform: rotateX(90deg) translateZ(60px); }
        .dice-face.bottom { transform: rotateX(-90deg) translateZ(60px); }

        /* Rotations finales pour chaque resultat */
        .dice.result-1 { transform: rotateX(0deg) rotateY(0deg); }
        .dice.result-2 { transform: rotateX(-90deg) rotateY(0deg); }
        .dice.result-3 { transform: rotateX(0deg) rotateY(-90deg); }
        .dice.result-4 { transform: rotateX(0deg) rotateY(90deg); }
        .dice.result-5 { transform: rotateX(90deg) rotateY(0deg); }
        .dice.result-6 { transform: rotateX(180deg) rotateY(0deg); }

        /* ========================================
           APERCU DES RECOMPENSES
        ======================================== */
        .rewards-preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .reward-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .reward-item span {
            font-size: 1.2rem;
        }

        /* ========================================
           BOUTONS
        ======================================== */
        .roll-dice-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border: none;
            border-radius: 12px;
            color: #1a1a3e;
            font-size: 1.2rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .roll-dice-btn:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
        }

        .roll-dice-btn:disabled {
            background: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* ========================================
           RESULTAT
        ======================================== */
        .result-container {
            margin-top: 1.5rem;
            animation: resultAppear 0.5s ease;
        }

        @keyframes resultAppear {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }

        .result-dice {
            font-size: 4rem;
            margin-bottom: 0.5rem;
        }

        .result-coins {
            font-size: 2rem;
            font-weight: 900;
            color: #FFD700;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .result-balance {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1.5rem;
        }

        .close-modal-btn {
            width: 100%;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #10B981, #059669);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .close-modal-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.5);
        }

        /* Animation confetti */
        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
        }

        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            background: #FFD700;
            animation: confetti 1s ease-out forwards;
        }
    </style>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const diceSymbols = ['', '&#9856;', '&#9857;', '&#9858;', '&#9859;', '&#9860;', '&#9861;'];

        // Verifier le bonus au chargement
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                const response = await fetch('/daily-bonus/check', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.can_claim) {
                    document.getElementById('dailyBonusModal').classList.add('active');
                }
            } catch (error) {
                console.error('Erreur verification bonus:', error);
            }
        });

        async function rollDice() {
            const btn = document.getElementById('rollDiceBtn');
            const dice = document.getElementById('dice');
            const resultContainer = document.getElementById('resultContainer');
            const rewardsPreview = document.querySelector('.rewards-preview');

            btn.disabled = true;
            btn.textContent = 'Lancement...';

            // Animation de roulement
            dice.classList.add('rolling');

            try {
                const response = await fetch('/daily-bonus/claim', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Attendre la fin de l'animation
                    setTimeout(() => {
                        dice.classList.remove('rolling');
                        dice.classList.add('result-' + data.dice_result);

                        // Cacher le bouton et afficher le resultat
                        btn.style.display = 'none';
                        rewardsPreview.style.display = 'none';

                        document.getElementById('resultDice').innerHTML = diceSymbols[data.dice_result];
                        document.getElementById('resultCoins').textContent = '+' + data.coins_earned + ' pieces !';
                        document.getElementById('resultBalance').textContent = 'Nouveau solde: ' + data.new_balance.toLocaleString() + ' po';
                        resultContainer.style.display = 'block';

                        // Effet confetti
                        createConfetti();
                    }, 2000);
                } else {
                    alert(data.message || 'Erreur');
                    closeDailyBonusModal();
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
                closeDailyBonusModal();
            }
        }

        function closeDailyBonusModal() {
            document.getElementById('dailyBonusModal').classList.remove('active');
            // Rafraichir la page pour mettre a jour le solde dans la navbar
            location.reload();
        }

        function createConfetti() {
            const container = document.querySelector('.daily-bonus-content');
            const colors = ['#FFD700', '#FFA500', '#FF6B6B', '#4ECDC4', '#A78BFA'];

            for (let i = 0; i < 30; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + '%';
                confetti.style.top = Math.random() * 100 + '%';
                confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 0.5 + 's';
                confetti.style.transform = 'rotate(' + Math.random() * 360 + 'deg)';
                container.appendChild(confetti);

                setTimeout(() => confetti.remove(), 1500);
            }
        }
    </script>

    <script>
        // Drag to scroll pour les conteneurs de cartes
        document.addEventListener('DOMContentLoaded', function() {
            const scrollContainers = document.querySelectorAll('.cards-scroll-container');

            scrollContainers.forEach(container => {
                let isDown = false;
                let startX;
                let scrollLeft;

                container.addEventListener('mousedown', (e) => {
                    // Ignorer si on clique sur un lien ou bouton
                    if (e.target.closest('a, button')) return;

                    isDown = true;
                    container.style.cursor = 'grabbing';
                    startX = e.pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                    e.preventDefault();
                });

                container.addEventListener('mouseleave', () => {
                    isDown = false;
                    container.style.cursor = 'grab';
                });

                container.addEventListener('mouseup', () => {
                    isDown = false;
                    container.style.cursor = 'grab';
                });

                container.addEventListener('mousemove', (e) => {
                    if (!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - container.offsetLeft;
                    const walk = (x - startX) * 2;
                    container.scrollLeft = scrollLeft - walk;
                });

                // Support tactile amélioré
                container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].pageX - container.offsetLeft;
                    scrollLeft = container.scrollLeft;
                }, { passive: true });

                container.addEventListener('touchmove', (e) => {
                    if (!startX) return;
                    const x = e.touches[0].pageX - container.offsetLeft;
                    const walk = (x - startX) * 1.5;
                    container.scrollLeft = scrollLeft - walk;
                }, { passive: true });
            });
        });
    </script>
</x-app-layout>