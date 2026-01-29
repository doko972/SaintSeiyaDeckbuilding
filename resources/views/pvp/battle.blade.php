<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PvP - {{ $battle->player1->name }} vs {{ $battle->player2->name }}</title>
    
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

        /* ========================================
           FOND COSMOS ANIMÉ
        ======================================== */
        .cosmos-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
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
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        .bg-image {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            opacity: 0.1;
            object-fit: cover;
        }

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

        .player-vs {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .player-badge {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
        }

        .player-badge.player1 {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.5);
        }

        .player-badge.player2 {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
        }

        .player-badge.current-turn {
            animation: pulse-turn 1.5s infinite;
        }

        @keyframes pulse-turn {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.5); }
            50% { box-shadow: 0 0 20px 5px rgba(34, 197, 94, 0.3); }
        }

        .player-avatar {
            width: 40px; height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .player-badge.player1 .player-avatar { background: linear-gradient(135deg, #3B82F6, #1D4ED8); }
        .player-badge.player2 .player-avatar { background: linear-gradient(135deg, #EF4444, #DC2626); }

        .vs-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #FFD700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .turn-indicator {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .turn-indicator.my-turn {
            background: linear-gradient(135deg, #10B981, #059669);
            animation: pulse-turn 1.5s infinite;
        }

        .turn-indicator.opponent-turn {
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }

        /* Zone de combat */
        .battle-arena {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 1rem 2rem;
            gap: 1rem;
        }

        .field-zone {
            flex: 1;
            display: flex;
            gap: 1rem;
            justify-content: center;
            align-items: center;
            min-height: 180px;
            padding: 1rem;
            border-radius: 16px;
        }

        .opponent-field {
            background: rgba(239, 68, 68, 0.05);
            border: 1px dashed rgba(239, 68, 68, 0.3);
        }

        .player-field {
            background: rgba(59, 130, 246, 0.05);
            border: 1px dashed rgba(59, 130, 246, 0.3);
        }

        /* Notifications Toast - Position haut centre */
        .battle-log-zone {
            position: fixed;
            top: 2%;
            right: 40%;
            display: flex;
            flex-direction: column-reverse;
            gap: 0.5rem;
            z-index: 500;
            pointer-events: none;
            max-width: 350px;
            width: auto;
        }

        .log-entry {
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
            font-weight: 600;
            text-align: center;
            border-radius: 12px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            pointer-events: auto;
            position: relative;
            overflow: hidden;
        }

        /* Animation d'entrée */
        .log-entry.entering {
            animation: toastEnter 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        /* Animation de sortie */
        .log-entry.exiting {
            animation: toastExit 0.3s ease-in forwards;
        }

        /* Animation de descente (quand un nouveau toast arrive) */
        .log-entry.shifting {
            animation: toastShift 0.3s ease-out forwards;
        }

        .log-entry.damage {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(185, 28, 28, 0.9));
            border: 1px solid rgba(252, 165, 165, 0.5);
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .log-entry.heal {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.9), rgba(5, 150, 105, 0.9));
            border: 1px solid rgba(110, 231, 183, 0.5);
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .log-entry.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.9), rgba(37, 99, 235, 0.9));
            border: 1px solid rgba(147, 197, 253, 0.5);
            color: white;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .log-entry.turn {
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.95), rgba(245, 158, 11, 0.95));
            border: 1px solid rgba(253, 224, 71, 0.6);
            color: #1a1a2e;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.3);
            font-weight: 700;
        }

        /* Barre de progression */
        .log-entry::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            animation: progressBar 4s linear forwards;
        }

        .log-entry.turn::after {
            background: rgba(0, 0, 0, 0.3);
        }

        @keyframes toastEnter {
            0% {
                opacity: 0;
                transform: translateX(50px) scale(0.8);
            }
            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes toastExit {
            0% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
            100% {
                opacity: 0;
                transform: translateX(100px) scale(0.8);
            }
        }

        @keyframes toastShift {
            0% { transform: translateY(10px); }
            100% { transform: translateY(0); }
        }

        @keyframes progressBar {
            0% { width: 100%; }
            100% { width: 0%; }
        }

        /* Effet de brillance sur les messages importants */
        .log-entry.turn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 2s ease-in-out infinite;
        }

        /* Effet hover pour fermer */
        .log-entry:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
            transition: transform 0.2s, filter 0.2s;
        }

        .log-entry:hover::after {
            animation-play-state: paused;
        }

        @keyframes shine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        /* Cartes */
        .battle-card {
            width: 130px;
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
        }

        .battle-card.targetable {
            border-color: #EF4444;
            animation: pulse-target 1s infinite;
        }

        @keyframes pulse-target {
            0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.3); }
            50% { box-shadow: 0 0 25px rgba(239, 68, 68, 0.6); }
        }

        .battle-card-image {
            height: 90px;
            background-size: cover;
            background-position: top center;
        }

        .battle-card-info {
            padding: 8px;
            background: rgba(0, 0, 0, 0.7);
        }

        .battle-card-name {
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
        }

        .hp-bar-container {
            height: 6px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 4px;
        }

        .hp-bar {
            height: 100%;
            background: linear-gradient(90deg, #EF4444, #22C55E);
            border-radius: 3px;
            transition: width 0.5s ease;
        }

        .battle-card-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
        }

        /* Main du joueur */
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

        .cosmos-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(124, 58, 237, 0.2);
            border: 1px solid rgba(124, 58, 237, 0.4);
            padding: 0.4rem 1rem;
            border-radius: 20px;
        }

        .cosmos-display span { font-weight: 700; color: #A78BFA; }

        .player-hand {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            min-height: 160px;
        }

        .hand-card {
            width: 120px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #333), var(--color2, #555));
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .hand-card:hover {
            transform: translateY(-10px) scale(1.05);
            z-index: 10;
        }

        .hand-card.playable { border-color: rgba(16, 185, 129, 0.5); }
        .hand-card.playable:hover { border-color: #10B981; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3); }
        .hand-card.unplayable { opacity: 0.5; }

        .hand-card-cost {
            position: absolute;
            top: 5px; right: 5px;
            background: rgba(124, 58, 237, 0.9);
            color: white;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 8px;
        }

        .hand-card-image {
            height: 80px;
            background-size: cover;
            background-position: top center;
        }

        .hand-card-info {
            padding: 6px;
            background: rgba(0, 0, 0, 0.8);
        }

        .hand-card-name {
            font-size: 0.65rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hand-card-stats {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            margin-top: 4px;
            color: #E5E7EB;
        }

        .hand-card-stats span {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        /* Contrôles */
        .control-buttons {
            position: fixed;
            bottom: 110px;
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
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }

        .end-turn-btn {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
            color: white;
        }

        .end-turn-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
        }

        .end-turn-btn:disabled {
            background: #4B5563;
            cursor: not-allowed;
        }

        /* Action panel */
        .action-panel {
            position: fixed;
            bottom: 340px;
            right: 6rem;
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            padding: 1rem;
            min-width: 220px;
            z-index: 100;
            display: none;
        }

        .action-panel.visible { display: block; }

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
            width: 100%;
            margin-bottom: 0.5rem;
            color: white;
        }

        .attack-btn:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .attack-btn:disabled { opacity: 0.4; cursor: not-allowed; }

        .cancel-btn {
            width: 100%;
            padding: 0.5rem;
            background: rgba(107, 114, 128, 0.3);
            border: 1px solid rgba(107, 114, 128, 0.5);
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }

        /* Waiting overlay */
        .waiting-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
        }

        .waiting-overlay.visible { display: flex; }

        .waiting-content {
            text-align: center;
            padding: 2rem;
        }

        .waiting-content h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Empty slot */
        .empty-slot {
            width: 130px;
            height: 160px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.3);
            font-size: 2rem;
        }

        .quit-form {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
        }

        .quit-btn {
            padding: 0.5rem 0.8rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #EF4444;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .quit-btn:hover {
            background: rgba(239, 68, 68, 0.4);
        }

        .player-info {
            display: flex;
            flex-direction: column;
        }

        .player-name {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .deck-name {
            font-size: 0.7rem;
            color: #9CA3AF;
        }

        .battle-header {
            position: relative;
        }

        /* Music controls */
        .music-controls {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .music-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: rgba(124, 58, 237, 0.3);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(124, 58, 237, 0.5);
        }

        .music-btn:hover {
            background: rgba(124, 58, 237, 0.5);
            transform: scale(1.1);
        }

        .music-btn.playing {
            background: rgba(124, 58, 237, 0.6);
            animation: pulse-music 2s infinite;
        }

        @keyframes pulse-music {
            0%, 100% { box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.4); }
            50% { box-shadow: 0 0 15px 5px rgba(124, 58, 237, 0.2); }
        }

        .volume-panel {
            position: absolute;
            left: 50px;
            top: 0;
            background: rgba(0, 0, 0, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 0.75rem;
            min-width: 180px;
            display: none;
        }

        .volume-panel.visible {
            display: block;
        }

        .volume-slider-container {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .volume-slider {
            flex: 1;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            appearance: none;
            cursor: pointer;
        }

        .volume-slider::-webkit-slider-thumb {
            appearance: none;
            width: 14px;
            height: 14px;
            background: #A78BFA;
            border-radius: 50%;
            cursor: pointer;
        }

        .volume-value {
            font-size: 0.75rem;
            color: #A78BFA;
            min-width: 35px;
            text-align: right;
        }

        .track-select {
            margin-top: 0.5rem;
            width: 100%;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            padding: 0.4rem;
            color: white;
            font-size: 0.75rem;
        }

        .track-select option {
            background: #1a1a2e;
            color: white;
        }

        /* Overlay pour fermer le panneau */
        .action-panel-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 99;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .action-panel-overlay.visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* Loading overlay */
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

        /* Game Over Modal */
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

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
           RESPONSIVE - TABLETTE
        ======================================== */
        @media (max-width: 1024px) {
            .battle-arena {
                padding: 0.5rem 1rem;
            }

            .field-zone {
                min-height: 150px;
                gap: 0.5rem;
            }

            .battle-card {
                width: 110px;
            }

            .battle-card-image {
                height: 75px;
            }

            .hand-card {
                width: 100px;
            }

            .hand-card-image {
                height: 65px;
            }

            .player-hand {
                gap: 0.5rem;
                min-height: 140px;
            }

            .action-panel {
                bottom: 280px;
                right: 1rem;
            }

            .battle-log-zone {
                max-width: 300px;
                top: 2%;
                right: 15px;
            }

            .log-entry {
                font-size: 0.85rem;
                padding: 0.6rem 1rem;
            }
        }

        /* ========================================
           RESPONSIVE - MOBILE
        ======================================== */
        @media (max-width: 768px) {
            /* Music controls mobile */
            .music-controls {
                top: auto;
                bottom: 180px;
                left: 0.5rem;
            }

            .music-btn {
                width: 36px;
                height: 36px;
                font-size: 1rem;
            }

            .volume-panel {
                left: 42px;
                min-width: 150px;
                padding: 0.5rem;
            }

            /* Header mobile */
            .battle-header {
                flex-direction: column;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
            }

            .player-vs {
                gap: 0.5rem;
                width: 100%;
                justify-content: center;
            }

            .player-badge {
                padding: 0.3rem 0.5rem;
                gap: 0.4rem;
            }

            .player-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .vs-text {
                font-size: 1rem;
            }

            .turn-indicator {
                padding: 0.3rem 0.8rem;
                font-size: 0.75rem;
            }

            .quit-form {
                top: 0.3rem;
                right: 0.3rem;
            }

            .quit-btn {
                padding: 0.3rem 0.5rem;
                font-size: 0.7rem;
            }

            .player-info {
                display: none;
            }

            /* Zone de combat mobile */
            .battle-arena {
                padding: 0.3rem 0.5rem;
                gap: 0.5rem;
            }

            .field-zone {
                min-height: 120px;
                padding: 0.5rem;
                gap: 0.3rem;
                flex-wrap: wrap;
            }

            .battle-card {
                width: 85px;
            }

            .battle-card-image {
                height: 55px;
            }

            .battle-card-info {
                padding: 4px;
            }

            .battle-card-name {
                font-size: 0.55rem;
            }

            .hp-bar-container {
                height: 4px;
                margin-bottom: 2px;
            }

            .battle-card-stats {
                font-size: 0.5rem;
            }

            /* Log mobile */
            .battle-log-zone {
                top: 2%;
                right: 10px;
                max-width: 260px;
            }

            .log-entry {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }

            /* Main du joueur mobile */
            .player-hand-zone {
                padding: 0.5rem;
            }

            .hand-header {
                flex-direction: column;
                gap: 0.3rem;
                align-items: flex-start;
                margin-bottom: 0.5rem;
            }

            .hand-header > .flex {
                display: none;
            }

            .cosmos-display {
                padding: 0.3rem 0.6rem;
                font-size: 0.8rem;
            }

            .player-hand {
                gap: 0.4rem;
                min-height: 110px;
                justify-content: flex-start;
                overflow-x: auto;
                flex-wrap: nowrap;
                padding-bottom: 0.5rem;
            }

            .hand-card {
                width: 80px;
                flex-shrink: 0;
            }

            .hand-card-cost {
                font-size: 0.55rem;
                padding: 2px 4px;
                top: 3px;
                right: 3px;
            }

            .hand-card-image {
                height: 50px;
            }

            .hand-card-info {
                padding: 4px;
            }

            .hand-card-name {
                font-size: 0.55rem;
            }

            .hand-card-stats {
                font-size: 0.5rem;
                gap: 2px;
            }

            .hand-card-stats span {
                gap: 1px;
            }

            .hand-card:hover {
                transform: none;
            }

            /* Contrôles mobile */
            .control-buttons {
                position: fixed;
                /* bottom: auto; */
                top: 80%;
                left: 0.3rem;
                transform: translateY(-50%);
            }

            .control-btn {
                padding: 0.6rem 0.8rem;
                font-size: 0.7rem;
            }

            /* Panel d'action mobile */
            .action-panel {
                position: fixed;
                bottom: auto;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                right: auto;
                min-width: 250px;
                max-width: 90vw;
                z-index: 1500;
            }

            /* Empty slot mobile */
            .empty-slot {
                width: 85px;
                height: 100px;
                font-size: 1.5rem;
            }

            /* Waiting overlay mobile */
            .waiting-content h2 {
                font-size: 1.2rem;
            }

            .waiting-content {
                padding: 1rem;
            }
        }

        /* ========================================
           RESPONSIVE - PETIT MOBILE
        ======================================== */
        @media (max-width: 480px) {
            .battle-card {
                width: 70px;
            }

            .battle-card-image {
                height: 45px;
            }

            .battle-card-name {
                font-size: 0.5rem;
            }

            .hand-card {
                width: 70px;
            }

            .hand-card-image {
                height: 45px;
            }

            .player-hand {
                min-height: 95px;
            }

            .field-zone {
                min-height: 100px;
            }

            .empty-slot {
                width: 70px;
                height: 85px;
                font-size: 1.2rem;
            }

            .control-buttons {
                left: 70%;
            }

            .control-btn {
                padding: 0.5rem 0.6rem;
                font-size: 0.65rem;
            }
        }

        /* Amélioration tactile pour mobile */
        @media (hover: none) and (pointer: coarse) {
            .battle-card:hover,
            .hand-card:hover {
                transform: none;
            }

            .battle-card,
            .hand-card,
            .attack-btn,
            .control-btn {
                -webkit-tap-highlight-color: rgba(255, 255, 255, 0.1);
            }

            .battle-card.selected {
                transform: scale(1.05);
            }

            .battle-card.targetable {
                transform: scale(1.02);
            }
        }
    </style>
</head>
<body>
    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>
    <img src="{{ asset('images/baniere.webp') }}" alt="" class="bg-image">

    <!-- Music Player -->
    @if(isset($battleMusics) && $battleMusics->count() > 0)
    <audio id="battleMusic" loop preload="auto">
        <source src="{{ Storage::url($battleMusics->first()->file_path) }}" type="audio/mpeg">
    </audio>
    @endif

    <!-- Victory/Defeat Music -->
    @if(isset($victoryMusic) && $victoryMusic)
    <audio id="victoryMusic" preload="auto">
        <source src="{{ Storage::url($victoryMusic->file_path) }}" type="audio/mpeg">
    </audio>
    @endif

    @if(isset($defeatMusic) && $defeatMusic)
    <audio id="defeatMusic" preload="auto">
        <source src="{{ Storage::url($defeatMusic->file_path) }}" type="audio/mpeg">
    </audio>
    @endif

    @if(isset($battleMusics) && $battleMusics->count() > 0)

    <div class="music-controls">
        <button class="music-btn" id="musicToggle" title="Musique">
            🎵
        </button>
        <div class="volume-panel" id="volumePanel">
            <div class="volume-slider-container">
                <span>🔊</span>
                <input type="range" class="volume-slider" id="volumeSlider" min="0" max="100" value="{{ $battleMusics->first()->volume }}">
                <span class="volume-value" id="volumeValue">{{ $battleMusics->first()->volume }}%</span>
            </div>
            @if($battleMusics->count() > 1)
            <select class="track-select" id="trackSelect">
                @foreach($battleMusics as $music)
                <option value="{{ Storage::url($music->file_path) }}" data-volume="{{ $music->volume }}">{{ $music->name }}</option>
                @endforeach
            </select>
            @endif
        </div>
    </div>
    @endif

    <div class="battle-container">
        <!-- Header -->
        <header class="battle-header">
            <form action="{{ route('pvp.forfeit', $battle) }}" method="POST" class="quit-form" onsubmit="return confirm('Abandonner le combat ? Vous perdrez la partie.')">
                @csrf
                <button type="submit" class="quit-btn">✖</button>
            </form>

            <div class="player-vs">
                <div class="player-badge player1 {{ $battle->current_turn_user_id == $battle->player1_id ? 'current-turn' : '' }}">
                    <div class="player-avatar">{{ strtoupper(substr($battle->player1->name, 0, 1)) }}</div>
                    <div class="player-info">
                        <div class="player-name">{{ $battle->player1->name }}</div>
                        <div class="deck-name">{{ $battle->player1Deck->name }}</div>
                    </div>
                </div>

                <span class="vs-text">VS</span>

                <div class="player-badge player2 {{ $battle->current_turn_user_id == $battle->player2_id ? 'current-turn' : '' }}">
                    <div class="player-avatar">{{ strtoupper(substr($battle->player2->name, 0, 1)) }}</div>
                    <div class="player-info">
                        <div class="player-name">{{ $battle->player2->name }}</div>
                        <div class="deck-name">{{ $battle->player2Deck->name }}</div>
                    </div>
                </div>
            </div>

            <div class="turn-indicator {{ $isMyTurn ? 'my-turn' : 'opponent-turn' }}" id="turnIndicator">
                {{ $isMyTurn ? '🎮 Votre tour !' : '⏳ Tour adverse...' }}
            </div>
        </header>

        <!-- Zone de combat -->
        <div class="battle-arena">
            <!-- Terrain adversaire -->
            <div class="field-zone opponent-field" id="opponentField">
                <div class="empty-slot">👻</div>
            </div>

            <!-- Notifications Toast -->
            <div class="battle-log-zone" id="battleLog">
                <!-- Les notifications apparaissent ici dynamiquement -->
            </div>

            <!-- Terrain joueur -->
            <div class="field-zone player-field" id="playerField">
                <div class="empty-slot">🎴</div>
            </div>
        </div>

        <!-- Main du joueur -->
        <div class="player-hand-zone">
            <div class="hand-header">
                <div class="flex items-center gap-4">
                    <span class="text-gray-400">🎴 Votre main</span>
                    <span class="text-xs text-yellow-400 bg-yellow-400/20 px-2 py-1 rounded" id="helpText">
                        👆 Cliquez sur une carte pour la jouer sur le terrain
                    </span>
                </div>
                <div class="cosmos-display">
                    🌟 <span id="playerCosmos">0</span> / <span id="playerMaxCosmos">0</span>
                </div>
            </div>
            <div class="player-hand" id="playerHand">
                <!-- Cartes en main -->
            </div>
        </div>
    </div>

    <!-- Overlay pour fermer en cliquant à côté -->
    <div class="action-panel-overlay" id="actionPanelOverlay" onclick="cancelSelection()"></div>

    <!-- Panneau d'actions -->
    <div class="action-panel" id="actionPanel">
        <div style="font-weight: 700; color: #FBBF24; margin-bottom: 0.5rem;">⚔️ Choisir une attaque</div>
        <div id="attackList"></div>
        <button class="cancel-btn" onclick="cancelSelection()">Annuler</button>
    </div>

    <!-- Contrôles -->
    <div class="control-buttons">
        <button class="control-btn end-turn-btn" id="endTurnBtn" onclick="endTurn()" {{ !$isMyTurn ? 'disabled' : '' }}>
            ⏭️ Fin du tour
        </button>
    </div>

    <!-- Waiting overlay -->
    <div class="waiting-overlay {{ !$isMyTurn ? 'visible' : '' }}" id="waitingOverlay">
        <div class="waiting-content">
            <div class="text-4xl mb-4 animate-bounce">⏳</div>
            <h2>Tour de {{ $opponent->name }}</h2>
            <p class="text-gray-400">En attente de son action...</p>
        </div>
    </div>

    <!-- Modal Game Over -->
    <div class="game-over-modal" id="gameOverModal">
        <div class="game-over-content">
            <div class="game-over-title" id="gameOverTitle">Victoire !</div>
            <div class="game-over-subtitle" id="gameOverSubtitle">Vous avez gagné le combat !</div>
            <div class="game-over-buttons">
                <a href="{{ route('pvp.lobby') }}" class="game-over-btn primary">🎮 Retour au lobby</a>
                <a href="{{ route('dashboard') }}" class="game-over-btn secondary">🏠 Accueil</a>
            </div>
        </div>
    </div>

    <!-- Loading -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <script>
        // ========================================
        // CONFIGURATION
        // ========================================
        const battleId = {{ $battle->id }};
        const playerNumber = {{ $playerNumber }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        let gameState = @json($battle->battle_state);
        let isMyTurn = {{ $isMyTurn ? 'true' : 'false' }};
        let selectedAttacker = null;
        let selectedAttack = null;
        let phase = 'idle'; // idle, selectingAttacker, selectingAttack, selectingTarget

        // ========================================
        // SYSTÈME D'ANIMATIONS
        // ========================================
        const animations = {
            // Animation de jeu d'une carte
            playCardAnimation: async function(cardElement, targetPos) {
                return new Promise((resolve) => {
                    const clone = cardElement.cloneNode(true);
                    clone.style.position = 'fixed';
                    clone.style.zIndex = '1000';
                    const startRect = cardElement.getBoundingClientRect();
                    clone.style.left = startRect.left + 'px';
                    clone.style.top = startRect.top + 'px';
                    clone.style.width = startRect.width + 'px';
                    document.body.appendChild(clone);
                    cardElement.style.opacity = '0';

                    setTimeout(() => {
                        clone.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        clone.style.left = targetPos.x + 'px';
                        clone.style.top = targetPos.y + 'px';
                        clone.style.transform = 'scale(1.1) rotateY(360deg)';
                        clone.style.boxShadow = '0 0 30px rgba(16, 185, 129, 0.8)';
                    }, 50);

                    setTimeout(() => {
                        clone.remove();
                        cardElement.style.opacity = '1';
                        resolve();
                    }, 800);
                });
            },

            // Animation d'attaque
            attackAnimation: async function(attackerCard, targetCard, attackData) {
                return new Promise(async (resolve) => {
                    const clone = attackerCard.cloneNode(true);
                    clone.style.position = 'fixed';
                    clone.style.zIndex = '1001';
                    const startRect = attackerCard.getBoundingClientRect();
                    const targetRect = targetCard.getBoundingClientRect();
                    clone.style.left = startRect.left + 'px';
                    clone.style.top = startRect.top + 'px';
                    clone.style.width = startRect.width + 'px';
                    document.body.appendChild(clone);

                    setTimeout(() => {
                        clone.style.transition = 'all 0.3s ease-in';
                        clone.style.left = (targetRect.left - 20) + 'px';
                        clone.style.top = (targetRect.top - 20) + 'px';
                        clone.style.transform = 'scale(1.2)';
                    }, 50);

                    setTimeout(() => {
                        this.shakeElement(targetCard);
                        this.flashElement(targetCard, '#EF4444');
                    }, 350);

                    setTimeout(() => {
                        clone.style.transition = 'all 0.2s ease-out';
                        clone.style.left = startRect.left + 'px';
                        clone.style.top = startRect.top + 'px';
                        clone.style.transform = 'scale(1)';
                    }, 450);

                    setTimeout(() => {
                        clone.remove();
                        resolve();
                    }, 700);
                });
            },

            // Afficher les dégâts
            showDamage: function(targetElement, damage, type = 'damage') {
                const rect = targetElement.getBoundingClientRect();
                const x = rect.left + rect.width / 2;
                const y = rect.top + rect.height / 2;
                const damageText = document.createElement('div');
                damageText.textContent = type === 'heal' ? `+${damage}` : `-${damage}`;
                damageText.style.cssText = `
                    position: fixed;
                    left: ${x}px;
                    top: ${y}px;
                    font-size: 2rem;
                    font-weight: 900;
                    color: ${type === 'heal' ? '#10B981' : '#EF4444'};
                    text-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
                    z-index: 2000;
                    pointer-events: none;
                    transform: translate(-50%, -50%);
                `;
                document.body.appendChild(damageText);

                setTimeout(() => {
                    damageText.style.transition = 'all 1s ease-out';
                    damageText.style.top = (y - 100) + 'px';
                    damageText.style.opacity = '0';
                    damageText.style.transform = 'translate(-50%, -50%) scale(1.5)';
                }, 50);

                setTimeout(() => damageText.remove(), 1100);
            },

            // Secouer un élément
            shakeElement: function(element) {
                const keyframes = [
                    { transform: 'translateX(0)' },
                    { transform: 'translateX(-10px) rotate(-2deg)' },
                    { transform: 'translateX(10px) rotate(2deg)' },
                    { transform: 'translateX(-10px) rotate(-1deg)' },
                    { transform: 'translateX(10px) rotate(1deg)' },
                    { transform: 'translateX(0) rotate(0deg)' },
                ];
                element.animate(keyframes, { duration: 400, easing: 'ease-in-out' });
            },

            // Flash sur un élément
            flashElement: function(element, color = '#FFFFFF') {
                const overlay = document.createElement('div');
                overlay.style.cssText = `
                    position: absolute;
                    top: 0; left: 0; right: 0; bottom: 0;
                    background: ${color};
                    opacity: 0.6;
                    border-radius: inherit;
                    pointer-events: none;
                    z-index: 10;
                `;
                element.style.position = 'relative';
                element.appendChild(overlay);

                setTimeout(() => {
                    overlay.style.transition = 'opacity 0.3s';
                    overlay.style.opacity = '0';
                }, 50);

                setTimeout(() => overlay.remove(), 400);
            },

            // Animation de destruction spectaculaire
            destroyCardAnimation: async function(cardElement) {
                if (!cardElement) return;

                return new Promise(async (resolve) => {
                    const rect = cardElement.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    // Phase 1 : Warning (clignote en rouge)
                    cardElement.style.transition = 'all 0.1s ease-in-out';
                    for (let i = 0; i < 3; i++) {
                        await new Promise(r => setTimeout(r, 100));
                        cardElement.style.boxShadow = '0 0 30px 10px rgba(239, 68, 68, 0.9)';
                        cardElement.style.filter = 'brightness(1.5)';
                        await new Promise(r => setTimeout(r, 100));
                        cardElement.style.boxShadow = '';
                        cardElement.style.filter = '';
                    }

                    // Phase 2 : Impact violent
                    this.flashElement(cardElement, '#FFFFFF');
                    this.shakeElement(cardElement);

                    await new Promise(r => setTimeout(r, 200));

                    // Phase 3 : Explosion de particules
                    this.createDestructionParticles(centerX, centerY);

                    // Phase 4 : Brisure et rotation
                    setTimeout(() => {
                        cardElement.style.transition = 'all 0.6s cubic-bezier(0.6, -0.28, 0.735, 0.045)';
                        cardElement.style.transform = 'scale(0) rotate(720deg)';
                        cardElement.style.opacity = '0';
                        cardElement.style.filter = 'blur(5px) brightness(2)';
                    }, 100);

                    // Phase 5 : Effet de fumée
                    setTimeout(() => {
                        this.createSmokeEffect(centerX, centerY);
                    }, 400);

                    // Cleanup après animation complète
                    setTimeout(() => {
                        cardElement.remove();
                        resolve();
                    }, 1200);
                });
            },

            // Créer des particules de destruction
            createDestructionParticles: function(x, y) {
                const colors = ['#EF4444', '#DC2626', '#F97316', '#FBBF24', '#FFFFFF'];
                const particleCount = 30;

                for (let i = 0; i < particleCount; i++) {
                    setTimeout(() => {
                        const particle = document.createElement('div');
                        const angle = (Math.PI * 2 * i) / particleCount;
                        const velocity = 100 + Math.random() * 150;
                        const size = 4 + Math.random() * 8;
                        const color = colors[Math.floor(Math.random() * colors.length)];

                        particle.style.cssText = `
                            position: fixed;
                            left: ${x}px;
                            top: ${y}px;
                            width: ${size}px;
                            height: ${size}px;
                            background: ${color};
                            border-radius: ${Math.random() > 0.5 ? '50%' : '0'};
                            pointer-events: none;
                            z-index: 9999;
                            box-shadow: 0 0 10px ${color};
                        `;

                        document.body.appendChild(particle);

                        setTimeout(() => {
                            const targetX = x + Math.cos(angle) * velocity;
                            const targetY = y + Math.sin(angle) * velocity + (Math.random() * 50);
                            particle.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                            particle.style.left = targetX + 'px';
                            particle.style.top = targetY + 'px';
                            particle.style.opacity = '0';
                            particle.style.transform = `scale(${Math.random() * 0.5})`;
                        }, 50);

                        setTimeout(() => particle.remove(), 700);
                    }, i * 10);
                }
            },

            // Créer un effet de fumée
            createSmokeEffect: function(x, y) {
                for (let i = 0; i < 5; i++) {
                    setTimeout(() => {
                        const smoke = document.createElement('div');
                        const offsetX = (Math.random() - 0.5) * 40;
                        const offsetY = (Math.random() - 0.5) * 40;
                        const size = 40 + Math.random() * 40;

                        smoke.style.cssText = `
                            position: fixed;
                            left: ${x + offsetX}px;
                            top: ${y + offsetY}px;
                            width: ${size}px;
                            height: ${size}px;
                            background: radial-gradient(circle, rgba(100, 100, 100, 0.4) 0%, rgba(50, 50, 50, 0) 70%);
                            border-radius: 50%;
                            pointer-events: none;
                            z-index: 9998;
                        `;

                        document.body.appendChild(smoke);

                        setTimeout(() => {
                            smoke.style.transition = 'all 0.8s ease-out';
                            smoke.style.top = (y + offsetY - 80) + 'px';
                            smoke.style.transform = `scale(2)`;
                            smoke.style.opacity = '0';
                        }, 50);

                        setTimeout(() => smoke.remove(), 900);
                    }, i * 80);
                }
            },

            // Créer des étincelles
            createSparkles: function(x, y, color = '#FFD700') {
                for (let i = 0; i < 12; i++) {
                    setTimeout(() => {
                        const sparkle = document.createElement('div');
                        const angle = (Math.PI * 2 * i) / 12;
                        const distance = 40;
                        const targetX = x + Math.cos(angle) * distance;
                        const targetY = y + Math.sin(angle) * distance;

                        sparkle.style.cssText = `
                            position: fixed;
                            left: ${x}px;
                            top: ${y}px;
                            width: 6px;
                            height: 6px;
                            background: ${color};
                            border-radius: 50%;
                            pointer-events: none;
                            box-shadow: 0 0 10px ${color};
                            z-index: 1500;
                        `;

                        document.body.appendChild(sparkle);

                        setTimeout(() => {
                            sparkle.style.transition = 'all 0.5s ease-out';
                            sparkle.style.left = targetX + 'px';
                            sparkle.style.top = targetY + 'px';
                            sparkle.style.opacity = '0';
                        }, 50);

                        setTimeout(() => sparkle.remove(), 600);
                    }, i * 30);
                }
            }
        };

        // ========================================
        // INITIALISATION
        // ========================================
        document.addEventListener('DOMContentLoaded', () => {
            // Event listener pour l'overlay
            document.getElementById('actionPanelOverlay').addEventListener('click', function(e) {
                e.stopPropagation();
                cancelSelection();
            });

            // Message de démarrage
            addLogEntry('⚔️ Le combat commence !', 'turn');

            renderAll();

            if (isMyTurn) {
                setTimeout(() => {
                    addLogEntry('🎮 C\'est votre tour !', 'info');
                }, 500);
            }

            if (!isMyTurn) {
                startPolling();
            }
        });

        // Polling pour vérifier les mises à jour
        let pollingInterval = null;

        function startPolling() {
            pollingInterval = setInterval(checkForUpdates, 2000);
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        async function checkForUpdates() {
            try {
                const response = await fetch(`/api/v1/pvp/battle-state/${battleId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin'
                });

                // Rediriger vers login si non authentifié
                if (response.status === 401) {
                    stopPolling();
                    window.location.href = '/login';
                    return;
                }

                if (!response.ok) {
                    console.error('Polling error: HTTP', response.status);
                    return;
                }

                const data = await response.json();

                if (data.battle_state) {
                    const wasMyTurn = isMyTurn;
                    gameState = data.battle_state;
                    isMyTurn = data.is_my_turn;

                    renderAll();
                    updateTurnUI();

                    // Log quand c'est notre tour (après que l'adversaire ait terminé)
                    if (isMyTurn && !wasMyTurn) {
                        const myState = getMyState();
                        addLogEntry(`🔄 C'est votre tour ! Cosmos: ${myState.cosmos}/${myState.max_cosmos}`, 'turn');
                    }

                    if (isMyTurn) {
                        stopPolling();
                    }

                    // Vérifier fin de partie
                    if (data.status === 'finished') {
                        stopPolling();
                        const myId = {{ auth()->id() }};
                        const winnerId = parseInt(data.winner_id);
                        const isWinner = winnerId === myId;
                        console.log('Game finished - Winner ID:', winnerId, 'My ID:', myId, 'Is Winner:', isWinner);
                        endGame(isWinner);
                        return;
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }
        }

        function updateTurnUI() {
            const indicator = document.getElementById('turnIndicator');
            const overlay = document.getElementById('waitingOverlay');
            const endBtn = document.getElementById('endTurnBtn');

            if (isMyTurn) {
                indicator.textContent = '🎮 Votre tour !';
                indicator.className = 'turn-indicator my-turn';
                overlay.classList.remove('visible');
                endBtn.disabled = false;
            } else {
                indicator.textContent = '⏳ Tour adverse...';
                indicator.className = 'turn-indicator opponent-turn';
                overlay.classList.add('visible');
                endBtn.disabled = true;
            }
        }

        // Rendu
        function renderAll() {
            renderOpponentField();
            renderPlayerField();
            renderPlayerHand();
            updateStats();
            updateHelpText();
        }

        function getMyState() {
            return playerNumber === 1 ? gameState.player1 : gameState.player2;
        }

        function getOpponentState() {
            return playerNumber === 1 ? gameState.player2 : gameState.player1;
        }

        function renderOpponentField() {
            const container = document.getElementById('opponentField');
            const state = getOpponentState();
            container.innerHTML = '';

            if (!state.field || state.field.length === 0) {
                container.innerHTML = '<div class="empty-slot">👻</div>';
                return;
            }

            state.field.forEach((card, index) => {
                container.appendChild(createBattleCard(card, index, 'opponent'));
            });
        }

        function renderPlayerField() {
            const container = document.getElementById('playerField');
            const state = getMyState();
            container.innerHTML = '';

            if (!state.field || state.field.length === 0) {
                container.innerHTML = '<div class="empty-slot">🎴</div>';
                return;
            }

            state.field.forEach((card, index) => {
                container.appendChild(createBattleCard(card, index, 'player'));
            });
        }

        function renderPlayerHand() {
            const container = document.getElementById('playerHand');
            const state = getMyState();
            container.innerHTML = '';

            if (!state.hand || state.hand.length === 0) {
                container.innerHTML = '<div style="color: rgba(255,255,255,0.5);">Aucune carte en main</div>';
                return;
            }

            state.hand.forEach((card, index) => {
                container.appendChild(createHandCard(card, index));
            });
        }

        function createBattleCard(card, index, owner) {
            const div = document.createElement('div');
            div.className = 'battle-card';
            div.dataset.cardIndex = index;
            div.dataset.owner = owner;
            
            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            const hpPercent = (card.current_hp / card.max_hp) * 100;

            div.innerHTML = `
                <div class="battle-card-image" style="background-image: url('${card.image || ''}'); background-color: ${card.faction?.color_primary || '#333'};"></div>
                <div class="battle-card-info">
                    <div class="battle-card-name">${card.name}</div>
                    <div class="hp-bar-container">
                        <div class="hp-bar" style="width: ${hpPercent}%"></div>
                    </div>
                    <div class="battle-card-stats">
                        <span>❤️ ${card.current_hp}/${card.max_hp}</span>
                        <span>💪 ${card.power || 0}</span>
                    </div>
                </div>
            `;

            if (isMyTurn && owner === 'player' && phase === 'idle') {
                div.onclick = () => selectAttacker(index);
            } else if (isMyTurn && owner === 'opponent' && phase === 'selectingTarget') {
                div.classList.add('targetable');
                div.onclick = () => selectTarget(index);
            }

            if (owner === 'player' && selectedAttacker === index) {
                div.classList.add('selected');
            }

            return div;
        }

        function createHandCard(card, index) {
            const div = document.createElement('div');
            div.className = 'hand-card';
            div.dataset.cardIndex = index;
            
            const state = getMyState();
            const canPlay = isMyTurn && state.cosmos >= card.cost && state.field.length < 3;
            
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
                        <span title="Points de vie">❤️ ${card.max_hp || card.health_points || '?'}</span>
                        <span title="Puissance">⚔️ ${card.power || 0}</span>
                        <span title="Défense">🛡️ ${card.defense || 0}</span>
                    </div>
                </div>
            `;

            if (canPlay) {
                div.onclick = () => playCard(index, div);
            }

            return div;
        }

        function updateStats() {
            const state = getMyState();
            document.getElementById('playerCosmos').textContent = state.cosmos || 0;
            document.getElementById('playerMaxCosmos').textContent = state.max_cosmos || 0;
        }

        // Actions avec animations
        async function playCard(index, cardElement) {
            if (!isMyTurn) return;

            // Position cible (centre du terrain joueur)
            const fieldZone = document.getElementById('playerField');
            const fieldRect = fieldZone.getBoundingClientRect();
            const targetPos = {
                x: fieldRect.left + (fieldRect.width / 2) - 65,
                y: fieldRect.top + (fieldRect.height / 2) - 80
            };

            // Animation de la carte
            await animations.playCardAnimation(cardElement, targetPos);

            try {
                const response = await fetch('/api/v1/pvp/play-card', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        battle_id: battleId,
                        card_index: index,
                    }),
                    credentials: 'same-origin'
                });

                // Gérer les erreurs HTTP
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Erreur HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    gameState = data.battle_state;
                    addLogEntry(`🎴 Vous jouez ${data.card_played}`, 'info');
                    renderAll();
                } else {
                    addLogEntry(`❌ ${data.message || 'Erreur inconnue'}`, 'damage');
                    renderAll(); // Re-render pour restaurer l'état visuel
                }
            } catch (error) {
                console.error('Play card error:', error);
                addLogEntry(`❌ ${error.message || 'Erreur de connexion'}`, 'damage');
                renderAll(); // Re-render pour restaurer l'état visuel
            }
        }

        function selectAttacker(index) {
            if (!isMyTurn) return;
            
            const state = getMyState();
            const card = state.field[index];
            if (!card || card.has_attacked) {
                addLogEntry('❌ Cette carte a déjà attaqué', 'damage');
                return;
            }

            selectedAttacker = index;
            phase = 'selectingAttack';
            showAttackPanel(card);
            renderAll();
        }

        function showAttackPanel(card) {
            const panel = document.getElementById('actionPanel');
            const list = document.getElementById('attackList');
            list.innerHTML = '';

            const state = getMyState();
            const attacks = [
                { key: 'main', name: card.main_attack?.name || 'Attaque', damage: card.main_attack?.damage || 50, endCost: card.main_attack?.endurance_cost || 20, cosCost: card.main_attack?.cosmos_cost || 0 }
            ];

            // Afficher les stats de la carte sélectionnée
            const statsDiv = document.createElement('div');
            statsDiv.style.cssText = 'font-size: 0.75rem; color: #9CA3AF; margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid rgba(255,255,255,0.1);';
            statsDiv.innerHTML = `<span style="color: #60A5FA;">⚡ Endurance: ${card.current_endurance || 0}</span> | <span style="color: #A78BFA;">🌟 Cosmos: ${state.cosmos || 0}</span>`;
            list.appendChild(statsDiv);

            attacks.forEach(atk => {
                const hasEndurance = (card.current_endurance || 0) >= atk.endCost;
                const hasCosmos = (state.cosmos || 0) >= atk.cosCost;
                const canUse = hasEndurance && hasCosmos;

                const btn = document.createElement('button');
                btn.className = 'attack-btn';
                btn.disabled = !canUse;

                // Afficher les coûts et la raison du blocage
                let costInfo = '';
                if (atk.endCost > 0) costInfo += `⚡${atk.endCost}`;
                if (atk.cosCost > 0) costInfo += ` 🌟${atk.cosCost}`;

                let reasonText = '';
                if (!hasEndurance) reasonText = '(Endurance insuffisante)';
                else if (!hasCosmos) reasonText = '(Cosmos insuffisant)';

                btn.innerHTML = `
                    <div style="display: flex; flex-direction: column; align-items: flex-start; width: 100%;">
                        <div style="display: flex; justify-content: space-between; width: 100%;">
                            <span>${atk.name}</span>
                            <span style="font-size:0.7rem;color:#EF4444;">⚔️${atk.damage}</span>
                        </div>
                        <div style="font-size: 0.65rem; color: #9CA3AF; margin-top: 2px;">
                            Coût: ${costInfo || 'Aucun'} ${reasonText ? `<span style="color: #F87171;">${reasonText}</span>` : ''}
                        </div>
                    </div>
                `;
                btn.onclick = () => selectAttack(atk.key);
                list.appendChild(btn);
            });

            // Afficher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.add('visible');
            panel.classList.add('visible');
        }

        function selectAttack(attackKey) {
            selectedAttack = attackKey;
            phase = 'selectingTarget';

            // Cacher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');

            addLogEntry('🎯 Sélectionnez une cible', 'info');
            renderAll();
        }

        async function selectTarget(targetIndex) {
            if (!isMyTurn) return;

            // Récupérer les éléments
            const attackerCard = document.querySelector(`.battle-card[data-owner="player"][data-card-index="${selectedAttacker}"]`);
            const targetCard = document.querySelector(`.battle-card[data-owner="opponent"][data-card-index="${targetIndex}"]`);

            if (!attackerCard || !targetCard) {
                console.error('Elements not found');
                cancelSelection();
                return;
            }

            // Animation d'attaque
            const myState = getMyState();
            const card = myState.field[selectedAttacker];
            const attackData = {
                element: 'fire', // TODO: récupérer l'élément réel
                damage: card.main_attack?.damage || 50
            };

            await animations.attackAnimation(attackerCard, targetCard, attackData);

            try {
                const response = await fetch('/api/v1/pvp/attack', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        battle_id: battleId,
                        attacker_index: selectedAttacker,
                        attack_type: selectedAttack,
                        target_index: targetIndex,
                    }),
                    credentials: 'same-origin'
                });

                // Gérer les erreurs HTTP
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Erreur HTTP ${response.status}`);
                }

                const data = await response.json();
                let cardWasDestroyed = false;

                if (data.success) {
                    // Afficher les dégâts
                    if (data.damage) {
                        animations.showDamage(targetCard, data.damage, 'damage');
                    }

                    addLogEntry(`⚔️ ${data.message}`, 'damage');

                    // Vérifier si la carte cible est morte (avant mise à jour du state)
                    cardWasDestroyed = data.target_destroyed === true;

                    if (cardWasDestroyed && targetCard) {
                        await animations.destroyCardAnimation(targetCard);
                    }

                    // Mettre à jour gameState APRÈS l'animation
                    gameState = data.battle_state;

                    // Vérifier fin de partie
                    if (data.battle_ended) {
                        const myId = {{ auth()->id() }};
                        const winnerId = parseInt(data.winner);
                        const isWinner = winnerId === myId;
                        console.log('Battle ended - Winner ID:', winnerId, 'My ID:', myId, 'Is Winner:', isWinner);
                        endGame(isWinner);
                        return;
                    }
                } else {
                    addLogEntry(`❌ ${data.message || 'Erreur inconnue'}`, 'damage');
                }

                cancelSelection();

                // Render après un petit délai si carte détruite
                if (cardWasDestroyed) {
                    setTimeout(() => renderAll(), 100);
                } else {
                    renderAll();
                }
            } catch (error) {
                console.error('Attack error:', error);
                addLogEntry(`❌ ${error.message || 'Erreur de connexion'}`, 'damage');
                cancelSelection();
            }
        }

        function cancelSelection() {
            selectedAttacker = null;
            selectedAttack = null;
            phase = 'idle';

            // Cacher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');

            renderAll();
        }

        async function endTurn() {
            if (!isMyTurn) return;

            try {
                const response = await fetch('/api/v1/pvp/end-turn', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        battle_id: battleId,
                    }),
                    credentials: 'same-origin'
                });

                // Gérer les erreurs HTTP
                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || `Erreur HTTP ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    gameState = data.battle_state;
                    isMyTurn = false;
                    addLogEntry('⏭️ Fin de votre tour', 'turn');
                    renderAll();
                    updateTurnUI();
                    startPolling();
                } else {
                    addLogEntry(`❌ ${data.message || 'Erreur inconnue'}`, 'damage');
                }
            } catch (error) {
                console.error('End turn error:', error);
                addLogEntry(`❌ ${error.message || 'Erreur de connexion'}`, 'damage');
            }
        }

        // ========================================
        // NOTIFICATIONS TOAST DYNAMIQUES
        // ========================================
        const MAX_TOASTS = 3;
        const TOAST_DURATION = 4000; // 4 secondes

        function addLogEntry(message, type = 'info') {
            const log = document.getElementById('battleLog');
            const existingToasts = log.querySelectorAll('.log-entry:not(.exiting)');

            // Si on a déjà 3 toasts, retirer le plus ancien
            if (existingToasts.length >= MAX_TOASTS) {
                const oldestToast = existingToasts[0];
                removeToast(oldestToast);
            }

            // Créer le nouveau toast
            const entry = document.createElement('div');
            entry.className = `log-entry ${type} entering`;
            entry.textContent = message;
            entry.style.cursor = 'pointer';
            entry.title = 'Cliquer pour fermer';

            // Clic pour fermer
            entry.addEventListener('click', () => removeToast(entry));

            // Ajouter une animation de shift aux toasts existants
            existingToasts.forEach(toast => {
                toast.classList.add('shifting');
                setTimeout(() => toast.classList.remove('shifting'), 300);
            });

            // Ajouter le nouveau toast
            log.appendChild(entry);

            // Retirer la classe entering après l'animation
            setTimeout(() => {
                entry.classList.remove('entering');
            }, 400);

            // Programmer la suppression automatique
            setTimeout(() => {
                if (entry.parentNode) {
                    removeToast(entry);
                }
            }, TOAST_DURATION);
        }

        function removeToast(toast) {
            if (!toast || toast.classList.contains('exiting')) return;

            toast.classList.add('exiting');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }

        // ========================================
        // HELPERS
        // ========================================
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('visible');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('visible');
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        // ========================================
        // FIN DE PARTIE
        // ========================================
        function endGame(victory) {
            const modal = document.getElementById('gameOverModal');
            const title = document.getElementById('gameOverTitle');
            const subtitle = document.getElementById('gameOverSubtitle');

            // Arrêter la musique de combat
            const battleMusicEl = document.getElementById('battleMusic');
            if (battleMusicEl) {
                battleMusicEl.pause();
            }

            if (victory) {
                title.textContent = '🏆 Victoire !';
                title.className = 'game-over-title victory';
                subtitle.textContent = 'Vous avez vaincu votre adversaire !';

                // Jouer la musique de victoire
                const victoryMusicEl = document.getElementById('victoryMusic');
                if (victoryMusicEl) {
                    victoryMusicEl.volume = 0.7;
                    victoryMusicEl.play().catch(e => console.log('Victory music blocked:', e));
                }
            } else {
                title.textContent = '💀 Défaite';
                title.className = 'game-over-title defeat';
                subtitle.textContent = 'Votre adversaire vous a vaincu...';

                // Jouer la musique de défaite
                const defeatMusicEl = document.getElementById('defeatMusic');
                if (defeatMusicEl) {
                    defeatMusicEl.volume = 0.7;
                    defeatMusicEl.play().catch(e => console.log('Defeat music blocked:', e));
                }
            }

            modal.classList.add('visible');
        }

        function updateHelpText() {
            const helpText = document.getElementById('helpText');
            const myState = getMyState();

            if (!isMyTurn) {
                helpText.textContent = "⏳ Attendez votre tour...";
                helpText.className = "text-xs text-gray-400 bg-gray-400/20 px-2 py-1 rounded";
            } else if (myState.field.length === 0) {
                helpText.textContent = "👆 Cliquez sur une carte pour la jouer sur le terrain";
                helpText.className = "text-xs text-yellow-400 bg-yellow-400/20 px-2 py-1 rounded";
            } else if (phase === 'idle') {
                helpText.textContent = "⚔️ Cliquez sur une carte du terrain pour attaquer";
                helpText.className = "text-xs text-red-400 bg-red-400/20 px-2 py-1 rounded";
            } else if (phase === 'selectingTarget') {
                helpText.textContent = "🎯 Cliquez sur une carte adverse pour l'attaquer";
                helpText.className = "text-xs text-orange-400 bg-orange-400/20 px-2 py-1 rounded";
            }
        }

        // Music Player
        @if($battleMusics->count() > 0)
        const battleMusic = document.getElementById('battleMusic');
        const musicToggle = document.getElementById('musicToggle');
        const volumePanel = document.getElementById('volumePanel');
        const volumeSlider = document.getElementById('volumeSlider');
        const volumeValueEl = document.getElementById('volumeValue');
        const trackSelect = document.getElementById('trackSelect');

        let isMusicPlaying = false;
        let volumePanelVisible = false;
        let hasTriedAutoplay = false;

        // Initialize volume
        battleMusic.volume = volumeSlider.value / 100;

        // Try to autoplay
        function tryAutoplay() {
            if (hasTriedAutoplay && isMusicPlaying) return;

            battleMusic.play().then(() => {
                musicToggle.classList.add('playing');
                musicToggle.innerHTML = '⏸️';
                isMusicPlaying = true;
                hasTriedAutoplay = true;
            }).catch(err => {
                console.log('Autoplay blocked, waiting for user interaction');
            });
        }

        // Try autoplay on page load
        tryAutoplay();

        // Autoplay on first user interaction
        function onFirstInteraction() {
            if (!isMusicPlaying) {
                tryAutoplay();
            }
            document.removeEventListener('click', onFirstInteraction);
            document.removeEventListener('keydown', onFirstInteraction);
            document.removeEventListener('touchstart', onFirstInteraction);
        }

        document.addEventListener('click', onFirstInteraction);
        document.addEventListener('keydown', onFirstInteraction);
        document.addEventListener('touchstart', onFirstInteraction);

        // Toggle play/pause
        musicToggle.addEventListener('click', function(e) {
            e.stopPropagation();

            if (isMusicPlaying) {
                battleMusic.pause();
                musicToggle.classList.remove('playing');
                musicToggle.innerHTML = '🎵';
                isMusicPlaying = false;
            } else {
                battleMusic.play().then(() => {
                    musicToggle.classList.add('playing');
                    musicToggle.innerHTML = '⏸️';
                    isMusicPlaying = true;
                }).catch(err => {
                    console.log('Autoplay blocked:', err);
                });
            }
        });

        // Right-click or long-press to show volume panel
        musicToggle.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            volumePanelVisible = !volumePanelVisible;
            volumePanel.classList.toggle('visible', volumePanelVisible);
        });

        // Double-click to toggle volume panel
        musicToggle.addEventListener('dblclick', function(e) {
            e.preventDefault();
            volumePanelVisible = !volumePanelVisible;
            volumePanel.classList.toggle('visible', volumePanelVisible);
        });

        // Volume slider
        volumeSlider.addEventListener('input', function() {
            battleMusic.volume = this.value / 100;
            volumeValueEl.textContent = this.value + '%';
            localStorage.setItem('battleMusicVolume', this.value);
        });

        // Track selector
        if (trackSelect) {
            trackSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const newSrc = this.value;
                const defaultVolume = selectedOption.dataset.volume || 50;

                const wasPlaying = isMusicPlaying;
                battleMusic.src = newSrc;
                volumeSlider.value = defaultVolume;
                battleMusic.volume = defaultVolume / 100;
                volumeValueEl.textContent = defaultVolume + '%';

                if (wasPlaying) {
                    battleMusic.play();
                }

                localStorage.setItem('battleMusicTrack', newSrc);
            });
        }

        // Close volume panel when clicking outside
        document.addEventListener('click', function(e) {
            if (volumePanelVisible && !volumePanel.contains(e.target) && e.target !== musicToggle) {
                volumePanelVisible = false;
                volumePanel.classList.remove('visible');
            }
        });

        // Load saved preferences
        const savedVolume = localStorage.getItem('battleMusicVolume');
        if (savedVolume) {
            volumeSlider.value = savedVolume;
            battleMusic.volume = savedVolume / 100;
            volumeValueEl.textContent = savedVolume + '%';
        }

        const savedTrack = localStorage.getItem('battleMusicTrack');
        if (savedTrack && trackSelect) {
            const option = trackSelect.querySelector(`option[value="${savedTrack}"]`);
            if (option) {
                trackSelect.value = savedTrack;
                battleMusic.src = savedTrack;
            }
        }
        @endif
    </script>
</body>
</html>