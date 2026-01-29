<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-4xl">🎵</span> Gestion des Musiques
                </h1>
                <a href="{{ route('admin.musics.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold rounded-xl hover:from-purple-400 hover:to-indigo-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                    <span>➕</span> Ajouter une musique
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/50 rounded-xl p-4 mb-6 text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Liste des musiques -->
            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                @if($musics->isEmpty())
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">🎵</div>
                        <p class="text-gray-400 text-lg">Aucune musique pour le moment</p>
                        <p class="text-gray-500 text-sm mt-2">Ajoutez des musiques pour les jouer pendant les combats</p>
                    </div>
                @else
                    <table class="w-full">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Musique</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Type</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Volume</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Statut</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-300">Aperçu</th>
                                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @foreach($musics as $music)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                                🎵
                                            </div>
                                            <div>
                                                <div class="font-semibold text-white">{{ $music->name }}</div>
                                                <div class="text-xs text-gray-500">{{ basename($music->file_path) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($music->type)
                                            @case('battle')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-400">⚔️ Combat</span>
                                                @break
                                            @case('menu')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400">🏠 Menu</span>
                                                @break
                                            @case('victory')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 text-green-400">🏆 Victoire</span>
                                                @break
                                            @case('defeat')
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-500/20 text-gray-400">💀 Défaite</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <span class="text-white">{{ $music->volume }}%</span>
                                            <div class="w-16 h-2 bg-gray-700 rounded-full overflow-hidden">
                                                <div class="h-full bg-purple-500" style="width: {{ $music->volume }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('admin.musics.toggle', $music) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3 py-1 rounded-full text-xs font-semibold transition {{ $music->is_active ? 'bg-green-500/20 text-green-400 hover:bg-green-500/30' : 'bg-gray-500/20 text-gray-400 hover:bg-gray-500/30' }}">
                                                {{ $music->is_active ? '✅ Active' : '❌ Inactive' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <audio controls class="h-8 w-32">
                                            <source src="{{ Storage::url($music->file_path) }}" type="audio/mpeg">
                                        </audio>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('admin.musics.edit', $music) }}"
                                               class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition"
                                               title="Modifier">
                                                ✏️
                                            </a>
                                            <form action="{{ route('admin.musics.destroy', $music) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Supprimer cette musique ?')">
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
