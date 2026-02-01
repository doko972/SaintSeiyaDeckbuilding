<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-4xl">⚡</span> Gestion des Combos
                </h1>
                <a href="{{ route('admin.combos.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-xl hover:from-yellow-400 hover:to-orange-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    <span>➕</span> Ajouter un combo
                </a>
            </div>

            <!-- Info -->
            <div class="bg-blue-500/20 border border-blue-500/50 rounded-xl p-4 mb-6 text-blue-300">
                <p class="flex items-center gap-2">
                    <span>💡</span>
                    Un combo permet de lancer une attaque spéciale quand 3 cartes spécifiques sont sur le terrain. Seule la carte "leader" peut lancer l'attaque.
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/50 rounded-xl p-4 mb-6 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Liste des combos -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                @if($combos->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">⚡</div>
                        <p class="text-gray-400 text-lg">Aucun combo pour le moment</p>
                        <p class="text-gray-500 text-sm mt-2">Créez des combos pour ajouter des attaques spéciales en combat</p>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Combo</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Cartes</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Leader</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Attaque</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Coûts</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Statut</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($combos as $combo)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center text-xl">
                                                ⚡
                                            </div>
                                            <div>
                                                <div class="font-semibold text-white">{{ $combo->name }}</div>
                                                @if($combo->description)
                                                    <div class="text-xs text-gray-500 max-w-xs truncate">{{ $combo->description }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-xs text-gray-300">{{ $combo->card1->name }}</span>
                                            <span class="text-xs text-gray-300">{{ $combo->card2->name }}</span>
                                            <span class="text-xs text-gray-300">{{ $combo->card3->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-400">
                                            👑 {{ $combo->leaderCard->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-white font-medium">{{ $combo->attack->name }}</span>
                                            <span class="text-xs text-red-400">⚔️ {{ $combo->attack->damage }} dégâts</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            @if($combo->endurance_cost > 0)
                                                <span class="text-xs text-blue-400">⚡ {{ $combo->endurance_cost }} END</span>
                                            @endif
                                            @if($combo->cosmos_cost > 0)
                                                <span class="text-xs text-purple-400">🌟 {{ $combo->cosmos_cost }} COS</span>
                                            @endif
                                            @if($combo->endurance_cost == 0 && $combo->cosmos_cost == 0)
                                                <span class="text-xs text-gray-500">Aucun coût</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.combos.toggle', $combo) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold transition {{ $combo->is_active ? 'bg-green-500/20 text-green-400 hover:bg-green-500/30' : 'bg-gray-500/20 text-gray-400 hover:bg-gray-500/30' }}">
                                                {{ $combo->is_active ? '✅ Actif' : '❌ Inactif' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.combos.edit', $combo) }}"
                                               class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition"
                                               title="Modifier">
                                                ✏️
                                            </a>
                                            <form action="{{ route('admin.combos.destroy', $combo) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Supprimer ce combo ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition"
                                                        title="Supprimer">
                                                    🗑️
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Retour -->
            <div class="mt-6">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
                    ← Retour au dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
