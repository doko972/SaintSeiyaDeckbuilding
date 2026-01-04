<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouvelle Attaque') }}
            </h2>
            <a href="{{ route('attacks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-red-600 to-orange-600">
                    <h3 class="text-xl font-bold text-white">⚔️ Créer une nouvelle attaque</h3>
                </div>

                <form method="POST" action="{{ route('admin.attacks.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom de l'attaque *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-red-500 focus:ring-red-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-6">
                        <div>
                            <label for="damage" class="block text-sm font-medium text-gray-300 mb-2">🗡️ Dégâts *</label>
                            <input type="number" name="damage" id="damage" value="{{ old('damage', 50) }}" min="0" max="500" required
                                   class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label for="endurance_cost" class="block text-sm font-medium text-gray-300 mb-2">⚡ Coût Endurance *</label>
                            <input type="number" name="endurance_cost" id="endurance_cost" value="{{ old('endurance_cost', 20) }}" min="0" max="100" required
                                   class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                        <div>
                            <label for="cosmos_cost" class="block text-sm font-medium text-gray-300 mb-2">🌟 Coût Cosmos *</label>
                            <input type="number" name="cosmos_cost" id="cosmos_cost" value="{{ old('cosmos_cost', 15) }}" min="0" max="100" required
                                   class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <!-- Effet -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="effect_type" class="block text-sm font-medium text-gray-300 mb-2">✨ Type d'effet</label>
                            <select name="effect_type" id="effect_type"
                                    class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                <option value="none" {{ old('effect_type') == 'none' ? 'selected' : '' }}>🎯 Aucun</option>
                                <option value="burn" {{ old('effect_type') == 'burn' ? 'selected' : '' }}>🔥 Brûlure</option>
                                <option value="freeze" {{ old('effect_type') == 'freeze' ? 'selected' : '' }}>❄️ Gel</option>
                                <option value="stun" {{ old('effect_type') == 'stun' ? 'selected' : '' }}>⚡ Étourdissement</option>
                                <option value="heal" {{ old('effect_type') == 'heal' ? 'selected' : '' }}>💚 Soin</option>
                                <option value="drain" {{ old('effect_type') == 'drain' ? 'selected' : '' }}>💀 Drain</option>
                                <option value="buff_attack" {{ old('effect_type') == 'buff_attack' ? 'selected' : '' }}>💪 Buff Attaque</option>
                                <option value="buff_defense" {{ old('effect_type') == 'buff_defense' ? 'selected' : '' }}>🛡️ Buff Défense</option>
                                <option value="debuff" {{ old('effect_type') == 'debuff' ? 'selected' : '' }}>📉 Debuff</option>
                            </select>
                        </div>
                        <div>
                            <label for="effect_value" class="block text-sm font-medium text-gray-300 mb-2">Valeur de l'effet</label>
                            <input type="number" name="effect_value" id="effect_value" value="{{ old('effect_value', 0) }}" min="0" max="100"
                                   class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-bold rounded-lg hover:from-red-500 hover:to-orange-500 transition">
                            ✅ Créer l'attaque
                        </button>
                        <a href="{{ route('attacks.index') }}" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500 transition">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>