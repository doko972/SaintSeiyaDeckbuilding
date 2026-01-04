<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nouveau Deck') }}
            </h2>
            <a href="{{ route('decks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('decks.store') }}" id="deckForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- Colonne gauche : Infos du deck -->
                    <div class="lg:col-span-1">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden sticky top-8">
                            <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600">
                                <h3 class="text-xl font-bold text-white">🎴 Informations du deck</h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Nom -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom du deck *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                           placeholder="Ex: Les Chevaliers d'Or"
                                           class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                              placeholder="Décrivez votre stratégie..."
                                              class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">{{ old('description') }}</textarea>
                                </div>

                                <!-- Deck actif -->
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                                           class="w-5 h-5 bg-white/10 border-white/30 rounded text-purple-600 focus:ring-purple-500">
                                    <label for="is_active" class="text-gray-300">Définir comme deck actif</label>
                                </div>

                                <!-- Résumé -->
                                <div class="bg-black/30 rounded-xl p-4 border border-white/10">
                                    <h4 class="text-sm font-semibold text-gray-400 mb-3">📊 Résumé</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Cartes sélectionnées</span>
                                            <span class="text-white font-bold" id="totalCards">0</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-400">Coût total</span>
                                            <span class="text-purple-400 font-bold" id="totalCost">0</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bouton submit -->
                                <button type="submit" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-[1.02]">
                                    ✅ Créer le deck
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite : Sélection des cartes -->
                    <div class="lg:col-span-2">
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                            <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-600">
                                <h3 class="text-xl font-bold text-white">🃏 Ma Collection</h3>
                                <p class="text-white/70 text-sm">Sélectionnez les cartes à ajouter au deck</p>
                            </div>

                            <div class="p-6">
                                @if($collection->isEmpty())
                                    <div class="text-center py-12">
                                        <div class="text-6xl mb-4">📭</div>
                                        <h3 class="text-xl font-semibold text-white mb-2">Collection vide</h3>
                                        <p class="text-gray-400 mb-6">Achetez des boosters pour obtenir des cartes !</p>
                                        <a href="{{ route('shop.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition">
                                            🛒 Aller à la boutique
                                        </a>
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($collection as $card)
                                            <div class="card-selector group" data-card-id="{{ $card->id }}" data-cost="{{ $card->cost }}" data-max="{{ $card->pivot->quantity }}">
                                                <div class="relative bg-white/5 rounded-xl border-2 border-white/10 overflow-hidden transition-all duration-300 hover:border-purple-500/50 cursor-pointer"
                                                     style="--color1: {{ $card->faction->color_primary }}; --color2: {{ $card->faction->color_secondary }};">
                                                    
                                                    <!-- Image -->
                                                    <div class="aspect-[4/5] overflow-hidden"
                                                         style="background: linear-gradient(135deg, var(--color1), var(--color2));">
                                                        @if($card->image_primary)
                                                            <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover object-top">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-4xl">🃏</div>
                                                        @endif
                                                    </div>

                                                    <!-- Badge rareté -->
                                                    <div class="absolute top-2 right-2">
                                                        <span class="px-2 py-1 text-xs font-bold rounded
                                                            @switch($card->rarity)
                                                                @case('common') bg-gray-600 @break
                                                                @case('rare') bg-blue-600 @break
                                                                @case('epic') bg-purple-600 @break
                                                                @case('legendary') bg-gradient-to-r from-yellow-500 to-orange-500 @break
                                                            @endswitch text-white">
                                                            {{ $card->pivot->quantity }}x
                                                        </span>
                                                    </div>

                                                    <!-- Overlay de sélection -->
                                                    <div class="absolute inset-0 bg-purple-600/50 opacity-0 transition-opacity flex items-center justify-center selected-overlay">
                                                        <span class="text-4xl">✓</span>
                                                    </div>

                                                    <!-- Infos -->
                                                    <div class="p-3 bg-black/50">
                                                        <h4 class="text-sm font-bold text-white truncate">{{ $card->name }}</h4>
                                                        <div class="flex justify-between items-center mt-1">
                                                            <span class="text-xs text-gray-400">{{ $card->faction->name }}</span>
                                                            <span class="text-xs text-purple-400 font-bold">💎 {{ $card->cost }}</span>
                                                        </div>
                                                    </div>

                                                    <!-- Contrôles de quantité -->
                                                    <div class="quantity-controls hidden absolute bottom-16 left-0 right-0 bg-black/80 p-2 flex items-center justify-center gap-3">
                                                        <button type="button" class="qty-btn qty-minus w-8 h-8 rounded-full bg-red-600 text-white font-bold hover:bg-red-500">-</button>
                                                        <span class="qty-display text-white font-bold text-lg w-8 text-center">0</span>
                                                        <button type="button" class="qty-btn qty-plus w-8 h-8 rounded-full bg-green-600 text-white font-bold hover:bg-green-500">+</button>
                                                        <input type="hidden" name="cards[{{ $card->id }}]" value="0" class="qty-input">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardSelectors = document.querySelectorAll('.card-selector');
            let totalCards = 0;
            let totalCost = 0;

            cardSelectors.forEach(selector => {
                const cardId = selector.dataset.cardId;
                const cardCost = parseInt(selector.dataset.cost);
                const maxQty = parseInt(selector.dataset.max);
                const container = selector.querySelector('.relative');
                const overlay = selector.querySelector('.selected-overlay');
                const controls = selector.querySelector('.quantity-controls');
                const qtyDisplay = selector.querySelector('.qty-display');
                const qtyInput = selector.querySelector('.qty-input');
                const minusBtn = selector.querySelector('.qty-minus');
                const plusBtn = selector.querySelector('.qty-plus');

                let quantity = 0;

                // Clic sur la carte
                container.addEventListener('click', function(e) {
                    if (e.target.classList.contains('qty-btn') || e.target.closest('.qty-btn')) return;
                    
                    if (quantity === 0 && maxQty > 0) {
                        quantity = 1;
                        updateDisplay();
                    }
                });

                // Bouton moins
                minusBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (quantity > 0) {
                        quantity--;
                        updateDisplay();
                    }
                });

                // Bouton plus
                plusBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (quantity < maxQty) {
                        quantity++;
                        updateDisplay();
                    }
                });

                function updateDisplay() {
                    qtyDisplay.textContent = quantity;
                    qtyInput.value = quantity;

                    if (quantity > 0) {
                        overlay.style.opacity = '1';
                        controls.classList.remove('hidden');
                        container.classList.add('ring-2', 'ring-purple-500');
                    } else {
                        overlay.style.opacity = '0';
                        controls.classList.add('hidden');
                        container.classList.remove('ring-2', 'ring-purple-500');
                    }

                    updateTotals();
                }

                function updateTotals() {
                    totalCards = 0;
                    totalCost = 0;

                    document.querySelectorAll('.qty-input').forEach(input => {
                        const qty = parseInt(input.value) || 0;
                        const cost = parseInt(input.closest('.card-selector').dataset.cost) || 0;
                        totalCards += qty;
                        totalCost += qty * cost;
                    });

                    document.getElementById('totalCards').textContent = totalCards;
                    document.getElementById('totalCost').textContent = totalCost;
                }
            });
        });
    </script>

    <style>
        .card-selector:hover .quantity-controls {
            display: flex !important;
        }
        .card-selector .selected-overlay {
            pointer-events: none;
        }
    </style>
</x-app-layout>