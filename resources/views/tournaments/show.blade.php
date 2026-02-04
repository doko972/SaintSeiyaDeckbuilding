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
                <a href="{{ route('tournaments.index') }}" class="text-gray-400 hover:text-white transition">
                    &#8592;
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        @if($tournament->is_featured)
                            <span class="text-yellow-400">&#11088;</span>
                        @endif
                        {{ $tournament->name }}
                    </h1>
                    <p class="text-gray-400">{{ $tournament->getStatusLabel() }}</p>
                </div>
            </div>
            @if($tournament->status === 'in_progress' || $tournament->status === 'finished')
                <a href="{{ route('tournaments.bracket', $tournament) }}"
                    class="px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-lg hover:bg-yellow-500/30 transition">
                    Voir le bracket
                </a>
            @endif
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

                @if($tournament->description)
                    <p class="text-gray-400 mb-4">{{ $tournament->description }}</p>
                @endif

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
                    @if(!empty($rewards['winner_title']))
                        <div class="pt-2 border-t border-white/10">
                            <span class="text-purple-400">Titre: {{ $rewards['winner_title'] }}</span>
                        </div>
                    @endif
                </dl>

                <!-- Actions -->
                @if($tournament->status === 'registration')
                    <div class="mt-6 pt-6 border-t border-white/10">
                        @if($myParticipation)
                            <div class="bg-green-500/10 border border-green-500/30 rounded-lg p-4 mb-4">
                                <div class="text-green-400 font-bold mb-1">&#10003; Vous etes inscrit</div>
                                <div class="text-gray-400 text-sm">Deck: {{ $myParticipation->deck->name }}</div>
                            </div>
                            <button onclick="withdrawFromTournament({{ $tournament->id }})"
                                class="w-full px-4 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                                Se desinscrire
                            </button>
                        @elseif($tournament->canJoin(auth()->user())['can_join'])
                            <button onclick="openRegistrationModal()"
                                class="w-full px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg font-bold text-white hover:from-green-600 hover:to-emerald-600 transition">
                                S'inscrire
                            </button>
                        @elseif($tournament->participants->count() >= $tournament->max_players)
                            <div class="text-center text-red-400">Tournoi complet</div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Mon match en cours -->
            @if($myParticipation && $myCurrentMatch)
                <div class="lg:col-span-2 bg-gradient-to-br from-yellow-500/20 to-orange-500/20 backdrop-blur rounded-xl border border-yellow-500/30 p-6">
                    <h2 class="text-xl font-bold text-white mb-4">&#9876; Mon prochain match</h2>

                    <div class="bg-white/10 rounded-xl p-6">
                        <div class="text-center mb-4">
                            <span class="text-yellow-400 font-bold">{{ $tournament->getRoundName($myCurrentMatch->round) }}</span>
                            <span class="text-gray-400 mx-2">-</span>
                            <span class="text-gray-400">{{ $myCurrentMatch->bracket_code }}</span>
                        </div>

                        <div class="flex items-center justify-center gap-8">
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto bg-blue-500/20 rounded-full flex items-center justify-center text-2xl mb-2">
                                    {{ substr($myCurrentMatch->participant1?->user->name ?? '?', 0, 1) }}
                                </div>
                                <div class="text-white font-bold">{{ $myCurrentMatch->participant1?->user->name ?? 'TBD' }}</div>
                                @if($myCurrentMatch->participant1?->user_id === auth()->id())
                                    <div class="text-yellow-400 text-sm">Vous</div>
                                @endif
                            </div>

                            <div class="text-4xl text-gray-500">VS</div>

                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto bg-red-500/20 rounded-full flex items-center justify-center text-2xl mb-2">
                                    {{ substr($myCurrentMatch->participant2?->user->name ?? '?', 0, 1) }}
                                </div>
                                <div class="text-white font-bold">{{ $myCurrentMatch->participant2?->user->name ?? 'TBD' }}</div>
                                @if($myCurrentMatch->participant2?->user_id === auth()->id())
                                    <div class="text-yellow-400 text-sm">Vous</div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 text-center">
                            @if($myCurrentMatch->status === 'ready')
                                <a href="{{ route('tournaments.match', [$tournament, $myCurrentMatch]) }}"
                                    class="inline-block px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg font-bold text-white hover:from-yellow-600 hover:to-orange-600 transition">
                                    Rejoindre le match
                                </a>
                            @elseif($myCurrentMatch->status === 'in_progress')
                                <a href="{{ route('pvp.battle', $myCurrentMatch->battle_id) }}"
                                    class="inline-block px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg font-bold text-white hover:from-red-600 hover:to-orange-600 transition animate-pulse">
                                    Combat en cours!
                                </a>
                            @elseif($myCurrentMatch->status === 'pending')
                                <div class="text-gray-400">En attente de l'adversaire...</div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Participants -->
                <div class="lg:col-span-2 bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                    <h2 class="text-xl font-bold text-white mb-4">Participants ({{ $tournament->participants->count() }})</h2>

                    @if($tournament->participants->isEmpty())
                        <p class="text-gray-400 text-center py-8">Aucun participant pour le moment</p>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($tournament->participants->sortBy('seed_position') as $participant)
                                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-lg {{ $participant->user_id === auth()->id() ? 'ring-2 ring-yellow-500/50' : '' }}">
                                    @if($participant->seed_position)
                                        <span class="w-8 h-8 flex items-center justify-center bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-bold">
                                            {{ $participant->seed_position }}
                                        </span>
                                    @endif
                                    <div class="flex-1">
                                        <div class="text-white font-semibold">
                                            {{ $participant->user->name }}
                                            @if($participant->user_id === auth()->id())
                                                <span class="text-yellow-400 text-sm">(vous)</span>
                                            @endif
                                        </div>
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
            @endif
        </div>

        <!-- Bracket (si demarré) -->
        @if($tournament->matches->isNotEmpty())
            <div class="mt-6 bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-white">Bracket</h2>
                    <a href="{{ route('tournaments.bracket', $tournament) }}" class="text-yellow-400 hover:text-yellow-300">
                        Voir en grand &#10132;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <div class="flex gap-8 min-w-max pb-4">
                        @foreach($tournament->matches->groupBy('round') as $round => $matches)
                            <div class="flex-shrink-0 w-64">
                                <h3 class="text-center text-yellow-400 font-bold mb-4">{{ $tournament->getRoundName($round) }}</h3>
                                <div class="space-y-4">
                                    @foreach($matches->sortBy('match_number') as $match)
                                        <div class="bg-white/5 rounded-lg p-3 border {{ $match->status === 'in_progress' ? 'border-yellow-500' : 'border-white/10' }}">
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

    <!-- Modal d'inscription -->
    @if($tournament->status === 'registration' && !$myParticipation)
        <div id="registrationModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center">
            <div class="bg-gray-800 rounded-xl border border-white/10 p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-bold text-white mb-4">S'inscrire au tournoi</h3>
                <p class="text-gray-400 mb-4">Frais: <span class="text-yellow-400">{{ number_format($tournament->entry_fee) }}</span> pieces</p>

                <form id="registrationForm">
                    <div class="mb-4">
                        <label for="deck_id" class="block text-sm font-medium text-gray-300 mb-2">Choisir un deck (min {{ $tournament->min_deck_cards }} cartes)</label>
                        <select name="deck_id" id="deck_id" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="">Selectionnez un deck</option>
                            @foreach($userDecks as $deck)
                                <option value="{{ $deck->id }}" {{ $deck->cards->count() < $tournament->min_deck_cards ? 'disabled' : '' }}>
                                    {{ $deck->name }} ({{ $deck->cards->count() }} cartes)
                                    {{ $deck->cards->count() < $tournament->min_deck_cards ? '- Trop peu de cartes' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeRegistrationModal()"
                            class="flex-1 px-4 py-2 bg-gray-600 rounded-lg text-white hover:bg-gray-700 transition">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg font-bold text-white hover:from-green-600 hover:to-emerald-600 transition">
                            Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openRegistrationModal() {
                document.getElementById('registrationModal').classList.remove('hidden');
                document.getElementById('registrationModal').classList.add('flex');
            }

            function closeRegistrationModal() {
                document.getElementById('registrationModal').classList.add('hidden');
                document.getElementById('registrationModal').classList.remove('flex');
            }

            document.getElementById('registrationForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const deckId = document.getElementById('deck_id').value;

                if (!deckId) {
                    alert('Veuillez selectionner un deck');
                    return;
                }

                try {
                    const response = await fetch('/api/v1/tournaments/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            tournament_id: {{ $tournament->id }},
                            deck_id: deckId
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'inscription');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    alert('Erreur de connexion');
                }
            });

            document.getElementById('registrationModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRegistrationModal();
                }
            });
        </script>
    @endif

    <script>
        async function withdrawFromTournament(tournamentId) {
            if (!confirm('Voulez-vous vraiment vous desinscrire ? Les frais d\'inscription vous seront rembourses.')) {
                return;
            }

            try {
                const response = await fetch('/api/v1/tournaments/withdraw', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ tournament_id: tournamentId })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message || 'Erreur lors de la desinscription');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur de connexion');
            }
        }
    </script>
</x-app-layout>
