<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end items-center mb-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Nouvelle Carte') }}
                </h2>
                <a href="{{ route('cards.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600">
                    <h3 class="text-xl font-bold text-white">🃏 Créer une nouvelle carte</h3>
                </div>

                <form method="POST" action="{{ route('admin.cards.store') }}" enctype="multipart/form-data"
                    class="p-6 space-y-8">
                    @csrf

                    <!-- SECTION 1: Informations de base -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">📋 Informations de base
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom de la
                                    carte *</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    placeholder="Ex: Seiya de Pégase"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Grade -->
                            <div>
                                <label for="grade" class="block text-sm font-medium text-gray-300 mb-2">Grade (1-10)
                                    *</label>
                                <input type="number" name="grade" id="grade" value="{{ old('grade', 5) }}"
                                    min="1" max="10" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                @error('grade')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Faction -->
                            <div>
                                <label for="faction_id" class="block text-sm font-medium text-gray-300 mb-2">Faction
                                    *</label>
                                <select name="faction_id" id="faction_id" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($factions as $faction)
                                        <option value="{{ $faction->id }}"
                                            {{ old('faction_id') == $faction->id ? 'selected' : '' }}>
                                            {{ $faction->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('faction_id')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Coût -->
                            <div>
                                <label for="cost" class="block text-sm font-medium text-gray-300 mb-2">💎 Coût
                                    d'invocation *</label>
                                <input type="number" name="cost" id="cost" value="{{ old('cost', 3) }}"
                                    min="1" max="15" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                @error('cost')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Type d'armure, Élément, Rareté -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="armor_type" class="block text-sm font-medium text-gray-300 mb-2">🛡️ Type
                                    d'armure *</label>
                                <select name="armor_type" id="armor_type" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="bronze" {{ old('armor_type') == 'bronze' ? 'selected' : '' }}>🥉
                                        Bronze</option>
                                    <option value="silver" {{ old('armor_type') == 'silver' ? 'selected' : '' }}>🥈
                                        Argent</option>
                                    <option value="gold" {{ old('armor_type') == 'gold' ? 'selected' : '' }}>🥇 Or
                                    </option>
                                    <option value="divine" {{ old('armor_type') == 'divine' ? 'selected' : '' }}>👑
                                        Divine</option>
                                </select>
                            </div>

                            <div>
                                <label for="element" class="block text-sm font-medium text-gray-300 mb-2">✨ Élément
                                    *</label>
                                <select name="element" id="element" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="fire" {{ old('element') == 'fire' ? 'selected' : '' }}>🔥 Feu
                                    </option>
                                    <option value="water" {{ old('element') == 'water' ? 'selected' : '' }}>💧 Eau
                                    </option>
                                    <option value="ice" {{ old('element') == 'ice' ? 'selected' : '' }}>❄️ Glace
                                    </option>
                                    <option value="thunder" {{ old('element') == 'thunder' ? 'selected' : '' }}>⚡
                                        Foudre</option>
                                    <option value="darkness" {{ old('element') == 'darkness' ? 'selected' : '' }}>🌑
                                        Ténèbres</option>
                                    <option value="light" {{ old('element') == 'light' ? 'selected' : '' }}>✨ Lumière
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label for="rarity" class="block text-sm font-medium text-gray-300 mb-2">⭐ Rareté
                                    *</label>
                                <select name="rarity" id="rarity" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="common" {{ old('rarity') == 'common' ? 'selected' : '' }}>Commune
                                    </option>
                                    <option value="rare" {{ old('rarity') == 'rare' ? 'selected' : '' }}>Rare
                                    </option>
                                    <option value="epic" {{ old('rarity') == 'epic' ? 'selected' : '' }}>Épique
                                    </option>
                                    <option value="legendary" {{ old('rarity') == 'legendary' ? 'selected' : '' }}>
                                        Légendaire</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Statistiques -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">📊 Statistiques</h4>

                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4">
                                <label for="health_points" class="block text-sm font-medium text-red-400 mb-2">❤️ PV
                                    *</label>
                                <input type="number" name="health_points" id="health_points"
                                    value="{{ old('health_points', 100) }}" min="1" max="500" required
                                    class="w-full bg-white/10 border border-red-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-red-500 focus:ring-red-500">
                            </div>

                            <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-xl p-4">
                                <label for="endurance" class="block text-sm font-medium text-yellow-400 mb-2">⚡ END
                                    *</label>
                                <input type="number" name="endurance" id="endurance"
                                    value="{{ old('endurance', 80) }}" min="1" max="200" required
                                    class="w-full bg-white/10 border border-yellow-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-yellow-500 focus:ring-yellow-500">
                            </div>

                            <div class="bg-blue-500/20 border border-blue-500/30 rounded-xl p-4">
                                <label for="defense" class="block text-sm font-medium text-blue-400 mb-2">🛡️ DEF
                                    *</label>
                                <input type="number" name="defense" id="defense"
                                    value="{{ old('defense', 30) }}" min="0" max="100" required
                                    class="w-full bg-white/10 border border-blue-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="bg-orange-500/20 border border-orange-500/30 rounded-xl p-4">
                                <label for="power" class="block text-sm font-medium text-orange-400 mb-2">💪 PWR
                                    *</label>
                                <input type="number" name="power" id="power" value="{{ old('power', 50) }}"
                                    min="0" max="200" required
                                    class="w-full bg-white/10 border border-orange-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-orange-500 focus:ring-orange-500">
                            </div>

                            <div class="bg-purple-500/20 border border-purple-500/30 rounded-xl p-4">
                                <label for="cosmos" class="block text-sm font-medium text-purple-400 mb-2">🌟 COS
                                    *</label>
                                <input type="number" name="cosmos" id="cosmos" value="{{ old('cosmos', 60) }}"
                                    min="0" max="200" required
                                    class="w-full bg-white/10 border border-purple-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-purple-500 focus:ring-purple-500">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Attaques -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">⚔️ Attaques</h4>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                                <label for="main_attack_id"
                                    class="block text-sm font-medium text-red-400 mb-2">Attaque Principale *</label>
                                <select name="main_attack_id" id="main_attack_id" required
                                    class="w-full bg-white/10 border border-red-500/30 rounded-lg px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                                    <option value="">-- Sélectionner --</option>
                                    @foreach ($attacks as $attack)
                                        <option value="{{ $attack->id }}"
                                            {{ old('main_attack_id') == $attack->id ? 'selected' : '' }}>
                                            {{ $attack->name }} ({{ $attack->damage }} DMG)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                                <label for="secondary_attack_1_id"
                                    class="block text-sm font-medium text-blue-400 mb-2">Attaque Secondaire 1</label>
                                <select name="secondary_attack_1_id" id="secondary_attack_1_id"
                                    class="w-full bg-white/10 border border-blue-500/30 rounded-lg px-4 py-3 text-white focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Aucune --</option>
                                    @foreach ($attacks as $attack)
                                        <option value="{{ $attack->id }}"
                                            {{ old('secondary_attack_1_id') == $attack->id ? 'selected' : '' }}>
                                            {{ $attack->name }} ({{ $attack->damage }} DMG)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4">
                                <label for="secondary_attack_2_id"
                                    class="block text-sm font-medium text-green-400 mb-2">Attaque Secondaire 2</label>
                                <select name="secondary_attack_2_id" id="secondary_attack_2_id"
                                    class="w-full bg-white/10 border border-green-500/30 rounded-lg px-4 py-3 text-white focus:border-green-500 focus:ring-green-500">
                                    <option value="">-- Aucune --</option>
                                    @foreach ($attacks as $attack)
                                        <option value="{{ $attack->id }}"
                                            {{ old('secondary_attack_2_id') == $attack->id ? 'selected' : '' }}>
                                            {{ $attack->name }} ({{ $attack->damage }} DMG)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Capacité Passive -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">✨ Capacité Passive</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="passive_ability_name"
                                    class="block text-sm font-medium text-gray-300 mb-2">Nom de la capacité</label>
                                <input type="text" name="passive_ability_name" id="passive_ability_name"
                                    value="{{ old('passive_ability_name') }}" placeholder="Ex: Cosmos ardent"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">
                            </div>

                            <div>
                                <label for="passive_ability_description"
                                    class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                                <input type="text" name="passive_ability_description"
                                    id="passive_ability_description" value="{{ old('passive_ability_description') }}"
                                    placeholder="Ex: +10% de dégâts quand PV < 50%"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: Images -->
                    <div class="space-y-6">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">🖼️ Images</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="image_primary" class="block text-sm font-medium text-gray-300 mb-2">Image
                                    principale</label>
                                <input type="file" name="image_primary" id="image_primary" accept="image/*"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                                <p class="text-xs text-gray-500 mt-1">Format recommandé: 400x500px (ratio 4:5)</p>
                            </div>

                            <div>
                                <label for="image_secondary"
                                    class="block text-sm font-medium text-gray-300 mb-2">Image alternative</label>
                                <input type="file" name="image_secondary" id="image_secondary" accept="image/*"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4 pt-6 border-t border-white/20">
                        <button type="submit"
                            class="flex-1 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-[1.02]">
                            ✅ Créer la carte
                        </button>
                        <a href="{{ route('cards.index') }}"
                            class="px-8 py-4 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500 transition">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
