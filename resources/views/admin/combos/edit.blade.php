<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('admin.combos.index') }}" class="text-gray-400 hover:text-white transition">
                    ← Retour
                </a>
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-4xl">⚡</span> Modifier le Combo
                </h1>
            </div>

            <!-- Info -->
            <div class="bg-yellow-500/20 border border-yellow-500/50 rounded-xl p-4 mb-6 text-yellow-300">
                <p class="flex items-center gap-2">
                    <span>⚠️</span>
                    Le leader doit être une des 3 cartes du combo. C'est cette carte qui pourra lancer l'attaque spéciale.
                </p>
            </div>

            @if($errors->any())
                <div class="bg-red-500/20 border border-red-500/50 rounded-xl p-4 mb-6">
                    <ul class="list-disc list-inside text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulaire -->
            <form action="{{ route('admin.combos.update', $combo) }}" method="POST" class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 p-8">
                @csrf
                @method('PUT')

                <!-- Nom -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-300 mb-2">Nom du combo *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $combo->name) }}" required
                           class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition"
                           placeholder="Ex: Trinité des Chevaliers de Bronze">
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-300 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white placeholder-gray-500 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition"
                              placeholder="Décrivez l'effet du combo...">{{ old('description', $combo->description) }}</textarea>
                </div>

                <!-- Cartes du combo -->
                <div class="mb-6 p-4 bg-white/5 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <span>🃏</span> Les 3 cartes du combo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="card1_id" class="block text-sm font-semibold text-gray-300 mb-2">Carte 1 *</label>
                            <select name="card1_id" id="card1_id" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                                <option value="">Sélectionner...</option>
                                @foreach($cards as $card)
                                    <option value="{{ $card->id }}" {{ old('card1_id', $combo->card1_id) == $card->id ? 'selected' : '' }}>
                                        {{ $card->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="card2_id" class="block text-sm font-semibold text-gray-300 mb-2">Carte 2 *</label>
                            <select name="card2_id" id="card2_id" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                                <option value="">Sélectionner...</option>
                                @foreach($cards as $card)
                                    <option value="{{ $card->id }}" {{ old('card2_id', $combo->card2_id) == $card->id ? 'selected' : '' }}>
                                        {{ $card->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="card3_id" class="block text-sm font-semibold text-gray-300 mb-2">Carte 3 *</label>
                            <select name="card3_id" id="card3_id" required
                                    class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                                <option value="">Sélectionner...</option>
                                @foreach($cards as $card)
                                    <option value="{{ $card->id }}" {{ old('card3_id', $combo->card3_id) == $card->id ? 'selected' : '' }}>
                                        {{ $card->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Leader -->
                <div class="mb-6">
                    <label for="leader_card_id" class="block text-sm font-semibold text-gray-300 mb-2">
                        <span class="flex items-center gap-2">👑 Carte Leader * <span class="text-xs text-gray-500">(doit être une des 3 cartes)</span></span>
                    </label>
                    <select name="leader_card_id" id="leader_card_id" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                        <option value="">Sélectionner le leader...</option>
                        @foreach($cards as $card)
                            <option value="{{ $card->id }}" {{ old('leader_card_id', $combo->leader_card_id) == $card->id ? 'selected' : '' }}>
                                {{ $card->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Attaque -->
                <div class="mb-6">
                    <label for="attack_id" class="block text-sm font-semibold text-gray-300 mb-2">
                        <span class="flex items-center gap-2">⚔️ Attaque spéciale *</span>
                    </label>
                    <select name="attack_id" id="attack_id" required
                            class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                        <option value="">Sélectionner une attaque...</option>
                        @foreach($attacks as $attack)
                            <option value="{{ $attack->id }}" {{ old('attack_id', $combo->attack_id) == $attack->id ? 'selected' : '' }}>
                                {{ $attack->name }} ({{ $attack->damage }} dégâts)
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Coûts -->
                <div class="mb-6 p-4 bg-white/5 rounded-xl border border-white/10">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                        <span>💰</span> Coûts supplémentaires du combo
                    </h3>
                    <p class="text-xs text-gray-400 mb-4">Ces coûts s'ajoutent aux coûts de base de l'attaque</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="endurance_cost" class="block text-sm font-semibold text-gray-300 mb-2">
                                ⚡ Endurance supplémentaire
                            </label>
                            <input type="number" name="endurance_cost" id="endurance_cost" value="{{ old('endurance_cost', $combo->endurance_cost) }}" min="0"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                        </div>

                        <div>
                            <label for="cosmos_cost" class="block text-sm font-semibold text-gray-300 mb-2">
                                🌟 Cosmos supplémentaire
                            </label>
                            <input type="number" name="cosmos_cost" id="cosmos_cost" value="{{ old('cosmos_cost', $combo->cosmos_cost) }}" min="0"
                                   class="w-full px-4 py-3 bg-white/5 border border-white/20 rounded-xl text-white focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500 transition">
                        </div>
                    </div>
                </div>

                <!-- Statut actif -->
                <div class="mb-8">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $combo->is_active) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-white/20 bg-white/5 text-yellow-500 focus:ring-yellow-500 focus:ring-offset-0">
                        <span class="text-gray-300">Combo actif</span>
                    </label>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.combos.index') }}"
                       class="px-6 py-3 bg-gray-600 text-white font-semibold rounded-xl hover:bg-gray-500 transition">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-xl hover:from-yellow-400 hover:to-orange-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                        ⚡ Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mise à jour dynamique du leader en fonction des cartes sélectionnées
        function updateLeaderOptions() {
            const card1 = document.getElementById('card1_id').value;
            const card2 = document.getElementById('card2_id').value;
            const card3 = document.getElementById('card3_id').value;
            const leaderSelect = document.getElementById('leader_card_id');
            const selectedCards = [card1, card2, card3].filter(id => id !== '');

            // Désactiver toutes les options qui ne sont pas dans les cartes sélectionnées
            Array.from(leaderSelect.options).forEach(option => {
                if (option.value === '') return;
                option.disabled = selectedCards.length > 0 && !selectedCards.includes(option.value);
            });
        }

        document.getElementById('card1_id').addEventListener('change', updateLeaderOptions);
        document.getElementById('card2_id').addEventListener('change', updateLeaderOptions);
        document.getElementById('card3_id').addEventListener('change', updateLeaderOptions);

        // Initial call
        updateLeaderOptions();
    </script>
</x-app-layout>
