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
                                <label for="power_type" class="block text-sm font-medium text-gray-300 mb-2">⚡ Type
                                    de puissance *</label>
                                <select name="power_type" id="power_type" required
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="black" {{ old('power_type') == 'steel' ? 'selected' : '' }}>🤖
                                        Noir</option>
                                    <option value="bronze" {{ old('power_type') == 'bronze' ? 'selected' : '' }}>🥉
                                        Bronze</option>
                                    <option value="silver" {{ old('power_type') == 'silver' ? 'selected' : '' }}>🥈
                                        Argent</option>
                                    <option value="gold" {{ old('power_type') == 'gold' ? 'selected' : '' }}>🥇 Or
                                    </option>
                                    <option value="divine" {{ old('power_type') == 'divine' ? 'selected' : '' }}>👑
                                        Divine</option>
                                    <option value="surplis" {{ old('power_type') == 'surplis' ? 'selected' : '' }}>💀
                                        Surplis</option>
                                    <option value="god_warrior"
                                        {{ old('power_type') == 'god_warrior' ? 'selected' : '' }}>⚔️
                                        Guerrier Divin</option>
                                    <option value="steel" {{ old('power_type') == 'steel' ? 'selected' : '' }}>🤖
                                        Acier</option>
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
                                    <option value="earth" {{ old('element') == 'earth' ? 'selected' : '' }}>🌍 Terre
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
                                    <option value="mythic" {{ old('rarity') == 'mythic' ? 'selected' : '' }}>Mythique
                                    </option>
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
                                    value="{{ old('health_points', 100) }}" min="1" max="1000" required
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
                                    value="{{ old('defense', 30) }}" min="0" max="200" required
                                    class="w-full bg-white/10 border border-blue-500/30 rounded-lg px-3 py-2 text-white text-center text-xl font-bold focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div class="bg-orange-500/20 border border-orange-500/30 rounded-xl p-4">
                                <label for="power" class="block text-sm font-medium text-orange-400 mb-2">💪 PWR
                                    *</label>
                                <input type="number" name="power" id="power" value="{{ old('power', 50) }}"
                                    min="0" max="500" required
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="passive_effect_type"
                                    class="block text-sm font-medium text-gray-300 mb-2">Effet passif (mécanique)</label>
                                <select name="passive_effect_type" id="passive_effect_type"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                                    <option value="none" {{ old('passive_effect_type', 'none') === 'none' ? 'selected' : '' }}>Aucun</option>
                                    <option value="heal_allies" {{ old('passive_effect_type') === 'heal_allies' ? 'selected' : '' }}>💚 Soin des alliés au déploiement</option>
                                    <option value="shield_self" {{ old('passive_effect_type') === 'shield_self' ? 'selected' : '' }}>🛡️ Bouclier personnel au déploiement</option>
                                    <option value="boost_allies" {{ old('passive_effect_type') === 'boost_allies' ? 'selected' : '' }}>⬆️ Boost des alliés au déploiement</option>
                                </select>
                            </div>

                            <div>
                                <label for="passive_effect_value"
                                    class="block text-sm font-medium text-gray-300 mb-2">Valeur de l'effet passif</label>
                                <input type="number" name="passive_effect_value" id="passive_effect_value"
                                    value="{{ old('passive_effect_value', 0) }}" min="0" step="1"
                                    placeholder="Ex: 20 (PV soignés, bonus défense...)"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-yellow-500">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: Images par niveau de fusion -->
                    <div class="space-y-4" x-data="{ openLevel: 1 }">
                        <h4 class="text-lg font-bold text-white border-b border-white/20 pb-2">🖼️ Images par niveau de fusion</h4>
                        <p class="text-sm text-gray-400">Chaque niveau peut avoir une image principale et une image alternative (visuel inversé / holographique).</p>

                        @for ($level = 1; $level <= 10; $level++)
                            @php
                                $levelLabel = $level === 1 ? 'Niveau 1 — Base' : 'Niveau ' . $level . ' — Fusion +' . ($level - 1);
                                $levelColor = $level === 1 ? 'purple' : ($level >= 8 ? 'red' : ($level >= 5 ? 'amber' : 'indigo'));
                            @endphp
                            <div class="border border-white/10 rounded-xl overflow-hidden">
                                <button type="button"
                                    @click="openLevel = openLevel === {{ $level }} ? null : {{ $level }}"
                                    class="w-full flex items-center justify-between px-5 py-3 bg-white/5 hover:bg-white/10 transition text-left">
                                    <span class="font-semibold text-white flex items-center gap-2">
                                        <span class="inline-block w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center
                                            {{ $level === 1 ? 'bg-purple-500' : ($level >= 8 ? 'bg-red-500' : ($level >= 5 ? 'bg-amber-500' : 'bg-indigo-500')) }}">
                                            {{ $level }}
                                        </span>
                                        {{ $levelLabel }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform"
                                         :class="openLevel === {{ $level }} ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="openLevel === {{ $level }}" x-collapse class="p-5 bg-white/5">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Image principale</label>
                                            <input type="file" name="images[{{ $level }}][primary]" accept="image/*"
                                                class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                                            @if ($level === 1)
                                                <p class="text-xs text-gray-500 mt-1">Format recommandé : 400x500px (ratio 4:5)</p>
                                            @endif
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-300 mb-2">Image alternative</label>
                                            <input type="file" name="images[{{ $level }}][secondary]" accept="image/*"
                                                class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
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
