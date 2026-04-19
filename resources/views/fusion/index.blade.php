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
                radial-gradient(ellipse at 20% 80%, rgba(255, 100, 0, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(255, 50, 0, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #1a0a0a 0%, #2a1a0a 50%, #1a1a0a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #FFD700, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,200,100,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #FFA500, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,215,0,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #FFD700, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* ========================================
           CARTES FUSION
        ======================================== */
        .fusion-card {
            position: relative;
            background: #1a1a2e;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 3px solid rgba(255, 255, 255, 0.1);
            aspect-ratio: 2.5 / 4;
            cursor: pointer;
        }

        .fusion-card:hover {
            transform: translateY(-10px) scale(1.03);
        }

        /* Bordures par rareté */
        .fusion-card.rarity-common { border-color: #6B7280; }
        .fusion-card.rarity-rare {
            border-color: #3B82F6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        .fusion-card.rarity-epic {
            border-color: #8B5CF6;
            box-shadow: 0 0 25px rgba(139, 92, 246, 0.5);
        }
        .fusion-card.rarity-legendary {
            border-color: #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            animation: legendaryPulse 2s ease-in-out infinite;
        }
        .fusion-card.rarity-mythic {
            border: 3px solid transparent;
            background: linear-gradient(#1a1a2e, #1a1a2e) padding-box,
                        linear-gradient(135deg, #FF006E, #8338EC, #3A86FF, #FF006E) border-box;
            box-shadow: 0 0 40px rgba(131, 56, 236, 0.6);
            animation: mythicPulse 3s ease-in-out infinite;
        }

        @keyframes legendaryPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.8); }
        }

        @keyframes mythicPulse {
            0%, 100% { box-shadow: 0 0 40px rgba(131, 56, 236, 0.6); }
            50% { box-shadow: 0 0 60px rgba(58, 134, 255, 0.8); }
        }

        /* Image de la carte */
        .card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .card-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background: linear-gradient(180deg, var(--color1, #333), var(--color2, #555));
        }

        /* Overlay */
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 30%, transparent 50%, rgba(0,0,0,0.85) 100%);
            z-index: 2;
            pointer-events: none;
        }

        /* Badge niveau */
        .fusion-level-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            color: #000;
            font-weight: 900;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(255, 215, 0, 0.5);
        }

        .fusion-level-badge.max-level {
            background: linear-gradient(135deg, #FF006E, #8338EC);
            color: white;
            animation: maxLevelPulse 2s ease-in-out infinite;
        }

        @keyframes maxLevelPulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 0, 110, 0.5); }
            50% { box-shadow: 0 0 20px rgba(131, 56, 236, 0.8); }
        }

        /* Badge doublons */
        .doublons-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.85);
            color: #22C55E;
            font-weight: 800;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            border: 1px solid rgba(34, 197, 94, 0.5);
            z-index: 10;
        }

        /* Infos en bas */
        .card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 10px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
        }

        .card-name {
            font-size: 0.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-stats {
            display: flex;
            gap: 8px;
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .card-stats span {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        /* ========================================
           MODAL DE FUSION
        ======================================== */
        .fusion-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .fusion-modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(180deg, #1a1a2e 0%, #0f0f1a 100%);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 20px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(255, 0, 0, 0.5);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #FFD700;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* Stats comparaison */
        .stats-comparison {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .stats-column {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 12px;
        }

        .stats-column h4 {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 0.85rem;
        }

        .stat-row:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .stat-value {
            font-weight: 700;
            color: white;
        }

        .stat-value.increased {
            color: #22C55E;
        }

        .stats-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #FFD700;
        }

        .stat-gain {
            color: #22C55E;
            font-size: 0.75rem;
            margin-left: 4px;
        }

        /* Bouton de fusion */
        .fusion-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            border: none;
            border-radius: 12px;
            color: #000;
            font-weight: 900;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .fusion-btn:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
        }

        .fusion-btn:disabled {
            background: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .fusion-cost {
            text-align: center;
            margin-top: 0.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .fusion-cost .coins {
            color: #FFD700;
            font-weight: 700;
        }

        /* Message d'erreur */
        .fusion-error {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid #EF4444;
            border-radius: 8px;
            padding: 0.75rem;
            color: #FCA5A5;
            text-align: center;
            margin-top: 1rem;
        }

        /* Section vide */
        .empty-section {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-section .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* ========================================
           OVERLAY DE TRANSFORMATION
        ======================================== */
        .transform-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 3000;
            background: rgba(0, 0, 0, 0.97);
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1.5rem;
        }

        .transform-overlay.active {
            display: flex;
            animation: overlayIn 0.25s ease;
        }

        @keyframes overlayIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        /* Aura autour de la carte */
        .transform-aura {
            position: absolute;
            inset: -30px;
            border-radius: 20px;
            pointer-events: none;
        }

        .aura-ring {
            position: absolute;
            inset: 0;
            border-radius: 20px;
            border: 2px solid rgba(255, 215, 0, 0.5);
            animation: auraPulse 1s ease-in-out infinite;
        }

        .aura-ring:nth-child(2) {
            inset: -14px;
            border-color: rgba(255, 140, 0, 0.35);
            animation-delay: 0.25s;
        }

        .aura-ring:nth-child(3) {
            inset: -28px;
            border-color: rgba(255, 80, 0, 0.2);
            animation-delay: 0.5s;
        }

        @keyframes auraPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50%       { opacity: 1;   transform: scale(1.04); }
        }

        .transform-aura.intense .aura-ring {
            animation-duration: 0.25s;
            border-color: rgba(255, 255, 255, 0.9) !important;
            box-shadow: 0 0 25px rgba(255, 215, 0, 0.9);
        }

        /* Cadre de la carte */
        .transform-card-frame {
            position: relative;
            width: 190px;
            height: 266px;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(255, 215, 0, 0.4);
            flex-shrink: 0;
        }

        .transform-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .transform-card-img.img-hide { opacity: 0; }

        .transform-card-img.anim-fade-out {
            animation: fadeOut 0.35s ease forwards;
        }

        .transform-card-img.anim-fade-in {
            animation: fadeInScale 0.6s ease forwards;
        }

        @keyframes fadeOut {
            to { opacity: 0; }
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(1.08); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* Flash blanc */
        .transform-flash {
            position: absolute;
            inset: 0;
            background: white;
            opacity: 0;
            pointer-events: none;
            z-index: 20;
        }

        .transform-flash.anim-flash {
            animation: flashBurst 0.65s ease forwards;
        }

        @keyframes flashBurst {
            0%   { opacity: 0; }
            30%  { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Badge de niveau */
        .transform-badge {
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            color: #000;
            font-weight: 900;
            font-size: 1.3rem;
            padding: 0.6rem 2rem;
            border-radius: 50px;
            box-shadow: 0 0 35px rgba(255, 215, 0, 0.8), 0 0 70px rgba(255, 140, 0, 0.4);
            opacity: 0;
        }

        .transform-badge.anim-pop {
            animation: popIn 0.55s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes popIn {
            0%   { opacity: 0; transform: scale(0.2) rotate(-5deg); }
            60%  { transform: scale(1.18) rotate(2deg); }
            100% { opacity: 1; transform: scale(1) rotate(0deg); }
        }

        /* Titre de transformation */
        .transform-title {
            font-size: 1.1rem;
            font-weight: 900;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: white;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.9);
            opacity: 0;
        }

        .transform-title.anim-slide {
            animation: slideUp 0.5s ease 0.15s forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Particules d'énergie */
        .energy-particle {
            position: fixed;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 3001;
            animation: particleFly var(--dur, 0.8s) ease-out forwards;
        }

        @keyframes particleFly {
            0%   { opacity: 1; transform: translate(0, 0) scale(1); }
            100% { opacity: 0; transform: translate(var(--tx), var(--ty)) scale(0.2); }
        }

        /* Header stats */
        .header-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .stat-badge {
            background: rgba(0, 0, 0, 0.5);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-badge .value {
            font-weight: 700;
            color: #FFD700;
        }
    </style>

    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="min-h-screen relative z-10 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="text-4xl">&#9889;</span> Forge de Fusion
                    </h1>
                    <p class="text-gray-400 mt-2">Fusionnez vos doublons pour renforcer vos cartes</p>
                </div>
                <div class="header-stats">
                    <div class="stat-badge">
                        <span class="text-gray-400">Solde:</span>
                        <span class="value" id="fusion-coins">{{ number_format($userCoins) }} po</span>
                    </div>
                    <div class="stat-badge">
                        <span class="text-gray-400">Cartes fusionnables:</span>
                        <span class="value">{{ $fusionableCards->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Cartes fusionnables -->
            <div class="mb-12">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <span class="text-2xl">&#128293;</span> Cartes avec doublons disponibles
                </h2>

                @if($fusionableCards->isEmpty())
                    <div class="empty-section">
                        <div class="icon">&#128194;</div>
                        <p>Aucune carte fusionnable pour le moment.</p>
                        <p class="text-sm mt-2">Obtenez des doublons de cartes pour pouvoir les fusionner !</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach($fusionableCards as $card)
                            @php
                                $currentFusionLevel = $card->pivot->fusion_level ?? 1;
                                $levelImg = $card->imageForLevel($currentFusionLevel);
                                $displayImgSrc = $levelImg?->image_primary ?? $card->image_primary;
                            @endphp
                            <div class="fusion-card rarity-{{ $card->rarity }}"
                                 onclick="showFusionPreview({{ $card->id }})"
                                 style="--color1: {{ $card->faction?->color_primary ?? '#333' }}; --color2: {{ $card->faction?->color_secondary ?? '#555' }}">

                                <!-- Badge niveau -->
                                <div class="fusion-level-badge {{ $currentFusionLevel >= $maxLevel ? 'max-level' : '' }}">
                                    Niv. {{ $currentFusionLevel }}
                                </div>

                                <!-- Badge doublons -->
                                <div class="doublons-badge">
                                    +{{ ($card->pivot->quantity ?? 1) - 1 }}
                                </div>

                                <!-- Image -->
                                <div class="card-image">
                                    @if($displayImgSrc)
                                        <img src="{{ Storage::url($displayImgSrc) }}" alt="{{ $card->name }}">
                                    @else
                                        <div class="card-placeholder">&#9876;</div>
                                    @endif
                                </div>

                                <div class="card-overlay"></div>

                                <!-- Infos -->
                                <div class="card-info">
                                    <div class="card-name">{{ $card->name }}</div>
                                    <div class="card-stats">
                                        <span>&#10084;&#65039; {{ $card->health_points }}</span>
                                        <span>&#128170; {{ $card->power }}</span>
                                        <span>&#128737;&#65039; {{ $card->defense }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Cartes améliorées -->
            @if($upgradedCards->isNotEmpty())
                <div>
                    <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="text-2xl">&#11088;</span> Vos cartes améliorées
                    </h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach($upgradedCards as $card)
                            @php
                                $upgradedLevel = $card->pivot->fusion_level ?? 1;
                                $upgradedLevelImg = $card->imageForLevel($upgradedLevel);
                                $upgradedImgSrc = $upgradedLevelImg?->image_primary ?? $card->image_primary;
                            @endphp
                            <div class="fusion-card rarity-{{ $card->rarity }}"
                                 style="--color1: {{ $card->faction?->color_primary ?? '#333' }}; --color2: {{ $card->faction?->color_secondary ?? '#555' }}">

                                <!-- Badge niveau -->
                                <div class="fusion-level-badge {{ $upgradedLevel >= $maxLevel ? 'max-level' : '' }}">
                                    Niv. {{ $upgradedLevel }}
                                </div>

                                <!-- Image -->
                                <div class="card-image">
                                    @if($upgradedImgSrc)
                                        <img src="{{ Storage::url($upgradedImgSrc) }}" alt="{{ $card->name }}">
                                    @else
                                        <div class="card-placeholder">&#9876;</div>
                                    @endif
                                </div>

                                <div class="card-overlay"></div>

                                <!-- Infos avec stats boostées -->
                                <div class="card-info">
                                    <div class="card-name">{{ $card->name }}</div>
                                    <div class="card-stats">
                                        <span>&#10084;&#65039; {{ $card->boosted_stats['health_points'] }}</span>
                                        <span>&#128170; {{ $card->boosted_stats['power'] }}</span>
                                        <span>&#128737;&#65039; {{ $card->boosted_stats['defense'] }}</span>
                                        <span class="stat-gain">+{{ $card->boosted_stats['bonus_percent'] }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Overlay de transformation -->
    <div id="transformOverlay" class="transform-overlay">
        <div id="transformCardWrapper" style="position: relative;">
            <!-- Aura -->
            <div class="transform-aura" id="transformAura">
                <div class="aura-ring"></div>
                <div class="aura-ring"></div>
                <div class="aura-ring"></div>
            </div>
            <!-- Carte -->
            <div class="transform-card-frame">
                <img id="transformOldImg" class="transform-card-img" src="" alt="">
                <img id="transformNewImg" class="transform-card-img img-hide" src="" alt="">
                <div id="transformFlash" class="transform-flash"></div>
            </div>
        </div>

        <!-- Badge niveau -->
        <div id="transformBadge" class="transform-badge"></div>

        <!-- Titre -->
        <div id="transformTitle" class="transform-title">Fusion accomplie!</div>
    </div>

    <!-- Son de fusion (remplace par ton fichier MP3 dans public/sounds/) -->
    <audio id="fusionSound" src="{{ asset('sounds/fusion-transform.mp3') }}" preload="none"></audio>

    <!-- Modal de fusion -->
    <div id="fusionModal" class="fusion-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeFusionModal()">&times;</button>
            <h3 class="modal-title">&#9889; Fusion de Carte</h3>

            <div id="modalContent">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Mise a jour du solde (page + navigation)
        function updateAllBalances(newBalance) {
            const formattedBalance = newBalance.toLocaleString();

            // Mettre a jour le solde sur la page
            const fusionCoins = document.getElementById('fusion-coins');
            if (fusionCoins) fusionCoins.textContent = formattedBalance + ' po';

            // Mettre a jour le solde dans la navigation (desktop)
            const navDesktop = document.getElementById('nav-coins-desktop');
            if (navDesktop) navDesktop.textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (mobile)
            const navMobile = document.getElementById('nav-coins-mobile');
            if (navMobile) navMobile.textContent = formattedBalance;
        }

        async function showFusionPreview(cardId) {
            const modal = document.getElementById('fusionModal');
            const content = document.getElementById('modalContent');

            content.innerHTML = '<div class="text-center text-white py-8">Chargement...</div>';
            modal.classList.add('active');

            try {
                const response = await fetch('/fusion/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ card_id: cardId })
                });

                const data = await response.json();

                if (data.error) {
                    content.innerHTML = `<div class="fusion-error">${data.error}</div>`;
                    return;
                }

                renderFusionPreview(data);
            } catch (error) {
                content.innerHTML = `<div class="fusion-error">Erreur de chargement</div>`;
            }
        }

        function renderFusionPreview(data) {
            const content = document.getElementById('modalContent');
            const canFuse = data.can_fuse;

            content.innerHTML = `
                <div class="text-center mb-4">
                    <h4 class="text-xl font-bold text-white">${data.card.name}</h4>
                    <p class="text-gray-400">${data.card.rarity.charAt(0).toUpperCase() + data.card.rarity.slice(1)} - ${data.card.faction || 'Sans faction'}</p>
                </div>

                <div class="text-center mb-4">
                    <span class="fusion-level-badge" style="position: static; display: inline-block;">
                        Niveau ${data.current_level} &#10132; ${data.next_level}
                    </span>
                    <p class="text-sm text-gray-400 mt-2">
                        Doublons disponibles: <span class="text-green-400 font-bold">${data.doublons_disponibles}</span>
                    </p>
                </div>

                <div class="stats-comparison">
                    <div class="stats-column">
                        <h4>Actuelles</h4>
                        <div class="stat-row">
                            <span class="stat-label">&#10084;&#65039; PV</span>
                            <span class="stat-value">${data.current_stats.health_points}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#9889; END</span>
                            <span class="stat-value">${data.current_stats.endurance}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#128737;&#65039; DEF</span>
                            <span class="stat-value">${data.current_stats.defense}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#128170; PWR</span>
                            <span class="stat-value">${data.current_stats.power}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Bonus</span>
                            <span class="stat-value">+${data.current_stats.bonus_percent}%</span>
                        </div>
                    </div>

                    <div class="stats-arrow">&#10132;</div>

                    <div class="stats-column">
                        <h4>Apres fusion</h4>
                        <div class="stat-row">
                            <span class="stat-label">&#10084;&#65039; PV</span>
                            <span class="stat-value increased">${data.next_stats.health_points}</span>
                            <span class="stat-gain">+${data.stat_gains.health_points}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#9889; END</span>
                            <span class="stat-value increased">${data.next_stats.endurance}</span>
                            <span class="stat-gain">+${data.stat_gains.endurance}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#128737;&#65039; DEF</span>
                            <span class="stat-value increased">${data.next_stats.defense}</span>
                            <span class="stat-gain">+${data.stat_gains.defense}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">&#128170; PWR</span>
                            <span class="stat-value increased">${data.next_stats.power}</span>
                            <span class="stat-gain">+${data.stat_gains.power}</span>
                        </div>
                        <div class="stat-row">
                            <span class="stat-label">Bonus</span>
                            <span class="stat-value increased">+${data.next_stats.bonus_percent}%</span>
                        </div>
                    </div>
                </div>

                ${!canFuse ? `<div class="fusion-error">${data.reason}</div>` : ''}

                <button class="fusion-btn"
                        onclick="performFusion(${data.card.id})"
                        ${!canFuse ? 'disabled' : ''}>
                    &#9889; Fusionner
                </button>
                <p class="fusion-cost">
                    Cout: <span class="coins">${data.cost} po</span>
                    ${data.user_coins < data.cost ? `<span class="text-red-400">(Solde: ${data.user_coins} po)</span>` : ''}
                </p>
            `;
        }

        /* ============================================================
           SON DE TRANSFORMATION
        ============================================================ */
        function playTransformSound() {
            // Tentative avec le fichier MP3 custom (public/sounds/fusion-transform.mp3)
            const audio = document.getElementById('fusionSound');
            if (audio) {
                audio.currentTime = 0;
                audio.play().catch(() => playWebAudioTransform());
                return;
            }
            playWebAudioTransform();
        }

        function playWebAudioTransform() {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            const now = ctx.currentTime;

            const layer = (type, f0, f1, g0, g1, dur, delay = 0) => {
                const osc  = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.type = type;
                osc.frequency.setValueAtTime(f0, now + delay);
                osc.frequency.exponentialRampToValueAtTime(f1, now + delay + dur);
                gain.gain.setValueAtTime(g0, now + delay);
                gain.gain.exponentialRampToValueAtTime(0.001, now + delay + dur);
                osc.start(now + delay);
                osc.stop(now + delay + dur + 0.05);
            };

            // Montée de puissance (type Dragon Ball / Saint Seiya)
            layer('sawtooth', 80,   600,  0.18, 0.001, 2.2);
            layer('triangle', 160,  1200, 0.10, 0.001, 2.0, 0.1);
            layer('sine',     320,  2000, 0.06, 0.001, 1.6, 0.2);

            // Impact au flash (~1s)
            layer('sine', 220, 55,  0.35, 0.001, 0.4, 1.0);
            layer('sine', 180, 40,  0.20, 0.001, 0.35, 1.05);

            // Shimmer final (~1.8s)
            layer('sine', 1800, 3200, 0.04, 0.001, 0.6, 1.8);
        }

        /* ============================================================
           PARTICULES D'ÉNERGIE
        ============================================================ */
        function spawnParticles(originEl, count = 18) {
            const rect = originEl.getBoundingClientRect();
            const cx = rect.left + rect.width  / 2;
            const cy = rect.top  + rect.height / 2;
            const colors = ['#FFD700', '#FF8C00', '#FFF', '#FF4500', '#FFE066'];

            for (let i = 0; i < count; i++) {
                const p   = document.createElement('div');
                const ang = (Math.PI * 2 * i) / count + (Math.random() - 0.5) * 0.5;
                const dist = 80 + Math.random() * 160;
                const dur  = (0.5 + Math.random() * 0.6).toFixed(2);

                p.className = 'energy-particle';
                p.style.cssText = `
                    left: ${cx}px; top: ${cy}px;
                    background: ${colors[i % colors.length]};
                    --tx: ${Math.cos(ang) * dist}px;
                    --ty: ${Math.sin(ang) * dist}px;
                    --dur: ${dur}s;
                    width:  ${3 + Math.random() * 5}px;
                    height: ${3 + Math.random() * 5}px;
                `;
                document.body.appendChild(p);
                setTimeout(() => p.remove(), parseFloat(dur) * 1000 + 100);
            }
        }

        /* ============================================================
           ANIMATION DE TRANSFORMATION
        ============================================================ */
        const wait = ms => new Promise(r => setTimeout(r, ms));

        async function playTransformAnimation(oldUrl, newUrl, newLevel, cardName) {
            const overlay   = document.getElementById('transformOverlay');
            const oldImg    = document.getElementById('transformOldImg');
            const newImg    = document.getElementById('transformNewImg');
            const flash     = document.getElementById('transformFlash');
            const aura      = document.getElementById('transformAura');
            const badge     = document.getElementById('transformBadge');
            const title     = document.getElementById('transformTitle');
            const cardFrame = document.querySelector('.transform-card-frame');

            // Reset
            oldImg.src   = oldUrl || '';
            newImg.src   = newUrl || '';
            badge.textContent = `⚡ NIVEAU ${newLevel}`;
            oldImg.className  = 'transform-card-img';
            newImg.className  = 'transform-card-img img-hide';
            flash.className   = 'transform-flash';
            aura.className    = 'transform-aura';
            badge.className   = 'transform-badge';
            title.className   = 'transform-title';

            // Phase 1 — overlay + son
            overlay.classList.add('active');
            playTransformSound();

            await wait(700);

            // Phase 2 — aura s'intensifie + particules
            aura.classList.add('intense');
            spawnParticles(cardFrame, 20);

            await wait(500);

            // Phase 3 — flash + disparition ancienne image + particules
            flash.classList.add('anim-flash');
            oldImg.classList.add('anim-fade-out');
            spawnParticles(cardFrame, 28);

            await wait(320);

            // Phase 4 — nouvelle image apparaît
            newImg.classList.remove('img-hide');
            newImg.classList.add('anim-fade-in');

            await wait(600);

            // Phase 5 — badge et titre
            badge.classList.add('anim-pop');
            await wait(150);
            title.classList.add('anim-slide');

            await wait(1600);

            // Fin — fermer overlay
            overlay.classList.remove('active');
        }

        /* ============================================================
           FUSION
        ============================================================ */
        async function performFusion(cardId) {
            const btn = document.querySelector('.fusion-btn');
            btn.disabled = true;
            btn.textContent = 'Fusion en cours...';

            try {
                const response = await fetch('/fusion/fuse', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ card_id: cardId })
                });

                const data = await response.json();

                if (data.success) {
                    // Fermer la modal pendant l'animation
                    document.getElementById('fusionModal').classList.remove('active');

                    // Jouer l'animation de transformation
                    await playTransformAnimation(
                        data.old_image_url,
                        data.new_image_url,
                        data.new_level,
                        data.card_name
                    );

                    // Mettre à jour les soldes
                    updateAllBalances(data.new_balance);

                    // Afficher l'écran de succès dans la modal
                    const content = document.getElementById('modalContent');
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4">&#9889;</div>
                            <h4 class="text-2xl font-bold text-yellow-400 mb-2">${data.card_name}</h4>
                            <p class="text-white text-xl font-bold mb-1">
                                Niveau <span class="text-yellow-400">${data.new_level}</span> atteint !
                            </p>
                            <p class="text-gray-400 mt-2">Nouveau solde : ${data.new_balance.toLocaleString()} po</p>
                            <button class="fusion-btn mt-6" onclick="location.reload()">
                                Continuer
                            </button>
                        </div>
                    `;
                    document.getElementById('fusionModal').classList.add('active');

                } else {
                    alert(data.message || 'Erreur lors de la fusion');
                    btn.disabled = false;
                    btn.textContent = '&#9889; Fusionner';
                }
            } catch (error) {
                alert('Erreur de connexion');
                btn.disabled = false;
                btn.textContent = '&#9889; Fusionner';
            }
        }

        function closeFusionModal() {
            document.getElementById('fusionModal').classList.remove('active');
        }

        // Fermer modal avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFusionModal();
            }
        });

        // Fermer modal en cliquant en dehors
        document.getElementById('fusionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFusionModal();
            }
        });
    </script>
</x-app-layout>
