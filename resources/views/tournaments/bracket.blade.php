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

    <div class="relative z-10 py-8 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('tournaments.show', $tournament) }}" class="text-gray-400 hover:text-white transition">
                    &#8592;
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $tournament->name }}</h1>
                    <p class="text-yellow-400">Bracket - {{ $tournament->getStatusLabel() }}</p>
                </div>
            </div>
            @if($tournament->status === 'in_progress')
                <div class="flex items-center gap-2 text-green-400">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    En direct
                </div>
            @endif
        </div>

        <!-- Bracket -->
        <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6 overflow-x-auto">
            <div class="bracket-container flex gap-4 min-w-max">
                @php
                    $matchesByRound = $tournament->matches->groupBy('round')->sortKeys();
                    $totalRounds = $matchesByRound->count();
                @endphp

                @foreach($matchesByRound as $round => $matches)
                    @php
                        $matchCount = $matches->count();
                        $isFinal = $round === $totalRounds;
                        $isSemiFinal = $round === $totalRounds - 1;
                    @endphp

                    <div class="bracket-round flex flex-col" style="min-width: 240px;">
                        <h3 class="text-center text-yellow-400 font-bold mb-6 text-lg">
                            {{ $tournament->getRoundName($round) }}
                        </h3>

                        <div class="flex flex-col justify-around flex-1 gap-4" style="min-height: {{ max(200, $matchCount * 120) }}px;">
                            @foreach($matches->sortBy('match_number') as $index => $match)
                                @php
                                    $isMyMatch = $myParticipation && $match->involvesUser(auth()->user());
                                    $statusClass = match($match->status) {
                                        'finished' => 'border-green-500/50',
                                        'in_progress' => 'border-yellow-500 animate-pulse',
                                        'ready' => 'border-blue-500/50',
                                        default => 'border-white/10'
                                    };
                                @endphp

                                <div class="bracket-match bg-white/5 rounded-lg border-2 {{ $statusClass }} {{ $isMyMatch ? 'ring-2 ring-yellow-500/50' : '' }} overflow-hidden">
                                    <!-- Player 1 -->
                                    <div class="flex items-center gap-2 px-3 py-2 border-b border-white/10 {{ $match->winner_participant_id === $match->participant1_id ? 'bg-green-500/20' : '' }}">
                                        @if($match->participant1)
                                            <span class="w-6 h-6 flex items-center justify-center bg-blue-500/20 rounded-full text-xs text-blue-400">
                                                {{ $match->participant1->seed_position ?? '?' }}
                                            </span>
                                            <span class="flex-1 text-white text-sm font-medium truncate">
                                                {{ $match->participant1->user->name }}
                                            </span>
                                            @if($match->winner_participant_id === $match->participant1_id)
                                                <span class="text-green-400">&#10003;</span>
                                            @endif
                                        @else
                                            <span class="flex-1 text-gray-500 text-sm italic">En attente...</span>
                                        @endif
                                    </div>

                                    <!-- Player 2 -->
                                    <div class="flex items-center gap-2 px-3 py-2 {{ $match->winner_participant_id === $match->participant2_id ? 'bg-green-500/20' : '' }}">
                                        @if($match->participant2)
                                            <span class="w-6 h-6 flex items-center justify-center bg-red-500/20 rounded-full text-xs text-red-400">
                                                {{ $match->participant2->seed_position ?? '?' }}
                                            </span>
                                            <span class="flex-1 text-white text-sm font-medium truncate">
                                                {{ $match->participant2->user->name }}
                                            </span>
                                            @if($match->winner_participant_id === $match->participant2_id)
                                                <span class="text-green-400">&#10003;</span>
                                            @endif
                                        @else
                                            <span class="flex-1 text-gray-500 text-sm italic">En attente...</span>
                                        @endif
                                    </div>

                                    <!-- Status bar -->
                                    <div class="px-3 py-1 bg-black/20 text-center">
                                        @if($match->status === 'finished')
                                            <span class="text-green-400 text-xs">Termine</span>
                                        @elseif($match->status === 'in_progress')
                                            <span class="text-yellow-400 text-xs">&#9876; En combat</span>
                                        @elseif($match->status === 'ready')
                                            @if($isMyMatch)
                                                <a href="{{ route('tournaments.match', [$tournament, $match]) }}"
                                                    class="text-blue-400 hover:text-blue-300 text-xs font-bold">
                                                    Rejoindre &#10132;
                                                </a>
                                            @else
                                                <span class="text-blue-400 text-xs">Pret</span>
                                            @endif
                                        @elseif($match->status === 'bye')
                                            <span class="text-purple-400 text-xs">Bye</span>
                                        @else
                                            <span class="text-gray-500 text-xs">{{ $match->bracket_code }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if(!$loop->last)
                        <!-- Connectors -->
                        <div class="bracket-connectors flex flex-col justify-around" style="width: 40px; min-height: {{ max(200, $matchCount * 120) }}px;">
                            @for($i = 0; $i < ceil($matchCount / 2); $i++)
                                <div class="connector-group relative flex-1 flex items-center">
                                    <svg class="w-full h-full" viewBox="0 0 40 100" preserveAspectRatio="none">
                                        <path d="M 0 25 L 20 25 L 20 50 L 40 50" stroke="rgba(255,255,255,0.2)" stroke-width="2" fill="none"/>
                                        <path d="M 0 75 L 20 75 L 20 50 L 40 50" stroke="rgba(255,255,255,0.2)" stroke-width="2" fill="none"/>
                                    </svg>
                                </div>
                            @endfor
                        </div>
                    @endif
                @endforeach

                <!-- Winner display -->
                @if($tournament->status === 'finished')
                    @php $winner = $tournament->participants->where('status', 'winner')->first(); @endphp
                    @if($winner)
                        <div class="flex flex-col justify-center items-center ml-4" style="min-width: 160px;">
                            <div class="text-center">
                                <div class="text-6xl mb-3">&#127942;</div>
                                <div class="text-yellow-400 font-bold text-xl mb-1">Champion</div>
                                <div class="text-white text-lg">{{ $winner->user->name }}</div>
                                @php $rewards = $tournament->getRewardsConfig(); @endphp
                                <div class="text-yellow-400 text-sm mt-2">
                                    {{ number_format($rewards['winner_coins']) }} pieces
                                </div>
                                @if(!empty($rewards['winner_title']))
                                    <div class="text-purple-400 text-sm">
                                        "{{ $rewards['winner_title'] }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Legende -->
        <div class="mt-6 flex flex-wrap gap-4 justify-center text-sm">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-white/10 rounded"></div>
                <span class="text-gray-400">En attente</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-blue-500/50 rounded"></div>
                <span class="text-gray-400">Pret</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-yellow-500 rounded"></div>
                <span class="text-gray-400">En combat</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 border-2 border-green-500/50 rounded"></div>
                <span class="text-gray-400">Termine</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 ring-2 ring-yellow-500/50 rounded"></div>
                <span class="text-gray-400">Votre match</span>
            </div>
        </div>

        <!-- Classement final -->
        @if($tournament->status === 'finished')
            <div class="mt-8 bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Classement final</h2>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @foreach($tournament->participants->sortBy('final_rank')->take(4) as $participant)
                        @php
                            $rankColors = [
                                1 => 'from-yellow-500/30 to-orange-500/30 border-yellow-500/50',
                                2 => 'from-gray-400/30 to-gray-500/30 border-gray-400/50',
                                3 => 'from-orange-600/30 to-orange-700/30 border-orange-600/50',
                                4 => 'from-orange-600/30 to-orange-700/30 border-orange-600/50',
                            ];
                            $medals = [1 => '&#129351;', 2 => '&#129352;', 3 => '&#129353;', 4 => '&#129353;'];
                        @endphp
                        <div class="bg-gradient-to-br {{ $rankColors[$participant->final_rank] ?? 'from-gray-500/20 to-gray-600/20 border-gray-500/30' }} rounded-xl border p-4 text-center">
                            <div class="text-3xl mb-2">{!! $medals[$participant->final_rank] ?? '' !!}</div>
                            <div class="text-white font-bold">{{ $participant->user->name }}</div>
                            <div class="text-gray-400 text-sm">{{ $participant->wins }} V - {{ $participant->losses }} D</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        .bracket-container {
            padding: 20px 0;
        }

        .bracket-round {
            transition: all 0.3s ease;
        }

        .bracket-match {
            transition: all 0.2s ease;
        }

        .bracket-match:hover {
            transform: scale(1.02);
        }

        .connector-group {
            min-height: 80px;
        }

        @media (max-width: 768px) {
            .bracket-container {
                padding: 10px 0;
            }

            .bracket-round {
                min-width: 180px !important;
            }
        }
    </style>

    @if($tournament->status === 'in_progress')
        <script>
            // Auto-refresh every 10 seconds during active tournament
            setTimeout(() => {
                window.location.reload();
            }, 10000);
        </script>
    @endif
</x-app-layout>
