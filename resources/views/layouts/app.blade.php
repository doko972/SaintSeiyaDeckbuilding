<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Saint Seiya Deckbuilding') }}</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#8B5CF6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SS Deckbuilding">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="SS Deckbuilding">
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/icons/icon-192.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-transparent">
                <div>
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Flash Messages -->
        {{-- @if(session('success'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        @endif --}}

        @if(session('error'))
            <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- ========================================
         BOUTON FLOTTANT PLEIN ECRAN (MOBILE)
    ======================================== -->
    <button id="globalFullscreenFab" class="global-fullscreen-fab" onclick="enableGlobalFullscreen()" title="Activer le plein ecran">
        &#9974;
    </button>

    <!-- ========================================
         MODAL INVITATION RECUE (GLOBAL)
    ======================================== -->
    @auth
    <div id="globalReceivedInviteModal" class="global-invitation-modal">
        <div class="global-invitation-content incoming">
            <h2 class="text-xl font-black text-yellow-400 mb-2">&#9876; Defi recu !</h2>
            <p class="text-gray-400 text-sm mb-2"><span id="globalChallengerName" class="text-white font-bold"></span> vous defie !</p>
            <p class="text-gray-500 text-xs mb-4">Rang: <span id="globalChallengerRank"></span> | Victoires: <span id="globalChallengerWins"></span></p>

            <div class="global-invitation-timer" id="globalReceivedTimer">60</div>

            <div id="globalReceivedDeckSelector" class="space-y-2 max-h-32 overflow-y-auto mb-4">
                @foreach(auth()->user()->decks as $deck)
                <label class="flex items-center gap-3 p-2 bg-white/5 rounded-lg cursor-pointer hover:bg-white/10 transition text-sm">
                    <input type="radio" name="globalAcceptDeck" value="{{ $deck->id }}" class="w-4 h-4">
                    <span class="text-white">{{ $deck->name }}</span>
                </label>
                @endforeach
            </div>

            <div class="global-invitation-buttons">
                <button class="global-btn-decline" onclick="globalDeclineInvitation()">Refuser</button>
                <button class="global-btn-accept" onclick="globalAcceptInvitation()">Accepter !</button>
            </div>
        </div>
    </div>
    @endauth

    <style>
        /* ========================================
           GLOBAL INVITATION MODAL
        ======================================== */
        .global-invitation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 10000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .global-invitation-modal.active {
            display: flex;
        }

        .global-invitation-content {
            background: linear-gradient(180deg, #1a1a3e 0%, #0f0f2a 100%);
            border: 2px solid rgba(16, 185, 129, 0.4);
            border-radius: 24px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        .global-invitation-content.incoming {
            border-color: rgba(245, 158, 11, 0.5);
            animation: globalIncomingPulse 2s ease-in-out infinite;
        }

        @keyframes globalIncomingPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(245, 158, 11, 0.2); }
            50% { box-shadow: 0 0 40px rgba(245, 158, 11, 0.4); }
        }

        .global-invitation-timer {
            width: 60px;
            height: 60px;
            margin: 1rem auto;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.2);
            border-top-color: #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .global-invitation-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .global-invitation-buttons button {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .global-btn-accept {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }

        .global-btn-decline {
            background: rgba(239, 68, 68, 0.2);
            color: #EF4444;
            border: 1px solid rgba(239, 68, 68, 0.3) !important;
        }

        .global-fullscreen-fab {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #8B5CF6, #6366F1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            transition: all 0.3s ease;
            align-items: center;
            justify-content: center;
        }

        .global-fullscreen-fab:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(139, 92, 246, 0.6);
        }

        .global-fullscreen-fab.visible {
            display: flex;
        }

        :fullscreen .global-fullscreen-fab,
        :-webkit-full-screen .global-fullscreen-fab,
        :-moz-full-screen .global-fullscreen-fab {
            display: none !important;
        }
    </style>

    <script>
        (function() {
            // ==========================================
            // PLEIN ECRAN & PWA
            // ==========================================
            function isPWA() {
                return window.matchMedia('(display-mode: standalone)').matches ||
                       window.matchMedia('(display-mode: fullscreen)').matches ||
                       window.navigator.standalone === true;
            }

            function isMobileDevice() {
                return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            }

            function isInFullscreen() {
                return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement;
            }

            function updateGlobalFullscreenFab() {
                const fab = document.getElementById('globalFullscreenFab');
                if (!fab) return;

                if (isMobileDevice() && !isInFullscreen() && !isPWA()) {
                    fab.classList.add('visible');
                } else {
                    fab.classList.remove('visible');
                }
            }

            window.enableGlobalFullscreen = function() {
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
                updateGlobalFullscreenFab();
            };

            function registerServiceWorker() {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/sw.js')
                        .then((registration) => {
                            console.log('[PWA] Service Worker enregistre:', registration.scope);
                        })
                        .catch((error) => {
                            console.log('[PWA] Erreur enregistrement SW:', error);
                        });
                }
            }

            // ==========================================
            // INVITATIONS PVP (GLOBAL)
            // ==========================================
            let globalInvitationId = null;
            let globalReceivedInterval = null;
            let globalPollInterval = null;

            async function globalCheckReceivedInvitations() {
                // Ne pas verifier si on est deja sur la page dashboard (elle a son propre systeme)
                if (window.location.pathname === '/dashboard') return;

                // Ne pas verifier si le modal du dashboard est present et actif
                const dashboardModal = document.getElementById('receivedInviteModal');
                if (dashboardModal && dashboardModal.classList.contains('active')) return;

                try {
                    const response = await fetch('/api/v1/invitations/check-received', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await response.json();

                    const modal = document.getElementById('globalReceivedInviteModal');
                    if (!modal) return;

                    if (data.count > 0 && !modal.classList.contains('active')) {
                        const inv = data.invitations[0];
                        globalInvitationId = inv.id;
                        document.getElementById('globalChallengerName').textContent = inv.from_user.name;
                        document.getElementById('globalChallengerRank').textContent = inv.from_user.rank;
                        document.getElementById('globalChallengerWins').textContent = inv.from_user.wins;
                        modal.classList.add('active');
                        globalStartReceivedTimer(inv.expires_in);
                    }
                } catch (e) {}
            }

            function globalStartReceivedTimer(seconds) {
                const timerEl = document.getElementById('globalReceivedTimer');
                if (!timerEl) return;
                timerEl.textContent = seconds;

                if (globalReceivedInterval) clearInterval(globalReceivedInterval);

                globalReceivedInterval = setInterval(() => {
                    seconds--;
                    timerEl.textContent = Math.max(0, seconds);

                    if (seconds <= 0) {
                        clearInterval(globalReceivedInterval);
                        globalCloseReceivedModal();
                    }
                }, 1000);
            }

            window.globalAcceptInvitation = async function() {
                const deckRadio = document.querySelector('input[name="globalAcceptDeck"]:checked');
                if (!deckRadio) {
                    alert('Veuillez selectionner un deck');
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                try {
                    const response = await fetch('/api/v1/invitations/accept/' + globalInvitationId, {
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
                        clearInterval(globalReceivedInterval);
                        window.location.href = '/pvp/battle/' + data.battle_id;
                    } else {
                        alert(data.message);
                        globalCloseReceivedModal();
                    }
                } catch (error) {
                    console.error('Erreur acceptation:', error);
                    alert('Erreur de connexion');
                }
            };

            window.globalDeclineInvitation = async function() {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                try {
                    await fetch('/api/v1/invitations/decline/' + globalInvitationId, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });
                } catch (e) {}
                globalCloseReceivedModal();
            };

            function globalCloseReceivedModal() {
                if (globalReceivedInterval) clearInterval(globalReceivedInterval);
                const modal = document.getElementById('globalReceivedInviteModal');
                if (modal) modal.classList.remove('active');
                globalInvitationId = null;
            }

            // ==========================================
            // INITIALISATION
            // ==========================================
            document.addEventListener('fullscreenchange', updateGlobalFullscreenFab);
            document.addEventListener('webkitfullscreenchange', updateGlobalFullscreenFab);
            document.addEventListener('mozfullscreenchange', updateGlobalFullscreenFab);

            document.addEventListener('DOMContentLoaded', function() {
                updateGlobalFullscreenFab();
                registerServiceWorker();

                // Demarrer le polling des invitations si authentifie
                const inviteModal = document.getElementById('globalReceivedInviteModal');
                if (inviteModal) {
                    globalCheckReceivedInvitations();
                    globalPollInterval = setInterval(globalCheckReceivedInvitations, 3000);
                }
            });
        })();
    </script>
</body>
</html>