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

        /* ========================================
           CARTES DE COMBAT - STYLE TCG PRO
        ======================================== */
        .battle-card {
            width: 160px;
            height: 230px;
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
            padding: 10px;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.6));
            backdrop-filter: blur(4px);
            z-index: 5;
        }

        .battle-card-name {
            font-size: 0.75rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-bottom: 6px;
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
            font-size: 0.65rem;
            gap: 3px;
        }

        .battle-card-stats span,
        .mini-stat {
            display: flex;
            align-items: center;
            gap: 2px;
            background: rgba(0, 0, 0, 0.5);
            padding: 2px 6px;
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

        /* ========================================
           CARTES EN MAIN - STYLE TCG PRO
        ======================================== */
        .hand-card {
            width: 120px;
            height: 170px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            perspective: 1000px;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
        }

        .hand-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            transform-style: preserve-3d;
            pointer-events: none;
        }

        .hand-card.flipped .hand-card-inner {
            transform: rotateY(180deg);
        }

        .hand-card-front,
        .hand-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 12px;
            overflow: hidden;
            box-shadow:
                0 4px 12px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }

        .hand-card-front {
            background: linear-gradient(145deg, var(--color1, #1a1a2e), var(--color2, #16213e));
            border: 3px solid transparent;
            background-clip: padding-box;
            transform: rotateY(180deg);
        }

        .hand-card-front::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--color1, #4a5568), var(--color2, #2d3748));
            z-index: -1;
            pointer-events: none;
        }

        .hand-card-back {
            background: url('/images/card-back.webp') center center / cover no-repeat;
            border: 3px solid #4a3728;
        }

        .hand-card-back::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 215, 0, 0.1) 0%,
                transparent 50%,
                rgba(255, 215, 0, 0.05) 100%
            );
            pointer-events: none;
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
            font-size: 0.7rem;
            font-weight: 900;
            padding: 3px 8px;
            border-radius: 8px;
            z-index: 20;
            box-shadow:
                0 2px 8px rgba(124, 58, 237, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
            pointer-events: none;
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
            pointer-events: none;
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
            pointer-events: none;
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
            pointer-events: none;
        }

        .hand-card-name {
            font-size: 0.6rem;
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
            display: flex;
            align-items: center;
            gap: 2px;
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

        /* Indicateur niveau de fusion sur les cartes */
        .fusion-level-indicator {
            position: absolute;
            bottom: 50px;
            right: 5px;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            color: #000;
            font-size: 0.65rem;
            font-weight: bold;
            padding: 2px 5px;
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
            text-shadow: 0 0 4px rgba(255, 215, 0, 0.5);
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

        .cancel-btn {
            width: 100%;
            padding: 0.5rem;
            background: rgba(107, 114, 128, 0.3);
            border: 1px solid rgba(107, 114, 128, 0.5);
            border-radius: 8px;
            color: white;
            cursor: pointer;
        }

        .cancel-btn:hover {
            background: rgba(107, 114, 128, 0.5);
        }

        /* ========================================
           PANNEAU SELECTION CIBLE
        ======================================== */
        .target-selection-panel {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.95);
            border: 2px solid rgba(251, 191, 36, 0.5);
            border-radius: 16px;
            padding: 1rem 1.5rem;
            z-index: 100;
            display: none;
            box-shadow: 0 0 30px rgba(251, 191, 36, 0.3);
        }

        .target-selection-panel.visible {
            display: block;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        .target-selection-content {
            text-align: center;
        }

        .target-selection-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #FBBF24;
            margin-bottom: 0.5rem;
        }

        .target-selection-attack {
            font-size: 0.85rem;
            color: #9CA3AF;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .target-selection-buttons {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }

        .back-btn {
            padding: 0.5rem 1rem;
            background: rgba(59, 130, 246, 0.3);
            border: 1px solid rgba(59, 130, 246, 0.5);
            border-radius: 8px;
            color: #93C5FD;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .back-btn:hover {
            background: rgba(59, 130, 246, 0.5);
            color: white;
        }

        .target-selection-panel .cancel-btn {
            width: auto;
            padding: 0.5rem 1rem;
        }

        /* Waiting notification (non-blocking) */
        .waiting-overlay {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.85);
            border: 2px solid rgba(255, 165, 0, 0.6);
            border-radius: 12px;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 500;
            box-shadow: 0 4px 20px rgba(255, 165, 0, 0.3);
            pointer-events: none;
        }

        .waiting-overlay.visible { display: flex; }

        .waiting-content {
            text-align: center;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .waiting-content .text-4xl {
            font-size: 1.5rem;
            margin: 0;
        }

        .waiting-content h2 {
            font-size: 1rem;
            margin: 0;
            color: #ffa500;
        }

        .waiting-content p {
            font-size: 0.85rem;
            margin: 0;
        }

        .refresh-btn {
            margin-top: 12px;
            padding: 8px 16px;
            background: rgba(255, 165, 0, 0.2);
            border: 1px solid rgba(255, 165, 0, 0.5);
            border-radius: 8px;
            color: #ffa500;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .refresh-btn:hover {
            background: rgba(255, 165, 0, 0.4);
            transform: scale(1.05);
        }

        .refresh-btn:active {
            transform: scale(0.95);
        }

        /* Empty slot */
        .empty-slot {
            width: 160px;
            height: 195px;
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
                width: 140px;
                height: 200px;
            }

            .hand-card {
                width: 110px;
                height: 155px;
            }

            .player-hand {
                gap: 0.5rem;
                min-height: 140px;
            }

            .empty-slot {
                width: 140px;
                height: 170px;
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
            /* Images des cartes - montrer le haut du personnage */
            .battle-card-image,
            .hand-card-image {
                background-position: top center;
            }

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
                justify-content: center;
                align-content: flex-start;
            }

            /* Portrait: grille 3+2 */
            .battle-card {
                width: calc(33% - 0.3rem);
                max-width: 105px;
                height: 150px;
            }

            .battle-card-info {
                padding: 6px;
            }

            .battle-card-name {
                font-size: 0.6rem;
            }

            .hp-bar-container {
                height: 6px;
                margin-bottom: 3px;
            }

            .battle-card-stats {
                font-size: 0.5rem;
                gap: 2px;
            }

            .battle-card-stats span,
            .mini-stat {
                padding: 1px 3px;
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
                -webkit-overflow-scrolling: touch;
                touch-action: pan-x;
            }

            .hand-card {
                width: 85px;
                height: 120px;
                flex-shrink: 0;
                touch-action: manipulation;
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
                gap: 1px;
                padding: 1px 2px;
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
                width: 105px;
                height: 130px;
                font-size: 1.5rem;
            }

            /* Waiting notification mobile */
            .waiting-overlay {
                bottom: 10px;
                max-width: 90vw;
            }

            .waiting-content h2 {
                font-size: 0.9rem;
            }

            .waiting-content {
                padding: 0.6rem 1rem;
                gap: 8px;
            }

            .waiting-content .text-4xl {
                font-size: 1.2rem;
            }
        }

        /* ========================================
           RESPONSIVE - PETIT MOBILE
        ======================================== */
        @media (max-width: 480px) {
            .battle-card {
                width: 85px;
            }

            .battle-card-image {
                height: 100%;
            }

            .battle-card-name {
                font-size: 0.55rem;
            }

            .hand-card {
                width: 85px;
            }

            .hand-card-image {
                height: 100%;
            }

            .player-hand {
                min-height: 95px;
            }

            .field-zone {
                min-height: 100px;
            }

            .empty-slot {
                width: 85px;
                height: 105px;
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

        /* ========================================
           RESPONSIVE - MOBILE PAYSAGE (5 cartes côte à côte)
        ======================================== */
        @media (max-width: 915px) and (orientation: landscape) {
            .battle-container {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }

            .battle-header {
                padding: 0.25rem 0.5rem;
                min-height: auto;
            }

            .battle-arena {
                padding: 0.2rem;
                gap: 0.2rem;
            }

            .field-zone {
                min-height: 95px;
                gap: 1rem;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                padding: 0.25rem;
                width: 100%;
            }

            .battle-card {
                width: calc(20% - 0.3rem);
                max-width: 85px;
                height: 120px;
                flex-shrink: 0;
            }

            .battle-card-image {
                height: 60%;
            }

            .battle-card-info {
                padding: 3px;
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

            .mini-stat {
                padding: 1px 3px;
            }

            .player-hand-zone {
                padding: 0.2rem;
            }

            .player-hand {
                min-height: 80px;
                gap: 1rem;
                justify-content: center;
                width: 100%;
            }

            .hand-card {
                width: 60px;
                height: 85px;
            }

            .hand-card-name {
                font-size: 0.45rem;
            }

            .hand-card-stats {
                font-size: 0.38rem;
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

    <!-- Panneau de sélection de cible -->
    <div class="target-selection-panel" id="targetSelectionPanel">
        <div class="target-selection-content">
            <div class="target-selection-title">🎯 Choisissez une cible</div>
            <div class="target-selection-attack" id="selectedAttackName">Attaque sélectionnée</div>
            <div class="target-selection-buttons">
                <button class="back-btn" onclick="backToAttackSelection()">← Changer d'attaque</button>
                <button class="cancel-btn" onclick="cancelSelection()">✕ Annuler</button>
            </div>
        </div>
    </div>

    <!-- Contrôles -->
    <div class="control-buttons">
        <button class="control-btn end-turn-btn" id="endTurnBtn" onclick="endTurn()" {{ !$isMyTurn ? 'disabled' : '' }}>
            ⏭️ Fin du tour
        </button>
    </div>

    <!-- Waiting notification (non-blocking) -->
    <div class="waiting-overlay {{ !$isMyTurn ? 'visible' : '' }}" id="waitingOverlay">
        <div class="waiting-content">
            <div class="text-4xl animate-bounce">⏳</div>
            <div>
                <h2>Tour de {{ $opponent->name }}</h2>
                <p class="text-gray-400">En attente de son action...</p>
                <button class="refresh-btn" onclick="forceRefresh()" title="Forcer le rafraichissement">
                    🔄 Rafraichir
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Game Over -->
    <div class="game-over-modal" id="gameOverModal">
        <div class="game-over-content">
            <div class="game-over-title" id="gameOverTitle">Victoire !</div>
            <div class="game-over-subtitle" id="gameOverSubtitle">Vous avez gagné le combat !</div>
            <div class="rank-promotion-banner" id="rankPromotionBanner" style="display: none;">
                <div class="rank-promotion-icon" id="rankPromotionIcon"></div>
                <div class="rank-promotion-text">
                    <div class="rank-promotion-title">Promotion !</div>
                    <div class="rank-promotion-name" id="rankPromotionName"></div>
                    <div class="rank-promotion-reward" id="rankPromotionReward"></div>
                </div>
            </div>
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
        let previousGameState = JSON.parse(JSON.stringify(gameState)); // Pour détecter les changements
        let isMyTurn = {{ $isMyTurn ? 'true' : 'false' }};
        let selectedAttacker = null;
        let selectedAttack = null;
        let phase = 'idle'; // idle, selectingAttacker, selectingAttack, selectingTarget

        // ========================================
        // SYSTÈME D'ANIMATIONS
        // ========================================
        const animations = {
            // Animation de jeu d'une carte avec flip
            playCardAnimation: async function(cardElement, targetPos) {
                return new Promise((resolve) => {
                    const clone = cardElement.cloneNode(true);
                    clone.style.position = 'fixed';
                    clone.style.zIndex = '1000';
                    clone.style.perspective = '1000px';
                    const startRect = cardElement.getBoundingClientRect();
                    clone.style.left = startRect.left + 'px';
                    clone.style.top = startRect.top + 'px';
                    clone.style.width = startRect.width + 'px';
                    clone.style.height = startRect.height + 'px';

                    // Retirer la classe flipped pour montrer le dos
                    clone.classList.remove('flipped');

                    document.body.appendChild(clone);
                    cardElement.style.opacity = '0';

                    // Phase 1: Mouvement vers la cible + début du flip
                    setTimeout(() => {
                        clone.style.transition = 'left 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), top 0.5s cubic-bezier(0.34, 1.56, 0.64, 1), transform 0.5s ease';
                        clone.style.left = targetPos.x + 'px';
                        clone.style.top = targetPos.y + 'px';
                        clone.style.transform = 'scale(1.2)';
                        clone.style.boxShadow = '0 0 40px rgba(255, 215, 0, 0.8)';
                    }, 50);

                    // Phase 2: Flip pour révéler la carte
                    setTimeout(() => {
                        clone.classList.add('flipped');
                        const inner = clone.querySelector('.hand-card-inner');
                        if (inner) {
                            inner.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                        }
                    }, 400);

                    // Phase 3: Effet de brillance finale
                    setTimeout(() => {
                        clone.style.transform = 'scale(1.1)';
                        clone.style.boxShadow = '0 0 50px rgba(16, 185, 129, 0.9), 0 0 100px rgba(16, 185, 129, 0.4)';
                    }, 700);

                    setTimeout(() => {
                        clone.remove();
                        cardElement.style.opacity = '1';
                        resolve();
                    }, 1000);
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
        // ANIMATIONS ADVERSAIRE
        // ========================================

        // Détecter et animer les actions de l'adversaire
        async function detectAndAnimateOpponentActions(oldState, newState) {
            const oldOpponent = playerNumber === 1 ? oldState.player2 : oldState.player1;
            const newOpponent = playerNumber === 1 ? newState.player2 : newState.player1;
            const oldMy = playerNumber === 1 ? oldState.player1 : oldState.player2;
            const newMy = playerNumber === 1 ? newState.player1 : newState.player2;

            // Sauvegarder les références des cartes du joueur AVANT le rendu
            const playerCardElements = {};
            const playerCardsByName = {};
            document.querySelectorAll('.battle-card[data-owner="player"]').forEach(card => {
                const index = card.dataset.cardIndex;
                const name = card.querySelector('.battle-card-name')?.textContent;
                if (index !== undefined) {
                    playerCardElements[index] = card;
                }
                if (name) {
                    playerCardsByName[name] = card;
                }
            });

            // Détecter les nouvelles cartes jouées par l'adversaire
            const oldFieldIds = oldOpponent.field.map(c => c.id + '_' + c.name);
            const newFieldIds = newOpponent.field.map(c => c.id + '_' + c.name);

            for (let i = 0; i < newOpponent.field.length; i++) {
                const card = newOpponent.field[i];
                const cardKey = card.id + '_' + card.name;
                if (!oldFieldIds.includes(cardKey)) {
                    // Nouvelle carte jouée
                    await animateOpponentPlayCard(card.name);
                    addLogEntry(`🃏 L'adversaire joue ${card.name}`, 'info');
                }
            }

            // Détecter quelle carte adverse a attaqué (has_attacked passé de false à true)
            let attackerIndex = -1;
            for (let i = 0; i < newOpponent.field.length; i++) {
                const newOppCard = newOpponent.field[i];
                const oldOppCard = oldOpponent.field.find(c => c.name === newOppCard.name && c.id === newOppCard.id);

                if (oldOppCard && !oldOppCard.has_attacked && newOppCard.has_attacked) {
                    attackerIndex = i;
                    break;
                }
            }

            // Détecter les attaques (cartes du joueur qui ont perdu des HP ou ont été détruites)
            for (const oldCard of oldMy.field) {
                const newCard = newMy.field.find(c => c.name === oldCard.name && c.id === oldCard.id);

                if (newCard && newCard.current_hp < oldCard.current_hp) {
                    // Carte encore vivante mais a perdu des HP
                    const damage = oldCard.current_hp - newCard.current_hp;
                    await animateOpponentAttack(oldCard.name, damage, playerCardsByName, attackerIndex);
                    addLogEntry(`⚔️ L'adversaire attaque ${oldCard.name} (-${damage} PV)`, 'damage');
                } else if (!newCard) {
                    // Carte détruite - jouer l'animation d'attaque PUIS la destruction
                    const targetCard = playerCardsByName[oldCard.name];
                    if (targetCard && targetCard.parentNode) {
                        // Animation d'attaque avec les HP restants comme dégâts
                        const damage = oldCard.current_hp;
                        await animateOpponentAttack(oldCard.name, damage, playerCardsByName, attackerIndex);
                        addLogEntry(`⚔️ L'adversaire attaque ${oldCard.name} (-${damage} PV)`, 'damage');
                        addLogEntry(`💀 ${oldCard.name} a été vaincu !`, 'damage');
                        await animations.destroyCardAnimation(targetCard);
                    }
                }
            }

            // Détecter pioche adversaire (taille de la main augmente, deck diminue)
            if (newOpponent.hand.length > oldOpponent.hand.length &&
                newOpponent.deck.length < oldOpponent.deck.length) {
                await animateOpponentDraw();
                addLogEntry(`🎴 L'adversaire pioche une carte`, 'info');
            }
        }

        async function animateOpponentDraw() {
            const opponentField = document.getElementById('opponentField');
            const rect = opponentField.getBoundingClientRect();

            // Créer une carte temporaire pour l'animation
            const tempCard = document.createElement('div');
            tempCard.style.cssText = `
                position: fixed;
                width: 60px;
                height: 80px;
                background: linear-gradient(145deg, #EF4444, #DC2626);
                border: 2px solid rgba(255, 255, 255, 0.3);
                border-radius: 8px;
                z-index: 1000;
                left: ${rect.right + 50}px;
                top: ${rect.top}px;
                opacity: 0;
                transform: scale(0.5);
            `;
            document.body.appendChild(tempCard);

            // Animation d'entrée
            await new Promise(resolve => {
                setTimeout(() => {
                    tempCard.style.transition = 'all 0.5s ease-out';
                    tempCard.style.opacity = '1';
                    tempCard.style.transform = 'scale(1)';
                    tempCard.style.left = (rect.left + rect.width / 2 - 30) + 'px';
                }, 50);

                setTimeout(() => {
                    tempCard.style.opacity = '0';
                    tempCard.style.transform = 'scale(0.8)';
                }, 400);

                setTimeout(() => {
                    tempCard.remove();
                    resolve();
                }, 600);
            });
        }

        async function animateOpponentPlayCard(cardName) {
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

            await new Promise(resolve => {
                setTimeout(() => {
                    tempCard.style.transition = 'all 0.8s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    tempCard.style.left = (fieldRect.left + fieldRect.width / 2 - 70) + 'px';
                    tempCard.style.top = (fieldRect.top + 20) + 'px';
                    tempCard.style.opacity = '1';
                    tempCard.style.transform = 'rotateY(360deg) scale(1.1)';
                }, 50);

                setTimeout(() => {
                    animations.createSparkles(
                        fieldRect.left + fieldRect.width / 2,
                        fieldRect.top + fieldRect.height / 2,
                        '#EF4444'
                    );
                }, 500);

                setTimeout(() => {
                    tempCard.style.transition = 'all 0.3s ease-out';
                    tempCard.style.transform = 'scale(1)';
                }, 800);

                setTimeout(() => {
                    tempCard.remove();
                    resolve();
                }, 1100);
            });
        }

        async function animateOpponentAttack(targetName, damage, playerCardsByName, attackerIndex = -1) {
            console.log('🎯 Animation attaque adversaire sur:', targetName, 'Dégâts:', damage, 'Attaquant index:', attackerIndex);

            // Trouver la carte attaquante
            const opponentCards = document.querySelectorAll('.battle-card[data-owner="opponent"]');
            if (opponentCards.length === 0) {
                console.log('⚠️ Pas de cartes adverses à animer');
                return;
            }

            // Utiliser l'index de l'attaquant si fourni, sinon prendre la première carte
            const attackerCard = (attackerIndex >= 0 && attackerIndex < opponentCards.length)
                ? opponentCards[attackerIndex]
                : opponentCards[0];

            // Trouver la carte cible
            let targetCard = playerCardsByName[targetName];

            // Fallback: chercher dans le DOM actuel
            if (!targetCard) {
                const playerCards = document.querySelectorAll('.battle-card[data-owner="player"]');
                for (const card of playerCards) {
                    const name = card.querySelector('.battle-card-name')?.textContent;
                    if (name === targetName) {
                        targetCard = card;
                        break;
                    }
                }
                // Dernier fallback: première carte du joueur
                if (!targetCard && playerCards.length > 0) {
                    targetCard = playerCards[0];
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
        // INITIALISATION
        // ========================================
        document.addEventListener('DOMContentLoaded', () => {
            // Event listener pour l'overlay
            document.getElementById('actionPanelOverlay').addEventListener('click', function(e) {
                e.stopPropagation();
                cancelSelection();
            });

            // Délégation d'événements pour les cartes en main (meilleure compatibilité mobile)
            const playerHandContainer = document.getElementById('playerHand');
            let handTouchHandled = false;

            const handleHandCardInteraction = (e) => {
                const handCard = e.target.closest('.hand-card');
                if (!handCard) return;

                e.preventDefault();
                e.stopPropagation();

                if (handTouchHandled) return;
                handTouchHandled = true;
                setTimeout(() => { handTouchHandled = false; }, 500);

                const cardIndex = parseInt(handCard.dataset.cardIndex);
                const state = getMyState();
                const card = state.hand[cardIndex];

                if (!card) {
                    console.log('Carte non trouvée à l\'index:', cardIndex);
                    return;
                }

                const canPlay = isMyTurn && state.cosmos >= card.cost && state.field.length < 5;
                if (!canPlay) {
                    console.log('Carte non jouable:', card.name, 'isMyTurn:', isMyTurn, 'cosmos:', state.cosmos, 'cost:', card.cost);
                    return;
                }

                console.log('Délégation - Carte jouée:', card.name);
                playCard(cardIndex, handCard);
            };

            playerHandContainer.addEventListener('touchend', handleHandCardInteraction, { passive: false });
            playerHandContainer.addEventListener('click', handleHandCardInteraction);

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
        let pollingErrorCount = 0;
        let watchdogInterval = null;
        let isPollingActive = false;
        let lastSuccessfulPoll = Date.now();
        const BASE_POLLING_INTERVAL = 2000;
        const MAX_POLLING_INTERVAL = 10000;
        const WATCHDOG_INTERVAL = 5000;

        function getPollingInterval() {
            // Augmenter l'intervalle en cas d'erreurs (exponential backoff)
            if (pollingErrorCount === 0) return BASE_POLLING_INTERVAL;
            return Math.min(BASE_POLLING_INTERVAL * Math.pow(1.5, pollingErrorCount), MAX_POLLING_INTERVAL);
        }

        function startPolling() {
            if (isPollingActive) return;
            isPollingActive = true;
            pollingErrorCount = 0;
            lastSuccessfulPoll = Date.now();
            scheduleNextPoll();
            startWatchdog();
            console.log('Polling started');
        }

        function scheduleNextPoll() {
            if (!isPollingActive) return;
            const interval = getPollingInterval();
            pollingInterval = setTimeout(async () => {
                await checkForUpdates();
                if (isPollingActive) {
                    scheduleNextPoll();
                }
            }, interval);
        }

        function stopPolling() {
            isPollingActive = false;
            if (pollingInterval) {
                clearTimeout(pollingInterval);
                pollingInterval = null;
            }
            stopWatchdog();
            console.log('Polling stopped');
        }

        function startWatchdog() {
            if (watchdogInterval) return;
            watchdogInterval = setInterval(() => {
                // Si ce n'est pas notre tour et le polling n'est pas actif, le relancer
                if (!isMyTurn && !isPollingActive) {
                    console.warn('Watchdog: polling was stopped but should be active, restarting...');
                    startPolling();
                }
                // Si pas de poll réussi depuis 30 secondes et ce n'est pas notre tour, forcer une vérification
                if (!isMyTurn && Date.now() - lastSuccessfulPoll > 30000) {
                    console.warn('Watchdog: no successful poll in 30s, forcing check...');
                    pollingErrorCount = 0; // Reset pour permettre un nouveau cycle
                    checkForUpdates();
                }
            }, WATCHDOG_INTERVAL);
        }

        function stopWatchdog() {
            if (watchdogInterval) {
                clearInterval(watchdogInterval);
                watchdogInterval = null;
            }
        }

        // Fonction pour forcer un rafraîchissement manuel
        async function forceRefresh() {
            console.log('Force refresh requested');
            pollingErrorCount = 0;
            await checkForUpdates();
            if (!isMyTurn && !isPollingActive) {
                startPolling();
            }
        }

        async function checkForUpdates() {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 10000); // 10s timeout

                const response = await fetch(`/api/v1/pvp/battle-state/${battleId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    signal: controller.signal
                });

                clearTimeout(timeoutId);

                // Rediriger vers login si non authentifié
                if (response.status === 401) {
                    stopPolling();
                    window.location.href = '/login';
                    return;
                }

                if (!response.ok) {
                    console.error('Polling error: HTTP', response.status);
                    pollingErrorCount++;
                    // Ne pas arrêter complètement, juste ralentir
                    if (pollingErrorCount >= 10) {
                        addLogEntry('⚠️ Probleme de connexion. Cliquez ici pour rafraichir.', 'damage');
                    }
                    return;
                }

                // Reset error count on success
                pollingErrorCount = 0;
                lastSuccessfulPoll = Date.now();

                const data = await response.json();

                if (data.battle_state) {
                    const wasMyTurn = isMyTurn;
                    const newState = data.battle_state;

                    // Détecter si l'état a changé (pour éviter de rejouer les animations)
                    const stateChanged = JSON.stringify(previousGameState) !== JSON.stringify(newState);

                    if (stateChanged && !wasMyTurn) {
                        // Animer les actions de l'adversaire AVANT de mettre à jour l'affichage
                        // (utilise les éléments DOM actuels pour les animations)
                        await detectAndAnimateOpponentActions(previousGameState, newState);
                    }

                    // Mettre à jour le state et l'affichage
                    gameState = newState;
                    isMyTurn = data.is_my_turn;
                    previousGameState = JSON.parse(JSON.stringify(newState));

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
                // Ignorer les erreurs d'abort (timeout)
                if (error.name === 'AbortError') {
                    console.warn('Polling timeout, will retry...');
                    pollingErrorCount++;
                    return;
                }

                console.error('Polling error:', error);
                pollingErrorCount++;
                // Ne jamais arrêter complètement - le watchdog relancera si nécessaire
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

        // Variable pour tracker l'état de la main (éviter les re-renders inutiles)
        let lastHandSignature = '';

        function renderPlayerHand() {
            const container = document.getElementById('playerHand');
            const state = getMyState();

            if (!state.hand || state.hand.length === 0) {
                container.innerHTML = '<div style="color: rgba(255,255,255,0.5);">Aucune carte en main</div>';
                lastHandSignature = '';
                return;
            }

            // Créer une signature de la main actuelle (IDs des cartes)
            const currentSignature = state.hand.map(c => c.id).join(',');

            // Si la main n'a pas changé, juste mettre à jour les classes playable/unplayable
            if (currentSignature === lastHandSignature) {
                const existingCards = container.querySelectorAll('.hand-card');
                existingCards.forEach((div, index) => {
                    const card = state.hand[index];
                    if (card) {
                        const canPlay = isMyTurn && state.cosmos >= card.cost && state.field.length < 5;
                        div.classList.remove('playable', 'unplayable');
                        div.classList.add(canPlay ? 'playable' : 'unplayable');
                    }
                });
                return;
            }

            // La main a changé, recréer les cartes
            lastHandSignature = currentSignature;
            container.innerHTML = '';

            state.hand.forEach((card, index) => {
                container.appendChild(createHandCard(card, index));
            });
        }

        // Vérifie si une carte fait partie d'un combo actif sur le terrain
        function isCardInActiveCombo(card, state) {
            const allCombos = gameState.all_combos || [];
            const fieldCardIds = (state.field || []).map(c => c.id);

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
            div.dataset.cardIndex = index;
            div.dataset.owner = owner;

            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            const hpPercent = (card.current_hp / card.max_hp) * 100;

            // Vérifier si la carte fait partie d'un combo actif
            const state = owner === 'player' ? getMyState() : getOpponentState();
            const comboStatus = isCardInActiveCombo(card, state);
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
                        <div class="hp-bar" style="width: ${hpPercent}%"></div>
                    </div>
                    <div class="battle-card-stats">
                        <span>❤️ ${card.current_hp}/${card.max_hp}</span>
                        <span>💪 ${card.power || 0}</span>
                        ${bonusPercent > 0 ? `<span class="fusion-bonus">🔥+${bonusPercent}%</span>` : ''}
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
            const canPlay = isMyTurn && state.cosmos >= card.cost && state.field.length < 5;

            div.classList.add(canPlay ? 'playable' : 'unplayable');

            if (card.faction) {
                div.style.setProperty('--color1', card.faction.color_primary || '#333');
                div.style.setProperty('--color2', card.faction.color_secondary || '#555');
            }

            // Badge de niveau de fusion (si améliorée)
            const fusionLevel = card.fusion_level || 1;
            const bonusPercent = card.bonus_percent || 0;
            const fusionBadgeHtml = fusionLevel > 1
                ? `<div class="fusion-level-indicator" style="bottom: auto; top: 5px;" title="+${bonusPercent}% stats">+${fusionLevel - 1}</div>`
                : '';

            div.innerHTML = `
                <div class="hand-card-inner">
                    <div class="hand-card-back"></div>
                    <div class="hand-card-front">
                        <div class="hand-card-cost">💎 ${card.cost}</div>
                        ${fusionBadgeHtml}
                        <div class="hand-card-image" style="background-image: url('${card.image || ''}'); background-color: ${card.faction?.color_primary || '#333'};"></div>
                        <div class="hand-card-info">
                            <div class="hand-card-name">${card.name}</div>
                            <div class="hand-card-stats">
                                <span title="Points de vie">❤️ ${card.max_hp || card.health_points || '?'}</span>
                                <span title="Puissance">⚔️ ${card.power || 0}</span>
                                <span title="Défense">🛡️ ${card.defense || 0}</span>
                                ${bonusPercent > 0 ? `<span class="fusion-bonus" title="Bonus fusion">🔥+${bonusPercent}%</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Les cartes commencent face cachée puis se retournent avec une animation
            setTimeout(() => {
                div.classList.add('flipped');
            }, 100 + index * 150); // Délai progressif pour chaque carte

            // Les événements sont gérés par délégation sur le conteneur parent
            // Voir le gestionnaire dans DOMContentLoaded

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
                    previousGameState = JSON.parse(JSON.stringify(gameState));
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

        // Détecte les combos disponibles pour une carte
        function detectCombosForCard(card, state) {
            const allCombos = gameState.all_combos || [];
            const fieldCards = state.field || [];
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
            const list = document.getElementById('attackList');
            list.innerHTML = '';

            const state = getMyState();
            const attacks = [
                { key: 'main', name: card.main_attack?.name || 'Attaque', damage: card.main_attack?.damage || 50, endCost: card.main_attack?.endurance_cost || 20, cosCost: card.main_attack?.cosmos_cost || 0 }
            ];

            // Ajouter les attaques secondaires si disponibles
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

            // Détecter les combos disponibles pour cette carte
            const availableCombos = detectCombosForCard(card, state);

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
                    const hasCosmos = (state.cosmos || 0) >= totalCosCost;

                    // Vérifier si le combo a déjà été utilisé (une seule fois par partie)
                    const playerKey = playerNumber === 1 ? 'player1' : 'player2';
                    const usedCombos = gameState.used_combos?.[playerKey] || [];
                    const alreadyUsed = usedCombos.includes(combo.id);

                    const canUse = hasEndurance && hasCosmos && !alreadyUsed;

                    const btn = document.createElement('button');
                    btn.className = 'attack-btn combo-attack';
                    btn.disabled = !canUse;

                    let costInfo = '';
                    if (totalEndCost > 0) costInfo += `⚡${totalEndCost}`;
                    if (totalCosCost > 0) costInfo += ` 🌟${totalCosCost}`;

                    let reasonText = '';
                    if (alreadyUsed) reasonText = '(Déjà utilisé)';
                    else if (!hasEndurance) reasonText = '(Endurance insuffisante)';
                    else if (!hasCosmos) reasonText = '(Cosmos insuffisant)';

                    btn.innerHTML = `
                        <div style="display: flex; flex-direction: column; align-items: flex-start; width: 100%;">
                            <div style="display: flex; justify-content: space-between; width: 100%;">
                                <span style="color: ${alreadyUsed ? '#6B7280' : '#FFD700'}; font-weight: bold;">${alreadyUsed ? '✓' : '⚡'} ${combo.name}</span>
                                <span style="font-size:0.7rem;color:${alreadyUsed ? '#6B7280' : '#FFD700'};">⚔️${combo.attack?.damage || 0}</span>
                            </div>
                            <div style="font-size: 0.6rem; color: ${alreadyUsed ? '#6B7280' : '#FCD34D'}; margin-top: 2px;">
                                ${combo.attack?.name || 'Attaque Combo'}
                            </div>
                            <div style="font-size: 0.65rem; color: #9CA3AF; margin-top: 2px;">
                                Coût: ${costInfo || 'Aucun'} ${reasonText ? `<span style="color: ${alreadyUsed ? '#9CA3AF' : '#F87171'};">${reasonText}</span>` : ''}
                            </div>
                        </div>
                    `;
                    btn.onclick = () => selectAttack(`combo_${combo.id}`);
                    list.appendChild(btn);
                });
            }

            // Afficher l'overlay ET le panneau
            document.getElementById('actionPanelOverlay').classList.add('visible');
            panel.classList.add('visible');
        }

        function selectAttack(attackKey) {
            selectedAttack = attackKey;
            phase = 'selectingTarget';

            // Cacher l'overlay ET le panneau d'attaques
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');

            // Afficher le panneau de sélection de cible avec le nom de l'attaque
            const targetPanel = document.getElementById('targetSelectionPanel');
            const attackNameEl = document.getElementById('selectedAttackName');

            // Récupérer le nom de l'attaque sélectionnée
            let attackName = 'Attaque';
            const myState = getMyState();
            const card = myState.field[selectedAttacker];
            if (attackKey === 'main') {
                attackName = card.main_attack?.name || 'Attaque de base';
            } else if (attackKey === 'secondary1') {
                attackName = card.secondary_attack_1?.name || 'Attaque secondaire';
            } else if (attackKey === 'secondary2') {
                attackName = card.secondary_attack_2?.name || 'Attaque secondaire';
            } else if (attackKey.startsWith('combo_')) {
                const comboId = parseInt(attackKey.replace('combo_', ''));
                const allCombos = gameState.all_combos || [];
                const combo = allCombos.find(c => c.id === comboId);
                attackName = combo ? `⚡ ${combo.name}` : 'Combo';
            }

            attackNameEl.textContent = `💥 ${attackName}`;
            targetPanel.classList.add('visible');

            addLogEntry('🎯 Sélectionnez une cible', 'info');
            renderAll();
        }

        // Retour au choix d'attaque
        function backToAttackSelection() {
            // Cacher le panneau de sélection de cible
            document.getElementById('targetSelectionPanel').classList.remove('visible');

            // Revenir à la phase de sélection d'attaque
            selectedAttack = null;
            phase = 'selectingAttack';

            // Réafficher le panneau d'attaques
            const myState = getMyState();
            const card = myState.field[selectedAttacker];
            showAttackPanel(card);

            addLogEntry('↩️ Retour au choix d\'attaque', 'info');
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

            // Récupérer les dégâts de l'attaque sélectionnée
            let attackDamage = 50;
            if (selectedAttack === 'secondary1' && card.secondary_attack_1) {
                attackDamage = card.secondary_attack_1.damage;
            } else if (selectedAttack === 'secondary2' && card.secondary_attack_2) {
                attackDamage = card.secondary_attack_2.damage;
            } else if (card.main_attack) {
                attackDamage = card.main_attack.damage;
            }

            const attackData = {
                element: 'fire', // TODO: récupérer l'élément réel
                damage: attackDamage
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
                    previousGameState = JSON.parse(JSON.stringify(gameState));

                    // Vérifier fin de partie
                    if (data.battle_ended) {
                        const myId = {{ auth()->id() }};
                        const winnerId = parseInt(data.winner);
                        const isWinner = winnerId === myId;
                        console.log('Battle ended - Winner ID:', winnerId, 'My ID:', myId, 'Is Winner:', isWinner);
                        endGame(isWinner, data.rank_promotion);
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

            // Cacher tous les panneaux
            document.getElementById('actionPanelOverlay').classList.remove('visible');
            document.getElementById('actionPanel').classList.remove('visible');
            document.getElementById('targetSelectionPanel').classList.remove('visible');

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
                    previousGameState = JSON.parse(JSON.stringify(gameState));
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
        function endGame(victory, rankPromotion = null) {
            const modal = document.getElementById('gameOverModal');
            const title = document.getElementById('gameOverTitle');
            const subtitle = document.getElementById('gameOverSubtitle');
            const promotionBanner = document.getElementById('rankPromotionBanner');

            // Arrêter la musique de combat
            const battleMusicEl = document.getElementById('battleMusic');
            if (battleMusicEl) {
                battleMusicEl.pause();
            }

            if (victory) {
                title.textContent = '🏆 Victoire !';
                title.className = 'game-over-title victory';
                subtitle.textContent = 'Vous avez vaincu votre adversaire !';

                // Afficher la promotion de rang si applicable
                if (rankPromotion) {
                    promotionBanner.style.display = 'flex';
                    document.getElementById('rankPromotionIcon').textContent = rankPromotion.rank_icon;
                    document.getElementById('rankPromotionName').textContent = rankPromotion.rank_name;
                    document.getElementById('rankPromotionReward').textContent = `+${rankPromotion.reward} pièces`;
                } else {
                    promotionBanner.style.display = 'none';
                }

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
                promotionBanner.style.display = 'none';

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