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
           COINS DISPLAY
        ======================================== */
        .coins-display {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.2));
            border: 2px solid rgba(251, 191, 36, 0.5);
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.3);
        }

        .coins-display svg {
            width: 28px;
            height: 28px;
            color: #FBBF24;
        }

        .coins-display span {
            font-size: 1.5rem;
            font-weight: 800;
            color: #FBBF24;
        }

        /* ========================================
           INTRO BANNER
        ======================================== */
        .intro-banner {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            overflow: hidden;
        }

        .intro-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #A855F7, transparent);
        }

        .intro-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .intro-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .intro-text {
            color: rgba(255, 255, 255, 0.6);
            max-width: 500px;
            margin: 0 auto;
        }

        /* ========================================
           BOOSTER CARDS
        ======================================== */
        .booster-card {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .booster-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--booster-color);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 40px var(--booster-glow);
        }

        /* Header du booster */
        .booster-header {
            position: relative;
            padding: 2.5rem 1.5rem;
            text-align: center;
            overflow: hidden;
        }

        .booster-header-bg {
            position: absolute;
            inset: 0;
            opacity: 0.9;
        }

        /* Effet particules */
        .booster-particles {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .booster-particles::before,
        .booster-particles::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: floatParticle 6s ease-in-out infinite;
        }

        .booster-particles::before {
            top: -20px;
            left: -20px;
        }

        .booster-particles::after {
            bottom: -30px;
            right: -30px;
            animation-delay: -3s;
        }

        @keyframes floatParticle {
            0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.3; }
            50% { transform: translate(20px, 20px) scale(1.2); opacity: 0.1; }
        }

        /* Shine effect */
        .booster-shine {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.3),
                transparent
            );
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }

        .booster-card:hover .booster-shine {
            transform: translateX(100%);
        }

        .booster-icon {
            font-size: 4rem;
            display: block;
            margin-bottom: 0.75rem;
            position: relative;
            z-index: 1;
            transition: transform 0.4s ease;
            filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.3));
        }

        .booster-card:hover .booster-icon {
            transform: scale(1.15) rotate(5deg);
        }

        .booster-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        /* Contenu du booster */
        .booster-content {
            padding: 1.5rem;
        }

        .booster-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        /* Taux de drop */
        .drop-rates {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.25rem;
        }

        .drop-rates-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
        }

        .drop-rate {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            padding: 2px 0;
        }

        .drop-rate-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .drop-rate-dot.common { background: #9CA3AF; }
        .drop-rate-dot.rare { background: #3B82F6; }
        .drop-rate-dot.epic { background: #A855F7; }
        .drop-rate-dot.legendary { background: #FFD700; }

        /* Prix et bouton */
        .booster-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .booster-price {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .booster-price svg {
            width: 24px;
            height: 24px;
            color: #FBBF24;
        }

        .booster-price span {
            font-size: 1.5rem;
            font-weight: 800;
            color: #FBBF24;
        }

        .buy-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .buy-btn.available {
            background: linear-gradient(135deg, #7C3AED, #6366F1);
            color: white;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
        }

        .buy-btn.available:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(124, 58, 237, 0.5);
        }

        .buy-btn.disabled {
            background: rgba(75, 85, 99, 0.5);
            color: rgba(255, 255, 255, 0.4);
            cursor: not-allowed;
        }

        /* ========================================
           HOW TO EARN SECTION
        ======================================== */
        .earn-section {
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
        }

        .earn-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-align: center;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .earn-card {
            position: relative;
            padding: 1.5rem;
            border-radius: 16px;
            text-align: center;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .earn-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--earn-color);
        }

        .earn-card:hover {
            transform: translateY(-5px);
        }

        .earn-card.victory {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            --earn-color: #22C55E;
        }

        .earn-card.defeat {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            --earn-color: #3B82F6;
        }

        .earn-card.bonus {
            background: rgba(168, 85, 247, 0.1);
            border: 1px solid rgba(168, 85, 247, 0.3);
            --earn-color: #A855F7;
        }

        .earn-icon {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }

        .earn-label {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .earn-card.victory .earn-label { color: #22C55E; }
        .earn-card.defeat .earn-label { color: #3B82F6; }
        .earn-card.bonus .earn-label { color: #A855F7; }

        .earn-amount {
            font-size: 1.5rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .earn-card.victory .earn-amount { color: rgba(34, 197, 94, 0.9); }
        .earn-card.defeat .earn-amount { color: rgba(59, 130, 246, 0.9); }
        .earn-card.bonus .earn-amount { color: rgba(168, 85, 247, 0.9); }

        .earn-soon {
            font-size: 0.85rem;
            color: rgba(168, 85, 247, 0.8);
            font-style: italic;
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
                        <span class="text-4xl">🛒</span>
                        Boutique
                    </h1>
                    <p class="text-gray-400 mt-1">Achetez des boosters pour agrandir votre collection</p>
                </div>
                <div class="coins-display">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                    </svg>
                    <span>{{ number_format($user->coins) }}</span>
                </div>
            </div>

            <!-- Intro Banner -->
            <div class="intro-banner mb-8">
                <div class="intro-icon">🎴</div>
                <h2 class="intro-title">Achetez des Boosters</h2>
                <p class="intro-text">
                    Ouvrez des boosters pour obtenir de nouvelles cartes et compléter votre collection de Chevaliers !
                </p>
            </div>

            <!-- Boosters Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach($boosters as $booster)
                    @php
                        $gradients = [
                            'from-gray-600 to-gray-700' => ['#4B5563', 'rgba(75, 85, 99, 0.4)'],
                            'from-blue-600 to-blue-700' => ['#2563EB', 'rgba(37, 99, 235, 0.4)'],
                            'from-purple-600 to-purple-700' => ['#9333EA', 'rgba(147, 51, 234, 0.4)'],
                            'from-yellow-500 to-amber-600' => ['#F59E0B', 'rgba(245, 158, 11, 0.4)'],
                        ];
                        $colorInfo = $gradients[$booster['color']] ?? ['#6366F1', 'rgba(99, 102, 241, 0.4)'];
                    @endphp
                    
                    <div class="booster-card" 
                         style="--booster-color: {{ $colorInfo[0] }}; --booster-glow: {{ $colorInfo[1] }};">
                        
                        <!-- Header -->
                        <div class="booster-header">
                            <div class="booster-header-bg bg-gradient-to-br {{ $booster['color'] }}"></div>
                            <div class="booster-particles"></div>
                            <div class="booster-shine"></div>
                            <span class="booster-icon">{{ $booster['icon'] }}</span>
                            <h3 class="booster-name">{{ $booster['name'] }}</h3>
                        </div>

                        <!-- Contenu -->
                        <div class="booster-content">
                            <p class="booster-description">{{ $booster['description'] }}</p>

                            <!-- Taux de drop -->
                            <div class="drop-rates">
                                <p class="drop-rates-title">Chances d'obtention</p>
                                @foreach($booster['rates'] as $rate)
                                    @php
                                        $dotClass = 'common';
                                        if (str_contains(strtolower($rate), 'légendaire')) $dotClass = 'legendary';
                                        elseif (str_contains(strtolower($rate), 'épique')) $dotClass = 'epic';
                                        elseif (str_contains(strtolower($rate), 'rare')) $dotClass = 'rare';
                                    @endphp
                                    <div class="drop-rate">
                                        <span class="drop-rate-dot {{ $dotClass }}"></span>
                                        {{ $rate }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- Prix et bouton -->
                            <div class="booster-footer">
                                <div class="booster-price">
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                                    </svg>
                                    <span>{{ number_format($booster['price']) }}</span>
                                </div>

                                <form action="{{ route('shop.buy', $booster['id']) }}" method="POST">
                                    @csrf
                                    @if($user->coins >= $booster['price'])
                                        <button type="submit" class="buy-btn available">
                                            Acheter
                                        </button>
                                    @else
                                        <button type="button" class="buy-btn disabled" disabled>
                                            Insuffisant
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Comment gagner des pièces -->
            <div class="earn-section">
                <h3 class="earn-title">
                    <svg class="w-7 h-7 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z"/>
                    </svg>
                    Comment gagner des pièces ?
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="earn-card victory">
                        <div class="earn-icon">🏆</div>
                        <h4 class="earn-label">Victoire en combat</h4>
                        <p class="earn-amount">+100 🪙</p>
                    </div>
                    <div class="earn-card defeat">
                        <div class="earn-icon">⚔️</div>
                        <h4 class="earn-label">Défaite en combat</h4>
                        <p class="earn-amount">+25 🪙</p>
                    </div>
                    <div class="earn-card bonus">
                        <div class="earn-icon">🎁</div>
                        <h4 class="earn-label">Bonus quotidien</h4>
                        <p class="earn-soon">Bientôt disponible !</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>