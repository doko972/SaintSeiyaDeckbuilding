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
           INTRO BANNER
        ======================================== */
        .intro-banner {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 20px;
            padding: 2.5rem;
            text-align: center;
            overflow: hidden;
        }

        .intro-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, #EF4444, #F97316, #EF4444, transparent);
        }

        .intro-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: pulse-battle 2s ease-in-out infinite;
        }

        @keyframes pulse-battle {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.5)); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 20px rgba(239, 68, 68, 0.8)); }
        }

        .intro-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #EF4444, #F97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .intro-text {
            color: rgba(255, 255, 255, 0.6);
            max-width: 500px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        /* ========================================
           DECK BATTLE CARDS
        ======================================== */
        .deck-battle-card {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .deck-battle-card:hover {
            transform: translateY(-10px);
            border-color: rgba(239, 68, 68, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 40px rgba(239, 68, 68, 0.3);
        }

        /* Header du deck */
        .deck-header {
            position: relative;
            padding: 1.5rem;
            background: linear-gradient(135deg, #DC2626, #EA580C);
            overflow: hidden;
        }

        .deck-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }

        .deck-battle-card:hover .deck-header::before {
            transform: translateX(100%);
        }

        .deck-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .deck-count {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            position: relative;
            z-index: 1;
            margin-top: 0.25rem;
        }

        /* Contenu du deck */
        .deck-content {
            padding: 1.5rem;
        }

        .deck-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }

        /* Aperçu des cartes */
        .cards-preview {
            display: flex;
            margin-bottom: 1.5rem;
            padding-left: 8px;
        }

        .card-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 3px solid rgba(15, 15, 35, 0.9);
            overflow: hidden;
            margin-left: -12px;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .card-avatar:first-child {
            margin-left: 0;
        }

        .card-avatar:hover {
            transform: scale(1.2) translateY(-5px);
            z-index: 10;
            border-color: #EF4444;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }

        .card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
        }

        .card-avatar-more {
            background: rgba(255, 255, 255, 0.1) !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: white;
        }

        /* Bouton Combat */
        .battle-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
            background: linear-gradient(135deg, #DC2626, #EA580C);
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
        }

        .battle-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
        }

        .battle-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
        }

        .battle-btn:hover::before {
            transform: translateX(100%);
            transition: transform 0.6s ease;
        }

        .battle-btn-icon {
            font-size: 1.3rem;
            margin-right: 0.5rem;
            display: inline-block;
            animation: shake-icon 0.5s ease-in-out infinite;
            animation-play-state: paused;
        }

        .battle-btn:hover .battle-btn-icon {
            animation-play-state: running;
        }

        @keyframes shake-icon {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .battle-btn-disabled {
            background: rgba(75, 85, 99, 0.5);
            color: rgba(255, 255, 255, 0.4);
            cursor: not-allowed;
            box-shadow: none;
        }

        .battle-btn-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* ========================================
           EMPTY STATE
        ======================================== */
        .empty-state {
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        .empty-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
        }

        .empty-text {
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 2rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .create-deck-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #7C3AED, #6366F1);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
        }

        .create-deck-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(124, 58, 237, 0.5);
        }

        /* ========================================
           VS BADGE
        ======================================== */
        .vs-badge {
            position: absolute;
            top: 30px;
            right: 15px;
            background: linear-gradient(135deg, #FBBF24, #F59E0B);
            color: #1a1a2e;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(251, 191, 36, 0.4);
            z-index: 10;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 640px) {
            .intro-banner {
                padding: 1.5rem;
            }

            .intro-icon {
                font-size: 3rem;
            }

            .intro-title {
                font-size: 1.5rem;
            }

            .card-avatar {
                width: 44px;
                height: 44px;
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
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-4xl">⚔️</span>
                    Combat PvE
                </h1>
                <p class="text-gray-400 mt-1">Affrontez l'IA et gagnez des récompenses</p>
            </div>

            @if($decks->isEmpty())
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">🃏</div>
                    <h3 class="empty-title">Aucun deck disponible</h3>
                    <p class="empty-text">
                        Vous devez créer un deck avec des cartes pour pouvoir affronter l'IA et brûler votre cosmos !
                    </p>
                    <a href="{{ route('decks.create') }}" class="create-deck-btn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer un deck
                    </a>
                </div>
            @else
                <!-- Intro Banner -->
                <div class="intro-banner mb-8">
                    <div class="intro-icon">⚔️</div>
                    <h2 class="intro-title">Prêt pour le combat ?</h2>
                    <p class="intro-text">
                        Sélectionnez un deck pour affronter un adversaire contrôlé par l'IA. 
                        Victoire = 100 🪙 | Défaite = 25 🪙
                    </p>
                </div>

                <!-- Grille des decks -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($decks as $deck)
                        <div class="deck-battle-card">
                            <!-- Badge VS -->
                            <div class="vs-badge">🤖 VS IA</div>
                            
                            <!-- Header -->
                            <div class="deck-header">
                                <h3 class="deck-name">{{ $deck->name }}</h3>
                                <p class="deck-count">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    {{ $deck->cards_count }} carte(s)
                                </p>
                            </div>

                            <!-- Contenu -->
                            <div class="deck-content">
                                @if($deck->description)
                                    <p class="deck-description">{{ Str::limit($deck->description, 80) }}</p>
                                @endif

                                <!-- Aperçu des cartes -->
                                <div class="cards-preview">
                                    @forelse($deck->cards->take(6) as $card)
                                        <div class="card-avatar"
                                             style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#6366f1' }}, {{ $card->faction->color_secondary ?? '#8b5cf6' }});"
                                             title="{{ $card->name }}">
                                            @if($card->image_primary)
                                                <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                            @else
                                                <div class="card-avatar-placeholder">
                                                    {{ strtoupper(substr($card->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-gray-500 text-sm italic">Aucune carte</span>
                                    @endforelse
                                    
                                    @if($deck->cards->count() > 6)
                                        <div class="card-avatar card-avatar-more">
                                            +{{ $deck->cards->count() - 6 }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Bouton Combat -->
                                @if($deck->cards_count > 0)
                                    <a href="{{ route('game.battle', $deck) }}" class="battle-btn">
                                        <span class="battle-btn-icon">⚔️</span>
                                        Combattre !
                                    </a>
                                @else
                                    <div class="battle-btn battle-btn-disabled">
                                        <span class="battle-btn-icon">🚫</span>
                                        Deck vide
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Info supplémentaire -->
                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        💡 Conseil : Un deck équilibré avec différents types de cartes augmente vos chances de victoire !
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>