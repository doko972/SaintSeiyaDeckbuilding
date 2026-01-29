<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $battle->winner_id === auth()->id() ? 'Victoire' : 'Défaite' }} - PvP</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Figtree', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        .cosmos-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(120, 0, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 0, 100, 0.1) 0%, transparent 50%),
                linear-gradient(180deg, #0a0a1a 0%, #1a0a2a 50%, #0a1a2a 100%);
        }

        .bg-image {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            opacity: 0.1;
            object-fit: cover;
        }

        .result-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .result-card {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            text-align: center;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .result-header {
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .result-header.victory {
            background: linear-gradient(135deg, rgba(234, 179, 8, 0.3), rgba(249, 115, 22, 0.3));
        }

        .result-header.defeat {
            background: linear-gradient(135deg, rgba(107, 114, 128, 0.3), rgba(55, 65, 81, 0.3));
        }

        .result-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .result-title {
            font-size: 2.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .result-header.victory .result-title {
            background: linear-gradient(to right, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .result-header.defeat .result-title {
            color: #9CA3AF;
        }

        .result-body {
            padding: 2rem;
        }

        /* Versus section */
        .versus-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .player-result {
            text-align: center;
        }

        .player-result.winner {
            color: #FBBF24;
        }

        .player-result.loser {
            color: #6B7280;
        }

        .player-avatar-result {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            margin: 0 auto 0.5rem;
            border: 3px solid transparent;
        }

        .player-result.winner .player-avatar-result {
            border-color: #FBBF24;
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
        }

        .player1 .player-avatar-result {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
        }

        .player2 .player-avatar-result {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        .player-name-result {
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .winner-badge {
            font-size: 0.8rem;
            color: #FBBF24;
        }

        .vs-divider {
            font-size: 1.5rem;
            font-weight: 800;
            color: #4B5563;
        }

        /* Reward section */
        .reward-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .reward-label {
            color: #9CA3AF;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .reward-amount {
            font-size: 2rem;
            font-weight: 800;
            color: #FBBF24;
        }

        /* Actions */
        .result-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #8B5CF6, #6366F1);
            color: white;
        }

        .action-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4);
        }

        .action-btn.secondary {
            background: rgba(107, 114, 128, 0.3);
            color: #E5E7EB;
            border: 1px solid rgba(107, 114, 128, 0.5);
        }

        .action-btn.secondary:hover {
            background: rgba(107, 114, 128, 0.5);
        }

        /* Confetti animation for victory */
        .confetti {
            position: absolute;
            width: 10px;
            height: 10px;
            opacity: 0;
        }

        .result-header.victory .confetti {
            animation: confetti-fall 3s ease-out infinite;
        }

        @keyframes confetti-fall {
            0% {
                opacity: 1;
                transform: translateY(-100%) rotate(0deg);
            }
            100% {
                opacity: 0;
                transform: translateY(300%) rotate(720deg);
            }
        }

        /* Responsive */
        @media (max-width: 640px) {
            .result-card {
                border-radius: 16px;
            }

            .result-header {
                padding: 1.5rem;
            }

            .result-icon {
                font-size: 4rem;
            }

            .result-title {
                font-size: 1.8rem;
            }

            .result-body {
                padding: 1.5rem;
            }

            .versus-section {
                gap: 1rem;
            }

            .player-avatar-result {
                width: 55px;
                height: 55px;
                font-size: 1.4rem;
            }

            .player-name-result {
                font-size: 0.9rem;
            }

            .reward-amount {
                font-size: 1.6rem;
            }

            .action-btn {
                padding: 0.875rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="cosmos-bg"></div>
    <img src="{{ asset('images/baniere.webp') }}" alt="" class="bg-image">

    <!-- Result Music -->
    @if(isset($resultMusic) && $resultMusic)
    <audio id="resultMusic" preload="auto">
        <source src="{{ Storage::url($resultMusic->file_path) }}" type="audio/mpeg">
    </audio>
    @endif

    @php
        $isWinner = $battle->winner_id === auth()->id();
        $winner = $battle->winner;
    @endphp

    <div class="result-container">
        <div class="result-card">
            <!-- Header -->
            <div class="result-header {{ $isWinner ? 'victory' : 'defeat' }}">
                @if($isWinner)
                    <!-- Confetti for victory -->
                    @for($i = 0; $i < 20; $i++)
                        <div class="confetti" style="
                            left: {{ rand(0, 100) }}%;
                            background: {{ ['#FFD700', '#FFA500', '#FF6B6B', '#4ECDC4', '#A78BFA'][$i % 5] }};
                            animation-delay: {{ $i * 0.15 }}s;
                            border-radius: {{ rand(0, 1) ? '50%' : '0' }};
                        "></div>
                    @endfor
                @endif

                <div class="result-icon">{{ $isWinner ? '🏆' : '💀' }}</div>
                <h1 class="result-title">{{ $isWinner ? 'Victoire !' : 'Défaite' }}</h1>
            </div>

            <!-- Body -->
            <div class="result-body">
                <!-- Versus -->
                <div class="versus-section">
                    <div class="player-result player1 {{ $battle->winner_id === $battle->player1_id ? 'winner' : 'loser' }}">
                        <div class="player-avatar-result">
                            {{ strtoupper(substr($battle->player1->name, 0, 1)) }}
                        </div>
                        <div class="player-name-result">{{ $battle->player1->name }}</div>
                        @if($battle->winner_id === $battle->player1_id)
                            <div class="winner-badge">🏆 Vainqueur</div>
                        @endif
                    </div>

                    <div class="vs-divider">VS</div>

                    <div class="player-result player2 {{ $battle->winner_id === $battle->player2_id ? 'winner' : 'loser' }}">
                        <div class="player-avatar-result">
                            {{ strtoupper(substr($battle->player2->name, 0, 1)) }}
                        </div>
                        <div class="player-name-result">{{ $battle->player2->name }}</div>
                        @if($battle->winner_id === $battle->player2_id)
                            <div class="winner-badge">🏆 Vainqueur</div>
                        @endif
                    </div>
                </div>

                <!-- Reward -->
                <div class="reward-section">
                    <div class="reward-label">Récompense obtenue</div>
                    <div class="reward-amount">🪙 +{{ $isWinner ? '150' : '25' }} pièces</div>
                </div>

                <!-- Actions -->
                <div class="result-actions">
                    <a href="{{ route('pvp.lobby') }}" class="action-btn primary">
                        🎮 Rejouer
                    </a>
                    <a href="{{ route('dashboard') }}" class="action-btn secondary">
                        🏠 Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Music autoplay script -->
    @if(isset($resultMusic) && $resultMusic)
    <script>
        const resultMusic = document.getElementById('resultMusic');
        if (resultMusic) {
            resultMusic.volume = {{ $resultMusic->volume / 100 }};

            // Try autoplay
            resultMusic.play().catch(err => {
                console.log('Autoplay blocked, waiting for interaction');
            });

            // Play on first interaction if autoplay blocked
            function playOnInteraction() {
                resultMusic.play().catch(e => {});
                document.removeEventListener('click', playOnInteraction);
                document.removeEventListener('touchstart', playOnInteraction);
            }
            document.addEventListener('click', playOnInteraction);
            document.addEventListener('touchstart', playOnInteraction);
        }
    </script>
    @endif
</body>
</html>
