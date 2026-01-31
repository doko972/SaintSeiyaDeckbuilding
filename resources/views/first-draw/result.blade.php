<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Premier Tirage - Saint Seiya</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            overflow-x: hidden;
        }

        .stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent),
                radial-gradient(2px 2px at 300px 150px, rgba(255,255,255,0.7), transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .title {
            text-align: center;
            margin-bottom: 2rem;
        }

        .title h1 {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #A855F7, #6366F1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(168, 85, 247, 0.3);
            margin-bottom: 0.5rem;
        }

        .title p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 1.1rem;
        }

        .cards-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            perspective: 1000px;
        }

        .flip-card {
            width: 180px;
            height: 260px;
            cursor: pointer;
            perspective: 1000px;
            opacity: 0;
            transform: translateY(50px) rotateX(-20deg);
            animation: cardEnter 0.6s forwards;
        }

        @keyframes cardEnter {
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .flip-card:nth-child(1) { animation-delay: 0.1s; }
        .flip-card:nth-child(2) { animation-delay: 0.2s; }
        .flip-card:nth-child(3) { animation-delay: 0.3s; }
        .flip-card:nth-child(4) { animation-delay: 0.4s; }
        .flip-card:nth-child(5) { animation-delay: 0.5s; }
        .flip-card:nth-child(6) { animation-delay: 0.6s; }
        .flip-card:nth-child(7) { animation-delay: 0.7s; }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
        }

        .flip-card.flipped .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            border-radius: 12px;
            overflow: hidden;
        }

        .flip-card-front {
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            border: 3px solid rgba(168, 85, 247, 0.5);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.3), 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .flip-card-front::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent 40%, rgba(255, 255, 255, 0.1) 50%, transparent 60%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .card-back-logo {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            animation: pulse-logo 2s infinite;
            position: relative;
            z-index: 1;
        }

        @keyframes pulse-logo {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .card-back-text {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }

        .click-hint {
            position: absolute;
            bottom: 15px;
            color: rgba(168, 85, 247, 0.8);
            font-size: 0.7rem;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .flip-card-back {
            transform: rotateY(180deg);
            background: linear-gradient(145deg, var(--color1), var(--color2));
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .card-content {
            height: 100%;
            display: flex;
            flex-direction: column;
            padding: 10px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 6px;
        }

        .card-name {
            font-size: 0.85rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.2;
        }

        .card-faction {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .card-cost {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            padding: 3px 6px;
            font-size: 0.8rem;
            font-weight: 800;
            color: white;
        }

        .card-image {
            flex: 1;
            border-radius: 6px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: linear-gradient(180deg, rgba(0,0,0,0.2), rgba(0,0,0,0.5));
        }

        .card-rarity-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.55rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .quantity-badge {
            position: absolute;
            top: 6px;
            left: 6px;
            background: rgba(0, 0, 0, 0.7);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 0.65rem;
            font-weight: 700;
            color: #FFD700;
        }

        .rarity-common { background: rgba(107, 114, 128, 0.9); color: white; }
        .rarity-rare { background: rgba(59, 130, 246, 0.9); color: white; }
        .rarity-epic { background: rgba(139, 92, 246, 0.9); color: white; }
        .rarity-legendary {
            background: linear-gradient(135deg, #F59E0B, #EF4444);
            color: white;
        }

        .card-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 3px;
            margin-top: 6px;
        }

        .card-stat {
            text-align: center;
            padding: 3px;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.3);
        }

        .card-stat.hp { background: rgba(220, 38, 38, 0.4); }
        .card-stat.def { background: rgba(37, 99, 235, 0.4); }
        .card-stat.pwr { background: rgba(234, 88, 12, 0.4); }
        .card-stat.cos { background: rgba(124, 58, 237, 0.4); }

        .stat-value {
            font-size: 0.75rem;
            font-weight: 800;
            color: white;
        }

        .stat-label {
            font-size: 0.45rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
        }

        .flip-card-back.rarity-common { border-color: #6B7280; }
        .flip-card-back.rarity-rare { border-color: #3B82F6; box-shadow: 0 0 20px rgba(59, 130, 246, 0.3); }
        .flip-card-back.rarity-epic { border-color: #8B5CF6; box-shadow: 0 0 25px rgba(139, 92, 246, 0.4); }
        .flip-card-back.rarity-legendary {
            border-color: #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.5), 0 0 60px rgba(255, 100, 0, 0.3);
        }

        .buttons-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .btn {
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-reveal {
            background: linear-gradient(135deg, #A855F7, #6366F1);
            color: white;
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.4);
        }

        .btn-reveal:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(168, 85, 247, 0.6);
        }

        .btn-reveal:disabled {
            background: #4b5563;
            box-shadow: none;
            cursor: not-allowed;
            transform: none;
        }

        .btn-collection {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-collection:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(16, 185, 129, 0.6);
        }

        .btn-home {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            color: white;
            box-shadow: 0 4px 20px rgba(245, 158, 11, 0.4);
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(245, 158, 11, 0.6);
        }

        .particle {
            position: fixed;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1000;
            animation: particleExplode 0.8s forwards;
        }

        @keyframes particleExplode {
            0% {
                transform: scale(0);
                opacity: 1;
            }
            100% {
                transform: scale(1) translate(var(--tx), var(--ty));
                opacity: 0;
            }
        }

        @media (max-width: 768px) {
            .flip-card {
                width: 140px;
                height: 200px;
            }

            .title h1 {
                font-size: 1.8rem;
            }

            .cards-grid {
                gap: 0.8rem;
            }

            .btn {
                padding: 0.8rem 1.2rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="stars"></div>

    <div class="container">
        <div class="title">
            <h1>🎁 Premier Tirage Gratuit !</h1>
            <p>Cliquez sur les cartes pour les reveler - {{ $totalCards }} cartes obtenues</p>
        </div>

        <div class="cards-grid">
            @foreach($drawnCards as $cardId => $item)
                @php $card = $item['card']; @endphp
                <div class="flip-card" data-rarity="{{ $card->rarity }}" onclick="flipCard(this)">
                    <div class="flip-card-inner">
                        <div class="flip-card-front">
                            <div class="card-back-logo">🎴</div>
                            <div class="card-back-text">Saint Seiya</div>
                            <div class="click-hint">Cliquez pour reveler</div>
                        </div>

                        <div class="flip-card-back rarity-{{ $card->rarity }}"
                             style="--color1: {{ $card->faction->color_primary ?? '#667' }}; --color2: {{ $card->faction->color_secondary ?? '#445' }};">
                            <div class="card-content">
                                <div class="card-header">
                                    <div>
                                        <div class="card-name">{{ $card->name }}</div>
                                        <div class="card-faction">{{ $card->faction->name ?? 'Inconnu' }}</div>
                                    </div>
                                    <div class="card-cost">{{ $card->cost }}</div>
                                </div>

                                <div class="card-image">
                                    @if($card->image_primary)
                                        <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                    @else
                                        <div class="card-image-placeholder">🃏</div>
                                    @endif

                                    @if($item['quantity'] > 1)
                                        <div class="quantity-badge">x{{ $item['quantity'] }}</div>
                                    @endif

                                    <div class="card-rarity-badge rarity-{{ $card->rarity }}">
                                        @switch($card->rarity)
                                            @case('common') Commune @break
                                            @case('rare') Rare @break
                                            @case('epic') Epique @break
                                            @case('legendary') Legendaire @break
                                            @default {{ ucfirst($card->rarity) }}
                                        @endswitch
                                    </div>
                                </div>

                                <div class="card-stats">
                                    <div class="card-stat hp">
                                        <div class="stat-value">{{ $card->health_points }}</div>
                                        <div class="stat-label">PV</div>
                                    </div>
                                    <div class="card-stat def">
                                        <div class="stat-value">{{ $card->defense }}</div>
                                        <div class="stat-label">DEF</div>
                                    </div>
                                    <div class="card-stat pwr">
                                        <div class="stat-value">{{ $card->power }}</div>
                                        <div class="stat-label">PWR</div>
                                    </div>
                                    <div class="card-stat cos">
                                        <div class="stat-value">{{ $card->cosmos }}</div>
                                        <div class="stat-label">COS</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="buttons-container">
            <button class="btn btn-reveal" id="revealAllBtn" onclick="revealAll()">
                ✨ Reveler tout
            </button>
            <a href="{{ route('collection.index') }}" class="btn btn-collection">
                📚 Ma Collection
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-home">
                🚀 Commencer l'aventure
            </a>
        </div>
    </div>

    <script>
        let flippedCount = 0;
        const totalCards = {{ count($drawnCards) }};

        function flipCard(card) {
            if (card.classList.contains('flipped')) return;

            card.classList.add('flipped');
            flippedCount++;

            const rarity = card.dataset.rarity;
            createParticles(card, rarity);

            if (navigator.vibrate) {
                navigator.vibrate(rarity === 'legendary' ? [50, 30, 50] : 50);
            }

            if (flippedCount >= totalCards) {
                document.getElementById('revealAllBtn').disabled = true;
                document.getElementById('revealAllBtn').textContent = '✅ Toutes revelees !';
            }
        }

        function revealAll() {
            const cards = document.querySelectorAll('.flip-card:not(.flipped)');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    flipCard(card);
                }, index * 300);
            });
        }

        function createParticles(card, rarity) {
            const rect = card.getBoundingClientRect();
            const centerX = rect.left + rect.width / 2;
            const centerY = rect.top + rect.height / 2;

            const colors = {
                common: ['#9CA3AF', '#6B7280'],
                rare: ['#3B82F6', '#60A5FA', '#93C5FD'],
                epic: ['#8B5CF6', '#A78BFA', '#C4B5FD'],
                legendary: ['#F59E0B', '#EF4444', '#FFD700', '#FF6B00', '#FBBF24']
            };

            const particleColors = colors[rarity] || colors.common;
            const particleCount = rarity === 'legendary' ? 25 : rarity === 'epic' ? 18 : rarity === 'rare' ? 12 : 8;

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.background = particleColors[Math.floor(Math.random() * particleColors.length)];
                particle.style.left = centerX + 'px';
                particle.style.top = centerY + 'px';
                particle.style.width = (4 + Math.random() * 6) + 'px';
                particle.style.height = particle.style.width;

                const angle = (Math.random() * 360) * (Math.PI / 180);
                const distance = 60 + Math.random() * 120;
                const tx = Math.cos(angle) * distance + 'px';
                const ty = Math.sin(angle) * distance + 'px';

                particle.style.setProperty('--tx', tx);
                particle.style.setProperty('--ty', ty);

                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 800);
            }
        }
    </script>
</body>
</html>
