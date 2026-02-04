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

    <div class="relative z-10 py-8 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.tournaments.index') }}" class="text-gray-400 hover:text-white transition">
                    &#8592;
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $tournament->name }}</h1>
                    <p class="text-gray-400">{{ $tournament->getStatusLabel() }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if($tournament->status === 'draft')
                    <a href="{{ route('admin.tournaments.edit', $tournament) }}"
                        class="px-4 py-2 bg-purple-500/20 text-purple-400 rounded-lg hover:bg-purple-500/30 transition">
                        Modifier
                    </a>
                    <form action="{{ route('admin.tournaments.open-registration', $tournament) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition">
                            Ouvrir les inscriptions
                        </button>
                    </form>
                @endif
                @if($tournament->status === 'registration')
                    <form action="{{ route('admin.tournaments.start', $tournament) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-lg hover:bg-yellow-500/30 transition"
                            onclick="return confirm('Demarrer le tournoi avec {{ $tournament->participants->count() }} participants ?')">
                            Demarrer le tournoi
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500/50 rounded-xl p-4 mb-6 text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 mb-6 text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Infos tournoi -->
            <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Informations</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-gray-400 text-sm">Joueurs</dt>
                        <dd class="text-white font-bold">{{ $tournament->participants->count() }} / {{ $tournament->max_players }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-sm">Frais d'inscription</dt>
                        <dd class="text-yellow-400 font-bold">{{ number_format($tournament->entry_fee) }} pieces</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-sm">Cartes min par deck</dt>
                        <dd class="text-white">{{ $tournament->min_deck_cards }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400 text-sm">Tours</dt>
                        <dd class="text-white">{{ $tournament->total_rounds }}</dd>
                    </div>
                    @if($tournament->registration_start_at)
                    <div>
                        <dt class="text-gray-400 text-sm">Inscriptions</dt>
                        <dd class="text-white text-sm">
                            {{ $tournament->registration_start_at->format('d/m/Y H:i') }}
                            -
                            {{ $tournament->registration_end_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    @endif
                </dl>

                <h3 class="text-lg font-bold text-white mt-6 mb-3">Recompenses</h3>
                @php $rewards = $tournament->getRewardsConfig(); @endphp
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-yellow-400">&#129351; 1er</span>
                        <span class="text-white">{{ number_format($rewards['winner_coins']) }} po</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">&#129352; 2eme</span>
                        <span class="text-white">{{ number_format($rewards['runner_up_coins']) }} po</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-orange-400">&#129353; 3-4eme</span>
                        <span class="text-white">{{ number_format($rewards['semifinalist_coins']) }} po</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Participation</span>
                        <span class="text-white">{{ number_format($rewards['participation_coins']) }} po</span>
                    </div>
                </dl>
            </div>

            <!-- Participants -->
            <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Participants ({{ $tournament->participants->count() }})</h2>

                @if($tournament->participants->isEmpty())
                    <p class="text-gray-400 text-center py-8">Aucun participant pour le moment</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($tournament->participants->sortBy('seed_position') as $participant)
                            <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                                @if($participant->seed_position)
                                    <span class="w-8 h-8 flex items-center justify-center bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-bold">
                                        {{ $participant->seed_position }}
                                    </span>
                                @endif
                                <div class="flex-1">
                                    <div class="text-white font-semibold">{{ $participant->user->name }}</div>
                                    <div class="text-gray-400 text-sm">{{ $participant->deck->name }}</div>
                                </div>
                                @php
                                    $pStatusColors = [
                                        'registered' => 'text-blue-400',
                                        'active' => 'text-green-400',
                                        'eliminated' => 'text-red-400',
                                        'winner' => 'text-yellow-400',
                                    ];
                                @endphp
                                <span class="text-sm {{ $pStatusColors[$participant->status] ?? 'text-gray-400' }}">
                                    {{ $participant->getStatusLabel() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Bracket -->
        @if($tournament->matches->isNotEmpty())
            <div class="mt-6 bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Bracket</h2>

                <div class="overflow-x-auto">
                    <div class="flex gap-8 min-w-max pb-4">
                        @foreach($tournament->matches->groupBy('round') as $round => $matches)
                            <div class="flex-shrink-0 w-64">
                                <h3 class="text-center text-yellow-400 font-bold mb-4">{{ $tournament->getRoundName($round) }}</h3>
                                <div class="space-y-4">
                                    @foreach($matches->sortBy('match_number') as $match)
                                        <div class="bg-white/5 rounded-lg p-3 border {{ $match->status === 'in_progress' ? 'border-yellow-500' : 'border-white/10' }}">
                                            <div class="text-xs text-gray-500 mb-2">{{ $match->bracket_code }}</div>
                                            <div class="space-y-1">
                                                <div class="flex justify-between items-center {{ $match->winner_participant_id === $match->participant1_id ? 'text-green-400 font-bold' : 'text-white' }}">
                                                    <span>{{ $match->participant1?->user->name ?? 'TBD' }}</span>
                                                    @if($match->winner_participant_id === $match->participant1_id)
                                                        <span>&#10003;</span>
                                                    @endif
                                                </div>
                                                <div class="flex justify-between items-center {{ $match->winner_participant_id === $match->participant2_id ? 'text-green-400 font-bold' : 'text-white' }}">
                                                    <span>{{ $match->participant2?->user->name ?? 'TBD' }}</span>
                                                    @if($match->winner_participant_id === $match->participant2_id)
                                                        <span>&#10003;</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-xs text-center mt-2 {{ $match->status === 'finished' ? 'text-green-400' : ($match->status === 'in_progress' ? 'text-yellow-400' : 'text-gray-500') }}">
                                                {{ $match->getStatusLabel() }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
