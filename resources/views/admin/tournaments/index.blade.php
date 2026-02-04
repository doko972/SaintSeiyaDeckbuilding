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
                <span class="text-4xl">&#127942;</span> Gestion des Tournois
            </h1>
            <a href="{{ route('admin.tournaments.create') }}"
                class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg font-bold text-white hover:from-yellow-600 hover:to-orange-600 transition flex items-center gap-2">
                &#10133; Creer un tournoi
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

        <!-- Liste des tournois -->
        <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 overflow-hidden">
            @if($tournaments->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-6xl mb-4">&#127942;</div>
                    <p class="text-gray-400 text-lg">Aucun tournoi pour le moment</p>
                    <a href="{{ route('admin.tournaments.create') }}" class="inline-block mt-4 text-yellow-400 hover:text-yellow-300">
                        Creer votre premier tournoi &#10132;
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-yellow-600/20 to-orange-600/20">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-yellow-400 uppercase">Tournoi</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-yellow-400 uppercase">Joueurs</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-yellow-400 uppercase">Statut</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-yellow-400 uppercase">Inscriptions</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-yellow-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($tournaments as $tournament)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-white">{{ $tournament->name }}</div>
                                        <div class="text-sm text-gray-400">{{ $tournament->max_players }} joueurs max</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-white font-bold">{{ $tournament->participants_count }}</span>
                                        <span class="text-gray-400">/ {{ $tournament->max_players }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-500/20 text-gray-400',
                                                'registration' => 'bg-green-500/20 text-green-400',
                                                'in_progress' => 'bg-yellow-500/20 text-yellow-400',
                                                'finished' => 'bg-blue-500/20 text-blue-400',
                                                'cancelled' => 'bg-red-500/20 text-red-400',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-sm {{ $statusColors[$tournament->status] ?? '' }}">
                                            {{ $tournament->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-400">
                                        @if($tournament->registration_start_at && $tournament->registration_end_at)
                                            {{ $tournament->registration_start_at->format('d/m H:i') }}
                                            -
                                            {{ $tournament->registration_end_at->format('d/m H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2 flex-wrap">
                                            <a href="{{ route('admin.tournaments.show', $tournament) }}"
                                                class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded hover:bg-blue-500/30 transition text-sm">
                                                Voir
                                            </a>

                                            @if($tournament->status === 'draft')
                                                <a href="{{ route('admin.tournaments.edit', $tournament) }}"
                                                    class="px-3 py-1 bg-purple-500/20 text-purple-400 rounded hover:bg-purple-500/30 transition text-sm">
                                                    Modifier
                                                </a>
                                                <form action="{{ route('admin.tournaments.open-registration', $tournament) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-green-500/20 text-green-400 rounded hover:bg-green-500/30 transition text-sm">
                                                        Ouvrir
                                                    </button>
                                                </form>
                                            @endif

                                            @if($tournament->status === 'registration')
                                                <form action="{{ route('admin.tournaments.start', $tournament) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded hover:bg-yellow-500/30 transition text-sm"
                                                        onclick="return confirm('Demarrer le tournoi ?')">
                                                        Demarrer
                                                    </button>
                                                </form>
                                            @endif

                                            @if(in_array($tournament->status, ['draft', 'registration', 'in_progress']))
                                                <form action="{{ route('admin.tournaments.cancel', $tournament) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="px-3 py-1 bg-red-500/20 text-red-400 rounded hover:bg-red-500/30 transition text-sm"
                                                        onclick="return confirm('Annuler le tournoi ? Les frais d\'inscription seront rembourses.')">
                                                        Annuler
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-white/5">
                    {{ $tournaments->links() }}
                </div>
            @endif
        </div>

        <!-- Retour -->
        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition">
                &#8592; Retour au dashboard
            </a>
        </div>
    </div>
</x-app-layout>
