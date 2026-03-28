<x-app-layout>
    <style>
        /* ========================================
           HERO COMPACT
        ======================================== */
        .hero-compact {
            background: linear-gradient(135deg, rgba(88, 28, 135, 0.85), rgba(124, 58, 237, 0.65));
            border: 1.5px solid rgba(255, 215, 0, 0.3);
            border-radius: 18px;
            padding: 0.75rem 1rem;
            position: relative;
            overflow: hidden;
        }
        .hero-compact::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            animation: shimmer 4s ease-in-out infinite;
        }
        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }
        .hero-name {
            font-size: 1.05rem;
            font-weight: 800;
            color: white;
        }
        .rank-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            padding: 0.15rem 0.55rem;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.65rem;
            margin-left: 0.4rem;
        }
        .rank-bronze { background: linear-gradient(135deg, #CD7F32, #8B4513); color: white; }
        .rank-argent { background: linear-gradient(135deg, #C0C0C0, #A8A8A8); color: #1a1a2e; }
        .rank-or     { background: linear-gradient(135deg, #FFD700, #FFA500); color: #1a1a2e; }
        .rank-divin  { background: linear-gradient(135deg, #E0B0FF, #9400D3); color: white; }

        .stat-pill {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.65rem;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 20px;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
        }
        .stat-pill.coins { color: #FFD700; }
        .stat-pill.online { color: #10B981; }
        .online-dot {
            width: 7px; height: 7px;
            background: #10B981;
            border-radius: 50%;
            flex-shrink: 0;
            animation: onlinePulse 2s ease-in-out infinite;
        }
        @keyframes onlinePulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16,185,129,0.7); }
            50%       { transform: scale(1.2); box-shadow: 0 0 6px 2px rgba(16,185,129,0.4); }
        }

        /* ========================================
           BENTO GRID PRINCIPAL
        ======================================== */
        .bento-main {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 10px;
        }
        .bento-col {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Base tile */
        .bento-tile {
            position: relative;
            border-radius: 18px;
            overflow: hidden;
            display: block;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }
        .bento-tile:hover  { transform: scale(1.025); box-shadow: 0 10px 35px rgba(0,0,0,0.45); }
        .bento-tile:active { transform: scale(0.97); }

        /* Tile label overlay */
        .tile-label {
            position: absolute;
            top: 11px; left: 12px;
            font-size: 0.85rem;
            font-weight: 800;
            color: white;
            text-shadow: 0 1px 6px rgba(0,0,0,0.6);
            z-index: 2;
            line-height: 1.2;
        }
        .tile-label-sm {
            position: absolute;
            top: 10px; left: 10px;
            font-size: 0.7rem;
            font-weight: 800;
            color: rgba(255,255,255,0.9);
            z-index: 2;
            line-height: 1.2;
        }

        /* Big play tiles */
        .tile-large { height: 170px; }

        .tile-pve {
            background: linear-gradient(150deg, #4C1D95 0%, #7C3AED 55%, #4338CA 100%);
            border: 1.5px solid rgba(139,92,246,0.4);
        }
        .tile-pvp {
            background: linear-gradient(150deg, #7F1D1D 0%, #DC2626 55%, #9F1239 100%);
            border: 1.5px solid rgba(239,68,68,0.4);
        }

        /* Image inside tile */
        .tile-img {
            position: absolute;
            bottom: 0; right: 0;
            width: 65%; height: 100%;
            object-fit: cover;
            object-position: top center;
            mask-image: linear-gradient(to left, rgba(0,0,0,0.9) 30%, transparent 100%);
            -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,0.9) 30%, transparent 100%);
        }
        .tile-icon {
            position: absolute;
            bottom: 8px; right: 10px;
            font-size: 3.5rem;
            opacity: 0.85;
            line-height: 1;
        }

        /* Online badge on PvP tile */
        .tile-online-badge {
            position: absolute;
            top: 9px; right: 9px;
            background: #10B981;
            color: white;
            font-size: 0.55rem;
            font-weight: 700;
            padding: 0.15rem 0.45rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            z-index: 3;
        }

        /* Notification dot */
        .notification-dot {
            position: absolute;
            top: 8px; left: 8px;
            width: 10px; height: 10px;
            background: #10B981;
            border-radius: 50%;
            border: 2px solid rgba(15,15,35,0.9);
            animation: ndPulse 2s infinite;
            z-index: 10;
        }
        @keyframes ndPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.25); opacity: 0.8; }
        }

        /* Score tile (right col top) */
        .tile-score {
            background: linear-gradient(150deg, #78350F 0%, #D97706 60%, #F59E0B 100%);
            border: 1.5px solid rgba(245,158,11,0.5);
            border-radius: 18px;
            padding: 12px 14px;
        }
        .score-eyebrow {
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(255,255,255,0.75);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .score-big {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            line-height: 1;
            margin: 2px 0;
        }
        .score-sub {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.7);
        }

        /* Decks tile (right col middle) */
        .tile-decks {
            background: linear-gradient(150deg, #1E3A8A 0%, #2563EB 60%, #3B82F6 100%);
            border: 1.5px solid rgba(59,130,246,0.4);
            flex: 1;
            min-height: 75px;
        }

        /* Boutique CTA (right col bottom) */
        .tile-shop-cta {
            background: linear-gradient(150deg, #14532D 0%, #16A34A 60%, #22C55E 100%);
            border: 1.5px solid rgba(34,197,94,0.4);
            border-radius: 18px;
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            color: white;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .tile-shop-cta:hover {
            transform: scale(1.025);
            box-shadow: 0 6px 25px rgba(34,197,94,0.4);
            color: white;
        }
        .tile-shop-cta:active { transform: scale(0.97); }

        /* ========================================
           SECONDARY GRID (3 colonnes)
        ======================================== */
        .bento-secondary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }
        .sec-tile {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 12px 6px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            text-decoration: none;
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            text-align: center;
            transition: all 0.2s ease;
            min-height: 68px;
            position: relative;
        }
        .sec-tile:hover {
            background: rgba(255,255,255,0.13);
            transform: translateY(-2px);
            color: white;
        }
        .sec-tile-icon { font-size: 1.5rem; line-height: 1; display: flex; align-items: center; justify-content: center; }
        .sec-tile-label { font-size: 0.62rem; color: rgba(255,255,255,0.65); }
        .sec-tile.bonus-available {
            border-color: rgba(16,185,129,0.5);
            background: rgba(16,185,129,0.1);
        }

        /* ========================================
           SECTION HEADER COMPACT
        ======================================== */
        .section-header-compact {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .section-title-compact {
            font-size: 0.75rem;
            font-weight: 700;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.07em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* ========================================
           RECENT CARDS
        ======================================== */
        .cards-scroll {
            display: flex;
            gap: 0.65rem;
            overflow-x: auto;
            padding: 0.4rem;
            margin: -0.4rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(139,92,246,0.5) transparent;
        }
        .cards-scroll::-webkit-scrollbar { height: 3px; }
        .cards-scroll::-webkit-scrollbar-thumb { background: rgba(139,92,246,0.5); border-radius: 2px; }
        .recent-card {
            flex-shrink: 0;
            width: 68px; height: 92px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,0.2);
            transition: all 0.25s ease;
        }
        .recent-card:hover { transform: scale(1.1) translateY(-3px); border-color: rgba(255,215,0,0.6); }
        .recent-card img { width: 100%; height: 100%; object-fit: cover; }

        /* ========================================
           PUB NOUVELLE CARTE
        ======================================== */
        .new-card-ad {
            position: fixed;
            bottom: 50%;
            right: max(1rem, calc(50% - 18rem + 1rem));
            z-index: 200;
            width: 130px;
            background: linear-gradient(160deg, #1e1b4b 0%, #2d1b69 60%, #1a0a2a 100%);
            border: 1.5px solid rgba(255, 215, 0, 0.5);
            border-radius: 14px;
            padding: 10px 10px 8px;
            box-shadow: 0 16px 40px rgba(0,0,0,0.7), 0 0 30px rgba(139,92,246,0.3);
            cursor: pointer;
            opacity: 0;
            transform: translateY(30px) scale(0.88);
            transition: opacity 0.45s ease, transform 0.45s cubic-bezier(0.34,1.56,0.64,1), box-shadow 0.3s;
            pointer-events: none;
        }
        .new-card-ad.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: all;
        }
        .new-card-ad.dismissing {
            opacity: 0;
            transform: translateY(24px) scale(0.88);
            transition: opacity 0.35s ease, transform 0.35s ease;
        }
        .new-card-ad:hover {
            box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 40px rgba(255,215,0,0.25);
        }
        .nca-close {
            position: absolute;
            top: 6px; right: 7px;
            background: none; border: none;
            color: rgba(255,255,255,0.45);
            font-size: 0.7rem; cursor: pointer;
            line-height: 1; padding: 0;
            transition: color 0.2s;
        }
        .nca-close:hover { color: white; }
        .nca-tag {
            font-size: 0.5rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #fbbf24;
            margin-bottom: 7px;
            display: flex; align-items: center; gap: 3px;
        }
        .nca-card {
            width: 100%;
            aspect-ratio: 2.5 / 4;
            border-radius: 8px;
            overflow: hidden;
            border: 1.5px solid rgba(255,255,255,0.15);
            box-shadow: 0 4px 16px rgba(0,0,0,0.5);
            animation: ncaFloat 3s ease-in-out infinite;
        }
        .nca-card img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center top;
        }
        .nca-card-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
        }
        .nca-name {
            margin-top: 7px;
            font-size: 0.6rem;
            font-weight: 800;
            color: white;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nca-rarity {
            font-size: 0.5rem;
            font-weight: 600;
            text-align: center;
            color: rgba(255,255,255,0.45);
            margin-top: 2px;
        }
        .nca-progress {
            margin-top: 8px;
            height: 2px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
            overflow: hidden;
        }
        .nca-bar {
            height: 100%;
            width: 100%;
            background: linear-gradient(90deg, #7c3aed, #fbbf24);
            border-radius: 2px;
            transform-origin: left;
        }
        @keyframes ncaFloat {
            0%, 100% { transform: translateY(0) rotate(-3deg); }
            50%       { transform: translateY(-6px) rotate(-3deg); }
        }

        /* ========================================
           JOUEURS EN LIGNE
        ======================================== */
        .player-card {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.65rem 0.75rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .player-card:hover { background: rgba(255,255,255,0.1); border-color: rgba(16,185,129,0.3); }
        .player-card.in-battle { opacity: 0.6; }
        .player-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .player-info { flex: 1; min-width: 0; }
        .player-name {
            font-weight: 600;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.85rem;
        }
        .player-stats { font-size: 0.68rem; color: rgba(255,255,255,0.5); }
        .challenge-btn {
            padding: 0.4rem 0.7rem;
            background: linear-gradient(135deg, #10B981, #059669);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 700;
            font-size: 0.72rem;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .challenge-btn:hover:not(:disabled) { transform: scale(1.05); box-shadow: 0 0 15px rgba(16,185,129,0.4); }
        .challenge-btn:disabled { background: #4a4a4a; cursor: not-allowed; }

        /* ========================================
           INVITATION MODAL
        ======================================== */
        .invitation-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .invitation-modal.active { display: flex; }
        .invitation-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(16,185,129,0.4);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .invitation-content.incoming {
            border-color: rgba(245,158,11,0.5);
            animation: incomingPulse 2s ease-in-out infinite;
        }
        @keyframes incomingPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.2); }
            50%       { box-shadow: 0 0 40px rgba(245,158,11,0.4); }
        }
        .invitation-timer {
            width: 60px; height: 60px;
            margin: 1rem auto;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }
        .invitation-buttons { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
        .invitation-buttons button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-accept  { background: linear-gradient(135deg, #10B981, #059669); color: white; }
        .btn-decline { background: rgba(239,68,68,0.2); color: #EF4444; border: 1px solid rgba(239,68,68,0.3) !important; }
        .btn-cancel  { background: rgba(107,114,128,0.2); color: #9CA3AF; }

        /* ========================================
           DAILY BONUS MODAL
        ======================================== */
        .daily-bonus-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .daily-bonus-modal.active { display: flex; }
        .daily-bonus-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(255,215,0,0.4);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .dice-container { width: 100px; height: 100px; margin: 1.5rem auto; perspective: 600px; }
        .dice { width: 100%; height: 100%; position: relative; transform-style: preserve-3d; }
        .dice.rolling { animation: diceRoll 2s cubic-bezier(0.25, 0.1, 0.25, 1); }
        @keyframes diceRoll {
            0%   { transform: rotateX(0deg) rotateY(0deg); }
            20%  { transform: rotateX(360deg) rotateY(180deg); }
            40%  { transform: rotateX(720deg) rotateY(360deg); }
            60%  { transform: rotateX(1080deg) rotateY(540deg); }
            80%  { transform: rotateX(1440deg) rotateY(720deg); }
            100% { transform: var(--final-rotation, rotateX(1800deg) rotateY(900deg)); }
        }
        .dice-face {
            position: absolute;
            width: 100%; height: 100%;
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
        .rewards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin: 1rem 0; }
        .reward-item { background: rgba(255,255,255,0.05); padding: 0.4rem; border-radius: 6px; font-size: 0.75rem; color: rgba(255,255,255,0.7); }
        .roll-btn, .close-btn {
            width: 100%; padding: 0.875rem; border: none; border-radius: 10px;
            font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;
        }
        .roll-btn { background: linear-gradient(135deg, #FFD700, #FFA500); color: #1a1a3e; }
        .close-btn { background: linear-gradient(135deg, #10B981, #059669); color: white; margin-top: 1rem; }
        .roll-btn:hover, .close-btn:hover { transform: scale(1.02); }
        .roll-btn:disabled { background: #666; cursor: not-allowed; }
        .result-container { margin-top: 1rem; }
        .result-coins { font-size: 1.75rem; font-weight: 900; color: #FFD700; }
        .result-balance { color: rgba(255,255,255,0.6); font-size: 0.9rem; margin: 0.5rem 0; }

        /* ========================================
           FULLSCREEN MODAL
        ======================================== */
        .fullscreen-modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.95);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }
        .fullscreen-modal.active { display: flex; }
        .fullscreen-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(139,92,246,0.5);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: modalPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes modalPop {
            0%   { transform: scale(0.8); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .fullscreen-icon { font-size: 4rem; margin-bottom: 1rem; animation: floatIcon 2s ease-in-out infinite; }
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }
        .fullscreen-title { font-size: 1.5rem; font-weight: 800; color: #A78BFA; margin-bottom: 0.5rem; }
        .fullscreen-description { color: rgba(255,255,255,0.7); font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.5; }
        .fullscreen-buttons { display: flex; flex-direction: column; gap: 0.75rem; }
        .btn-fullscreen {
            width: 100%; padding: 1rem; border: none; border-radius: 12px;
            font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease;
            background: linear-gradient(135deg, #8B5CF6, #6366F1); color: white;
        }
        .btn-fullscreen:hover { transform: scale(1.02); box-shadow: 0 0 30px rgba(139,92,246,0.5); }
        .btn-skip-fullscreen {
            width: 100%; padding: 0.75rem;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 12px; font-size: 0.9rem; font-weight: 600;
            cursor: pointer; transition: all 0.3s ease;
            background: transparent; color: rgba(255,255,255,0.5);
        }
        .btn-skip-fullscreen:hover { background: rgba(255,255,255,0.1); color: white; }
        .fullscreen-note { margin-top: 1rem; font-size: 0.75rem; color: rgba(255,255,255,0.4); }
    </style>

    <!-- Fond Sanctuaire -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-800 via-gray-900 to-black"></div>
        <img src="{{ asset('images/baniere.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-[0.10]">
    </div>

    <div class="relative z-10 min-h-screen py-4 px-3 sm:px-5">
        <div class="max-w-xl mx-auto">

            {{-- ============================================================
                 HERO COMPACT
            ============================================================ --}}
            @php
                $wins = auth()->user()->wins;
                $rank = match(true) {
                    $wins >= 100 => ['name' => 'Divin',  'class' => 'rank-divin',  'icon' => '&#128081;'],
                    $wins >= 50  => ['name' => 'Or',     'class' => 'rank-or',     'icon' => '&#129351;'],
                    $wins >= 20  => ['name' => 'Argent', 'class' => 'rank-argent', 'icon' => '&#129352;'],
                    default      => ['name' => 'Bronze', 'class' => 'rank-bronze', 'icon' => '&#129353;'],
                };
            @endphp

            <div class="hero-compact mb-3">
                <div class="relative z-10 flex items-center justify-between gap-2">
                    <div class="flex items-center flex-wrap gap-1 min-w-0">
                        <span class="hero-name truncate">{{ auth()->user()->name }}</span>
                        <span class="rank-badge {{ $rank['class'] }}">{!! $rank['icon'] !!} {{ $rank['name'] }}</span>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="stat-pill coins">&#128176; <span id="dashboard-coins">{{ number_format(auth()->user()->coins) }}</span></div>
                        <div class="stat-pill online"><span class="online-dot"></span><span id="heroOnlineCount">0</span></div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 BENTO GRID PRINCIPAL
            ============================================================ --}}
            <div class="bento-main mb-3">

                {{-- Colonne gauche : PvE + PvP (grandes tuiles) --}}
                <div class="bento-col">
                    {{-- Combat PvE --}}
                    <a href="{{ route('game.index') }}" class="bento-tile tile-pve tile-large">
                        <span class="tile-label"><img src="{{ asset('images/icons/pve.webp') }}" alt="PvE" class="w-7 h-7 object-contain inline-block"> Combat<br>PvE</span>
                        @if(file_exists(public_path('images/features/pve.webp')))
                            <img class="tile-img" src="{{ asset('images/features/pve.webp') }}" alt="PvE">
                        @else
                            <span class="tile-icon"><img src="{{ asset('images/icons/pve.webp') }}" alt="PvE" class="w-10 h-10 object-contain"></span>
                        @endif
                    </a>

                    {{-- Arena PvP --}}
                    <a href="{{ route('pvp.lobby') }}" class="bento-tile tile-pvp tile-large">
                        <div class="notification-dot"></div>
                        <span class="tile-online-badge">En ligne</span>
                        <span class="tile-label"><img src="{{ asset('images/icons/trophee.webp') }}" alt="PvP" class="w-7 h-7 object-contain inline-block"> Arena<br>PvP</span>
                        @if(file_exists(public_path('images/features/pvp.webp')))
                            <img class="tile-img" src="{{ asset('images/features/pvp.webp') }}" alt="PvP">
                        @else
                            <span class="tile-icon"><img src="{{ asset('images/icons/trophee.webp') }}" alt="PvP" class="w-10 h-10 object-contain"></span>
                        @endif
                    </a>
                </div>

                {{-- Colonne droite : Score + Decks + Boutique --}}
                <div class="bento-col">
                    {{-- Score --}}
                    <div class="tile-score">
                        <div class="score-eyebrow"><img src="{{ asset('images/icons/trophee.webp') }}" alt="Score" class="w-5 h-5 object-contain inline-block"> Score</div>
                        <div class="score-big">{{ auth()->user()->wins }}</div>
                        <div class="score-sub">victoires &bull; {{ auth()->user()->cards()->count() }} cartes</div>
                    </div>

                    {{-- Mes Decks --}}
                    <a href="{{ route('decks.index') }}" class="bento-tile tile-decks" style="flex:1; min-height:75px;">
                        <span class="tile-label-sm"><img src="{{ asset('images/icons/livres.webp') }}" alt="Decks" class="w-7 h-7 object-contain inline-block"> Mes<br>Decks</span>
                        @if(file_exists(public_path('images/features/decks.webp')))
                            <img class="tile-img" src="{{ asset('images/features/decks.webp') }}" alt="Decks">
                        @else
                            <span class="tile-icon"><img src="{{ asset('images/icons/livres.webp') }}" alt="Decks" class="w-10 h-10 object-contain"></span>
                        @endif
                    </a>

                    {{-- Boutique --}}
                    <a href="{{ route('shop.index') }}" class="tile-shop-cta">
                        <img src="{{ asset('images/icons/achat.webp') }}" alt="Boutique" class="w-6 h-6 object-contain inline-block"> BOUTIQUE
                    </a>
                </div>

            </div>

            {{-- ============================================================
                 TUILES SECONDAIRES (3 colonnes)
            ============================================================ --}}
            @php
                $user = auth()->user();
                $streakInfo = $user->getStreakInfo();
                $canSpin = $user->canSpinWheel();
                $hasReward = $streakInfo['can_claim'] || $canSpin;
            @endphp

            <div class="bento-secondary mb-3">
                <a href="{{ route('collection.index') }}" class="sec-tile">
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/livres.webp') }}" alt="Collection" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Collection</span>
                </a>
                <a href="{{ route('fusion.index') }}" class="sec-tile">
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/fusion.webp') }}" alt="Fusion" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Fusion</span>
                </a>
                <a href="{{ route('sell.index') }}" class="sec-tile">
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/vente.webp') }}" alt="Vente" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Vente</span>
                </a>
                <a href="{{ route('rewards.index') }}" class="sec-tile {{ $hasReward ? 'bonus-available' : '' }}">
                    @if($hasReward)<div class="notification-dot" style="top:6px;left:6px;width:8px;height:8px;"></div>@endif
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/bonus.webp') }}" alt="Bonus" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Bonus{{ $hasReward ? ' !' : '' }}</span>
                </a>
                <a href="{{ route('factions.index') }}" class="sec-tile">
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/chateau.webp') }}" alt="Factions" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Factions</span>
                </a>
                <a href="{{ route('cards.index') }}" class="sec-tile">
                    <span class="sec-tile-icon"><img src="{{ asset('images/icons/recherche.webp') }}" alt="Encyclopédie" class="w-7 h-7 object-contain"></span>
                    <span class="sec-tile-label">Encyclop.</span>
                </a>
            </div>

            {{-- ============================================================
                 DERNIERES CARTES OBTENUES
            ============================================================ --}}
            @php
                $recentCards = auth()->user()->cards()->with('faction')->latest('user_cards.created_at')->take(8)->get();
            @endphp
            @if($recentCards->count() > 0)
            <div class="mb-3">
                <div class="section-header-compact">
                    <span class="section-title-compact">Dernieres cartes</span>
                    <a href="{{ route('collection.index') }}" class="text-purple-400 hover:text-purple-300 text-xs">Voir tout &#10132;</a>
                </div>
                <div class="bg-white/5 backdrop-blur rounded-xl border border-white/10 p-3">
                    <div class="cards-scroll">
                        @foreach($recentCards as $card)
                        <div class="recent-card" style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#333' }}, {{ $card->faction->color_secondary ?? '#555' }});">
                            @if($card->image_primary)
                                <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-2xl">&#127183;</div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- ============================================================
                 JOUEURS EN LIGNE
            ============================================================ --}}
            <div class="mb-3">
                <div class="section-header-compact">
                    <span class="section-title-compact">
                        <span class="relative inline-flex">
                            <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        Joueurs en ligne
                    </span>
                    <span id="onlineCount" class="text-green-400 text-xs font-bold">0 en ligne</span>
                </div>
                <div id="onlinePlayersContainer" class="bg-white/5 backdrop-blur rounded-xl border border-white/10 p-3">
                    <div id="onlinePlayersList" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div class="col-span-full text-center text-gray-400 py-4">
                            <div class="animate-spin inline-block w-5 h-5 border-2 border-gray-400 border-t-transparent rounded-full mb-2"></div>
                            <p class="text-sm">Chargement des joueurs...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================================
                 SECTION ADMIN
            ============================================================ --}}
            @if(auth()->user()->isAdmin())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 mb-3">
                <h4 class="text-sm font-bold text-red-400 mb-3 flex items-center gap-2">&#9881; Administration</h4>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.cards.create') }}"    class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">&#10133; Carte</a>
                    <a href="{{ route('admin.factions.create') }}" class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">&#10133; Faction</a>
                    <a href="{{ route('admin.attacks.create') }}"  class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">&#10133; Attaque</a>
                    <a href="{{ route('admin.musics.index') }}"    class="px-3 py-2 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">&#127925; Musiques</a>
                    <a href="{{ route('admin.combos.index') }}"    class="px-3 py-6 bg-white/5 border border-white/20 rounded-lg text-white text-sm hover:bg-red-500/20 transition">&#9889; Combos</a>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Pub nouvelle carte — flash one-time par session (dernière carte créée dans le jeu) --}}
    @php $latestCard = \App\Models\Card::with('faction')->latest()->first(); @endphp
    @if($latestCard)
    <div class="new-card-ad" id="newCardAd" role="dialog" aria-label="Nouvelle carte ajoutée">
        <button class="nca-close" onclick="dismissNewCardAd()" aria-label="Fermer">✕</button>
        <div class="nca-tag">✨ Dernière carte ajoutée</div>
        <div class="nca-card" style="background: linear-gradient(135deg, {{ $latestCard->faction?->color_primary ?? '#2d1b69' }}, {{ $latestCard->faction?->color_secondary ?? '#4c1d95' }});">
            @if($latestCard->image_primary)
                <img src="{{ Storage::url($latestCard->image_primary) }}" alt="{{ $latestCard->name }}">
            @else
                <div class="nca-card-placeholder">⚔️</div>
            @endif
        </div>
        <div class="nca-name">{{ $latestCard->name }}</div>
        <div class="nca-rarity">{{ ucfirst($latestCard->rarity ?? 'commune') }}</div>
        <div class="nca-progress"><div class="nca-bar" id="ncaBar"></div></div>
    </div>

    <script>
    (function() {
        var cardId = {{ $latestCard->id }};
        var storageKey = 'nc_ad_seen_' + cardId;
        if (sessionStorage.getItem(storageKey)) return;

        sessionStorage.setItem(storageKey, '1');

        var ad = document.getElementById('newCardAd');
        var bar = document.getElementById('ncaBar');
        var duration = 5000;
        var dismissTimeout;

        // Affichage avec léger délai pour que la transition soit visible
        setTimeout(function() {
            ad.classList.add('visible');
            // Barre de progression : part de 100%, descend à 0 en `duration` ms
            bar.style.transition = 'none';
            bar.style.width = '100%';
            bar.getBoundingClientRect(); // force reflow
            bar.style.transition = 'width ' + duration + 'ms linear';
            bar.style.width = '0%';
            dismissTimeout = setTimeout(dismissNewCardAd, duration);
        }, 600);
    })();

    function dismissNewCardAd() {
        var ad = document.getElementById('newCardAd');
        if (!ad) return;
        ad.classList.remove('visible');
        ad.classList.add('dismissing');
        setTimeout(function() { ad.style.display = 'none'; }, 400);
        clearTimeout(window._ncaDismissTimeout);
    }
    window._ncaDismissTimeout = null;
    </script>
    @endif

    <!-- ========================================
         MODAL BONUS QUOTIDIEN
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

    <!-- ========================================
         MODAL ENVOI INVITATION
    ======================================== -->
    <div id="sendInviteModal" class="invitation-modal">
        <div class="invitation-content">
            <h2 class="text-xl font-black text-green-400 mb-2">&#9876; Defier un joueur</h2>
            <p class="text-gray-400 text-sm mb-4">Choisissez votre deck pour le combat</p>

            <div id="targetPlayerInfo" class="mb-4 p-3 bg-white/5 rounded-lg">
                <span class="text-white font-bold" id="targetPlayerName"></span>
            </div>

            <div id="deckSelector" class="space-y-2 max-h-48 overflow-y-auto mb-4">
                @foreach(auth()->user()->decks as $deck)
                <label class="flex items-center gap-3 p-3 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition">
                    <input type="radio" name="inviteDeck" value="{{ $deck->id }}" class="w-4 h-4">
                    <span class="text-white">{{ $deck->name }}</span>
                    <span class="text-gray-500 text-sm">({{ $deck->cards()->count() }} cartes)</span>
                </label>
                @endforeach
            </div>

            <div class="invitation-buttons">
                <button class="btn-cancel" onclick="closeSendInviteModal()">Annuler</button>
                <button class="btn-accept" onclick="sendInvitation()">Envoyer le defi !</button>
            </div>
        </div>
    </div>

    <!-- ========================================
         MODAL ATTENTE REPONSE
    ======================================== -->
    <div id="waitingInviteModal" class="invitation-modal">
        <div class="invitation-content">
            <h2 class="text-xl font-black text-yellow-400 mb-2">&#9203; En attente...</h2>
            <p class="text-gray-400 text-sm mb-4">Invitation envoyee a <span id="waitingPlayerName" class="text-white font-bold"></span></p>

            <div class="invitation-timer" id="waitingTimer">60</div>

            <p class="text-gray-500 text-sm">Le joueur a 60 secondes pour repondre</p>

            <div class="invitation-buttons">
                <button class="btn-cancel" onclick="cancelSentInvitation()">Annuler l'invitation</button>
            </div>
        </div>
    </div>

    <!-- ========================================
         MODAL PLEIN ECRAN
    ======================================== -->
    <div id="fullscreenModal" class="fullscreen-modal">
        <div class="fullscreen-content">
            <div class="fullscreen-icon">&#128241;</div>
            <h2 class="fullscreen-title">Mode Plein Ecran</h2>
            <p class="fullscreen-description">
                Pour une meilleure experience de jeu sur mobile, activez le mode plein ecran !
            </p>
            <div class="fullscreen-buttons">
                <button class="btn-fullscreen" onclick="enableFullscreen()">
                    &#128377; Activer le plein ecran
                </button>
                <button class="btn-skip-fullscreen" onclick="skipFullscreen()">
                    Continuer sans plein ecran
                </button>
            </div>
            <p class="fullscreen-note">Vous pourrez toujours l'activer plus tard depuis les parametres</p>
        </div>
    </div>

    <!-- ========================================
         MODAL INVITATION RECUE
    ======================================== -->
    <div id="receivedInviteModal" class="invitation-modal">
        <div class="invitation-content incoming">
            <h2 class="text-xl font-black text-yellow-400 mb-2">&#9876; Defi recu !</h2>
            <p class="text-gray-400 text-sm mb-2"><span id="challengerName" class="text-white font-bold"></span> vous defie !</p>
            <p class="text-gray-500 text-xs mb-4">Rang: <span id="challengerRank"></span> | Victoires: <span id="challengerWins"></span></p>

            <div class="invitation-timer" id="receivedTimer">60</div>

            <div id="receivedDeckSelector" class="space-y-2 max-h-32 overflow-y-auto mb-4">
                @foreach(auth()->user()->decks as $deck)
                <label class="flex items-center gap-3 p-2 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition text-sm">
                    <input type="radio" name="acceptDeck" value="{{ $deck->id }}" class="w-4 h-4">
                    <span class="text-white">{{ $deck->name }}</span>
                </label>
                @endforeach
            </div>

            <div class="invitation-buttons">
                <button class="btn-decline" onclick="declineInvitation()">Refuser</button>
                <button class="btn-accept" onclick="acceptInvitation()">Accepter !</button>
            </div>
        </div>
    </div>

    <!-- ========================================
         MODAL CARTE MYTHIQUE HEBDOMADAIRE
    ======================================== -->
    <style>
        .weekly-card-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 3000;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(8px);
            justify-content: center;
            align-items: center;
        }
        .weekly-card-modal.active { display: flex; animation: wcFadeIn 0.4s ease; }
        @keyframes wcFadeIn { from { opacity:0; } to { opacity:1; } }

        .weekly-card-box {
            background: linear-gradient(160deg, #1a0a3a, #0d0620, #1a0a2a);
            border: 2px solid #FFD700;
            border-radius: 24px;
            padding: 2rem 1.5rem;
            max-width: 340px;
            width: 92%;
            text-align: center;
            position: relative;
            overflow: hidden;
            animation: wcPop 0.5s cubic-bezier(0.34,1.56,0.64,1);
        }
        @keyframes wcPop { 0%{transform:scale(0.7);opacity:0;} 100%{transform:scale(1);opacity:1;} }

        .weekly-card-box::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 50% 0%, rgba(255,215,0,0.18) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Aureole dorée animée */
        .wc-aura {
            position: absolute;
            top: -30px; left: 50%;
            transform: translateX(-50%);
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(255,215,0,0.25) 0%, transparent 70%);
            animation: wcPulse 2s ease-in-out infinite;
        }
        @keyframes wcPulse {
            0%,100% { transform: translateX(-50%) scale(1); opacity:0.6; }
            50% { transform: translateX(-50%) scale(1.3); opacity:1; }
        }

        .wc-badge {
            display: inline-block;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a0a00;
            font-size: 0.65rem; font-weight: 900;
            letter-spacing: 0.12em; text-transform: uppercase;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            margin-bottom: 0.75rem;
        }

        .wc-title {
            font-size: 1.5rem; font-weight: 900;
            background: linear-gradient(to right, #FFD700, #FFA500, #FFD700);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.25rem;
        }
        .wc-subtitle {
            font-size: 0.8rem; color: rgba(255,255,255,0.5);
            margin-bottom: 1.2rem;
        }

        /* Zone carte (avant révélation) */
        .wc-card-wrap {
            perspective: 800px;
            width: 130px; height: 190px;
            margin: 0 auto 1.2rem;
        }
        .wc-card-inner {
            width: 100%; height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s cubic-bezier(0.4,0,0.2,1);
        }
        .wc-card-inner.revealed { transform: rotateY(180deg); }

        .wc-card-face {
            position: absolute; inset: 0;
            border-radius: 14px;
            backface-visibility: hidden;
        }
        .wc-card-back {
            background: linear-gradient(135deg, #2d0a5e, #4a0080);
            border: 2px solid #FFD700;
            display: flex; align-items: center; justify-content: center;
            font-size: 3.5rem;
            box-shadow: 0 0 30px rgba(255,215,0,0.5), inset 0 0 20px rgba(255,215,0,0.1);
        }
        .wc-card-front {
            transform: rotateY(180deg);
            background: linear-gradient(135deg, #1a0a3a, #2d1060);
            border: 2px solid #FFD700;
            overflow: hidden;
            box-shadow: 0 0 40px rgba(255,215,0,0.6);
        }
        .wc-card-front img {
            width: 100%; height: 100%;
            object-fit: cover;
        }
        .wc-card-front .wc-card-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem;
        }

        /* Particules dorées */
        .wc-particles {
            position: absolute; inset: 0;
            pointer-events: none;
            overflow: hidden;
        }
        .wc-particle {
            position: absolute;
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #FFD700;
            opacity: 0;
        }
        @keyframes wcParticle {
            0%   { opacity:1; transform: translate(0,0) scale(1); }
            100% { opacity:0; transform: translate(var(--tx), var(--ty)) scale(0); }
        }

        .wc-card-name {
            font-size: 1rem; font-weight: 800;
            color: #FFD700;
            margin-bottom: 0.2rem;
            min-height: 1.4rem;
        }
        .wc-card-rarity {
            font-size: 0.7rem; color: rgba(255,255,255,0.5);
            text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }

        .btn-wc-reveal {
            width: 100%; padding: 0.9rem;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a0a00; font-weight: 900; font-size: 1rem;
            border: none; border-radius: 14px;
            cursor: pointer; transition: all 0.3s;
            margin-bottom: 0.5rem;
        }
        .btn-wc-reveal:hover { transform: scale(1.03); box-shadow: 0 0 25px rgba(255,215,0,0.5); }
        .btn-wc-reveal:disabled { opacity: 0.5; cursor: default; transform: none; }

        .btn-wc-close {
            width: 100%; padding: 0.65rem;
            background: transparent;
            color: rgba(255,255,255,0.4); font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px; cursor: pointer; transition: all 0.3s;
        }
        .btn-wc-close:hover { background: rgba(255,255,255,0.08); color: white; }
    </style>

    <div id="weeklyCardModal" class="weekly-card-modal">
        <div class="weekly-card-box">
            <div class="wc-aura"></div>
            <div class="wc-particles" id="wcParticles"></div>

            <div class="relative z-10">
                <div class="wc-badge">&#11088; Carte de la Semaine</div>
                <div class="wc-title">Carte Mythique !</div>
                <div class="wc-subtitle">Une carte légendaire vous attend...</div>

                <div class="wc-card-wrap">
                    <div class="wc-card-inner" id="wcCardInner">
                        <div class="wc-card-face wc-card-back">&#10024;</div>
                        <div class="wc-card-face wc-card-front" id="wcCardFront">
                            <div class="wc-card-placeholder">&#10024;</div>
                        </div>
                    </div>
                </div>

                <div class="wc-card-name" id="wcCardName"></div>
                <div class="wc-card-rarity" id="wcCardRarity"></div>

                <button class="btn-wc-reveal" id="wcRevealBtn" onclick="claimWeeklyCard()">
                    &#10024; Révéler ma carte !
                </button>
                <button class="btn-wc-close" id="wcCloseBtn" onclick="closeWeeklyCardModal()" style="display:none;">
                    Superbe ! Fermer
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const diceSymbols = ['', '&#9856;', '&#9857;', '&#9858;', '&#9859;', '&#9860;', '&#9861;'];

        // ==========================================
        // MISE A JOUR DU SOLDE (page + navigation)
        // ==========================================
        function updateAllBalances(newBalance) {
            const formattedBalance = newBalance.toLocaleString();

            // Mettre a jour le solde dans le mini-stat du dashboard
            const dashboardCoins = document.getElementById('dashboard-coins');
            if (dashboardCoins) dashboardCoins.textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (desktop)
            const navDesktop = document.getElementById('nav-coins-desktop');
            if (navDesktop) navDesktop.textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (mobile)
            const navMobile = document.getElementById('nav-coins-mobile');
            if (navMobile) navMobile.textContent = formattedBalance;
        }

        // ==========================================
        // VARIABLES GLOBALES
        // ==========================================
        let selectedPlayerId = null;
        let currentInvitationId = null;
        let waitingInterval = null;
        let receivedInterval = null;
        let pollInterval = null;

        // ==========================================
        // INITIALISATION
        // ==========================================
        document.addEventListener('DOMContentLoaded', async function() {
            // Carte mythique hebdomadaire (vérifier en premier)
            try {
                const wcResponse = await fetch('/rewards/check-weekly-card', {
                    headers: { 'Accept': 'application/json' }
                });
                const wcData = await wcResponse.json();
                if (wcData.can_claim) {
                    document.getElementById('weeklyCardModal').classList.add('active');
                }
            } catch (e) {
                console.error('Erreur vérification carte hebdo:', e);
            }

            // Bonus quotidien
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

            // Charger les joueurs en ligne
            loadOnlinePlayers();
            setInterval(loadOnlinePlayers, 10000); // Rafraichir toutes les 10s

            // Verifier les invitations recues
            checkReceivedInvitations();
            pollInterval = setInterval(checkReceivedInvitations, 3000); // Toutes les 3s
        });

        // ==========================================
        // JOUEURS EN LIGNE
        // ==========================================
        async function loadOnlinePlayers() {
            try {
                const response = await fetch('/api/v1/invitations/online-players', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                document.getElementById('onlineCount').textContent = data.count + ' en ligne';
                document.getElementById('heroOnlineCount').textContent = data.total_online;

                const container = document.getElementById('onlinePlayersList');

                if (data.count === 0) {
                    container.innerHTML = `
                        <div class="col-span-full text-center text-gray-400 py-8">
                            <div class="text-4xl mb-2">&#128564;</div>
                            <p>Aucun autre joueur en ligne</p>
                            <p class="text-sm">Revenez plus tard ou invitez vos amis !</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = data.players.map(player => `
                    <div class="player-card ${player.in_battle ? 'in-battle' : ''}">
                        <div class="player-avatar" style="background: linear-gradient(135deg, ${getRankColor(player.rank)});">
                            ${player.name.charAt(0).toUpperCase()}
                        </div>
                        <div class="player-info">
                            <div class="player-name">${player.name}</div>
                            <div class="player-stats">
                                ${getRankIcon(player.rank)} ${player.wins}V / ${player.losses}D
                                ${player.in_battle ? '<span class="text-red-400 ml-1">En combat</span>' : ''}
                            </div>
                        </div>
                        <button class="challenge-btn" onclick="openSendInviteModal(${player.id}, '${player.name}')" ${player.in_battle ? 'disabled' : ''}>
                            ${player.in_battle ? 'Occupe' : 'Defier'}
                        </button>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Erreur chargement joueurs:', error);
            }
        }

        function getRankColor(rank) {
            const colors = {
                'bronze': '#CD7F32, #8B4513',
                'argent': '#C0C0C0, #A8A8A8',
                'or': '#FFD700, #FFA500',
                'divin': '#E0B0FF, #9400D3'
            };
            return colors[rank] || colors['bronze'];
        }

        function getRankIcon(rank) {
            const icons = { 'bronze': '&#129353;', 'argent': '&#129352;', 'or': '&#129351;', 'divin': '&#128081;' };
            return icons[rank] || icons['bronze'];
        }

        // ==========================================
        // CARTE MYTHIQUE HEBDOMADAIRE
        // ==========================================
        function closeWeeklyCardModal() {
            document.getElementById('weeklyCardModal').classList.remove('active');
        }

        function spawnWeeklyParticles() {
            const container = document.getElementById('wcParticles');
            container.innerHTML = '';
            for (let i = 0; i < 20; i++) {
                const p = document.createElement('div');
                p.className = 'wc-particle';
                const tx = (Math.random() - 0.5) * 260;
                const ty = (Math.random() - 0.5) * 260;
                p.style.cssText = `
                    left: ${Math.random() * 100}%;
                    top: ${Math.random() * 100}%;
                    --tx: ${tx}px; --ty: ${ty}px;
                    background: ${['#FFD700','#FFA500','#fff','#FFE066'][Math.floor(Math.random()*4)]};
                    animation: wcParticle ${0.6 + Math.random() * 0.8}s ease-out ${Math.random() * 0.3}s forwards;
                `;
                container.appendChild(p);
            }
        }

        async function claimWeeklyCard() {
            const btn = document.getElementById('wcRevealBtn');
            btn.disabled = true;
            btn.textContent = '⏳ Récupération...';

            try {
                const response = await fetch('/rewards/claim-weekly-card', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
                const data = await response.json();

                if (!data.success) {
                    btn.disabled = false;
                    btn.textContent = '✨ Révéler ma carte !';
                    alert(data.message);
                    return;
                }

                // Préparer le recto de la carte
                const front = document.getElementById('wcCardFront');
                if (data.card.image) {
                    front.innerHTML = `<img src="/storage/${data.card.image}" alt="${data.card.name}">`;
                } else {
                    front.innerHTML = `<div class="wc-card-placeholder">&#11088;</div>`;
                }

                // Retourner la carte
                document.getElementById('wcCardInner').classList.add('revealed');

                // Particules après 400ms (milieu de l'animation)
                setTimeout(() => {
                    spawnWeeklyParticles();
                    document.getElementById('wcCardName').textContent = data.card.name;
                    document.getElementById('wcCardRarity').textContent = '✦ ' + (data.card.rarity || 'Mythique') + ' ✦';
                }, 450);

                // Afficher le bouton fermer
                setTimeout(() => {
                    btn.style.display = 'none';
                    document.getElementById('wcCloseBtn').style.display = 'block';
                }, 900);

            } catch (e) {
                console.error('Erreur claim weekly card:', e);
                btn.disabled = false;
                btn.textContent = '✨ Révéler ma carte !';
            }
        }

        // ==========================================
        // ENVOI INVITATION
        // ==========================================
        function openSendInviteModal(playerId, playerName) {
            selectedPlayerId = playerId;
            document.getElementById('targetPlayerName').textContent = playerName;
            document.getElementById('sendInviteModal').classList.add('active');
        }

        function closeSendInviteModal() {
            selectedPlayerId = null;
            document.getElementById('sendInviteModal').classList.remove('active');
        }

        async function sendInvitation() {
            const deckRadio = document.querySelector('input[name="inviteDeck"]:checked');
            if (!deckRadio) {
                alert('Veuillez selectionner un deck');
                return;
            }

            try {
                const response = await fetch('/api/v1/invitations/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        to_user_id: selectedPlayerId,
                        deck_id: deckRadio.value
                    })
                });

                const data = await response.json();

                if (data.success) {
                    closeSendInviteModal();
                    currentInvitationId = data.invitation.id;
                    document.getElementById('waitingPlayerName').textContent = document.getElementById('targetPlayerName').textContent;
                    document.getElementById('waitingInviteModal').classList.add('active');
                    startWaitingTimer();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                console.error('Erreur envoi invitation:', error);
                alert('Erreur de connexion');
            }
        }

        function startWaitingTimer() {
            let seconds = 60;
            const timerEl = document.getElementById('waitingTimer');

            waitingInterval = setInterval(async () => {
                seconds--;
                timerEl.textContent = seconds;

                // Verifier le statut de l'invitation
                try {
                    const response = await fetch('/api/v1/invitations/check-sent', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();

                    if (data.accepted && data.battle_id) {
                        clearInterval(waitingInterval);
                        document.getElementById('waitingInviteModal').classList.remove('active');
                        window.location.href = '/pvp/battle/' + data.battle_id;
                    } else if (!data.has_pending) {
                        clearInterval(waitingInterval);
                        document.getElementById('waitingInviteModal').classList.remove('active');
                        if (!data.accepted) {
                            alert('Invitation refusee ou expiree');
                        }
                    }
                } catch (e) {}

                if (seconds <= 0) {
                    clearInterval(waitingInterval);
                    document.getElementById('waitingInviteModal').classList.remove('active');
                }
            }, 1000);
        }

        async function cancelSentInvitation() {
            if (currentInvitationId) {
                try {
                    await fetch('/api/v1/invitations/cancel/' + currentInvitationId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {}
            }
            clearInterval(waitingInterval);
            document.getElementById('waitingInviteModal').classList.remove('active');
            currentInvitationId = null;
        }

        // ==========================================
        // RECEPTION INVITATION
        // ==========================================
        async function checkReceivedInvitations() {
            try {
                const response = await fetch('/api/v1/invitations/check-received', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (data.count > 0 && !document.getElementById('receivedInviteModal').classList.contains('active')) {
                    const inv = data.invitations[0];
                    currentInvitationId = inv.id;
                    document.getElementById('challengerName').textContent = inv.from_user.name;
                    document.getElementById('challengerRank').textContent = inv.from_user.rank;
                    document.getElementById('challengerWins').textContent = inv.from_user.wins;
                    document.getElementById('receivedInviteModal').classList.add('active');
                    startReceivedTimer(inv.expires_in);
                }
            } catch (e) {}
        }

        function startReceivedTimer(seconds) {
            const timerEl = document.getElementById('receivedTimer');
            timerEl.textContent = seconds;

            receivedInterval = setInterval(() => {
                seconds--;
                timerEl.textContent = Math.max(0, seconds);

                if (seconds <= 0) {
                    clearInterval(receivedInterval);
                    document.getElementById('receivedInviteModal').classList.remove('active');
                    currentInvitationId = null;
                }
            }, 1000);
        }

        async function acceptInvitation() {
            const deckRadio = document.querySelector('input[name="acceptDeck"]:checked');
            if (!deckRadio) {
                alert('Veuillez selectionner un deck');
                return;
            }

            try {
                const response = await fetch('/api/v1/invitations/accept/' + currentInvitationId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ deck_id: deckRadio.value })
                });

                const data = await response.json();

                if (data.success && data.battle_id) {
                    clearInterval(receivedInterval);
                    window.location.href = '/pvp/battle/' + data.battle_id;
                } else {
                    alert(data.message);
                    closeReceivedInviteModal();
                }
            } catch (error) {
                console.error('Erreur acceptation:', error);
                alert('Erreur de connexion');
            }
        }

        async function declineInvitation() {
            try {
                await fetch('/api/v1/invitations/decline/' + currentInvitationId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}
            closeReceivedInviteModal();
        }

        function closeReceivedInviteModal() {
            clearInterval(receivedInterval);
            document.getElementById('receivedInviteModal').classList.remove('active');
            currentInvitationId = null;
        }

        // ==========================================
        // BONUS QUOTIDIEN
        // ==========================================
        const diceRotations = {
            1: 'rotateX(1800deg) rotateY(900deg)',
            2: 'rotateX(1710deg) rotateY(900deg)',
            3: 'rotateX(1800deg) rotateY(810deg)',
            4: 'rotateX(1800deg) rotateY(990deg)',
            5: 'rotateX(1890deg) rotateY(900deg)',
            6: 'rotateX(1980deg) rotateY(900deg)'
        };

        async function rollDice() {
            const btn = document.getElementById('rollDiceBtn');
            const dice = document.getElementById('dice');
            const resultContainer = document.getElementById('resultContainer');

            btn.disabled = true;
            btn.textContent = 'Lancement...';

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
                    dice.style.setProperty('--final-rotation', diceRotations[data.dice_result]);
                    dice.classList.add('rolling');

                    setTimeout(() => {
                        dice.classList.remove('rolling');
                        dice.classList.add('result-' + data.dice_result);

                        btn.style.display = 'none';
                        document.querySelector('.rewards-grid').style.display = 'none';

                        document.getElementById('resultDice').innerHTML = diceSymbols[data.dice_result];
                        document.getElementById('resultCoins').textContent = '+' + data.coins_earned + ' pieces !';
                        document.getElementById('resultBalance').textContent = 'Nouveau solde: ' + data.new_balance.toLocaleString() + ' po';
                        resultContainer.style.display = 'block';

                        updateAllBalances(data.new_balance);
                    }, 2100);
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
            checkShowFullscreenModal();
        }

        // ==========================================
        // PLEIN ECRAN (Modal initial uniquement)
        // ==========================================
        function checkShowFullscreenModal() {
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const fullscreenChoice = localStorage.getItem('fullscreenChoice');
            const isFullscreen = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;

            if (isMobile && !fullscreenChoice && !isFullscreen) {
                setTimeout(() => {
                    document.getElementById('fullscreenModal').classList.add('active');
                }, 500);
            }
        }

        function enableFullscreen() {
            const elem = document.documentElement;

            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }

            localStorage.setItem('fullscreenChoice', 'enabled');
            document.getElementById('fullscreenModal').classList.remove('active');
        }

        function skipFullscreen() {
            localStorage.setItem('fullscreenChoice', 'skipped');
            document.getElementById('fullscreenModal').classList.remove('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const bonusModal = document.getElementById('dailyBonusModal');
                if (!bonusModal.classList.contains('active')) {
                    checkShowFullscreenModal();
                }
            }, 1000);
        });
    </script>
</x-app-layout>
