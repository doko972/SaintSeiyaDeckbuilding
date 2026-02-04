<x-app-layout>
    <style>
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
    </style>

    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="relative z-10 py-8 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('tournaments.show', $tournament) }}" class="text-gray-400 hover:text-white transition">
                &#8592;
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $tournament->name }}</h1>
                <p class="text-yellow-400">{{ $tournament->getRoundName($match->round) }} - {{ $match->bracket_code }}</p>
            </div>
        </div>

        <!-- Match Card -->
        <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-8">
            <h2 class="text-xl font-bold text-white text-center mb-8">Salle d'attente</h2>

            <!-- Players -->
            <div class="flex items-center justify-center gap-8 mb-8">
                <!-- Player 1 -->
                <div class="text-center">
                    <div id="player1Avatar" class="w-24 h-24 mx-auto bg-blue-500/20 rounded-full flex items-center justify-center text-4xl mb-3 border-4 border-transparent transition-all duration-300">
                        {{ substr($match->participant1?->user->name ?? '?', 0, 1) }}
                    </div>
                    <div class="text-white font-bold text-lg">{{ $match->participant1?->user->name ?? 'TBD' }}</div>
                    <div class="text-gray-400 text-sm">{{ $match->participant1?->deck->name ?? '' }}</div>
                    <div id="player1Status" class="mt-2 text-sm text-gray-500">En attente...</div>
                </div>

                <div class="text-5xl text-gray-500 font-bold">VS</div>

                <!-- Player 2 -->
                <div class="text-center">
                    <div id="player2Avatar" class="w-24 h-24 mx-auto bg-red-500/20 rounded-full flex items-center justify-center text-4xl mb-3 border-4 border-transparent transition-all duration-300">
                        {{ substr($match->participant2?->user->name ?? '?', 0, 1) }}
                    </div>
                    <div class="text-white font-bold text-lg">{{ $match->participant2?->user->name ?? 'TBD' }}</div>
                    <div class="text-gray-400 text-sm">{{ $match->participant2?->deck->name ?? '' }}</div>
                    <div id="player2Status" class="mt-2 text-sm text-gray-500">En attente...</div>
                </div>
            </div>

            <!-- Ready Button -->
            <div class="text-center">
                <button id="readyBtn"
                    onclick="setReady()"
                    class="px-8 py-4 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg font-bold text-white text-lg hover:from-green-600 hover:to-emerald-600 transition transform hover:scale-105">
                    &#9745; Je suis pret!
                </button>

                <div id="waitingMessage" class="hidden mt-4 text-yellow-400">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>En attente de l'adversaire...</span>
                    </div>
                </div>

                <div id="startingMessage" class="hidden mt-4 text-green-400">
                    <div class="flex items-center justify-center gap-2">
                        <span class="text-2xl">&#9876;</span>
                        <span>Le combat va commencer!</span>
                    </div>
                </div>
            </div>

            <!-- Countdown -->
            <div id="countdown" class="hidden mt-8 text-center">
                <div class="text-6xl font-bold text-yellow-400 animate-pulse" id="countdownNumber">3</div>
                <div class="text-gray-400 mt-2">Le combat commence dans...</div>
            </div>
        </div>

        <!-- Rules reminder -->
        <div class="mt-6 bg-gray-800/30 rounded-xl p-4 text-center">
            <div class="text-gray-400 text-sm">
                <p>&#9888; Ne quittez pas cette page une fois pret.</p>
                <p class="mt-1">Le combat commencera automatiquement quand les deux joueurs seront prets.</p>
            </div>
        </div>
    </div>

    <script>
        const matchId = {{ $match->id }};
        const currentUserId = {{ auth()->id() }};
        const participant1UserId = {{ $match->participant1?->user_id ?? 'null' }};
        const participant2UserId = {{ $match->participant2?->user_id ?? 'null' }};

        let isReady = false;
        let checkInterval = null;

        async function setReady() {
            if (isReady) return;

            const btn = document.getElementById('readyBtn');
            btn.disabled = true;
            btn.textContent = 'Chargement...';

            try {
                const response = await fetch(`/api/v1/tournaments/match/${matchId}/ready`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    isReady = true;
                    btn.classList.add('hidden');
                    document.getElementById('waitingMessage').classList.remove('hidden');

                    // Update my status
                    updatePlayerStatus(currentUserId, true);

                    // If both ready, start!
                    if (data.both_ready) {
                        startBattle();
                    }
                } else {
                    alert(data.error || 'Erreur');
                    btn.disabled = false;
                    btn.textContent = 'Je suis pret!';
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
                btn.disabled = false;
                btn.textContent = 'Je suis pret!';
            }
        }

        function updatePlayerStatus(userId, ready) {
            const isPlayer1 = userId === participant1UserId;
            const statusEl = document.getElementById(isPlayer1 ? 'player1Status' : 'player2Status');
            const avatarEl = document.getElementById(isPlayer1 ? 'player1Avatar' : 'player2Avatar');

            if (ready) {
                statusEl.textContent = 'Pret!';
                statusEl.className = 'mt-2 text-sm text-green-400 font-bold';
                avatarEl.classList.add('border-green-500');
            } else {
                statusEl.textContent = 'En attente...';
                statusEl.className = 'mt-2 text-sm text-gray-500';
                avatarEl.classList.remove('border-green-500');
            }
        }

        async function checkReadyStatus() {
            try {
                const response = await fetch(`/api/v1/tournaments/match/${matchId}/check-ready`);
                const data = await response.json();

                if (data.status === 'battle_started') {
                    // Battle already started, redirect
                    window.location.href = `/pvp/battle/${data.battle_id}`;
                    return;
                }

                updatePlayerStatus(participant1UserId, data.player1_ready);
                updatePlayerStatus(participant2UserId, data.player2_ready);

                if (data.both_ready && isReady) {
                    startBattle();
                }
            } catch (error) {
                console.error('Erreur check ready:', error);
            }
        }

        async function startBattle() {
            clearInterval(checkInterval);

            document.getElementById('waitingMessage').classList.add('hidden');
            document.getElementById('startingMessage').classList.remove('hidden');
            document.getElementById('countdown').classList.remove('hidden');

            // Countdown
            let count = 3;
            const countdownEl = document.getElementById('countdownNumber');

            const countdownInterval = setInterval(async () => {
                count--;
                if (count > 0) {
                    countdownEl.textContent = count;
                } else {
                    clearInterval(countdownInterval);
                    countdownEl.textContent = 'GO!';

                    // Create battle
                    try {
                        const response = await fetch(`/api/v1/tournaments/match/${matchId}/start`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();

                        if (data.success && data.battle_id) {
                            window.location.href = `/pvp/battle/${data.battle_id}`;
                        } else {
                            alert(data.error || 'Erreur lors du demarrage du combat');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        alert('Erreur de connexion');
                    }
                }
            }, 1000);
        }

        // Start checking ready status
        checkInterval = setInterval(checkReadyStatus, 2000);
        checkReadyStatus(); // Initial check
    </script>
</x-app-layout>
