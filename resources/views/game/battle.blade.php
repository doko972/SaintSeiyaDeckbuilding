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

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
            }

            50% {
                box-shadow: 0 0 20px 5px rgba(16, 185, 129, 0.3);
            }
        }

        .quit-btn {
            padding: 1rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #EF4444;
            border-radius: 32px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            margin: 0 0.5rem;
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
            justify-content: space-between;
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

        /* Notifications Toast - Position bas droite */
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
            0% {
                transform: translateY(10px);
            }
            100% {
                transform: translateY(0);
            }
        }

        @keyframes progressBar {
            0% {
                width: 100%;
            }
            100% {
                width: 0%;
            }
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
            0% {
                left: -100%;
            }
            50%, 100% {
                left: 100%;
            }
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
           CARTES DE COMBAT - STYLE TCG PRO
        ======================================== */
        .battle-card {
            width: 140px;
            height: 200px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #1a1a2e), var(--color2, #16213e));
            border: 3px solid transparent;
            background-clip: padding-box;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .battle-card::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--color1, #4a5568), var(--color2, #2d3748));
            z-index: -1;
        }

        .battle-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 50%,
                rgba(0, 0, 0, 0.2) 100%
            );
            pointer-events: none;
            z-index: 10;
            border-radius: 10px;
        }

        .battle-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.5),
                0 0 30px rgba(var(--color1-rgb, 100, 100, 100), 0.3);
        }

        .battle-card.selected {
            border-color: #FBBF24;
            transform: translateY(-10px) scale(1.03);
        }

        .battle-card.selected::before {
            background: linear-gradient(135deg, #FBBF24, #F59E0B, #FBBF24);
            animation: border-glow 2s ease-in-out infinite;
        }

        @keyframes border-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .battle-card.targetable {
            border-color: #EF4444;
            animation: pulse-target 1s infinite;
        }

        .battle-card.targetable::before {
            background: linear-gradient(135deg, #EF4444, #DC2626, #EF4444);
        }

        @keyframes pulse-target {
            0%, 100% {
                box-shadow: 0 0 15px rgba(239, 68, 68, 0.4), 0 4px 15px rgba(0, 0, 0, 0.4);
            }
            50% {
                box-shadow: 0 0 35px rgba(239, 68, 68, 0.7), 0 4px 15px rgba(0, 0, 0, 0.4);
            }
        }

        .battle-card.disabled {
            opacity: 0.5;
            pointer-events: none;
            filter: grayscale(0.5);
        }

        /* Rarity glow effects */
        .battle-card[data-rarity="rare"]::before {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8, #3B82F6);
            animation: rare-shimmer 3s ease-in-out infinite;
        }

        .battle-card[data-rarity="epic"]::before {
            background: linear-gradient(135deg, #A855F7, #7C3AED, #A855F7);
            animation: epic-shimmer 2.5s ease-in-out infinite;
        }

        .battle-card[data-rarity="legendary"]::before {
            background: linear-gradient(135deg, #FBBF24, #F59E0B, #EF4444, #FBBF24);
            animation: legendary-shimmer 2s ease-in-out infinite;
        }

        .battle-card[data-rarity="mythic"]::before {
            background: linear-gradient(135deg, #EC4899, #8B5CF6, #06B6D4, #EC4899);
            animation: mythic-shimmer 1.5s ease-in-out infinite;
        }

        @keyframes rare-shimmer {
            0%, 100% { filter: brightness(1); }
            50% { filter: brightness(1.2); }
        }

        @keyframes epic-shimmer {
            0%, 100% { filter: brightness(1) hue-rotate(0deg); }
            50% { filter: brightness(1.3) hue-rotate(10deg); }
        }

        @keyframes legendary-shimmer {
            0%, 100% { filter: brightness(1) hue-rotate(0deg); }
            50% { filter: brightness(1.4) hue-rotate(15deg); }
        }

        @keyframes mythic-shimmer {
            0% { filter: brightness(1) hue-rotate(0deg); }
            50% { filter: brightness(1.5) hue-rotate(30deg); }
            100% { filter: brightness(1) hue-rotate(0deg); }
        }

        .battle-card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center center;
            z-index: 1;
        }

        .battle-card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 70%;
            background: linear-gradient(
                to top,
                rgba(0, 0, 0, 0.95) 0%,
                rgba(0, 0, 0, 0.7) 30%,
                rgba(0, 0, 0, 0.3) 60%,
                transparent 100%
            );
            z-index: 2;
        }

        .battle-card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.6));
            backdrop-filter: blur(4px);
            z-index: 5;
        }

        .battle-card-name {
            font-size: 0.7rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 5px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
            letter-spacing: 0.3px;
        }

        /* Barre de vie - Style PRO */
        .hp-bar-container {
            height: 10px;
            background: rgba(0, 0, 0, 0.6);
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.5);
        }

        .hp-bar {
            height: 100%;
            background: linear-gradient(90deg, #22C55E 0%, #16A34A 50%, #22C55E 100%);
            border-radius: 4px;
            transition: width 0.5s ease;
            box-shadow: 0 0 8px rgba(34, 197, 94, 0.5);
            position: relative;
        }

        .hp-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50%;
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), transparent);
            border-radius: 4px 4px 0 0;
        }

        .hp-bar.low {
            background: linear-gradient(90deg, #EF4444 0%, #DC2626 50%, #EF4444 100%);
            box-shadow: 0 0 12px rgba(239, 68, 68, 0.6);
            animation: pulse-hp 1s infinite;
        }

        @keyframes pulse-hp {
            0%, 100% { opacity: 1; box-shadow: 0 0 12px rgba(239, 68, 68, 0.6); }
            50% { opacity: 0.7; box-shadow: 0 0 20px rgba(239, 68, 68, 0.9); }
        }

        .battle-card-stats {
            display: flex;
            justify-content: space-around;
            font-size: 0.6rem;
            gap: 2px;
        }

        .mini-stat {
            display: flex;
            align-items: center;
            gap: 2px;
            background: rgba(0, 0, 0, 0.5);
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
        }

        /* Holo shimmer effect pour cartes en combat */
        .battle-card .holo-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                125deg,
                transparent 0%,
                rgba(255, 255, 255, 0.1) 25%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0.1) 75%,
                transparent 100%
            );
            background-size: 200% 200%;
            animation: holo-move 3s ease-in-out infinite;
            pointer-events: none;
            z-index: 15;
            mix-blend-mode: overlay;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .battle-card:hover .holo-shimmer {
            opacity: 1;
        }

        @keyframes holo-move {
            0% { background-position: 200% 0%; }
            100% { background-position: -200% 0%; }
        }

        /* Combo indicator style */
        .combo-indicator {
            position: absolute;
            top: 5px;
            left: 5px;
            background: linear-gradient(135deg, #FBBF24, #F59E0B);
            color: #000;
            font-size: 0.6rem;
            font-weight: 900;
            padding: 2px 6px;
            border-radius: 6px;
            z-index: 20;
        }

        /* Fusion level indicator */
        .fusion-level-indicator {
            position: absolute;
            bottom: 50px;
            right: 5px;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            color: #000;
            font-size: 0.6rem;
            font-weight: 900;
            padding: 2px 6px;
            border-radius: 6px;
            z-index: 20;
            box-shadow: 0 2px 6px rgba(255, 215, 0, 0.4);
            animation: fusionPulse 2s ease-in-out infinite;
        }

        @keyframes fusionPulse {
            0%, 100% { box-shadow: 0 2px 6px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 2px 12px rgba(255, 215, 0, 0.8); }
        }

        .fusion-bonus {
            color: #FFD700 !important;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(251, 191, 36, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
            display: flex !important;
            align-items: center;
            gap: 0.5rem;
            background: rgba(124, 58, 237, 0.5) !important;
            border: 2px solid rgba(124, 58, 237, 0.8) !important;
            padding: 0.75rem 1.5rem !important;
            margin: 0.5rem 0;
            border-radius: 20px;
            font-size: 1.2rem !important;
            box-shadow: 0 0 20px rgba(124, 58, 237, 0.5) !important;
        }

        .cosmos-display span {
            font-weight: 800 !important;
            color: #E9D5FF !important;
            font-size: 1.3rem !important;
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
            height: 185px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #1a1a2e), var(--color2, #16213e));
            border: 3px solid transparent;
            background-clip: padding-box;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .hand-card::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--color1, #4a5568), var(--color2, #2d3748));
            z-index: -1;
            transition: all 0.3s ease;
        }

        .hand-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 50%,
                rgba(0, 0, 0, 0.2) 100%
            );
            pointer-events: none;
            z-index: 10;
            border-radius: 10px;
        }

        .hand-card:hover {
            transform: translateY(-20px) scale(1.08);
            z-index: 10;
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.6),
                0 0 40px rgba(var(--color1-rgb, 100, 100, 100), 0.4);
        }

        .hand-card.playable {
            border-color: transparent;
        }

        .hand-card.playable::before {
            background: linear-gradient(135deg, #10B981, #059669, #10B981);
            animation: playable-pulse 2s ease-in-out infinite;
        }

        @keyframes playable-pulse {
            0%, 100% { filter: brightness(1); box-shadow: 0 0 10px rgba(16, 185, 129, 0.3); }
            50% { filter: brightness(1.2); box-shadow: 0 0 20px rgba(16, 185, 129, 0.6); }
        }

        .hand-card.playable:hover {
            box-shadow:
                0 20px 50px rgba(0, 0, 0, 0.6),
                0 0 50px rgba(16, 185, 129, 0.5);
        }

        .hand-card.unplayable {
            opacity: 0.5;
            filter: grayscale(0.4);
        }

        .hand-card.unplayable::before {
            background: linear-gradient(135deg, #4a5568, #2d3748);
        }

        /* Rarity effects for hand cards */
        .hand-card[data-rarity="rare"]::before {
            background: linear-gradient(135deg, #3B82F6, #1D4ED8, #3B82F6) !important;
        }

        .hand-card[data-rarity="epic"]::before {
            background: linear-gradient(135deg, #A855F7, #7C3AED, #A855F7) !important;
        }

        .hand-card[data-rarity="legendary"]::before {
            background: linear-gradient(135deg, #FBBF24, #F59E0B, #EF4444, #FBBF24) !important;
            animation: legendary-shimmer 2s ease-in-out infinite !important;
        }

        .hand-card[data-rarity="mythic"]::before {
            background: linear-gradient(135deg, #EC4899, #8B5CF6, #06B6D4, #EC4899) !important;
            animation: mythic-shimmer 1.5s ease-in-out infinite !important;
        }

        .hand-card-cost {
            position: absolute;
            top: 6px;
            right: 6px;
            background: linear-gradient(135deg, #7C3AED, #5B21B6);
            color: white;
            font-size: 0.75rem;
            font-weight: 900;
            padding: 4px 10px;
            border-radius: 8px;
            z-index: 20;
            box-shadow:
                0 2px 8px rgba(124, 58, 237, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        .hand-card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center center;
            z-index: 1;
        }

        .hand-card-image::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65%;
            background: linear-gradient(
                to top,
                rgba(0, 0, 0, 0.95) 0%,
                rgba(0, 0, 0, 0.7) 35%,
                rgba(0, 0, 0, 0.2) 70%,
                transparent 100%
            );
            z-index: 2;
        }

        .hand-card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 8px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.5));
            backdrop-filter: blur(4px);
            z-index: 5;
        }

        .hand-card-name {
            font-size: 0.65rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 4px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.8);
            letter-spacing: 0.3px;
        }

        .hand-card-stats {
            display: flex;
            justify-content: space-around;
            font-size: 0.55rem;
            color: rgba(255, 255, 255, 0.95);
            gap: 2px;
        }

        .hand-card-stats span {
            background: rgba(0, 0, 0, 0.4);
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: 700;
        }

        /* Holo effect for hand cards */
        .hand-card .hand-holo-shimmer {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                125deg,
                transparent 0%,
                rgba(255, 255, 255, 0.15) 25%,
                rgba(255, 255, 255, 0.35) 50%,
                rgba(255, 255, 255, 0.15) 75%,
                transparent 100%
            );
            background-size: 200% 200%;
            animation: hand-holo-move 3s ease-in-out infinite;
            pointer-events: none;
            z-index: 15;
            mix-blend-mode: overlay;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 10px;
        }

        .hand-card:hover .hand-holo-shimmer {
            opacity: 1;
        }

        @keyframes hand-holo-move {
            0% { background-position: 200% 0%; }
            100% { background-position: -200% 0%; }
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
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
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
            color: white;
        }

        .attack-btn:hover:not(:disabled) {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .attack-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Styles pour les combos */
        .combo-separator {
            text-align: center;
            padding: 0.5rem 0;
            margin: 0.5rem 0;
            border-top: 1px solid rgba(255, 215, 0, 0.3);
            border-bottom: 1px solid rgba(255, 215, 0, 0.3);
            font-weight: bold;
            color: #FFD700;
            font-size: 0.8rem;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
            animation: comboPulse 2s ease-in-out infinite;
        }

        @keyframes comboPulse {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; text-shadow: 0 0 15px rgba(255, 215, 0, 0.8); }
        }

        .combo-attack {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), rgba(255, 165, 0, 0.15)) !important;
            border: 1px solid rgba(255, 215, 0, 0.4) !important;
            position: relative;
            overflow: hidden;
        }

        .combo-attack::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.2), transparent);
            animation: comboShine 3s ease-in-out infinite;
        }

        @keyframes comboShine {
            0% { left: -100%; }
            50%, 100% { left: 100%; }
        }

        .combo-attack:hover:not(:disabled) {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.25), rgba(255, 165, 0, 0.25)) !important;
            border-color: rgba(255, 215, 0, 0.6) !important;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
        }

        /* Indicateur combo sur les cartes */
        .combo-indicator {
            position: absolute;
            top: 5px;
            left: 5px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #000;
            font-size: 0.7rem;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 8px;
            z-index: 10;
            animation: comboIndicatorPulse 1.5s ease-in-out infinite;
        }

        @keyframes comboIndicatorPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 5px rgba(255, 215, 0, 0.5); }
            50% { transform: scale(1.1); box-shadow: 0 0 15px rgba(255, 215, 0, 0.8); }
        }

        /* ========================================
           EXPLOSION COMBO - ANIMATION SPECTACULAIRE
        ======================================== */
        .combo-explosion-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            display: none;
        }

        .combo-explosion-container.active {
            display: block;
        }

        /* Flash doré initial */
        .combo-flash {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(255, 215, 0, 0.9) 0%, rgba(255, 165, 0, 0.6) 30%, transparent 70%);
            opacity: 0;
            animation: comboFlash 0.8s ease-out forwards;
        }

        @keyframes comboFlash {
            0% { opacity: 0; transform: scale(0.5); }
            20% { opacity: 1; transform: scale(1.2); }
            100% { opacity: 0; transform: scale(2); }
        }

        /* Cercle d'onde de choc */
        .combo-shockwave {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            margin: -50px 0 0 -50px;
            border: 4px solid rgba(255, 215, 0, 0.8);
            border-radius: 50%;
            animation: comboShockwave 1s ease-out forwards;
        }

        .combo-shockwave:nth-child(2) { animation-delay: 0.1s; }
        .combo-shockwave:nth-child(3) { animation-delay: 0.2s; }

        @keyframes comboShockwave {
            0% { transform: scale(0); opacity: 1; border-width: 8px; }
            100% { transform: scale(15); opacity: 0; border-width: 1px; }
        }

        /* Particules d'explosion */
        .combo-particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            border-radius: 50%;
            box-shadow: 0 0 20px #FFD700, 0 0 40px #FFA500;
        }

        @keyframes comboParticle {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(var(--tx), var(--ty)) scale(0);
                opacity: 0;
            }
        }

        /* Étoiles cosmiques */
        .combo-star {
            position: absolute;
            font-size: 2rem;
            animation: comboStar 1.2s ease-out forwards;
        }

        @keyframes comboStar {
            0% {
                transform: translate(-50%, -50%) scale(0) rotate(0deg);
                opacity: 1;
            }
            50% {
                transform: translate(var(--tx), var(--ty)) scale(1.5) rotate(180deg);
                opacity: 1;
            }
            100% {
                transform: translate(calc(var(--tx) * 2), calc(var(--ty) * 2)) scale(0) rotate(360deg);
                opacity: 0;
            }
        }

        /* Texte COMBO au centre */
        .combo-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 4rem;
            font-weight: 900;
            color: #FFD700;
            text-shadow:
                0 0 10px #FFD700,
                0 0 20px #FFA500,
                0 0 40px #FF6B00,
                0 0 80px #FF0000,
                2px 2px 0 #8B0000;
            animation: comboText 1.5s ease-out forwards;
            white-space: nowrap;
        }

        @keyframes comboText {
            0% {
                transform: translate(-50%, -50%) scale(0) rotate(-10deg);
                opacity: 0;
            }
            30% {
                transform: translate(-50%, -50%) scale(1.3) rotate(5deg);
                opacity: 1;
            }
            50% {
                transform: translate(-50%, -50%) scale(1) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(1.5) rotate(0deg);
                opacity: 0;
            }
        }

        /* Rayons de lumière */
        .combo-ray {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 4px;
            height: 200px;
            background: linear-gradient(to top, transparent, rgba(255, 215, 0, 0.8), transparent);
            transform-origin: bottom center;
            animation: comboRay 1s ease-out forwards;
        }

        @keyframes comboRay {
            0% {
                transform: translateX(-50%) scaleY(0);
                opacity: 1;
            }
            50% {
                transform: translateX(-50%) scaleY(1);
                opacity: 1;
            }
            100% {
                transform: translateX(-50%) scaleY(1.5);
                opacity: 0;
            }
        }

        /* Effet de tremblement d'écran */
        @keyframes comboScreenShake {
            0%, 100% { transform: translate(0, 0); }
            10% { transform: translate(-10px, -5px); }
            20% { transform: translate(10px, 5px); }
            30% { transform: translate(-8px, 8px); }
            40% { transform: translate(8px, -8px); }
            50% { transform: translate(-5px, 5px); }
            60% { transform: translate(5px, -5px); }
            70% { transform: translate(-3px, 3px); }
            80% { transform: translate(3px, -3px); }
            90% { transform: translate(-1px, 1px); }
        }

        .screen-shake {
            animation: comboScreenShake 0.6s ease-out;
        }

        /* Cercle cosmique central */
        .combo-cosmos-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 3px solid transparent;
            border-top-color: #FFD700;
            border-bottom-color: #FFA500;
            border-radius: 50%;
            animation: comboCosmosRing 1s ease-out forwards;
        }

        @keyframes comboCosmosRing {
            0% {
                transform: scale(0) rotate(0deg);
                opacity: 1;
            }
            50% {
                transform: scale(2) rotate(180deg);
                opacity: 1;
            }
            100% {
                transform: scale(4) rotate(360deg);
                opacity: 0;
            }
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
            padding: 1rem;
            border-radius: 32px;
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

        /* Rank Promotion Banner */
        .rank-promotion-banner {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 165, 0, 0.2));
            border: 2px solid rgba(255, 215, 0, 0.5);
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: rankPromotionPulse 2s ease-in-out infinite, rankPromotionSlideIn 0.5s ease;
        }

        @keyframes rankPromotionPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.3); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.6); }
        }

        @keyframes rankPromotionSlideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .rank-promotion-icon {
            font-size: 3rem;
            animation: rankIconBounce 1s ease infinite;
        }

        @keyframes rankIconBounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .rank-promotion-text {
            text-align: left;
        }

        .rank-promotion-title {
            font-size: 0.85rem;
            color: #FFD700;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
        }

        .rank-promotion-name {
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            margin: 4px 0;
        }

        .rank-promotion-reward {
            font-size: 0.9rem;
            color: #4ADE80;
            font-weight: 600;
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
            to {
                transform: rotate(360deg);
            }
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

        /* ========================================
           RESPONSIVE - TABLETTE
        ======================================== */
        @media (max-width: 1024px) {

            .battle-card {
                width: 120px;
                height: 170px;
            }

            .hand-card {
                width: 110px;
                height: 155px;
            }

            .battle-card-info,
            .hand-card-info {
                padding: 6px;
            }

            .battle-card-name,
            .hand-card-name {
                font-size: 0.65rem;
            }

            .hp-bar-container {
                height: 8px;
            }

            .battle-card-stats,
            .hand-card-stats {
                font-size: 0.55rem;
            }

            .action-panel {
                right: 1rem;
                min-width: 200px;
            }

            .control-buttons {
                left: 1rem;
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

            .opponent-info,
            .player-info {
                display: none;
            }

            .battle-header {
                padding: 0.75rem 1rem;
            }

            .battle-arena {
                padding: 0.5rem 0.5rem;
                gap: 0.5rem;
            }

            .player-field,
            .opponent-field {
                min-height: 130px;
                gap: 0.3rem;
                flex-wrap: wrap;
            }

            .battle-card {
                width: 95px;
                height: 135px;
            }

            .battle-card-info {
                padding: 5px;
            }

            .battle-card-name {
                font-size: 0.55rem;
                margin-bottom: 3px;
            }

            .hp-bar-container {
                height: 6px;
                margin-bottom: 3px;
            }

            .battle-card-stats {
                font-size: 0.45rem;
                gap: 1px;
            }

            .mini-stat {
                padding: 1px 3px;
            }

            .player-hand-zone {
                padding: 0.5rem;
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
                width: 85px;
                height: 120px;
                flex-shrink: 0;
            }

            .hand-card-cost {
                font-size: 0.55rem;
                padding: 2px 5px;
                top: 3px;
                right: 3px;
            }

            .hand-card-info {
                padding: 5px;
            }

            .hand-card-name {
                font-size: 0.5rem;
                margin-bottom: 2px;
            }

            .hand-card-stats {
                font-size: 0.45rem;
                gap: 1px;
            }

            .hand-card-stats span {
                padding: 1px 2px;
            }

            .battle-log-zone {
                top: 2%;
                right: 10px;
                max-width: 260px;
            }

            .log-entry {
                font-size: 0.75rem;
                padding: 0.5rem 0.75rem;
            }

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

            .cosmos-display {
                padding: 0.5rem 1rem !important;
                font-size: 1rem !important;
            }

            .cosmos-display span {
                font-size: 1.1rem !important;
            }

            .combo-indicator {
                font-size: 0.5rem;
                padding: 1px 4px;
            }
        }

        /* ========================================
           RESPONSIVE - PETIT MOBILE
        ======================================== */
        @media (max-width: 480px) {

            .battle-card {
                width: 80px;
                height: 115px;
            }

            .battle-card-name {
                font-size: 0.5rem;
            }

            .hp-bar-container {
                height: 5px;
                margin-bottom: 2px;
            }

            .battle-card-stats {
                font-size: 0.4rem;
            }

            .hand-card {
                width: 75px;
                height: 105px;
            }

            .hand-card-name {
                font-size: 0.45rem;
            }

            .hand-card-stats {
                font-size: 0.4rem;
            }
        }

        /* Music controls */
        .music-controls {
            position: fixed;
            top: 25.5rem;
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
    </style>
</head>

<body>
    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <!-- Conteneur explosion combo -->
    <div class="combo-explosion-container" id="comboExplosion"></div>

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
            <h1 class="battle-title">⚔️ Combat</h1>
            <div class="turn-indicator">
                <span class="turn-badge player-turn" id="turnBadge">Tour 1</span>
            </div>
            <a href="{{ route('game.index') }}" class="quit-btn" onclick="return confirm('Abandonner le combat ?')">✖</a>
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

            <!-- Notifications Toast -->
            <div class="battle-log-zone" id="battleLog">
                <!-- Les notifications apparaissent ici dynamiquement -->
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
                <span class="hand-title">
                    🎴 Votre main
                    <span style="color: #A78BFA; margin-left: 1rem; font-weight: 800; font-size: 1rem;">
                        (🌟 <span id="playerCosmosAlt">0</span>/<span id="playerMaxCosmosAlt">0</span>)
                    </span>
                </span>
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
        <div class="action-panel-title">⚔️ Choisir une attaque</div>
        <div class="attack-list" id="attackList">
            <!-- Attaques disponibles -->
        </div>
        <button class="cancel-btn" onclick="cancelSelection()">Annuler</button>
        <div class="cosmos-display">🌟 {{ $battleState['player']['cosmos'] ?? 0 }} /
            {{ $battleState['player']['max_cosmos'] ?? 3 }}</div>
    </div>

    <!-- Boutons de contrôle -->
    <div class="control-buttons">
        <button class="control-btn end-turn-btn" id="endTurnBtn" onclick="endTurn()">⏭️</button>
    </div>

    <!-- Modal Game Over -->
    <div class="game-over-modal" id="gameOverModal">
        <div class="game-over-content">
            <div class="game-over-title" id="gameOverTitle">Victoire !</div>
            <div class="game-over-subtitle" id="gameOverSubtitle">Vous avez vaincu l'adversaire !</div>
            <div class="rank-promotion-banner" id="rankPromotionBanner" style="display: none;">
                <div class="rank-promotion-icon" id="rankPromotionIcon"></div>
                <div class="rank-promotion-text">
                    <div class="rank-promotion-title">Promotion !</div>
                    <div class="rank-promotion-name" id="rankPromotionName"></div>
                    <div class="rank-promotion-reward" id="rankPromotionReward"></div>
                </div>
            </div>
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
        // ========================================
        // CONFIGURATION
        // ========================================
        const deckId = {{ $deck->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // État du jeu
        let gameState = null;
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
                const keyframes = [{
                        transform: 'translateX(0)'
                    },
                    {
                        transform: 'translateX(-10px) rotate(-2deg)'
                    },
                    {
                        transform: 'translateX(10px) rotate(2deg)'
                    },
                    {
                        transform: 'translateX(-10px) rotate(-1deg)'
                    },
                    {
                        transform: 'translateX(10px) rotate(1deg)'
                    },
                    {
                        transform: 'translateX(0) rotate(0deg)'
                    },
                ];
                element.animate(keyframes, {
                    duration: 400,
                    easing: 'ease-in-out'
                });
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

            // ✅ ANIMATION DE DESTRUCTION SPECTACULAIRE
            destroyCardAnimation: async function(cardElement) {
                console.log('💥 destroyCardAnimation appelée', cardElement);

                if (!cardElement) {
                    console.error('❌ Pas d\'élément à détruire !');
                    return;
                }

                return new Promise(async (resolve) => {
                    const rect = cardElement.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;

                    console.log('⚠️ Phase 1: Warning');
                    // Phase 1 : Warning (clignote en rouge) - 0.4s
                    cardElement.style.transition = 'all 0.1s ease-in-out';
                    for (let i = 0; i < 3; i++) {
                        await new Promise(r => setTimeout(r, 100));
                        cardElement.style.boxShadow = '0 0 30px 10px rgba(239, 68, 68, 0.9)';
                        cardElement.style.filter = 'brightness(1.5)';
                        await new Promise(r => setTimeout(r, 100));
                        cardElement.style.boxShadow = '';
                        cardElement.style.filter = '';
                    }

                    console.log('💥 Phase 2: Impact');
                    // Phase 2 : Impact violent - 0.3s
                    this.flashElement(cardElement, '#FFFFFF');
                    this.shakeElement(cardElement);

                    await new Promise(r => setTimeout(r, 200));

                    console.log('🎆 Phase 3: Particules');
                    // Phase 3 : Explosion de particules - 0.6s
                    this.createDestructionParticles(centerX, centerY);

                    console.log('🌀 Phase 4: Rotation');
                    // Phase 4 : Brisure et rotation - 0.6s
                    setTimeout(() => {
                        cardElement.style.transition =
                            'all 0.6s cubic-bezier(0.6, -0.28, 0.735, 0.045)';
                        cardElement.style.transform = 'scale(0) rotate(720deg)';
                        cardElement.style.opacity = '0';
                        cardElement.style.filter = 'blur(5px) brightness(2)';
                    }, 100);

                    console.log('💨 Phase 5: Fumée');
                    // Phase 5 : Effet de fumée - 0.4s
                    setTimeout(() => {
                        this.createSmokeEffect(centerX, centerY);
                    }, 400);

                    // Cleanup après animation complète
                    setTimeout(() => {
                        console.log('🗑️ Cleanup - suppression de la carte du DOM');
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

                            particle.style.transition =
                                'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
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

            // Animation de pioche
            drawCardAnimation: async function(startPos, endPos) {
                return new Promise((resolve) => {
                    const cardBack = document.createElement('div');
                    cardBack.style.cssText = `
                        position: fixed;
                        left: ${startPos.x}px;
                        top: ${startPos.y}px;
                        width: 80px;
                        height: 110px;
                        background: linear-gradient(135deg, #7C3AED, #5B21B6);
                        border: 2px solid rgba(255, 255, 255, 0.3);
                        border-radius: 8px;
                        z-index: 1002;
                        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                    `;
                    document.body.appendChild(cardBack);

                    setTimeout(() => {
                        cardBack.style.transition = 'all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        cardBack.style.left = endPos.x + 'px';
                        cardBack.style.top = endPos.y + 'px';
                        cardBack.style.transform = 'rotateY(180deg)';
                    }, 50);

                    setTimeout(() => {
                        cardBack.remove();
                        resolve();
                    }, 700);
                });
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
            },

            // Animation explosion COMBO spectaculaire
            comboExplosion: async function(comboName) {
                return new Promise((resolve) => {
                    const container = document.getElementById('comboExplosion');
                    container.innerHTML = '';
                    container.classList.add('active');

                    // Ajouter le tremblement d'écran
                    document.body.classList.add('screen-shake');

                    // Flash doré
                    const flash = document.createElement('div');
                    flash.className = 'combo-flash';
                    container.appendChild(flash);

                    // Ondes de choc (3 vagues)
                    for (let i = 0; i < 3; i++) {
                        const shockwave = document.createElement('div');
                        shockwave.className = 'combo-shockwave';
                        container.appendChild(shockwave);
                    }

                    // Cercle cosmique
                    const cosmosRing = document.createElement('div');
                    cosmosRing.className = 'combo-cosmos-ring';
                    container.appendChild(cosmosRing);

                    // Rayons de lumière (12 rayons)
                    for (let i = 0; i < 12; i++) {
                        const ray = document.createElement('div');
                        ray.className = 'combo-ray';
                        ray.style.transform = `translateX(-50%) rotate(${i * 30}deg)`;
                        ray.style.animationDelay = `${i * 0.05}s`;
                        container.appendChild(ray);
                    }

                    // Particules d'explosion (30 particules)
                    for (let i = 0; i < 30; i++) {
                        const particle = document.createElement('div');
                        particle.className = 'combo-particle';
                        const angle = (Math.PI * 2 * i) / 30;
                        const distance = 200 + Math.random() * 300;
                        const tx = Math.cos(angle) * distance;
                        const ty = Math.sin(angle) * distance;
                        particle.style.left = '50%';
                        particle.style.top = '50%';
                        particle.style.setProperty('--tx', tx + 'px');
                        particle.style.setProperty('--ty', ty + 'px');
                        particle.style.animation = `comboParticle ${0.8 + Math.random() * 0.4}s ease-out forwards`;
                        particle.style.animationDelay = `${Math.random() * 0.2}s`;
                        particle.style.width = (6 + Math.random() * 8) + 'px';
                        particle.style.height = particle.style.width;
                        container.appendChild(particle);
                    }

                    // Étoiles cosmiques (8 étoiles)
                    const starEmojis = ['⭐', '✨', '💫', '🌟', '⚡'];
                    for (let i = 0; i < 8; i++) {
                        const star = document.createElement('div');
                        star.className = 'combo-star';
                        star.textContent = starEmojis[Math.floor(Math.random() * starEmojis.length)];
                        const angle = (Math.PI * 2 * i) / 8;
                        const distance = 100 + Math.random() * 100;
                        const tx = Math.cos(angle) * distance;
                        const ty = Math.sin(angle) * distance;
                        star.style.left = '50%';
                        star.style.top = '50%';
                        star.style.setProperty('--tx', tx + 'px');
                        star.style.setProperty('--ty', ty + 'px');
                        star.style.animationDelay = `${0.1 + Math.random() * 0.2}s`;
                        container.appendChild(star);
                    }

                    // Texte COMBO au centre
                    const text = document.createElement('div');
                    text.className = 'combo-text';
                    text.innerHTML = `⚡ ${comboName || 'COMBO'} ⚡`;
                    container.appendChild(text);

                    // Retirer le shake après animation
                    setTimeout(() => {
                        document.body.classList.remove('screen-shake');
                    }, 600);

                    // Nettoyer après l'animation
                    setTimeout(() => {
                        container.classList.remove('active');
                        container.innerHTML = '';
                        resolve();
                    }, 1800);
                });
            }
        };

        // ========================================
        // INITIALISATION
        // ========================================
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('actionPanelOverlay').addEventListener('click', function(e) {
                e.stopPropagation();
                cancelSelection();
            });
            initBattle();
        });

        // ========================================
        // API CALLS
        // ========================================
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

        // ========================================
        // INITIALISER LE COMBAT
        // ========================================
        async function initBattle() {
            addLogEntry('⚔️ Le combat commence !', 'turn');
            try {
                const data = await apiCall('init-battle', 'POST', {
                    deck_id: deckId
                });
                gameState = data.battle_state;
                renderAll();
                setTimeout(() => {
                    addLogEntry('🎮 C\'est votre tour !', 'info');
                }, 500);
            } catch (error) {
                console.error('Init failed:', error);
            }
        }

        // ========================================
        // RENDU COMPLET
        // ========================================
        function renderAll() {
            // DEBUG: Afficher le nombre de cartes dans chaque zone
            console.log('=== renderAll() DEBUG ===');
            console.log('Player field:', gameState?.player?.field?.length, gameState?.player?.field?.map(c => c.name));
            console.log('Player hand:', gameState?.player?.hand?.length);
            console.log('Opponent field:', gameState?.opponent?.field?.length, gameState?.opponent?.field?.map(c => c.name));
            console.log('========================');

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

        // ========================================
        // CRÉATION DES CARTES
        // ========================================
        // Vérifie si une carte fait partie d'un combo actif sur le terrain (PvE)
        function isCardInActiveCombo(card, fieldCards) {
            const allCombos = gameState.all_combos || [];
            const fieldCardIds = (fieldCards || []).map(c => c.id);

            for (const combo of allCombos) {
                const comboCardIds = combo.card_ids || [];

                // Vérifie si cette carte fait partie du combo
                if (!comboCardIds.includes(card.id)) continue;

                // Vérifie si les 3 cartes du combo sont sur le terrain
                let matchCount = 0;
                const usedIndices = [];

                comboCardIds.forEach(comboCardId => {
                    for (let i = 0; i < fieldCardIds.length; i++) {
                        if (fieldCardIds[i] === comboCardId && !usedIndices.includes(i)) {
                            matchCount++;
                            usedIndices.push(i);
                            break;
                        }
                    }
                });

                if (matchCount >= 3) {
                    return { inCombo: true, isLeader: combo.leader_card_id === card.id, comboName: combo.name };
                }
            }

            return { inCombo: false, isLeader: false, comboName: null };
        }

        function createBattleCard(card, index, owner) {
            const div = document.createElement('div');


            div.className = 'battle-card';
            div.dataset.index = index;
            div.dataset.owner = owner;
            div.dataset.name = card.name;
            div.dataset.instanceId = card.instance_id || `${card.id}_${index}`;

            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            const hpPercent = (card.current_hp / card.max_hp) * 100;
            const hpClass = hpPercent <= 25 ? 'low' : '';

            // Vérifier si la carte fait partie d'un combo actif (seulement pour le joueur)
            const fieldCards = owner === 'player' ? gameState.player.field : gameState.opponent.field;
            const comboStatus = owner === 'player' ? isCardInActiveCombo(card, fieldCards) : { inCombo: false };
            const comboIndicatorHtml = comboStatus.inCombo
                ? `<div class="combo-indicator" title="${comboStatus.comboName}">${comboStatus.isLeader ? '👑⚡' : '⚡'}</div>`
                : '';

            // Badge de niveau de fusion (si améliorée)
            const fusionLevel = card.fusion_level || 1;
            const bonusPercent = card.bonus_percent || 0;
            const fusionBadgeHtml = fusionLevel > 1
                ? `<div class="fusion-level-indicator" title="+${bonusPercent}% stats">+${fusionLevel - 1}</div>`
                : '';

            div.innerHTML = `
                ${comboIndicatorHtml}
                ${fusionBadgeHtml}
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
                        ${bonusPercent > 0 ? `<span class="mini-stat fusion-bonus">🔥 +${bonusPercent}%</span>` : ''}
                    </div>
                </div>
            `;

            // Events selon le contexte
            // if (owner === 'player' && phase === 'idle') {
            //     div.onclick = () => selectAttacker(index);
            // } else if (owner === 'opponent' && phase === 'selectingTarget') {
            //     div.classList.add('targetable');
            //     div.onclick = () => selectTarget(index);
            // }

            // if (owner === 'player' && selectedAttacker === index) {
            //     div.classList.add('selected');
            // }


            // Events selon le contexte
            if (owner === 'player') {
                console.log('Création carte joueur, phase:', phase, 'index:', index);

                if (phase === 'idle') {
                    div.onclick = () => {
                        console.log('Clic sur carte, phase actuelle:', phase);
                        selectAttacker(index);
                    };
                    div.style.cursor = 'pointer';
                }

                if (selectedAttacker === index) {
                    div.classList.add('selected');
                }
            } else if (owner === 'opponent' && phase === 'selectingTarget') {
                div.classList.add('targetable');
                div.style.cursor = 'pointer';
                div.onclick = () => {
                    console.log('Clic sur cible adverse, index:', index);
                    selectTarget(index);
                };
            }

            return div;
        }

        function createHandCard(card, index) {
            const div = document.createElement('div');
            div.className = 'hand-card';
            div.dataset.index = index;
            div.dataset.instanceId = card.instance_id || `${card.id}_hand_${index}`;

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
                div.onclick = () => playCard(index, div);
            }

            return div;
        }

        // ========================================
        // JOUER UNE CARTE
        // ========================================
        async function playCard(index, cardElement) {
            const fieldZone = document.getElementById('playerField');
            const fieldRect = fieldZone.getBoundingClientRect();
            const targetPos = {
                x: fieldRect.left + (fieldRect.width / 2) - 70,
                y: fieldRect.top + (fieldRect.height / 2) - 90
            };

            await animations.playCardAnimation(cardElement, targetPos);

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

        // ========================================
        // SÉLECTION D'ATTAQUANT
        // ========================================
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

        // Détecte les combos disponibles pour une carte (PvE)
        function detectCombosForCard(card) {
            const allCombos = gameState.all_combos || [];
            const fieldCards = gameState.player.field || [];
            const fieldCardIds = fieldCards.map(c => c.id);
            const availableCombos = [];

            allCombos.forEach(combo => {
                // Vérifier si la carte est le leader
                if (combo.leader_card_id !== card.id) return;

                // Vérifier si les 3 cartes du combo sont sur le terrain
                const comboCardIds = combo.card_ids || [];
                let matchCount = 0;
                const usedIndices = [];

                comboCardIds.forEach(comboCardId => {
                    for (let i = 0; i < fieldCardIds.length; i++) {
                        if (fieldCardIds[i] === comboCardId && !usedIndices.includes(i)) {
                            matchCount++;
                            usedIndices.push(i);
                            break;
                        }
                    }
                });

                if (matchCount >= 3) {
                    availableCombos.push(combo);
                }
            });

            return availableCombos;
        }

        function showAttackPanel(card) {
            const panel = document.getElementById('actionPanel');
            const overlay = document.getElementById('actionPanelOverlay');
            const list = document.getElementById('attackList');
            list.innerHTML = '';

            const mainAttack = card.main_attack || {
                name: 'Attaque de base',
                damage: 40 + (card.power || 0),
                endurance_cost: 20,
                cosmos_cost: 0
            };
            const attacks = [{
                key: 'main',
                name: mainAttack.name,
                damage: mainAttack.damage,
                endCost: mainAttack.endurance_cost || 20,
                cosCost: mainAttack.cosmos_cost || 0
            }];
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
            attacks.forEach(atk => {
                const canUse = (card.current_endurance || 100) >= atk.endCost && gameState.player.cosmos >= atk
                    .cosCost;

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

            // Détecter les combos disponibles pour cette carte
            const availableCombos = detectCombosForCard(card);

            if (availableCombos.length > 0) {
                // Séparateur COMBOS
                const separator = document.createElement('div');
                separator.className = 'combo-separator';
                separator.innerHTML = `<span>⚡ COMBOS ⚡</span>`;
                list.appendChild(separator);

                availableCombos.forEach(combo => {
                    const totalEndCost = (combo.attack?.endurance_cost || 0) + (combo.endurance_cost || 0);
                    const totalCosCost = (combo.attack?.cosmos_cost || 0) + (combo.cosmos_cost || 0);

                    const hasEndurance = (card.current_endurance || 0) >= totalEndCost;
                    const hasCosmos = (gameState.player.cosmos || 0) >= totalCosCost;

                    // Vérifier si le combo a déjà été utilisé (une seule fois par partie)
                    const usedCombos = gameState.used_combos || [];
                    const alreadyUsed = usedCombos.includes(combo.id);

                    const canUse = hasEndurance && hasCosmos && !alreadyUsed;

                    const btn = document.createElement('button');
                    btn.className = 'attack-btn combo-attack';
                    btn.disabled = !canUse;

                    let reasonText = '';
                    if (alreadyUsed) reasonText = '(Déjà utilisé)';
                    else if (!hasEndurance) reasonText = '(END insuffisant)';
                    else if (!hasCosmos) reasonText = '(COS insuffisant)';

                    btn.innerHTML = `
                        <div style="width: 100%;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-weight: 600; color: ${alreadyUsed ? '#6B7280' : '#FFD700'};">${alreadyUsed ? '✓' : '⚡'} ${combo.name}</div>
                                <div style="font-size: 1.2rem; color: ${alreadyUsed ? '#6B7280' : '#FFD700'}; font-weight: bold;">
                                    ${combo.attack?.damage || 0} 💥
                                </div>
                            </div>
                            <div style="font-size: 0.65rem; color: ${alreadyUsed ? '#6B7280' : '#FCD34D'}; margin-top: 2px;">
                                ${combo.attack?.name || 'Attaque Combo'}
                            </div>
                            <div style="font-size: 0.7rem; color: #9CA3AF; margin-top: 4px;">
                                ⚡ ${totalEndCost} END | 🌟 ${totalCosCost} COS
                                ${reasonText ? `<span style="color: ${alreadyUsed ? '#9CA3AF' : '#F87171'}; margin-left: 5px;">${reasonText}</span>` : ''}
                            </div>
                        </div>
                    `;
                    btn.onclick = () => {
                        if (canUse) selectAttack(`combo_${combo.id}`);
                    };
                    list.appendChild(btn);
                });
            }

            // ✅ Afficher l'overlay ET le panneau
            overlay.classList.add('visible');
            panel.classList.add('visible');
        }

        function selectAttack(attackKey) {
            selectedAttack = attackKey;
            phase = 'selectingTarget';

            // ✅ Cacher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');

            addLogEntry('🎯 Sélectionnez une cible adverse', 'info');
            renderAll();
        }

        function cancelSelection(skipRender = false) {
            console.log('cancelSelection appelé, phase avant:', phase);

            selectedAttacker = null;
            selectedAttack = null;
            phase = 'idle'; // ✅ Doit être AVANT renderAll

            // Cacher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');

            console.log('phase après:', phase);

            if (!skipRender) {
                renderAll();
            }

            console.log('phase finale:', phase);
        }
        // ========================================
        // ✅ SÉLECTION DE CIBLE - CORRIGÉE
        // ========================================
        async function selectTarget(targetIndex) {
            // Récupérer les éléments AVANT l'appel API
            const attackerCard = document.querySelector(
                `.battle-card[data-owner="player"][data-index="${selectedAttacker}"]`);
            const targetCard = document.querySelector(
                `.battle-card[data-owner="opponent"][data-index="${targetIndex}"]`);

            if (!attackerCard || !targetCard) {
                console.error('Elements not found');
                cancelSelection();
                return;
            }

            // Animation d'attaque
            const card = gameState.player.field[selectedAttacker];
            const attackData = {
                element: 'fire',
                damage: card.main_attack?.damage || 50
            };

            // Si c'est une attaque combo, lancer l'explosion spectaculaire d'abord
            const isComboAttack = selectedAttack && selectedAttack.startsWith('combo_');
            if (isComboAttack) {
                // Trouver le nom du combo pour l'afficher
                const comboId = parseInt(selectedAttack.replace('combo_', ''));
                const allCombos = gameState.all_combos || [];
                const combo = allCombos.find(c => c.id === comboId);
                const comboName = combo ? combo.name : 'COMBO';

                await animations.comboExplosion(comboName);
            }

            await animations.attackAnimation(attackerCard, targetCard, attackData);

            try {
                const data = await apiCall('attack', 'POST', {
                    deck_id: deckId,
                    attacker_index: selectedAttacker,
                    attack_type: selectedAttack,
                    target_index: targetIndex,
                    battle_state: gameState
                });

                // Afficher les dégâts
                if (data.damage) {
                    animations.showDamage(targetCard, data.damage, 'damage');
                }

                addLogEntry(`⚔️ ${data.message}`, 'damage');

                // ✅ VÉRIFIER LA DESTRUCTION AVEC LE FLAG DE L'API
                const cardWasDestroyed = data.target_destroyed === true;

                console.log('💀 Carte détruite ?', cardWasDestroyed, 'target_destroyed:', data.target_destroyed);

                if (cardWasDestroyed && targetCard) {
                    console.log('🔥 Carte détruite, lancement animation...');
                    // ✅ ATTENDRE que l'animation soit COMPLÈTE avant de mettre à jour gameState
                    await animations.destroyCardAnimation(targetCard);
                    console.log('✅ Animation terminée');
                }

                // ✅ METTRE À JOUR gameState APRÈS l'animation
                gameState = data.battle_state;

                // Vérifier fin de partie
                if (data.battle_ended) {
                    endGame(data.winner === 'player');
                    return;
                }

                // Reset sélection et render
                cancelSelection(cardWasDestroyed);

                // Render seulement si carte pas détruite (l'animation l'a déjà supprimée)
                if (!cardWasDestroyed) {
                    renderAll();
                } else {
                    // Render après un petit délai pour que le DOM soit stable
                    setTimeout(() => renderAll(), 100);
                }
            } catch (error) {
                console.error('Attack failed:', error);
                cancelSelection();
            }
        }

        // ========================================
        // ANNULER LA SÉLECTION
        // ========================================
        // function cancelSelection(skipRender = false) {
        //     selectedAttacker = null;
        //     selectedAttack = null;
        //     phase = 'idle';
        //     document.getElementById('actionPanel').classList.remove('visible');
        //     if (!skipRender) {
        //         renderAll();
        //     }
        // }

        // ========================================
        // FIN DU TOUR
        // ========================================
        async function endTurn() {
            try {
                // ✅ SAUVEGARDER les références des cartes du joueur AVANT l'appel API
                // Utilise instance_id comme clé principale, avec fallback sur le nom
                const playerCardElements = {};
                const playerCardsByName = {};
                document.querySelectorAll('.battle-card[data-owner="player"]').forEach(card => {
                    if (card.dataset.instanceId) {
                        playerCardElements[card.dataset.instanceId] = card;
                    }
                    // Fallback par nom (pour compatibilité)
                    playerCardsByName[card.dataset.name] = card;
                });

                const data = await apiCall('end-turn', 'POST', {
                    deck_id: deckId,
                    battle_state: gameState
                });

                // ✅ NE PAS mettre à jour gameState tout de suite !
                // On garde l'ancien state pour les animations

                addLogEntry('⏭️ Fin de votre tour', 'turn');

                // Animer les actions IA
                if (data.ai_actions && data.ai_actions.length > 0) {
                    addLogEntry('🤖 Tour de l\'IA...', 'turn');
                    await sleep(800);

                    for (const action of data.ai_actions) {
                        // ✅ Vérifier si cette action correspond à une destruction
                        const destroyedCard = data.destroyed_cards?.find(dc =>
                            action.includes(dc.name) && action.includes('vaincu')
                        );

                        await animateAIAction(action, playerCardElements, playerCardsByName, destroyedCard);
                        await sleep(600);
                    }
                }

                // ✅ MAINTENANT on met à jour gameState après toutes les animations
                gameState = data.battle_state;

                addLogEntry(`🔄 Tour ${gameState.turn} - Votre tour !`, 'turn');

                // Vérifier fin de partie
                if (data.battle_ended) {
                    endGame(data.winner === 'player');
                    return;
                }

                // Render à la fin
                renderAll();
            } catch (error) {
                console.error('End turn failed:', error);
            }
        }

        // ========================================
        // ANIMATIONS IA
        // ========================================
        async function animateAIAction(actionText, playerCardElements = {}, playerCardsByName = {}, destroyedCard = null) {
            console.log('AI Action:', actionText, 'Destroyed:', destroyedCard);

            if (actionText.includes('pioche') || actionText.includes('tire')) {
                await animateAIDraw();
                addLogEntry(`🤖 ${actionText}`, 'info');
            } else if (actionText.includes('joue') || actionText.includes('invoque')) {
                const cardName = extractCardName(actionText);
                await animateAIPlayCard(cardName);
                addLogEntry(`🤖 ${actionText}`, 'info');
            } else if (actionText.includes('attaque') || actionText.includes('inflige')) {
                await animateAIAttack(actionText, playerCardElements, playerCardsByName);
                addLogEntry(`🤖 ${actionText}`, 'damage');
            } else if (actionText.includes('vaincu') && destroyedCard) {
                // ✅ Animation de destruction pour la carte vaincue
                // Chercher par instance_id d'abord, puis par nom
                let targetCard = destroyedCard.instance_id ? playerCardElements[destroyedCard.instance_id] : null;
                if (!targetCard) {
                    targetCard = playerCardsByName[destroyedCard.name];
                }
                if (targetCard && targetCard.parentNode) {
                    console.log('🔥 Carte vaincue, animation de destruction:', destroyedCard.name);
                    await animations.destroyCardAnimation(targetCard);
                }
                addLogEntry(`🤖 ${actionText}`, 'damage');
            } else if (actionText.includes('déploie')) {
                addLogEntry(`🤖 ${actionText}`, 'info');
                await sleep(400);
            } else {
                addLogEntry(`🤖 ${actionText}`, 'info');
            }
        }

        function extractCardName(text) {
            const match = text.match(/joue (.+)/i) || text.match(/"(.+)"/) || text.match(/invoque (.+)/i);
            return match ? match[1].trim() : 'une carte';
        }

        async function animateAIDraw() {
            const opponentField = document.getElementById('opponentField');
            const rect = opponentField.getBoundingClientRect();
            const startPos = {
                x: 100,
                y: 100
            };
            const endPos = {
                x: rect.left + 50,
                y: rect.top - 100
            };
            await animations.drawCardAnimation(startPos, endPos);
        }

        async function animateAIPlayCard(cardName) {
            const tempCard = document.createElement('div');
            tempCard.className = 'battle-card';
            tempCard.style.position = 'fixed';
            tempCard.style.width = '140px';
            tempCard.style.height = '180px';
            tempCard.style.background = 'linear-gradient(145deg, #EF4444, #DC2626)';
            tempCard.style.border = '2px solid rgba(255, 255, 255, 0.3)';
            tempCard.style.borderRadius = '12px';
            tempCard.style.display = 'flex';
            tempCard.style.alignItems = 'center';
            tempCard.style.justifyContent = 'center';
            tempCard.style.color = 'white';
            tempCard.style.fontWeight = 'bold';
            tempCard.style.fontSize = '0.9rem';
            tempCard.style.textAlign = 'center';
            tempCard.style.padding = '1rem';
            tempCard.style.zIndex = '1000';
            tempCard.textContent = cardName;

            const opponentField = document.getElementById('opponentField');
            const fieldRect = opponentField.getBoundingClientRect();

            tempCard.style.left = (fieldRect.left - 100) + 'px';
            tempCard.style.top = '50px';
            tempCard.style.opacity = '0';

            document.body.appendChild(tempCard);

            setTimeout(() => {
                tempCard.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
                tempCard.style.left = (fieldRect.left + fieldRect.width / 2 - 70) + 'px';
                tempCard.style.top = (fieldRect.top + 20) + 'px';
                tempCard.style.opacity = '1';
                tempCard.style.transform = 'rotateY(360deg) scale(1.1)';
            }, 50);

            await sleep(500);

            animations.createSparkles(
                fieldRect.left + fieldRect.width / 2,
                fieldRect.top + fieldRect.height / 2,
                '#EF4444'
            );

            await sleep(300);

            tempCard.style.transition = 'all 0.3s ease-out';
            tempCard.style.transform = 'scale(1)';

            await sleep(300);

            tempCard.remove();
            // Ne pas appeler renderAll() ici car gameState n'est pas encore mis à jour
            // renderAll() sera appelé à la fin de endTurn() après mise à jour de gameState
        }

        async function animateAIAttack(actionText, playerCardElements = {}, playerCardsByName = {}) {
            console.log('🤖 Animation attaque IA:', actionText);

            const damageMatch = actionText.match(/(\d+)\s*(?:dégâts|PV)/i);
            const damage = damageMatch ? parseInt(damageMatch[1]) : 50;

            // Extraire le nom de l'attaquant (avant "attaque")
            const attackerNameMatch = actionText.match(/^(.+?)\s+attaque/i);
            const attackerName = attackerNameMatch ? attackerNameMatch[1].trim() : null;

            const targetNameMatch = actionText.match(/attaque\s+(.+?)\s+\(/i);
            const targetName = targetNameMatch ? targetNameMatch[1].trim() : null;

            console.log('🎯 Attaquant:', attackerName, 'Cible:', targetName, 'Dégâts:', damage);

            const opponentCards = document.querySelectorAll('.battle-card[data-owner="opponent"]');

            if (opponentCards.length === 0) {
                console.log('⚠️ Pas de cartes IA à animer');
                return;
            }

            // Trouver la carte attaquante par son nom
            let attackerCard = null;
            if (attackerName) {
                for (const card of opponentCards) {
                    if (card.dataset.name === attackerName) {
                        attackerCard = card;
                        break;
                    }
                }
            }
            // Fallback: utiliser la première carte si non trouvée
            if (!attackerCard) {
                attackerCard = opponentCards[0];
            }

            // ✅ Utiliser les références sauvegardées (par nom)
            let targetCard = null;
            if (targetName && playerCardsByName[targetName]) {
                targetCard = playerCardsByName[targetName];
                console.log('✅ Carte trouvée dans les références sauvegardées:', targetName);
            }

            // Fallback : chercher dans le DOM actuel
            if (!targetCard) {
                const playerCards = document.querySelectorAll('.battle-card[data-owner="player"]');
                if (playerCards.length > 0) {
                    for (const card of playerCards) {
                        if (card.dataset.name === targetName) {
                            targetCard = card;
                            break;
                        }
                    }
                    if (!targetCard) {
                        targetCard = playerCards[0];
                    }
                }
            }

            if (!targetCard || !targetCard.parentNode) {
                console.log('⚠️ Pas de carte cible valide');
                return;
            }

            // Animation d'attaque
            await animations.attackAnimation(attackerCard, targetCard, {
                element: 'fire',
                damage: damage
            });

            animations.showDamage(targetCard, damage, 'damage');
            animations.shakeElement(targetCard);
        }
        // ========================================
        // FIN DE PARTIE
        // ========================================
        async function endGame(victory) {
            const modal = document.getElementById('gameOverModal');
            const title = document.getElementById('gameOverTitle');
            const subtitle = document.getElementById('gameOverSubtitle');
            const reward = document.getElementById('rewardAmount');
            const promotionBanner = document.getElementById('rankPromotionBanner');

            // Stop battle music and play victory/defeat music
            const battleMusicEl = document.getElementById('battleMusic');
            if (battleMusicEl) {
                battleMusicEl.pause();
            }

            if (victory) {
                title.textContent = '🏆 Victoire !';
                title.className = 'game-over-title victory';
                subtitle.textContent = 'Vous avez vaincu l\'adversaire !';
                reward.textContent = '100';

                // Play victory music
                const victoryMusicEl = document.getElementById('victoryMusic');
                if (victoryMusicEl) {
                    victoryMusicEl.volume = 0.7;
                    victoryMusicEl.play().catch(e => console.log('Victory music blocked:', e));
                }
            } else {
                title.textContent = '💀 Défaite';
                title.className = 'game-over-title defeat';
                subtitle.textContent = 'L\'adversaire vous a vaincu...';
                reward.textContent = '25';
                promotionBanner.style.display = 'none';

                // Play defeat music
                const defeatMusicEl = document.getElementById('defeatMusic');
                if (defeatMusicEl) {
                    defeatMusicEl.volume = 0.7;
                    defeatMusicEl.play().catch(e => console.log('Defeat music blocked:', e));
                }
            }

            // Appel API pour récupérer la récompense et vérifier la promotion
            try {
                const response = await apiCall('claim-reward', 'POST', {
                    deck_id: deckId,
                    victory: victory,
                    battle_state: gameState
                });

                // Afficher la promotion de rang si applicable
                if (response && response.rank_promotion) {
                    promotionBanner.style.display = 'flex';
                    document.getElementById('rankPromotionIcon').textContent = response.rank_promotion.rank_icon;
                    document.getElementById('rankPromotionName').textContent = response.rank_promotion.rank_name;
                    document.getElementById('rankPromotionReward').textContent = `+${response.rank_promotion.reward} pièces`;
                } else {
                    promotionBanner.style.display = 'none';
                }
            } catch (e) {
                console.error('Error claiming reward:', e);
                promotionBanner.style.display = 'none';
            }

            modal.classList.add('visible');
        }

        // ========================================
        // MISE À JOUR DES STATS
        // ========================================
        function updateStats() {
            if (!gameState) return;

            const playerCosmos = gameState.player.cosmos || 0;
            const playerMaxCosmos = gameState.player.max_cosmos || 0;

            document.getElementById('playerDeckCount').textContent = gameState.player.deck?.length || 0;
            document.getElementById('playerHandCount').textContent = gameState.player.hand?.length || 0;

            document.getElementById('playerCosmos').textContent = playerCosmos;
            document.getElementById('playerMaxCosmos').textContent = playerMaxCosmos;
            document.getElementById('playerCosmosAlt').textContent = playerCosmos;
            document.getElementById('playerMaxCosmosAlt').textContent = playerMaxCosmos;

            document.getElementById('opponentDeckCount').textContent = gameState.opponent.deck?.length || 0;
            document.getElementById('opponentHandCount').textContent = gameState.opponent.hand?.length || 0;
            document.getElementById('opponentCosmos').textContent =
                `${gameState.opponent.cosmos || 0}/${gameState.opponent.max_cosmos || 0}`;
        }

        function updateTurnIndicator() {
            if (!gameState) return;
            const badge = document.getElementById('turnBadge');
            badge.textContent = `Tour ${gameState.turn}`;
        }

        // ========================================
        // NOTIFICATIONS TOAST DYNAMIQUES
        // ========================================
        const MAX_TOASTS = 3;
        const TOAST_DURATION = 4000; // 4 secondes

        function addLogEntry(message, type = 'info') {
            const log = document.getElementById('battleLog');
            const existingToasts = log.querySelectorAll('.log-entry:not(.exiting)');

            // Si on a deja 3 toasts, retirer le plus ancien (le premier dans le DOM avec column-reverse)
            if (existingToasts.length >= MAX_TOASTS) {
                const oldestToast = existingToasts[0];
                removeToast(oldestToast);
            }

            // Creer le nouveau toast
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

            // Ajouter le nouveau toast a la fin (apparait en bas avec column-reverse)
            log.appendChild(entry);

            // Retirer la classe entering apres l'animation
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
        // MUSIC PLAYER
        // ========================================
        @if(isset($battleMusics) && $battleMusics->count() > 0)
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
