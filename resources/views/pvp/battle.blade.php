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

        /* Log de combat */
        .battle-log {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            max-height: 100px;
            overflow-y: auto;
        }

        .log-entry {
            padding: 0.25rem 0;
            font-size: 0.85rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .log-entry:last-child { border-bottom: none; }
        .log-entry.damage { color: #EF4444; }
        .log-entry.heal { color: #10B981; }
        .log-entry.info { color: #60A5FA; }
        .log-entry.turn { color: #FBBF24; font-weight: 600; }

        /* Cartes */
        .battle-card {
            width: 130px;
            border-radius: 12px;
            overflow: hidden;
            background: linear-gradient(145deg, var(--color1, #333), var(--color2, #555));
            border: 2px solid rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
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

        /* Contrôles */
        .control-buttons {
            position: fixed;
            bottom: 180px;
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
            bottom: 200px;
            right: 2rem;
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

        .quit-btn {
            padding: 0.5rem 1rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.5);
            color: #EF4444;
            border-radius: 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="cosmos-bg"></div>
    <img src="{{ asset('images/baniere.webp') }}" alt="" class="bg-image">

    <div class="battle-container">
        <!-- Header -->
        <header class="battle-header">
            <div class="player-vs">
                <div class="player-badge player1 {{ $battle->current_turn_user_id == $battle->player1_id ? 'current-turn' : '' }}">
                    <div class="player-avatar">{{ strtoupper(substr($battle->player1->name, 0, 1)) }}</div>
                    <div>
                        <div class="font-bold">{{ $battle->player1->name }}</div>
                        <div class="text-xs text-gray-400">{{ $battle->player1Deck->name }}</div>
                    </div>
                </div>

                <span class="vs-text">VS</span>

                <div class="player-badge player2 {{ $battle->current_turn_user_id == $battle->player2_id ? 'current-turn' : '' }}">
                    <div class="player-avatar">{{ strtoupper(substr($battle->player2->name, 0, 1)) }}</div>
                    <div>
                        <div class="font-bold">{{ $battle->player2->name }}</div>
                        <div class="text-xs text-gray-400">{{ $battle->player2Deck->name }}</div>
                    </div>
                </div>
            </div>

            <div class="turn-indicator {{ $isMyTurn ? 'my-turn' : 'opponent-turn' }}" id="turnIndicator">
                {{ $isMyTurn ? '🎮 Votre tour !' : '⏳ Tour adverse...' }}
            </div>

            <a href="{{ route('pvp.lobby') }}" class="quit-btn" onclick="return confirm('Abandonner le combat ? Vous perdrez la partie.')">
                ✖ Quitter
            </a>
        </header>

        <!-- Zone de combat -->
        <div class="battle-arena">
            <!-- Terrain adversaire -->
            <div class="field-zone opponent-field" id="opponentField">
                <div class="empty-slot">👻</div>
            </div>

            <!-- Log -->
            <div class="battle-log" id="battleLog">
                <div class="log-entry turn">⚔️ Le combat commence !</div>
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

    <script>
        const battleId = {{ $battle->id }};
        const playerNumber = {{ $playerNumber }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        let gameState = @json($battle->battle_state);
        let isMyTurn = {{ $isMyTurn ? 'true' : 'false' }};
        let selectedAttacker = null;
        let selectedAttack = null;
        let phase = 'idle';

        // Initialisation
        document.addEventListener('DOMContentLoaded', () => {
            renderAll();
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

                const data = await response.json();

                if (data.battle_state) {
                    gameState = data.battle_state;
                    isMyTurn = data.is_my_turn;
                    
                    renderAll();
                    updateTurnUI();

                    if (isMyTurn) {
                        stopPolling();
                    }

                    if (data.status === 'finished') {
                        stopPolling();
                        window.location.href = `/pvp/battle/${battleId}`;
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
                </div>
            `;

            if (canPlay) {
                div.onclick = () => playCard(index);
            }

            return div;
        }

        function updateStats() {
            const state = getMyState();
            document.getElementById('playerCosmos').textContent = state.cosmos || 0;
            document.getElementById('playerMaxCosmos').textContent = state.max_cosmos || 0;
        }

        // Actions
        async function playCard(index) {
            if (!isMyTurn) return;

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

                const data = await response.json();

                if (data.success) {
                    gameState = data.battle_state;
                    addLogEntry(`🎴 Vous jouez ${data.card_played}`, 'info');
                    renderAll();
                } else {
                    addLogEntry(`❌ ${data.message}`, 'damage');
                }
            } catch (error) {
                console.error('Play card error:', error);
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

            attacks.forEach(atk => {
                const canUse = card.current_endurance >= atk.endCost && state.cosmos >= atk.cosCost;
                const btn = document.createElement('button');
                btn.className = 'attack-btn';
                btn.disabled = !canUse;
                btn.innerHTML = `<span>${atk.name}</span><span style="font-size:0.7rem;color:#EF4444;">⚔️${atk.damage}</span>`;
                btn.onclick = () => selectAttack(atk.key);
                list.appendChild(btn);
            });

            panel.classList.add('visible');
        }

        function selectAttack(attackKey) {
            selectedAttack = attackKey;
            phase = 'selectingTarget';
            document.getElementById('actionPanel').classList.remove('visible');
            addLogEntry('🎯 Sélectionnez une cible', 'info');
            renderAll();
        }

        async function selectTarget(targetIndex) {
            if (!isMyTurn) return;

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

                const data = await response.json();

                if (data.success) {
                    gameState = data.battle_state;
                    addLogEntry(`⚔️ ${data.message}`, 'damage');

                    if (data.battle_ended) {
                        window.location.reload();
                    }
                } else {
                    addLogEntry(`❌ ${data.message}`, 'damage');
                }

                cancelSelection();
                renderAll();
            } catch (error) {
                console.error('Attack error:', error);
                cancelSelection();
            }
        }

        function cancelSelection() {
            selectedAttacker = null;
            selectedAttack = null;
            phase = 'idle';
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

                const data = await response.json();

                if (data.success) {
                    gameState = data.battle_state;
                    isMyTurn = false;
                    addLogEntry('⏭️ Fin de votre tour', 'turn');
                    renderAll();
                    updateTurnUI();
                    startPolling();
                }
            } catch (error) {
                console.error('End turn error:', error);
            }
        }

        function addLogEntry(message, type = 'info') {
            const log = document.getElementById('battleLog');
            const entry = document.createElement('div');
            entry.className = `log-entry ${type}`;
            entry.textContent = message;
            log.appendChild(entry);
            log.scrollTop = log.scrollHeight;
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
    </script>
</body>
</html>