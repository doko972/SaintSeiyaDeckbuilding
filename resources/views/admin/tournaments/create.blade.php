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
            <a href="{{ route('admin.tournaments.index') }}" class="text-gray-400 hover:text-white transition">
                &#8592;
            </a>
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="text-4xl">&#127942;</span> Creer un tournoi
            </h1>
        </div>

        <form action="{{ route('admin.tournaments.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Informations generales -->
            <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Informations generales</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom du tournoi *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label for="max_players" class="block text-sm font-medium text-gray-300 mb-2">Nombre de joueurs *</label>
                        <select name="max_players" id="max_players" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="8" {{ old('max_players') == 8 ? 'selected' : '' }}>8 joueurs (3 tours)</option>
                            <option value="16" {{ old('max_players') == 16 ? 'selected' : '' }}>16 joueurs (4 tours)</option>
                            <option value="32" {{ old('max_players') == 32 ? 'selected' : '' }}>32 joueurs (5 tours)</option>
                        </select>
                    </div>

                    <div>
                        <label for="min_deck_cards" class="block text-sm font-medium text-gray-300 mb-2">Cartes minimum par deck *</label>
                        <input type="number" name="min_deck_cards" id="min_deck_cards" value="{{ old('min_deck_cards', 10) }}" min="5" max="30" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="entry_fee" class="block text-sm font-medium text-gray-300 mb-2">Frais d'inscription (pieces)</label>
                        <input type="number" name="entry_fee" id="entry_fee" value="{{ old('entry_fee', 0) }}" min="0" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                class="w-5 h-5 rounded bg-white/10 border-white/30 text-yellow-500 focus:ring-yellow-500">
                            <span class="text-gray-300">Mettre en avant</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Dates -->
            <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Dates</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="registration_start_at" class="block text-sm font-medium text-gray-300 mb-2">Debut des inscriptions *</label>
                        <input type="datetime-local" name="registration_start_at" id="registration_start_at" value="{{ old('registration_start_at') }}" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="registration_end_at" class="block text-sm font-medium text-gray-300 mb-2">Fin des inscriptions *</label>
                        <input type="datetime-local" name="registration_end_at" id="registration_end_at" value="{{ old('registration_end_at') }}" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>
                </div>
            </div>

            <!-- Recompenses -->
            <div class="bg-gray-800/50 backdrop-blur rounded-xl border border-white/10 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Recompenses</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="winner_coins" class="block text-sm font-medium text-gray-300 mb-2">1er - Pieces</label>
                        <input type="number" name="winner_coins" id="winner_coins" value="{{ old('winner_coins', 5000) }}" min="0" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="runner_up_coins" class="block text-sm font-medium text-gray-300 mb-2">2eme - Pieces</label>
                        <input type="number" name="runner_up_coins" id="runner_up_coins" value="{{ old('runner_up_coins', 2500) }}" min="0" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="semifinalist_coins" class="block text-sm font-medium text-gray-300 mb-2">3-4eme - Pieces</label>
                        <input type="number" name="semifinalist_coins" id="semifinalist_coins" value="{{ old('semifinalist_coins', 1000) }}" min="0" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="participation_coins" class="block text-sm font-medium text-gray-300 mb-2">Participation - Pieces</label>
                        <input type="number" name="participation_coins" id="participation_coins" value="{{ old('participation_coins', 200) }}" min="0" required
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="winner_title" class="block text-sm font-medium text-gray-300 mb-2">Titre du vainqueur</label>
                        <input type="text" name="winner_title" id="winner_title" value="{{ old('winner_title') }}" placeholder="Ex: Champion du Sanctuaire"
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">
                    </div>

                    <div>
                        <label for="exclusive_card_id" class="block text-sm font-medium text-gray-300 mb-2">Carte exclusive (1er)</label>
                        <select name="exclusive_card_id" id="exclusive_card_id"
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                            <option value="">Aucune</option>
                            @foreach($cards as $card)
                                <option value="{{ $card->id }}" {{ old('exclusive_card_id') == $card->id ? 'selected' : '' }}>
                                    {{ $card->name }} ({{ ucfirst($card->rarity) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.tournaments.index') }}"
                    class="px-6 py-3 bg-gray-600 rounded-lg font-bold text-white hover:bg-gray-700 transition">
                    Annuler
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg font-bold text-white hover:from-yellow-600 hover:to-orange-600 transition">
                    Creer le tournoi
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
