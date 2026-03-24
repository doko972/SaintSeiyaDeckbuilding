<x-app-layout>

    <style>
        .cosmos-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            background:
                radial-gradient(ellipse at 15% 85%, rgba(120, 0, 255, 0.18) 0%, transparent 50%),
                radial-gradient(ellipse at 85% 15%, rgba(255, 100, 0, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(0, 80, 255, 0.10) 0%, transparent 70%),
                linear-gradient(180deg, #0a0a1a 0%, #1a0a2a 50%, #0a1a2a 100%);
        }
        .stars {
            position: absolute;
            width: 100%; height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent),
                radial-gradient(1px 1px at 310px 55px, rgba(255,255,255,0.7), transparent),
                radial-gradient(2px 2px at 270px 150px, #eee, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50%       { opacity: 1; }
        }

        /* Halo doré derrière le titre */
        .title-halo {
            text-shadow: 0 0 40px rgba(251,191,36,0.4), 0 0 80px rgba(139,92,246,0.3);
        }

        /* Glow sur le podium n°1 */
        .crown-glow {
            filter: drop-shadow(0 0 12px rgba(251,191,36,0.7));
        }

        /* Glassmorphism pour les cartes liste */
        .glass-card {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(139,92,246,0.15);
        }
        .glass-card:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(139,92,246,0.35);
        }

        /* Barre de tabs */
        .tab-bar {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(139,92,246,0.25);
        }
    </style>

    {{-- Fond cosmique --}}
    <div class="cosmos-bg"><div class="stars"></div></div>

    <div class="relative z-10 min-h-screen pb-16">

        {{-- ── En-tête ── --}}
        <div class="text-center pt-10 pb-6 px-4">
            <div class="flex items-center justify-center gap-3 mb-1">
                <span class="text-5xl crown-glow">&#127942;</span>
                <h1 class="text-4xl sm:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 via-amber-400 to-yellow-300 title-halo tracking-tight">
                    Classement
                </h1>
                <span class="text-5xl crown-glow">&#127942;</span>
            </div>
            <p class="text-purple-300/70 text-sm tracking-widest uppercase">Temple de la renommée</p>
        </div>

        <div class="max-w-2xl mx-auto px-4">

            {{-- ── Tabs PvP / PvE ── --}}
            <div class="tab-bar flex rounded-2xl overflow-hidden mb-8">
                <a href="{{ route('leaderboard.index', ['tab' => 'pvp']) }}"
                   class="flex-1 py-3 text-center text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2
                          {{ $tab === 'pvp'
                              ? 'bg-gradient-to-r from-red-600 to-orange-500 text-white shadow-lg shadow-red-500/30'
                              : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    &#9876; Classement PvP
                </a>
                <a href="{{ route('leaderboard.index', ['tab' => 'pve']) }}"
                   class="flex-1 py-3 text-center text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2
                          {{ $tab === 'pve'
                              ? 'bg-gradient-to-r from-blue-600 to-indigo-500 text-white shadow-lg shadow-blue-500/30'
                              : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    &#129302; Classement PvE
                </a>
            </div>

            @php
                $user = auth()->user();
                if ($tab === 'pvp') {
                    $topPlayers  = $pvpTop;
                    $userRankPos = $userPvpRank;
                    $leaderScore = $pvpLeaderScore;
                    $scoreField  = 'pvp_wins';
                    $scoreLabel  = 'victoires PvP';
                    $accentFrom  = '#ef4444';
                    $accentTo    = '#f97316';
                    $accentTw    = 'from-red-500 to-orange-400';
                    $ringColor   = '#ef4444';
                    $glowColor   = 'rgba(239,68,68,0.35)';
                    $userScore   = $user->pvp_wins;
                } else {
                    $topPlayers  = $pveTop;
                    $userRankPos = $userPveRank;
                    $leaderScore = $pveLeaderScore;
                    $scoreField  = 'pve_wins';
                    $scoreLabel  = 'victoires PvE';
                    $accentFrom  = '#3b82f6';
                    $accentTo    = '#6366f1';
                    $accentTw    = 'from-blue-500 to-indigo-400';
                    $ringColor   = '#3b82f6';
                    $glowColor   = 'rgba(59,130,246,0.35)';
                    $userScore   = $user->pve_wins;
                }
                $userPercent = $leaderScore > 0 ? round(($userScore / $leaderScore) * 100) : 0;
                $top3 = $topPlayers->take(3);
                $rest = $topPlayers->skip(3);
            @endphp

            {{-- ── Cercle de score joueur connecté ── --}}
            <div class="flex flex-col items-center mb-10">
                <div class="relative w-40 h-40">
                    {{-- Halo externe --}}
                    <div class="absolute inset-0 rounded-full opacity-20 blur-xl"
                         style="background: radial-gradient(circle, {{ $ringColor }}, transparent 70%);"></div>
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                        {{-- Anneau de fond --}}
                        <circle cx="60" cy="60" r="50" fill="none"
                                stroke="rgba(255,255,255,0.07)" stroke-width="10"/>
                        {{-- Anneau de progression --}}
                        <circle cx="60" cy="60" r="50" fill="none"
                                stroke="{{ $ringColor }}" stroke-width="10"
                                stroke-linecap="round"
                                stroke-dasharray="{{ round(314 * $userPercent / 100) }} 314"/>
                    </svg>
                    {{-- Centre --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl">&#127941;</span>
                        <span class="text-white font-black text-xl leading-none mt-1">{{ $userPercent }}%</span>
                    </div>
                </div>

                <p class="text-gray-400 text-sm mt-3">
                    Votre score&nbsp;:
                    <span class="font-bold" style="color: {{ $ringColor }};">{{ $userScore }}</span>
                    <span class="text-gray-500">{{ $scoreLabel }}</span>
                </p>

                @if($userRankPos !== false)
                    <span class="mt-1 px-3 py-0.5 rounded-full text-xs font-bold text-yellow-400 border border-yellow-400/30 bg-yellow-400/10">
                        &#11088; Vous êtes #{{ $userRankPos + 1 }} au classement
                    </span>
                @elseif($userScore === 0)
                    <span class="mt-1 text-gray-600 text-xs">Jouez pour apparaître au classement !</span>
                @else
                    <span class="mt-1 text-gray-600 text-xs">Vous êtes hors top 20</span>
                @endif
            </div>

            {{-- ── Vide ── --}}
            @if($topPlayers->isEmpty())
                <div class="text-center py-16">
                    <div class="text-7xl mb-4">&#127942;</div>
                    <p class="text-gray-300 text-lg font-semibold">Aucune victoire enregistrée.</p>
                    <p class="text-gray-600 text-sm mt-2">Soyez le premier à figurer au classement !</p>
                    @if($tab === 'pvp')
                        <a href="{{ route('pvp.lobby') }}"
                           class="inline-flex items-center gap-2 mt-6 px-8 py-3 bg-gradient-to-r from-red-600 to-orange-500 text-white font-bold rounded-2xl hover:scale-105 transition-transform shadow-lg shadow-red-500/30">
                            &#9876; Aller dans l'Arena PvP
                        </a>
                    @else
                        <a href="{{ route('game.index') }}"
                           class="inline-flex items-center gap-2 mt-6 px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-bold rounded-2xl hover:scale-105 transition-transform shadow-lg shadow-blue-500/30">
                            &#129302; Lancer un Combat PvE
                        </a>
                    @endif
                </div>

            @else

            {{-- ── Podium Top 3 ── --}}
            <div class="flex items-end justify-center gap-3 mb-10 px-2">
                @foreach([1, 0, 2] as $podiumOrder)
                    @if($top3->has($podiumOrder))
                        @php
                            $p        = $top3->get($podiumOrder);
                            $rank     = $podiumOrder + 1;
                            $isFirst  = $rank === 1;
                            $heights  = [1 => 'h-32', 2 => 'h-24', 3 => 'h-16'];
                            $podiumGrad = [
                                1 => 'from-yellow-400 to-amber-500',
                                2 => 'from-gray-300 to-gray-400',
                                3 => 'from-amber-600 to-amber-800',
                            ];
                            $icons      = [1 => '&#129351;', 2 => '&#129352;', 3 => '&#129353;'];
                            $avatarSize = $isFirst ? 'w-20 h-20 text-3xl' : 'w-14 h-14 text-xl';
                            $isMe       = $p->id === $user->id;
                            $order      = $rank === 1 ? 'order-2' : ($rank === 2 ? 'order-1' : 'order-3');
                        @endphp
                        <div class="flex flex-col items-center {{ $order }}">

                            {{-- Couronne n°1 --}}
                            @if($isFirst)
                                <div class="text-3xl crown-glow mb-1">&#128081;</div>
                            @endif

                            {{-- Avatar --}}
                            <div class="relative mb-2">
                                <div class="{{ $avatarSize }} rounded-full flex items-center justify-center text-white font-black shadow-xl
                                            {{ $isMe ? 'ring-2 ring-yellow-400 ring-offset-2 ring-offset-transparent' : '' }}"
                                     style="background: linear-gradient(135deg, {{ $accentFrom }}, {{ $accentTo }});
                                            {{ $isFirst ? 'box-shadow: 0 0 20px '.$glowColor.';' : '' }}">
                                    {{ mb_strtoupper(mb_substr($p->name, 0, 1)) }}
                                </div>
                                {{-- Badge numéro --}}
                                <div class="absolute -bottom-2 -right-2 w-7 h-7 rounded-full flex items-center justify-center text-xs font-black shadow-md border-2 border-gray-900
                                            bg-gradient-to-br {{ $podiumGrad[$rank] }} text-gray-900">
                                    {{ $rank }}
                                </div>
                            </div>

                            {{-- Nom --}}
                            <p class="text-xs font-bold text-center max-w-[72px] truncate mt-1
                                       {{ $isFirst ? 'text-yellow-300' : ($isMe ? 'text-yellow-400' : 'text-gray-200') }}">
                                {{ $p->name }}
                            </p>

                            {{-- Score --}}
                            <div class="mt-1 px-3 py-0.5 rounded-full text-xs font-black text-white shadow-md"
                                 style="background: linear-gradient(90deg, {{ $accentFrom }}, {{ $accentTo }});">
                                {{ $p->$scoreField }}
                            </div>

                            {{-- Base du podium --}}
                            <div class="{{ $heights[$rank] }} w-20 mt-3 rounded-t-xl border border-purple-500/20 flex items-end justify-center pb-2
                                        bg-gradient-to-t from-purple-950/90 via-purple-900/60 to-purple-800/30"
                                 style="{{ $isFirst ? 'box-shadow: 0 -4px 20px '.$glowColor.';' : '' }}">
                                <span class="text-xl">{!! $icons[$rank] !!}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- ── Liste 4→20 ── --}}
            @if($rest->isNotEmpty())
                <div class="space-y-2">
                    @foreach($rest as $index => $p)
                        @php
                            $position = $index + 4;
                            $isMe     = $p->id === $user->id;
                        @endphp
                        <div class="glass-card flex items-center gap-4 px-4 py-3 rounded-2xl transition-all
                                    {{ $isMe ? 'border-yellow-500/40 bg-yellow-900/10' : '' }}">
                            {{-- Numéro --}}
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-gray-400 shrink-0"
                                 style="background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3);">
                                {{ $position }}
                            </div>
                            {{-- Avatar initial --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold shrink-0 text-sm"
                                 style="background: linear-gradient(135deg, {{ $accentFrom }}, {{ $accentTo }});">
                                {{ mb_strtoupper(mb_substr($p->name, 0, 1)) }}
                            </div>
                            {{-- Nom + rang --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate {{ $isMe ? 'text-yellow-400' : 'text-white' }}">
                                    {{ $p->name }}{{ $isMe ? ' (vous)' : '' }}
                                </p>
                                <p class="text-xs text-purple-400/70">
                                    {{ \App\Models\User::RANKS[$p->current_rank ?? 'bronze']['icon'] }}
                                    {{ \App\Models\User::RANKS[$p->current_rank ?? 'bronze']['name'] }}
                                </p>
                            </div>
                            {{-- Score --}}
                            <div class="text-right shrink-0">
                                <span class="font-black text-base" style="color: {{ $ringColor }};">{{ $p->$scoreField }}</span>
                                <p class="text-xs text-gray-600">victoires</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @endif {{-- fin topPlayers non vide --}}

        </div>
    </div>

</x-app-layout>
