<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Combat - Saint Seiya Deckbuilding</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            color: white;
            overflow-x: hidden;
        }

        /* ========================================
           FOND COSMOS ANIMÉ
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
           LAYOUT PRINCIPAL
        ======================================== */
        .battle-container {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .battle-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .battle-title {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(to right, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .turn-indicator {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .turn-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .turn-badge.player-turn {
            background: linear-gradient(135deg, #10B981, #059669);
            animation: pulse-turn 1.5s infinite;
        }

        .turn-badge.enemy-turn {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        @keyframes pulse-turn {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
            50% { box-shadow: 0 0 20px 5px rgba(16, 185, 129, 0.3); }
        }

        .quit-btn {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #EF4444;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .quit-btn:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        /* ========================================
           ZONE DE COMBAT
        ======================================== */
        .battle-arena {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem 2rem;
            gap: 1rem;
        }

        /* Zone adversaire */
        .opponent-zone {
            display: flex;
            gap: 1.5rem;
            align-items: flex-start;
        }

        .opponent-info {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 16px;
            padding: 1rem;
            min-width: 200px;
        }

        .opponent-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #EF4444;
            margin-bottom: 0.5rem;
        }

        .opponent-stats {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            font-size: 0.85rem;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.6);
        }

        .stat-value {
            font-weight: 700;
        }

        .opponent-field {
            flex: 1;
            display: flex;
            gap: 1rem;
            justify-content: center;
            min-height: 200px;
            padding: 1rem;
            background: rgba(239, 68, 68, 0.05);
            border: 1px dashed rgba(239, 68, 68, 0.2);
            border-radius: 16px;
        }

        /* Zone centrale - Log de combat */
        .battle-log-zone {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            max-height: 120px;
            overflow-y: auto;
        }

        .battle-log-zone::-webkit-scrollbar {
            width: 6px;
        }

        .battle-log-zone::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
        }

        .battle-log-zone::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .log-entry {
            padding: 0.25rem 0;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            animation: fadeIn 0.3s ease;
        }

        .log-entry:last-child {
            border-bottom: none;
        }

        .log-entry.damage { color: #EF4444; }
        .log-entry.heal { color: #10B981; }
        .log-entry.info { color: #60A5FA; }
        .log-entry.turn { color: #FBBF24; font-weight: 600; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Zone joueur */
        .player-zone {
            display: flex;
            gap: 1.5rem;
            align-items: flex-end;
        }

        .player-info {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 16px;
            padding: 1rem;
            min-width: 200px;
        }

        .player-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #10B981;
            margin-bottom: 0.5rem;
        }

        .player-field {
            flex: 1;
            display: flex;
            gap: 1rem;
            justify-content: center;
            min-height: 200px;
            padding: 1rem;
            background: rgba(16, 185, 129, 0.05);
            border: 1px dashed rgba(16, 185, 129, 0.2);
            border-radius: 16px;
        }

        /* ========================================
           CARTES DE COMBAT
        ======================================== */
        .battle-card {
            width: 140px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #333), var(--color2, #555));
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .battle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .battle-card.selected {
            border-color: #FBBF24;
            box-shadow: 0 0 20px rgba(251, 191, 36, 0.5);
            transform: translateY(-10px);
        }

        .battle-card.targetable {
            border-color: #EF4444;
            animation: pulse-target 1s infinite;
        }

        @keyframes pulse-target {
            0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 25px rgba(239, 68, 68, 0.6); }
        }

        .battle-card.disabled {
            opacity: 0.5;
            pointer-events: none;
        }

        .battle-card-image {
            height: 100px;
            background-size: cover;
            background-position: top center;
            position: relative;
        }

        .battle-card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
        }

        .battle-card-info {
            padding: 8px;
            background: rgba(0, 0, 0, 0.7);
        }

        .battle-card-name {
            font-size: 0.75rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 6px;
        }

        /* Barre de vie */
        .hp-bar-container {
            height: 8px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .hp-bar {
            height: 100%;
            background: linear-gradient(90deg, #EF4444, #22C55E);
            border-radius: 4px;
            transition: width 0.5s ease;
        }

        .hp-bar.low {
            background: #EF4444;
            animation: pulse-hp 1s infinite;
        }

        @keyframes pulse-hp {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .battle-card-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.65rem;
        }

        .mini-stat {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        /* ========================================
           MAIN DU JOUEUR
        ======================================== */
        .player-hand-zone {
            background: rgba(0, 0, 0, 0.6);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 1rem 2rem;
        }

        .hand-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .hand-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
        }

        .cosmos-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(124, 58, 237, 0.2);
            border: 1px solid rgba(124, 58, 237, 0.4);
            padding: 0.4rem 1rem;
            border-radius: 20px;
        }

        .cosmos-display span {
            font-weight: 700;
            color: #A78BFA;
        }

        .player-hand {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            min-height: 180px;
            align-items: center;
        }

        .hand-card {
            width: 130px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #333), var(--color2, #555));
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .hand-card:hover {
            transform: translateY(-15px) scale(1.05);
            z-index: 10;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        }

        .hand-card.playable {
            border-color: rgba(16, 185, 129, 0.5);
        }

        .hand-card.playable:hover {
            border-color: #10B981;
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.3);
        }

        .hand-card.unplayable {
            opacity: 0.5;
        }

        .hand-card-cost {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(124, 58, 237, 0.9);
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 10px;
            z-index: 5;
        }

        .hand-card-image {
            height: 90px;
            background-size: cover;
            background-position: top center;
        }

        .hand-card-info {
            padding: 8px;
            background: rgba(0, 0, 0, 0.8);
        }

        .hand-card-name {
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }

        .hand-card-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.8);
        }

        /* ========================================
           PANNEAU D'ACTIONS
        ======================================== */
        .action-panel {
            position: fixed;
            bottom: 200px;
            right: 2rem;
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1rem;
            min-width: 250px;
            z-index: 100;
            display: none;
        }

        .action-panel.visible {
            display: block;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .action-panel-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #FBBF24;
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .attack-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .attack-btn {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .attack-btn:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .attack-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .attack-name {
            font-weight: 600;
            font-size: 0.85rem;
        }

        .attack-info {
            display: flex;
            gap: 0.5rem;
            font-size: 0.7rem;
        }

        .attack-damage {
            color: #EF4444;
            font-weight: 700;
        }

        .attack-cost {
            color: #A78BFA;
        }

        .cancel-btn {
            width: 100%;
            margin-top: 0.75rem;
            padding: 0.5rem;
            background: rgba(107, 114, 128, 0.3);
            border: 1px solid rgba(107, 114, 128, 0.5);
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .cancel-btn:hover {
            background: rgba(107, 114, 128, 0.5);
        }

        /* ========================================
           BOUTONS DE CONTRÔLE
        ======================================== */
        .control-buttons {
            position: fixed;
            bottom: 130px;
            left: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            z-index: 100;
        }

        .control-btn {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .end-turn-btn {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .end-turn-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }

        .end-turn-btn:disabled {
            background: #4B5563;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* ========================================
           ANIMATIONS D'ATTAQUE
        ======================================== */
        .attack-animation {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            pointer-events: none;
        }

        .damage-number {
            position: absolute;
            font-size: 2rem;
            font-weight: 800;
            color: #EF4444;
            text-shadow: 0 0 10px rgba(239, 68, 68, 0.8);
            animation: damageFloat 1s forwards;
            z-index: 1000;
        }

        @keyframes damageFloat {
            0% { opacity: 1; transform: translateY(0) scale(1); }
            100% { opacity: 0; transform: translateY(-50px) scale(1.5); }
        }

        .heal-number {
            color: #10B981;
            text-shadow: 0 0 10px rgba(16, 185, 129, 0.8);
        }

        /* ========================================
           MODAL GAME OVER
        ======================================== */
        .game-over-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .game-over-modal.visible {
            display: flex;
            animation: fadeIn 0.5s ease;
        }

        .game-over-content {
            text-align: center;
            padding: 3rem;
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            border-radius: 24px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            max-width: 400px;
        }

        .game-over-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .game-over-title.victory {
            background: linear-gradient(to right, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .game-over-title.defeat {
            color: #EF4444;
        }

        .game-over-subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
        }

        .reward-display {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #FBBF24;
            margin-bottom: 2rem;
            padding: 1rem;
            background: rgba(251, 191, 36, 0.1);
            border-radius: 12px;
        }

        .game-over-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .game-over-btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .game-over-btn.primary {
            background: linear-gradient(135deg, #8B5CF6, #6366F1);
            color: white;
        }

        .game-over-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(139, 92, 246, 0.4);
        }

        .game-over-btn.secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .game-over-btn.secondary:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* ========================================
           LOADING OVERLAY
        ======================================== */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
        }

        .loading-overlay.visible {
            display: flex;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-top-color: #8B5CF6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ========================================
           EMPTY SLOT
        ======================================== */
        .empty-slot {
            width: 140px;
            height: 180px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 2rem;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 1024px) {
            .battle-card, .hand-card {
                width: 110px;
            }

            .battle-card-image, .hand-card-image {
                height: 70px;
            }

            .action-panel {
                right: 1rem;
                min-width: 200px;
            }

            .control-buttons {
                left: 1rem;
            }
        }

        @media (max-width: 768px) {
            .opponent-info, .player-info {
                display: none;
            }

            .battle-header {
                padding: 0.75rem 1rem;
            }

            .battle-arena {
                padding: 0.5rem 1rem;
            }

            .player-hand-zone {
                padding: 0.75rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="battle-container">
        <!-- Header -->
        <header class="battle-header">
            <h1 class="battle-title">⚔️ Combat</h1>
            <div class="turn-indicator">
                <span class="turn-badge player-turn" id="turnBadge">Tour 1 - Votre tour</span>
            </div>
            <a href="{{ route('game.index') }}" class="quit-btn" onclick="return confirm('Abandonner le combat ?')">
                ✖ Quitter
            </a>
        </header>

        <!-- Zone de combat -->
        <div class="battle-arena">
            <!-- Zone adversaire -->
            <div class="opponent-zone">
                <div class="opponent-info">
                    <div class="opponent-name">🤖 Adversaire IA</div>
                    <div class="opponent-stats">
                        <div class="stat-row">
                            <span class="stat-label">Deck</span>
                            <span class="stat-value" id="opponentDeckCount">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Main</span>
                            <span class="stat-value" id="opponentHandCount">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">🌟 Cosmos</span>
                            <span class="stat-value" id="opponentCosmos">0/0</span>
                        </div>
                    </div>
                </div>
                <div class="opponent-field" id="opponentField">
                    <!-- Cartes adversaires -->
                </div>
            </div>

            <!-- Log de combat -->
            <div class="battle-log-zone" id="battleLog">
                <div class="log-entry turn">⚔️ Le combat commence !</div>
            </div>

            <!-- Zone joueur -->
            <div class="player-zone">
                <div class="player-info">
                    <div class="player-name">👤 {{ auth()->user()->name }}</div>
                    <div class="opponent-stats">
                        <div class="stat-row">
                            <span class="stat-label">Deck</span>
                            <span class="stat-value" id="playerDeckCount">0</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Main</span>
                            <span class="stat-value" id="playerHandCount">0</span>
                        </div>
                    </div>
                </div>
                <div class="player-field" id="playerField">
                    <!-- Cartes du joueur -->
                </div>
            </div>
        </div>

        <!-- Main du joueur -->
        <div class="player-hand-zone">
            <div class="hand-header">
                <span class="hand-title">🎴 Votre main</span>
                <div class="cosmos-display">
                    🌟 <span id="playerCosmos">0</span> / <span id="playerMaxCosmos">0</span>
                </div>
            </div>
            <div class="player-hand" id="playerHand">
                <!-- Cartes en main -->
            </div>
        </div>
    </div>

    <!-- Panneau d'actions -->
    <div class="action-panel" id="actionPanel">
        <div class="action-panel-title">⚔️ Choisir une attaque</div>
        <div class="attack-list" id="attackList">
            <!-- Attaques disponibles -->
        </div>
        <button class="cancel-btn" onclick="cancelSelection()">Annuler</button>
    </div>

    <!-- Boutons de contrôle -->
    <div class="control-buttons">
        <button class="control-btn end-turn-btn" id="endTurnBtn" onclick="endTurn()">
            ⏭️ Fin du tour
        </button>
    </div>

    <!-- Modal Game Over -->
    <div class="game-over-modal" id="gameOverModal">
        <div class="game-over-content">
            <div class="game-over-title" id="gameOverTitle">Victoire !</div>
            <div class="game-over-subtitle" id="gameOverSubtitle">Vous avez vaincu l'adversaire !</div>
            <div class="reward-display">
                🪙 +<span id="rewardAmount">100</span> pièces
            </div>
            <div class="game-over-buttons">
                <a href="{{ route('game.index') }}" class="game-over-btn primary">🎮 Rejouer</a>
                <a href="{{ route('dashboard') }}" class="game-over-btn secondary">🏠 Accueil</a>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // Configuration
        const deckId = {{ $deck->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // État du jeu
        let gameState = null;
        let selectedAttacker = null;
        let selectedAttack = null;
        let phase = 'idle'; // idle, selectingAttacker, selectingAttack, selectingTarget

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            initBattle();
        });

        // API calls
        async function apiCall(endpoint, method = 'POST', body = {}) {
            showLoading();
            try {
                const response = await fetch(`/api/v1/game/${endpoint}`, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: method !== 'GET' ? JSON.stringify(body) : undefined,
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                hideLoading();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Erreur API');
                }
                
                return data;
            } catch (error) {
                hideLoading();
                console.error('API Error:', error);
                addLogEntry('❌ Erreur: ' + error.message, 'damage');
                throw error;
            }
        }

        // Initialiser le combat
        async function initBattle() {
            try {
                const data = await apiCall('init-battle', 'POST', { deck_id: deckId });
                gameState = data.battle_state;
                renderAll();
                addLogEntry('🎮 Combat initialisé ! C\'est votre tour.', 'turn');
            } catch (error) {
                console.error('Init failed:', error);
            }
        }

        // Rendu complet
        function renderAll() {
            renderOpponentField();
            renderPlayerField();
            renderPlayerHand();
            updateStats();
            updateTurnIndicator();
        }

        // Rendu du terrain adverse
        function renderOpponentField() {
            const container = document.getElementById('opponentField');
            container.innerHTML = '';
            
            if (!gameState || !gameState.opponent.field.length) {
                container.innerHTML = '<div class="empty-slot">👻</div>';
                return;
            }

            gameState.opponent.field.forEach((card, index) => {
                container.appendChild(createBattleCard(card, index, 'opponent'));
            });
        }

        // Rendu du terrain joueur
        function renderPlayerField() {
            const container = document.getElementById('playerField');
            container.innerHTML = '';
            
            if (!gameState || !gameState.player.field.length) {
                container.innerHTML = '<div class="empty-slot">🎴</div>';
                return;
            }

            gameState.player.field.forEach((card, index) => {
                container.appendChild(createBattleCard(card, index, 'player'));
            });
        }

        // Rendu de la main
        function renderPlayerHand() {
            const container = document.getElementById('playerHand');
            container.innerHTML = '';
            
            if (!gameState || !gameState.player.hand.length) {
                container.innerHTML = '<div style="color: rgba(255,255,255,0.5);">Aucune carte en main</div>';
                return;
            }

            gameState.player.hand.forEach((card, index) => {
                container.appendChild(createHandCard(card, index));
            });
        }

        // Créer une carte de combat
        function createBattleCard(card, index, owner) {
            const div = document.createElement('div');
            div.className = 'battle-card';
            div.dataset.index = index;
            div.dataset.owner = owner;
            
            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            const hpPercent = (card.current_hp / card.max_hp) * 100;
            const hpClass = hpPercent <= 25 ? 'low' : '';

            div.innerHTML = `
                <div class="battle-card-image" style="background-image: url('${card.image || ''}'); background-color: ${card.faction?.color_primary || '#333'};"></div>
                <div class="battle-card-info">
                    <div class="battle-card-name">${card.name}</div>
                    <div class="hp-bar-container">
                        <div class="hp-bar ${hpClass}" style="width: ${hpPercent}%"></div>
                    </div>
                    <div class="battle-card-stats">
                        <span class="mini-stat">❤️ ${card.current_hp}/${card.max_hp}</span>
                        <span class="mini-stat">⚡ ${card.current_endurance || 0}</span>
                        <span class="mini-stat">💪 ${card.power || 0}</span>
                    </div>
                </div>
            `;

            // Events selon le contexte
            if (owner === 'player' && phase === 'idle') {
                div.onclick = () => selectAttacker(index);
            } else if (owner === 'opponent' && phase === 'selectingTarget') {
                div.classList.add('targetable');
                div.onclick = () => selectTarget(index);
            }

            if (owner === 'player' && selectedAttacker === index) {
                div.classList.add('selected');
            }

            return div;
        }

        // Créer une carte de main
        function createHandCard(card, index) {
            const div = document.createElement('div');
            div.className = 'hand-card';
            div.dataset.index = index;
            
            const canPlay = gameState.player.cosmos >= card.cost && gameState.player.field.length < 3;
            div.classList.add(canPlay ? 'playable' : 'unplayable');

            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            div.innerHTML = `
                <div class="hand-card-cost">💎 ${card.cost}</div>
                <div class="hand-card-image" style="background-image: url('${card.image || ''}'); background-color: ${card.faction?.color_primary || '#333'};"></div>
                <div class="hand-card-info">
                    <div class="hand-card-name">${card.name}</div>
                    <div class="hand-card-stats">
                        <span>❤️ ${card.health_points}</span>
                        <span>💪 ${card.power}</span>
                    </div>
                </div>
            `;

            if (canPlay) {
                div.onclick = () => playCard(index);
            }

            return div;
        }

        // Jouer une carte
        async function playCard(index) {
            try {
                const data = await apiCall('play-card', 'POST', {
                    deck_id: deckId,
                    card_index: index,
                    battle_state: gameState
                });
                
                gameState = data.battle_state;
                addLogEntry(`🎴 Vous jouez ${data.card_played}`, 'info');
                renderAll();
            } catch (error) {
                console.error('Play card failed:', error);
            }
        }

        // Sélectionner un attaquant
        function selectAttacker(index) {
            const card = gameState.player.field[index];
            if (!card || card.has_attacked) {
                addLogEntry('❌ Cette carte a déjà attaqué ce tour', 'damage');
                return;
            }

            selectedAttacker = index;
            phase = 'selectingAttack';
            showAttackPanel(card);
            renderAll();
        }

        // Afficher le panneau d'attaque
        function showAttackPanel(card) {
    const panel = document.getElementById('actionPanel');
    const list = document.getElementById('attackList');
    list.innerHTML = '';

    const state = getMyState();
    
    // Attaque principale (toujours disponible)
    const mainAttack = card.main_attack || {
        name: 'Attaque de base',
        damage: 40 + (card.power || 0),
        endurance_cost: 20,
        cosmos_cost: 0
    };

    const attacks = [
        { 
            key: 'main', 
            name: mainAttack.name, 
            damage: mainAttack.damage, 
            endCost: mainAttack.endurance_cost || 20, 
            cosCost: mainAttack.cosmos_cost || 0 
        }
    ];

    // Attaques secondaires si disponibles
    if (card.secondary_attack_1) {
        attacks.push({ 
            key: 'secondary1', 
            name: card.secondary_attack_1.name, 
            damage: card.secondary_attack_1.damage, 
            endCost: card.secondary_attack_1.endurance_cost, 
            cosCost: card.secondary_attack_1.cosmos_cost 
        });
    }

    if (card.secondary_attack_2) {
        attacks.push({ 
            key: 'secondary2', 
            name: card.secondary_attack_2.name, 
            damage: card.secondary_attack_2.damage, 
            endCost: card.secondary_attack_2.endurance_cost, 
            cosCost: card.secondary_attack_2.cosmos_cost 
        });
    }

    console.log('Attaques disponibles:', attacks); // Debug

    attacks.forEach(atk => {
        const canUse = (card.current_endurance || 100) >= atk.endCost && state.cosmos >= atk.cosCost;
        
        const btn = document.createElement('button');
        btn.className = 'attack-btn';
        btn.disabled = !canUse;
        btn.innerHTML = `
            <div>
                <div style="font-weight: 600;">${atk.name}</div>
                <div style="font-size: 0.7rem; color: #9CA3AF;">
                    ⚡ ${atk.endCost} END | 🌟 ${atk.cosCost} COS
                </div>
            </div>
            <div style="font-size: 1.2rem; color: #EF4444; font-weight: bold;">
                ${atk.damage} 💥
            </div>
        `;
        btn.onclick = () => {
            if (canUse) selectAttack(atk.key);
        };
        list.appendChild(btn);
    });

    panel.classList.add('visible');
    
    // Positionner le panneau près de la carte
    console.log('Panneau d\'attaque affiché');
}

        // Sélectionner une attaque
        function selectAttack(attackKey) {
            selectedAttack = attackKey;
            phase = 'selectingTarget';
            document.getElementById('actionPanel').classList.remove('visible');
            addLogEntry('🎯 Sélectionnez une cible adverse', 'info');
            renderAll();
        }

        // Sélectionner une cible
        async function selectTarget(targetIndex) {
            try {
                const data = await apiCall('attack', 'POST', {
                    deck_id: deckId,
                    attacker_index: selectedAttacker,
                    attack_type: selectedAttack,
                    target_index: targetIndex,
                    battle_state: gameState
                });

                gameState = data.battle_state;
                
                // Animation de dégâts
                showDamageNumber(data.damage, targetIndex);
                
                addLogEntry(`⚔️ ${data.message}`, 'damage');

                // Vérifier fin de partie
                if (data.battle_ended) {
                    endGame(data.winner === 'player');
                }

                cancelSelection();
                renderAll();
            } catch (error) {
                console.error('Attack failed:', error);
                cancelSelection();
            }
        }

        // Annuler la sélection
        function cancelSelection() {
            selectedAttacker = null;
            selectedAttack = null;
            phase = 'idle';
            document.getElementById('actionPanel').classList.remove('visible');
            renderAll();
        }

        // Fin du tour
        async function endTurn() {
            try {
                const data = await apiCall('end-turn', 'POST', {
                    deck_id: deckId,
                    battle_state: gameState
                });

                gameState = data.battle_state;
                
                addLogEntry('⏭️ Fin de votre tour', 'turn');
                
                // Actions IA
                if (data.ai_actions) {
                    for (const action of data.ai_actions) {
                        addLogEntry(`🤖 ${action}`, 'info');
                        await sleep(500);
                    }
                }

                addLogEntry(`🔄 Tour ${gameState.turn} - Votre tour !`, 'turn');

                // Vérifier fin de partie
                if (data.battle_ended) {
                    endGame(data.winner === 'player');
                }

                renderAll();
            } catch (error) {
                console.error('End turn failed:', error);
            }
        }

        // Afficher les dégâts
        function showDamageNumber(damage, targetIndex) {
            const field = document.getElementById('opponentField');
            const card = field.children[targetIndex];
            if (!card) return;

            const rect = card.getBoundingClientRect();
            const num = document.createElement('div');
            num.className = 'damage-number';
            num.textContent = `-${damage}`;
            num.style.left = rect.left + rect.width / 2 + 'px';
            num.style.top = rect.top + 'px';
            document.body.appendChild(num);

            setTimeout(() => num.remove(), 1000);
        }

        // Fin de partie
        function endGame(victory) {
            const modal = document.getElementById('gameOverModal');
            const title = document.getElementById('gameOverTitle');
            const subtitle = document.getElementById('gameOverSubtitle');
            const reward = document.getElementById('rewardAmount');

            if (victory) {
                title.textContent = '🏆 Victoire !';
                title.className = 'game-over-title victory';
                subtitle.textContent = 'Vous avez vaincu l\'adversaire !';
                reward.textContent = '100';
            } else {
                title.textContent = '💀 Défaite';
                title.className = 'game-over-title defeat';
                subtitle.textContent = 'L\'adversaire vous a vaincu...';
                reward.textContent = '25';
            }

            // Claim reward
            apiCall('claim-reward', 'POST', {
                deck_id: deckId,
                victory: victory,
                battle_state: gameState
            });

            modal.classList.add('visible');
        }

        // Mettre à jour les stats
        function updateStats() {
            if (!gameState) return;

            document.getElementById('playerDeckCount').textContent = gameState.player.deck?.length || 0;
            document.getElementById('playerHandCount').textContent = gameState.player.hand?.length || 0;
            document.getElementById('playerCosmos').textContent = gameState.player.cosmos || 0;
            document.getElementById('playerMaxCosmos').textContent = gameState.player.max_cosmos || 0;

            document.getElementById('opponentDeckCount').textContent = gameState.opponent.deck?.length || 0;
            document.getElementById('opponentHandCount').textContent = gameState.opponent.hand?.length || 0;
            document.getElementById('opponentCosmos').textContent = `${gameState.opponent.cosmos || 0}/${gameState.opponent.max_cosmos || 0}`;
        }

        // Mettre à jour l'indicateur de tour
        function updateTurnIndicator() {
            if (!gameState) return;
            const badge = document.getElementById('turnBadge');
            badge.textContent = `Tour ${gameState.turn} - Votre tour`;
        }

        // Ajouter une entrée au log
        function addLogEntry(message, type = 'info') {
            const log = document.getElementById('battleLog');
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            entry.textContent = message;
            log.appendChild(entry);
            log.scrollTop = log.scrollHeight;
        }

        // Helpers
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('visible');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('visible');
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    </script>
</body>
</html>