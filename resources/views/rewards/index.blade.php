<x-app-layout>
    <style>
        /* ========================================
           FOND COSMOS DORE
        ======================================== */
        .cosmos-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(255, 215, 0, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 165, 0, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(255, 200, 100, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #1a1a0a 0%, #2a1a0a 50%, #1a0a1a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #FFD700, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,215,0,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #FFA500, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,200,100,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #FFD700, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* ========================================
           SERIE DE CONNEXION
        ======================================== */
        .streak-container {
            background: rgba(15, 15, 35, 0.9);
            border: 2px solid rgba(255, 215, 0, 0.3);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
        }

        .streak-days {
            display: flex;
            justify-content: space-between;
            gap: 0.5rem;
            margin: 1.5rem 0;
        }

        .streak-day {
            flex: 1;
            text-align: center;
            padding: 1rem 0.5rem;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            position: relative;
        }

        .streak-day.completed {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.3), rgba(16, 185, 129, 0.3));
            border-color: #22C55E;
        }

        .streak-day.current {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.3), rgba(255, 165, 0, 0.3));
            border-color: #FFD700;
            animation: currentDayPulse 2s ease-in-out infinite;
        }

        .streak-day.future {
            opacity: 0.5;
        }

        @keyframes currentDayPulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 215, 0, 0.3); }
            50% { box-shadow: 0 0 25px rgba(255, 215, 0, 0.6); }
        }

        .streak-day-number {
            font-size: 1.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.25rem;
        }

        .streak-day-reward {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .streak-day-reward.card-reward {
            color: #3B82F6;
            font-weight: 700;
        }

        .streak-day .check-icon {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 24px;
            height: 24px;
            background: #22C55E;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: white;
        }

        .claim-streak-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            border: none;
            border-radius: 12px;
            color: #1a1a2e;
            font-size: 1.1rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .claim-streak-btn:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
        }

        .claim-streak-btn:disabled {
            background: #4a4a4a;
            color: #888;
            cursor: not-allowed;
        }

        /* ========================================
           ROUE DE LA FORTUNE
        ======================================== */
        .wheel-container {
            background: rgba(15, 15, 35, 0.9);
            border: 2px solid rgba(139, 92, 246, 0.3);
            border-radius: 20px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .wheel-wrapper {
            position: relative;
            width: 300px;
            height: 300px;
            margin: 1.5rem auto;
        }

        .wheel {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            position: relative;
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.3);
            overflow: hidden;
            border: 4px solid rgba(255, 215, 0, 0.5);
        }

        .wheel.spinning {
            transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
        }

        .wheel-segment {
            position: absolute;
            width: 50%;
            height: 50%;
            left: 50%;
            top: 0;
            transform-origin: 0% 100%;
        }

        .wheel-segment-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            clip-path: polygon(0% 100%, 100% 0%, 0% 0%);
        }

        .wheel-segment span {
            position: absolute;
            left: 15%;
            top: 50%;
            transform: rotate(-67.5deg);
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
            white-space: nowrap;
        }

        .wheel-center {
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #FFD700, #FF8C00);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .wheel-pointer {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 15px solid transparent;
            border-right: 15px solid transparent;
            border-top: 30px solid #FFD700;
            z-index: 20;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .spin-wheel-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #8B5CF6, #6366F1);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .spin-wheel-btn:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.5);
        }

        .spin-wheel-btn:disabled {
            background: #4a4a4a;
            color: #888;
            cursor: not-allowed;
        }

        .wheel-cooldown {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        /* ========================================
           MODAL RESULTAT
        ======================================== */
        .reward-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .reward-modal.active {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .reward-modal-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(255, 215, 0, 0.4);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .reward-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .reward-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #FFD700;
            margin-bottom: 0.5rem;
        }

        .reward-description {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 1.5rem;
        }

        .reward-close-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #10B981, #059669);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reward-close-btn:hover {
            transform: scale(1.02);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .streak-days {
                flex-wrap: wrap;
            }
            .streak-day {
                flex: 0 0 calc(25% - 0.5rem);
                margin-bottom: 0.5rem;
            }
            .streak-day:nth-child(7) {
                flex: 0 0 100%;
            }
            .wheel-wrapper {
                width: 250px;
                height: 250px;
            }
        }
    </style>

    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="min-h-screen relative z-10 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-white flex items-center justify-center gap-3">
                    <span class="text-4xl">&#127873;</span> Centre de Recompenses
                </h1>
                <p class="text-gray-400 mt-2">Connectez-vous chaque jour pour des bonus exclusifs !</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Serie de connexion -->
                <div class="streak-container">
                    <h2 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                        <span>&#128293;</span> Serie de Connexion
                    </h2>
                    <p class="text-gray-400 text-sm mb-4">
                        Jour actuel : <span class="text-yellow-400 font-bold">{{ $streakInfo['current_day'] }}/7</span>
                    </p>

                    <div class="streak-days">
                        @for($day = 1; $day <= 7; $day++)
                            @php
                                $reward = $streakInfo['rewards'][$day];
                                $isCompleted = $day < $streakInfo['current_day'];
                                $isCurrent = $day == $streakInfo['current_day'];
                                $isFuture = $day > $streakInfo['current_day'];
                            @endphp
                            <div class="streak-day {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }} {{ $isFuture ? 'future' : '' }}">
                                @if($isCompleted)
                                    <div class="check-icon">&#10003;</div>
                                @endif
                                <div class="streak-day-number">J{{ $day }}</div>
                                <div class="streak-day-reward {{ $reward['card'] ? 'card-reward' : '' }}">
                                    {{ $reward['coins'] }} po
                                    @if($reward['card'])
                                        <br>+ Carte !
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>

                    <button
                        id="claimStreakBtn"
                        class="claim-streak-btn"
                        {{ !$streakInfo['can_claim'] ? 'disabled' : '' }}
                        onclick="claimStreakReward()">
                        @if($streakInfo['can_claim'])
                            &#127873; Reclamer la recompense du Jour {{ $streakInfo['current_day'] }}
                        @else
                            &#10003; Recompense reclamee aujourd'hui
                        @endif
                    </button>
                </div>

                <!-- Roue de la fortune -->
                <div class="wheel-container">
                    <h2 class="text-xl font-bold text-white mb-2 flex items-center justify-center gap-2">
                        <span>&#127921;</span> Roue de la Fortune
                    </h2>
                    <p class="text-gray-400 text-sm mb-4">Une chance par semaine !</p>

                    <div class="wheel-wrapper">
                        <div class="wheel-pointer"></div>
                        <div class="wheel" id="wheel" style="background: conic-gradient(
                            from 0deg,
                            #6B7280 0deg 45deg,
                            #3B82F6 45deg 90deg,
                            #8B5CF6 90deg 135deg,
                            #FFD700 135deg 180deg,
                            #9CA3AF 180deg 225deg,
                            #60A5FA 225deg 270deg,
                            #A855F7 270deg 315deg,
                            #CD7F32 315deg 360deg
                        );">
                            @php
                                $segments = [
                                    ['label' => '100 po', 'angle' => 22.5],
                                    ['label' => '200 po', 'angle' => 67.5],
                                    ['label' => '500 po', 'angle' => 112.5],
                                    ['label' => '1000 po', 'angle' => 157.5],
                                    ['label' => 'Commune', 'angle' => 202.5],
                                    ['label' => 'Rare', 'angle' => 247.5],
                                    ['label' => 'Epique', 'angle' => 292.5],
                                    ['label' => 'Booster', 'angle' => 337.5],
                                ];
                            @endphp
                            @foreach($segments as $segment)
                                <div style="position: absolute; top: 50%; left: 50%; transform: rotate({{ $segment['angle'] }}deg) translateY(-115px); transform-origin: center center;">
                                    <span style="display: block; transform: rotate(-{{ $segment['angle'] }}deg); font-size: 0.65rem; font-weight: 700; color: white; text-shadow: 1px 1px 3px rgba(0,0,0,0.8), 0 0 5px rgba(0,0,0,0.5); white-space: nowrap;">
                                        {{ $segment['label'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <div class="wheel-center">&#127922;</div>
                    </div>

                    <button
                        id="spinWheelBtn"
                        class="spin-wheel-btn"
                        {{ !$canSpinWheel ? 'disabled' : '' }}
                        onclick="spinWheel()">
                        @if($canSpinWheel)
                            &#127921; Tourner la roue !
                        @else
                            &#128274; Disponible dans {{ $timeUntilSpin['days'] }}j {{ $timeUntilSpin['hours'] }}h
                        @endif
                    </button>

                    @if(!$canSpinWheel)
                        <p class="wheel-cooldown">
                            Prochaine rotation disponible dans {{ $timeUntilSpin['days'] }} jour(s) et {{ $timeUntilSpin['hours'] }} heure(s)
                        </p>
                    @endif
                </div>
            </div>

            <!-- Solde actuel -->
            <div class="mt-6 text-center">
                <div class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 border border-yellow-500/30 rounded-full">
                    <span class="text-2xl">&#129689;</span>
                    <span class="text-xl font-bold text-yellow-400" id="currentBalance">{{ number_format($user->coins) }}</span>
                    <span class="text-gray-400">pieces</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de recompense -->
    <div id="rewardModal" class="reward-modal">
        <div class="reward-modal-content">
            <div class="reward-icon" id="rewardIcon">&#127873;</div>
            <h3 class="reward-title" id="rewardTitle">Felicitations !</h3>
            <p class="reward-description" id="rewardDescription"></p>
            <button class="reward-close-btn" onclick="closeRewardModal()">
                Super !
            </button>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ==========================================
        // MISE A JOUR DU SOLDE (page + navigation)
        // ==========================================
        function updateAllBalances(newBalance) {
            const formattedBalance = newBalance.toLocaleString();

            // Mettre a jour le solde sur la page
            document.getElementById('currentBalance').textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (desktop)
            const navDesktop = document.getElementById('nav-coins-desktop');
            if (navDesktop) navDesktop.textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (mobile)
            const navMobile = document.getElementById('nav-coins-mobile');
            if (navMobile) navMobile.textContent = formattedBalance;
        }

        // ==========================================
        // SERIE DE CONNEXION
        // ==========================================
        async function claimStreakReward() {
            const btn = document.getElementById('claimStreakBtn');
            btn.disabled = true;
            btn.textContent = 'Chargement...';

            try {
                const response = await fetch('/rewards/claim-streak', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Mettre a jour tous les soldes
                    updateAllBalances(data.new_balance);

                    // Afficher la modal
                    let icon = '&#129689;';
                    let title = 'Jour ' + data.day + ' !';
                    let description = '+' + data.coins_earned + ' pieces';

                    if (data.card) {
                        icon = '&#127183;';
                        description += ' + Carte ' + data.card.name + ' !';
                    }

                    showRewardModal(icon, title, description);

                    btn.textContent = '&#10003; Recompense reclamee aujourd\'hui';
                } else {
                    alert(data.message || 'Erreur');
                    btn.disabled = false;
                    btn.innerHTML = '&#127873; Reclamer la recompense';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
                btn.disabled = false;
            }
        }

        // ==========================================
        // ROUE DE LA FORTUNE
        // ==========================================
        let isSpinning = false;

        async function spinWheel() {
            if (isSpinning) return;

            const btn = document.getElementById('spinWheelBtn');
            const wheel = document.getElementById('wheel');

            btn.disabled = true;
            btn.textContent = 'La roue tourne...';
            isSpinning = true;

            try {
                const response = await fetch('/rewards/spin-wheel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Calculer l'angle de rotation
                    const segmentCount = 8;
                    const segmentAngle = 360 / segmentCount;
                    const winningIndex = data.segment_index;

                    // Rotation: plusieurs tours + angle pour arriver sur le segment gagnant
                    const extraRotations = 5 * 360;
                    const targetAngle = extraRotations + (360 - (winningIndex * segmentAngle + segmentAngle / 2));

                    wheel.style.transform = `rotate(${targetAngle}deg)`;

                    // Attendre la fin de l'animation
                    setTimeout(() => {
                        // Mettre a jour tous les soldes
                        updateAllBalances(data.new_balance);

                        // Afficher le resultat
                        let icon = '&#127873;';
                        let title = 'Bravo !';

                        if (data.reward_type === 'coins') {
                            icon = '&#129689;';
                        } else if (data.reward_type === 'card') {
                            icon = '&#127183;';
                        } else if (data.reward_type === 'booster') {
                            icon = '&#127183;';
                        }

                        showRewardModal(icon, title, data.message);

                        btn.innerHTML = '&#128274; Disponible dans 7 jours';
                        isSpinning = false;
                    }, 4500);
                } else {
                    alert(data.message || 'Erreur');
                    btn.disabled = false;
                    btn.innerHTML = '&#127921; Tourner la roue !';
                    isSpinning = false;
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
                btn.disabled = false;
                btn.innerHTML = '&#127921; Tourner la roue !';
                isSpinning = false;
            }
        }

        // ==========================================
        // MODAL
        // ==========================================
        function showRewardModal(icon, title, description) {
            document.getElementById('rewardIcon').innerHTML = icon;
            document.getElementById('rewardTitle').textContent = title;
            document.getElementById('rewardDescription').textContent = description;
            document.getElementById('rewardModal').classList.add('active');
        }

        function closeRewardModal() {
            document.getElementById('rewardModal').classList.remove('active');
        }

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeRewardModal();
        });
    </script>
</x-app-layout>
