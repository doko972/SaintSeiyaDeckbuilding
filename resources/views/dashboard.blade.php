<x-app-layout>
    <style>
        /* ========================================
           FOND COSMOS ANIME
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
           HERO BANNER COMPACT
        ======================================== */
        .hero-banner {
            background: linear-gradient(135deg, rgba(88, 28, 135, 0.8), rgba(124, 58, 237, 0.6));
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 20px;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }

        .rank-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .rank-bronze { background: linear-gradient(135deg, #CD7F32, #8B4513); color: white; }
        .rank-argent { background: linear-gradient(135deg, #C0C0C0, #A8A8A8); color: #1a1a2e; }
        .rank-or { background: linear-gradient(135deg, #FFD700, #FFA500); color: #1a1a2e; }
        .rank-divin { background: linear-gradient(135deg, #E0B0FF, #9400D3); color: white; }

        /* ========================================
           FEATURE CARDS - MOBILE FIRST
        ======================================== */
        .feature-card {
            position: relative;
            background: rgba(15, 15, 35, 0.9);
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            flex-direction: column;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: var(--feature-color, rgba(255, 215, 0, 0.5));
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4), 0 0 30px rgba(var(--glow-rgb, 255, 215, 0), 0.2);
        }

        .feature-image {
            position: relative;
            height: 140px;
            background: linear-gradient(135deg, var(--bg-from, #333), var(--bg-to, #555));
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .feature-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top;
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-image img {
            transform: scale(1.1);
        }

        .feature-image .feature-icon {
            font-size: 4rem;
            opacity: 0.9;
            transition: transform 0.4s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.2);
        }

        .feature-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .feature-content {
            padding: 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feature-description {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.4;
            flex: 1;
        }

        .feature-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, var(--cta-from, #8B5CF6), var(--cta-to, #6366F1));
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.3s ease;
            justify-content: center;
        }

        .feature-cta:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(var(--glow-rgb, 139, 92, 246), 0.4);
        }

        /* ========================================
           SECTION HEADERS
        ======================================== */
        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
        }

        .section-subtitle {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
        }

        /* ========================================
           STATS MINI CARDS
        ======================================== */
        .mini-stat {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        .mini-stat:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .mini-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--stat-color, #FFD700), var(--stat-color-light, #FFA500));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .mini-stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            margin-top: 0.25rem;
        }

        /* ========================================
           RECENT CARDS
        ======================================== */
        .cards-scroll {
            display: flex;
            gap: 0.75rem;
            overflow-x: auto;
            padding: 0.5rem;
            margin: -0.5rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(139, 92, 246, 0.5) transparent;
        }

        .cards-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .cards-scroll::-webkit-scrollbar-thumb {
            background: rgba(139, 92, 246, 0.5);
            border-radius: 2px;
        }

        .recent-card {
            flex-shrink: 0;
            width: 80px;
            height: 110px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .recent-card:hover {
            transform: scale(1.1);
            border-color: rgba(255, 215, 0, 0.5);
            z-index: 10;
        }

        .recent-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ========================================
           NOTIFICATION DOT
        ======================================== */
        .notification-dot {
            position: absolute;
            top: 8px;
            left: 8px;
            width: 12px;
            height: 12px;
            background: #10B981;
            border-radius: 50%;
            border: 2px solid rgba(15, 15, 35, 0.9);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 640px) {
            .hero-banner {
                padding: 1rem;
            }

            .feature-image {
                height: 120px;
            }

            .feature-icon {
                font-size: 3rem !important;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .mini-stat-value {
                font-size: 1.25rem;
            }
        }

        /* ========================================
           DAILY BONUS MODAL
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
        }

        .daily-bonus-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(255, 215, 0, 0.4);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .dice-container {
            width: 100px;
            height: 100px;
            margin: 1.5rem auto;
            perspective: 600px;
        }

        .dice {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
        }

        .dice.rolling {
            animation: diceRoll 2s ease-out;
        }

        @keyframes diceRoll {
            0% { transform: rotateX(0) rotateY(0); }
            100% { transform: var(--final-rotation); }
        }

        .dice-face {
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #fff, #e0e0e0);
            border: 3px solid #333;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 900;
            color: #1a1a3e;
        }

        .dice-face.front  { transform: translateZ(50px); }
        .dice-face.back   { transform: rotateY(180deg) translateZ(50px); }
        .dice-face.right  { transform: rotateY(90deg) translateZ(50px); }
        .dice-face.left   { transform: rotateY(-90deg) translateZ(50px); }
        .dice-face.top    { transform: rotateX(90deg) translateZ(50px); }
        .dice-face.bottom { transform: rotateX(-90deg) translateZ(50px); }

        .dice.result-1 { transform: rotateX(0deg) rotateY(0deg); }
        .dice.result-2 { transform: rotateX(-90deg) rotateY(0deg); }
        .dice.result-3 { transform: rotateX(0deg) rotateY(-90deg); }
        .dice.result-4 { transform: rotateX(0deg) rotateY(90deg); }
        .dice.result-5 { transform: rotateX(90deg) rotateY(0deg); }
        .dice.result-6 { transform: rotateX(180deg) rotateY(0deg); }

        .rewards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin: 1rem 0;
        }

        .reward-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.4rem;
            border-radius: 6px;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .roll-btn, .close-btn {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .roll-btn {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a3e;
        }

        .close-btn {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            margin-top: 1rem;
        }

        .roll-btn:hover, .close-btn:hover {
            transform: scale(1.02);
        }

        .roll-btn:disabled {
            background: #666;
            cursor: not-allowed;
        }

        .result-container {
            margin-top: 1rem;
        }

        .result-coins {
            font-size: 1.75rem;
            font-weight: 900;
            color: #FFD700;
        }

        .result-balance {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin: 0.5rem 0;
        }
    </style>

    <!-- Fond Cosmos -->
    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="relative z-10 min-h-screen py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <!-- ========================================
                 HERO BANNER COMPACT
            ======================================== -->
            <div class="hero-banner mb-6">
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <!-- Infos Joueur -->
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-xl sm:text-2xl font-bold text-white">
                                Salut, {{ auth()->user()->name }} !
                            </h1>
                            @php
                                $wins = auth()->user()->wins;
                                $rank = match(true) {
                                    $wins >= 100 => ['name' => 'Divin', 'class' => 'rank-divin', 'icon' => '&#128081;'],
                                    $wins >= 50 => ['name' => 'Or', 'class' => 'rank-or', 'icon' => '&#129351;'],
                                    $wins >= 20 => ['name' => 'Argent', 'class' => 'rank-argent', 'icon' => '&#129352;'],
                                    default => ['name' => 'Bronze', 'class' => 'rank-bronze', 'icon' => '&#129353;'],
                                };
                            @endphp
                            <span class="rank-badge {{ $rank['class'] }}">{!! $rank['icon'] !!} {{ $rank['name'] }}</span>
                        </div>
                        <p class="text-purple-200 text-sm">Brule ton cosmos et deviens une legende !</p>
                    </div>

                    <!-- Mini Stats -->
                    <div class="grid grid-cols-3 gap-2 sm:gap-3">
                        <div class="mini-stat" style="--stat-color: #FFD700; --stat-color-light: #FFA500;">
                            <div class="mini-stat-value">{{ number_format(auth()->user()->coins) }}</div>
                            <div class="mini-stat-label">Pieces</div>
                        </div>
                        <div class="mini-stat" style="--stat-color: #10B981; --stat-color-light: #34D399;">
                            <div class="mini-stat-value">{{ auth()->user()->wins }}</div>
                            <div class="mini-stat-label">Victoires</div>
                        </div>
                        <div class="mini-stat" style="--stat-color: #8B5CF6; --stat-color-light: #A78BFA;">
                            <div class="mini-stat-value">{{ auth()->user()->cards()->count() }}</div>
                            <div class="mini-stat-label">Cartes</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================
                 SECTION: MODES DE JEU
            ======================================== -->
            <div class="mb-8">
                <div class="section-header">
                    <div class="section-icon bg-gradient-to-br from-red-500 to-orange-500">&#9876;</div>
                    <div>
                        <h2 class="section-title">Modes de jeu</h2>
                        <p class="section-subtitle">Affrontez vos adversaires</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Combat PvE -->
                    <a href="{{ route('game.index') }}" class="feature-card" style="--feature-color: rgba(59, 130, 246, 0.5); --glow-rgb: 59, 130, 246;">
                        <div class="feature-image" style="--bg-from: #1E40AF; --bg-to: #3B82F6;">
                            @if(file_exists(public_path('images/features/pve.webp')))
                                <img src="{{ asset('images/features/pve.webp') }}" alt="Combat PvE">
                            @else
                                <span class="feature-icon">&#129302;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">&#129302; Combat PvE</h3>
                            <p class="feature-description">Affrontez l'intelligence artificielle et perfectionnez vos strategies de combat.</p>
                            <span class="feature-cta" style="--cta-from: #2563EB; --cta-to: #1D4ED8;">
                                Jouer &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Arena PvP -->
                    <a href="{{ route('pvp.lobby') }}" class="feature-card" style="--feature-color: rgba(239, 68, 68, 0.5); --glow-rgb: 239, 68, 68;">
                        <div class="notification-dot"></div>
                        <div class="feature-image" style="--bg-from: #991B1B; --bg-to: #DC2626;">
                            @if(file_exists(public_path('images/features/pvp.webp')))
                                <img src="{{ asset('images/features/pvp.webp') }}" alt="Arena PvP">
                            @else
                                <span class="feature-icon">&#127942;</span>
                            @endif
                            <span class="feature-badge bg-green-500 text-white">En ligne</span>
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">&#127942; Arena PvP</h3>
                            <p class="feature-description">Defiez d'autres joueurs en temps reel et grimpez dans le classement !</p>
                            <span class="feature-cta" style="--cta-from: #DC2626; --cta-to: #B91C1C;">
                                Defier &#10132;
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ========================================
                 SECTION: GESTION DES CARTES
            ======================================== -->
            <div class="mb-8">
                <div class="section-header">
                    <div class="section-icon bg-gradient-to-br from-purple-500 to-indigo-500">&#127183;</div>
                    <div>
                        <h2 class="section-title">Gestion des cartes</h2>
                        <p class="section-subtitle">Construisez votre collection</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Collection -->
                    <a href="{{ route('collection.index') }}" class="feature-card" style="--feature-color: rgba(168, 85, 247, 0.5); --glow-rgb: 168, 85, 247;">
                        <div class="feature-image" style="--bg-from: #6B21A8; --bg-to: #9333EA;">
                            @if(file_exists(public_path('images/features/collection.webp')))
                                <img src="{{ asset('images/features/collection.webp') }}" alt="Collection">
                            @else
                                <span class="feature-icon">&#128218;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Collection</h3>
                            <p class="feature-description">Consultez toutes vos cartes et decouvrez leurs statistiques.</p>
                            <span class="feature-cta" style="--cta-from: #9333EA; --cta-to: #7C3AED;">
                                Voir &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Mes Decks -->
                    <a href="{{ route('decks.index') }}" class="feature-card" style="--feature-color: rgba(99, 102, 241, 0.5); --glow-rgb: 99, 102, 241;">
                        <div class="feature-image" style="--bg-from: #3730A3; --bg-to: #4F46E5;">
                            @if(file_exists(public_path('images/features/decks.webp')))
                                <img src="{{ asset('images/features/decks.webp') }}" alt="Mes Decks">
                            @else
                                <span class="feature-icon">&#127156;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Mes Decks</h3>
                            <p class="feature-description">Creez et gerez vos decks pour dominer vos adversaires.</p>
                            <span class="feature-cta" style="--cta-from: #4F46E5; --cta-to: #4338CA;">
                                Gerer &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Fusion -->
                    <a href="{{ route('fusion.index') }}" class="feature-card" style="--feature-color: rgba(245, 158, 11, 0.5); --glow-rgb: 245, 158, 11;">
                        <div class="feature-image" style="--bg-from: #B45309; --bg-to: #F59E0B;">
                            @if(file_exists(public_path('images/features/fusion.webp')))
                                <img src="{{ asset('images/features/fusion.webp') }}" alt="Fusion">
                            @else
                                <span class="feature-icon">&#9889;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Fusion</h3>
                            <p class="feature-description">Fusionnez vos doublons pour ameliorer vos cartes.</p>
                            <span class="feature-cta" style="--cta-from: #F59E0B; --cta-to: #D97706;">
                                Fusionner &#10132;
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ========================================
                 SECTION: ECONOMIE
            ======================================== -->
            <div class="mb-8">
                <div class="section-header">
                    <div class="section-icon bg-gradient-to-br from-yellow-500 to-amber-500">&#129689;</div>
                    <div>
                        <h2 class="section-title">Economie</h2>
                        <p class="section-subtitle">Gagnez et depensez vos pieces</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <!-- Boutique -->
                    <a href="{{ route('shop.index') }}" class="feature-card" style="--feature-color: rgba(251, 191, 36, 0.5); --glow-rgb: 251, 191, 36;">
                        <div class="feature-image" style="--bg-from: #B45309; --bg-to: #FBBF24;">
                            @if(file_exists(public_path('images/features/shop.webp')))
                                <img src="{{ asset('images/features/shop.webp') }}" alt="Boutique">
                            @else
                                <span class="feature-icon">&#128722;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">&#128722; Boutique</h3>
                            <p class="feature-description">Achetez des boosters pour obtenir de nouvelles cartes.</p>
                            <span class="feature-cta" style="--cta-from: #FBBF24; --cta-to: #F59E0B; color: #1a1a2e;">
                                Acheter &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Vente -->
                    <a href="{{ route('sell.index') }}" class="feature-card" style="--feature-color: rgba(16, 185, 129, 0.5); --glow-rgb: 16, 185, 129;">
                        <div class="feature-image" style="--bg-from: #065F46; --bg-to: #10B981;">
                            @if(file_exists(public_path('images/features/sell.webp')))
                                <img src="{{ asset('images/features/sell.webp') }}" alt="Vente">
                            @else
                                <span class="feature-icon">&#128176;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">&#128176; Vente</h3>
                            <p class="feature-description">Revendez vos cartes en double pour recuperer des pieces.</p>
                            <span class="feature-cta" style="--cta-from: #10B981; --cta-to: #059669;">
                                Vendre &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Bonus & Recompenses -->
                    <a href="{{ route('rewards.index') }}" class="feature-card" style="--feature-color: rgba(236, 72, 153, 0.5); --glow-rgb: 236, 72, 153;">
                        @php
                            $user = auth()->user();
                            $streakInfo = $user->getStreakInfo();
                            $canSpin = $user->canSpinWheel();
                            $hasReward = $streakInfo['can_claim'] || $canSpin;
                        @endphp
                        @if($hasReward)
                            <div class="notification-dot"></div>
                        @endif
                        <div class="feature-image" style="--bg-from: #9D174D; --bg-to: #EC4899;">
                            @if(file_exists(public_path('images/features/bonus.webp')))
                                <img src="{{ asset('images/features/bonus.webp') }}" alt="Bonus">
                            @else
                                <span class="feature-icon">&#127873;</span>
                            @endif
                            @if($hasReward)
                                <span class="feature-badge bg-green-500 text-white">Disponible !</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">&#127873; Bonus</h3>
                            <p class="feature-description">Serie de connexion et roue de la fortune pour gagner des recompenses.</p>
                            <span class="feature-cta" style="--cta-from: #EC4899; --cta-to: #DB2777;">
                                Reclamer &#10132;
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ========================================
                 DERNIERES CARTES OBTENUES
            ======================================== -->
            @php
                $recentCards = auth()->user()->cards()->with('faction')->latest('user_cards.created_at')->take(8)->get();
            @endphp
            @if($recentCards->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        Dernieres cartes obtenues
                    </h3>
                    <a href="{{ route('collection.index') }}" class="text-purple-400 hover:text-purple-300 text-sm">
                        Voir tout &#10132;
                    </a>
                </div>
                <div class="bg-white/5 backdrop-blur rounded-xl border border-white/10 p-4">
                    <div class="cards-scroll">
                        @foreach($recentCards as $card)
                        <div class="recent-card" style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#333' }}, {{ $card->faction->color_secondary ?? '#555' }});">
                            @if($card->image_primary)
                                <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-3xl">&#127183;</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- ========================================
                 EXPLORER
            ======================================== -->
            <div class="mb-8">
                <div class="section-header">
                    <div class="section-icon bg-gradient-to-br from-cyan-500 to-teal-500">&#128270;</div>
                    <div>
                        <h2 class="section-title">Explorer</h2>
                        <p class="section-subtitle">Decouvrez l'univers du jeu</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                    <!-- Factions -->
                    <a href="{{ route('factions.index') }}" class="feature-card" style="--feature-color: rgba(6, 182, 212, 0.5); --glow-rgb: 6, 182, 212;">
                        <div class="feature-image" style="--bg-from: #155E75; --bg-to: #06B6D4;">
                            @if(file_exists(public_path('images/features/factions.webp')))
                                <img src="{{ asset('images/features/factions.webp') }}" alt="Factions">
                            @else
                                <span class="feature-icon">&#127984;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Factions</h3>
                            <p class="feature-description">Explorez les differentes factions et leurs chevaliers.</p>
                            <span class="feature-cta" style="--cta-from: #06B6D4; --cta-to: #0891B2;">
                                Explorer &#10132;
                            </span>
                        </div>
                    </a>

                    <!-- Toutes les cartes -->
                    <a href="{{ route('cards.index') }}" class="feature-card" style="--feature-color: rgba(139, 92, 246, 0.5); --glow-rgb: 139, 92, 246;">
                        <div class="feature-image" style="--bg-from: #5B21B6; --bg-to: #8B5CF6;">
                            @if(file_exists(public_path('images/features/cards.webp')))
                                <img src="{{ asset('images/features/cards.webp') }}" alt="Cartes">
                            @else
                                <span class="feature-icon">&#127183;</span>
                            @endif
                        </div>
                        <div class="feature-content">
                            <h3 class="feature-title">Encyclopedie</h3>
                            <p class="feature-description">Consultez toutes les cartes existantes dans le jeu.</p>
                            <span class="feature-cta" style="--cta-from: #8B5CF6; --cta-to: #7C3AED;">
                                Consulter &#10132;
                            </span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- ========================================
                 SECTION ADMIN
            ======================================== -->
            @if(auth()->user()->isAdmin())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <h4 class="text-lg font-bold text-red-400 mb-3 flex items-center gap-2">
                    &#9881; Administration
                </h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.cards.create') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">
                        &#10133; Carte
                    </a>
                    <a href="{{ route('admin.factions.create') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">
                        &#10133; Faction
                    </a>
                    <a href="{{ route('admin.attacks.create') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">
                        &#10133; Attaque
                    </a>
                    <a href="{{ route('admin.musics.index') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">
                        &#127925; Musiques
                    </a>
                    <a href="{{ route('admin.combos.index') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">
                        &#9889; Combos
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- ========================================
         MODAL BONUS QUOTIDIEN (de)
    ======================================== -->
    <div id="dailyBonusModal" class="daily-bonus-modal">
        <div class="daily-bonus-content">
            <h2 class="text-2xl font-black text-yellow-400 mb-2">&#127922; Bonus Quotidien</h2>
            <p class="text-gray-400 text-sm mb-4">Lancez le de pour gagner des pieces !</p>

            <div class="dice-container">
                <div class="dice" id="dice">
                    <div class="dice-face front">1</div>
                    <div class="dice-face back">6</div>
                    <div class="dice-face right">3</div>
                    <div class="dice-face left">4</div>
                    <div class="dice-face top">2</div>
                    <div class="dice-face bottom">5</div>
                </div>
            </div>

            <div class="rewards-grid">
                <div class="reward-item">&#9856; = 100</div>
                <div class="reward-item">&#9857; = 200</div>
                <div class="reward-item">&#9858; = 300</div>
                <div class="reward-item">&#9859; = 400</div>
                <div class="reward-item">&#9860; = 500</div>
                <div class="reward-item">&#9861; = 600</div>
            </div>

            <button id="rollDiceBtn" class="roll-btn" onclick="rollDice()">
                &#127922; Lancer le de !
            </button>

            <div id="resultContainer" class="result-container" style="display: none;">
                <div class="text-4xl mb-2" id="resultDice"></div>
                <div class="result-coins" id="resultCoins"></div>
                <div class="result-balance" id="resultBalance"></div>
                <button class="close-btn" onclick="closeDailyBonusModal()">Super !</button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const diceSymbols = ['', '&#9856;', '&#9857;', '&#9858;', '&#9859;', '&#9860;', '&#9861;'];

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

            btn.disabled = true;
            btn.textContent = 'Lancement...';
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
                    setTimeout(() => {
                        dice.classList.remove('rolling');
                        dice.classList.add('result-' + data.dice_result);

                        btn.style.display = 'none';
                        document.querySelector('.rewards-grid').style.display = 'none';

                        document.getElementById('resultDice').innerHTML = diceSymbols[data.dice_result];
                        document.getElementById('resultCoins').textContent = '+' + data.coins_earned + ' pieces !';
                        document.getElementById('resultBalance').textContent = 'Nouveau solde: ' + data.new_balance.toLocaleString() + ' po';
                        resultContainer.style.display = 'block';
                    }, 2000);
                } else {
                    alert(data.message || 'Erreur');
                    closeDailyBonusModal();
                }
            } catch (error) {
                console.error('Erreur:', error);
                closeDailyBonusModal();
            }
        }

        function closeDailyBonusModal() {
            document.getElementById('dailyBonusModal').classList.remove('active');
            location.reload();
        }
    </script>
</x-app-layout>
