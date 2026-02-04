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
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="text-4xl">&#127942;</span> Tournois
            </h1>
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition">
                &#8592; Retour au dashboard
            </a>
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

        <!-- Mes participations actives -->
        @if($myParticipations->isNotEmpty())
            <div class="mb-8">
                <h2 class="text-xl font-bold text-white mb-4">Mes tournois en cours</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($myParticipations as $participation)
                        <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 backdrop-blur rounded-xl border border-yellow-500/30 p-4">
                            <div class="flex justify-between items-start mb-3">
                                <h3 class="text-lg font-bold text-white">{{ $participation->tournament->name }}</h3>
                                @php
                                    $pStatusColors = [
                                        'registered' => 'bg-blue-500/20 text-blue-400',
                                        'active' => 'bg-green-500/20 text-green-400',
                                        'eliminated' => 'bg-red-500/20 text-red-400',
                                        'winner' => 'bg-yellow-500/20 text-yellow-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs {{ $pStatusColors[$participation->status] ?? '' }}">
                                    {{ $participation->getStatusLabel() }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-400 mb-3">
                                Deck: <span class="text-white">{{ $participation->deck->name }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tournaments.show', $participation->tournament) }}"
                                    class="flex-1 px-3 py-2 bg-white/10 text-white text-center rounded-lg hover:bg-white/20 transition text-sm">
                                    Details
                                </a>
                                @if($participation->tournament->status === 'in_progress')
                                    <a href="{{ route('tournaments.bracket', $participation->tournament) }}"
                                        class="flex-1 px-3 py-2 bg-yellow-500/20 text-yellow-400 text-center rounded-lg hover:bg-yellow-500/30 transition text-sm">
                                        Bracket
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Tournois en cours d'inscription -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Inscriptions ouvertes</h2>
            @if($registrationTournaments->isEmpty())
                <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-8 text-center">
                    <div class="text-4xl mb-3">&#128269;</div>
                    <p class="text-gray-400">Aucun tournoi ouvert aux inscriptions pour le moment</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($registrationTournaments as $tournament)
                        <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-4 {{ $tournament->is_featured ? 'ring-2 ring-yellow-500/50' : '' }}">
                            @if($tournament->is_featured)
                                <div class="text-yellow-400 text-xs font-bold mb-2">&#11088; EN VEDETTE</div>
                            @endif
                            <h3 class="text-lg font-bold text-white mb-2">{{ $tournament->name }}</h3>
                            @if($tournament->description)
                                <p class="text-gray-400 text-sm mb-3 line-clamp-2">{{ $tournament->description }}</p>
                            @endif
                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Joueurs</span>
                                    <span class="text-white">{{ $tournament->participants_count }} / {{ $tournament->max_players }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Inscription</span>
                                    <span class="text-yellow-400">{{ number_format($tournament->entry_fee) }} pieces</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Deck min</span>
                                    <span class="text-white">{{ $tournament->min_deck_cards }} cartes</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Fin inscriptions</span>
                                    <span class="text-white">{{ $tournament->registration_end_at->format('d/m H:i') }}</span>
                                </div>
                            </div>
                            @php $rewards = $tournament->getRewardsConfig(); @endphp
                            <div class="bg-white/5 rounded-lg p-2 mb-4 text-xs">
                                <div class="text-gray-400 mb-1">Recompenses:</div>
                                <div class="flex justify-between text-yellow-400">
                                    <span>&#129351; {{ number_format($rewards['winner_coins']) }} po</span>
                                    <span>&#129352; {{ number_format($rewards['runner_up_coins']) }} po</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('tournaments.show', $tournament) }}"
                                    class="flex-1 px-3 py-2 bg-white/10 text-white text-center rounded-lg hover:bg-white/20 transition text-sm">
                                    Details
                                </a>
                                @if($tournament->canJoin(auth()->user())['can_join'])
                                    <button type="button"
                                        onclick="openRegistrationModal({{ $tournament->id }}, '{{ $tournament->name }}', {{ $tournament->entry_fee }})"
                                        class="flex-1 px-3 py-2 bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 transition text-sm">
                                        S'inscrire
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tournois en cours -->
        <div class="mb-8">
            <h2 class="text-xl font-bold text-white mb-4">Tournois en cours</h2>
            @if($inProgressTournaments->isEmpty())
                <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-8 text-center">
                    <p class="text-gray-400">Aucun tournoi en cours</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($inProgressTournaments as $tournament)
                        <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-yellow-500/30 p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white">{{ $tournament->name }}</h3>
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs">
                                    {{ $tournament->getRoundName($tournament->current_round) }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Participants</span>
                                    <span class="text-white">{{ $tournament->participants_count }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Tour</span>
                                    <span class="text-white">{{ $tournament->current_round }} / {{ $tournament->total_rounds }}</span>
                                </div>
                            </div>
                            <a href="{{ route('tournaments.bracket', $tournament) }}"
                                class="block w-full px-3 py-2 bg-yellow-500/20 text-yellow-400 text-center rounded-lg hover:bg-yellow-500/30 transition text-sm">
                                Voir le bracket
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Tournois termines -->
        @if($finishedTournaments->isNotEmpty())
            <div>
                <h2 class="text-xl font-bold text-white mb-4">Tournois termines</h2>
                <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-400 uppercase">Tournoi</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-400 uppercase">Vainqueur</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-gray-400 uppercase">Date</th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($finishedTournaments as $tournament)
                                @php
                                    $winner = $tournament->participants->where('status', 'winner')->first();
                                @endphp
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-3 text-white">{{ $tournament->name }}</td>
                                    <td class="px-4 py-3 text-center">
                                        @if($winner)
                                            <span class="text-yellow-400 font-bold">&#127942; {{ $winner->user->name }}</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-400">
                                        {{ $tournament->finished_at?->format('d/m/Y') ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('tournaments.bracket', $tournament) }}"
                                            class="text-blue-400 hover:text-blue-300 text-sm">
                                            Voir le bracket
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal d'inscription -->
    <div id="registrationModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center">
        <div class="bg-gray-800 rounded-xl border border-white/10 p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold text-white mb-4">S'inscrire au tournoi</h3>
            <p class="text-gray-400 mb-2">Tournoi: <span id="modalTournamentName" class="text-white"></span></p>
            <p class="text-gray-400 mb-4">Frais: <span id="modalEntryFee" class="text-yellow-400"></span> pieces</p>

            <form id="registrationForm" action="#" method="POST">
                <div class="mb-4">
                    <label for="deck_id" class="block text-sm font-medium text-gray-300 mb-2">Choisir un deck</label>
                    <select name="deck_id" id="deck_id" required
                        class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                        <option value="">Selectionnez un deck</option>
                        @foreach($userDecks as $deck)
                            <option value="{{ $deck->id }}">{{ $deck->name }} ({{ $deck->cards->count() }} cartes)</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="tournament_id" id="modalTournamentId">

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
        function openRegistrationModal(tournamentId, name, fee) {
            document.getElementById('modalTournamentId').value = tournamentId;
            document.getElementById('modalTournamentName').textContent = name;
            document.getElementById('modalEntryFee').textContent = new Intl.NumberFormat().format(fee);
            document.getElementById('registrationModal').classList.remove('hidden');
            document.getElementById('registrationModal').classList.add('flex');
        }

        function closeRegistrationModal() {
            document.getElementById('registrationModal').classList.add('hidden');
            document.getElementById('registrationModal').classList.remove('flex');
        }

        document.getElementById('registrationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const tournamentId = document.getElementById('modalTournamentId').value;
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
                        tournament_id: tournamentId,
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

        // Fermer le modal en cliquant en dehors
        document.getElementById('registrationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeRegistrationModal();
            }
        });
    </script>
</x-app-layout>
