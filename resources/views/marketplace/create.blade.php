<x-app-layout>
    <style>
        .card-picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: 0.75rem;
        }
        .card-pick {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            aspect-ratio: 2.5 / 4;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .card-pick:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.5); }
        .card-pick.selected { border-color: #fbbf24; box-shadow: 0 0 20px rgba(251,191,36,0.5); }
        .card-pick img { width: 100%; height: 100%; object-fit: cover; object-position: center top; }
        .card-pick-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2rem; }
        .card-pick-name {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.9), transparent);
            padding: 0.5rem 0.3rem 0.3rem;
            font-size: 0.55rem; font-weight: 700; color: white; text-align: center;
        }
        .card-pick-rarity {
            position: absolute; top: 4px; left: 4px;
            font-size: 0.5rem; font-weight: 800; padding: 1px 5px;
            border-radius: 4px; text-transform: uppercase;
        }
        .rarity-common    { background: rgba(100,100,100,0.8); color: #ccc; }
        .rarity-rare      { background: rgba(59,130,246,0.8);  color: #bfdbfe; }
        .rarity-epic      { background: rgba(139,92,246,0.8);  color: #ddd6fe; }
        .rarity-legendary { background: rgba(245,158,11,0.8);  color: #fef3c7; }
        .rarity-mythic    { background: rgba(236,72,153,0.8);  color: #fce7f3; }
        .price-panel {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.5rem;
        }
        .price-input {
            width: 100%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 0.65rem 1rem;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            outline: none;
            transition: border-color 0.2s;
        }
        .price-input:focus { border-color: rgba(251,191,36,0.6); }
        .price-hint { font-size: 0.7rem; color: rgba(255,255,255,0.35); margin-top: 4px; }
    </style>

    <div class="min-h-screen relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-800 via-gray-900 to-black"></div>
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-[0.10]" loading="eager">
        </div>

        <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 py-10">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">🏛️ Mettre en vente</h1>
                    <p class="text-gray-400 mt-1 text-sm">La carte sera retirée de votre collection pendant 48h</p>
                </div>
                <a href="{{ route('marketplace.index') }}"
                   class="px-4 py-2 bg-white/10 text-white text-sm rounded-xl hover:bg-white/20 transition">
                    ← Retour
                </a>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-500/20 border border-red-500/40 rounded-xl text-red-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if($cards->isEmpty())
                <div class="text-center py-20 text-gray-500">
                    <div class="text-5xl mb-4">📭</div>
                    <p>Vous n'avez aucune carte disponible à mettre en vente.</p>
                    <a href="{{ route('shop.index') }}" class="inline-block mt-4 px-6 py-2 bg-yellow-500 text-gray-900 font-bold rounded-xl hover:bg-yellow-400 transition">
                        Acheter des boosters
                    </a>
                </div>
            @else
            <form method="POST" action="{{ route('marketplace.store') }}" id="listingForm">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Sélecteur de carte --}}
                    <div class="lg:col-span-2">
                        <h2 class="text-white font-bold mb-3">1. Choisissez une carte</h2>

                        {{-- Filtre rareté --}}
                        <div class="flex gap-2 flex-wrap mb-4">
                            <button type="button" onclick="filterRarity(null)" class="rarity-filter active px-3 py-1 rounded-full text-xs font-bold border border-white/20 bg-white/10 text-white">Toutes</button>
                            @foreach(['common','rare','epic','legendary','mythic'] as $r)
                            <button type="button" onclick="filterRarity('{{ $r }}')" class="rarity-filter px-3 py-1 rounded-full text-xs font-bold border border-white/20 text-gray-400 bg-white/5">
                                {{ ucfirst($r) }}
                            </button>
                            @endforeach
                        </div>

                        <div class="card-picker-grid" id="cardGrid">
                            @foreach($cards as $card)
                            <div class="card-pick"
                                 data-card-id="{{ $card->id }}"
                                 data-rarity="{{ $card->rarity }}"
                                 data-name="{{ $card->name }}"
                                 data-min="{{ $minPrices[$card->rarity] ?? 50 }}"
                                 data-color1="{{ $card->faction->color_primary ?? '#2d1b69' }}"
                                 data-color2="{{ $card->faction->color_secondary ?? '#4c1d95' }}"
                                 onclick="selectCard(this)"
                                 style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#2d1b69' }}, {{ $card->faction->color_secondary ?? '#4c1d95' }});">
                                @if($card->image_primary)
                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" loading="lazy">
                                @else
                                    <div class="card-pick-placeholder">⚔️</div>
                                @endif
                                <span class="card-pick-rarity rarity-{{ $card->rarity }}">{{ $card->rarity }}</span>
                                <div class="card-pick-name">{{ $card->name }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Panel prix --}}
                    <div class="lg:col-span-1">
                        <h2 class="text-white font-bold mb-3">2. Définissez les prix</h2>

                        <div class="price-panel" id="pricePanel">
                            {{-- Carte sélectionnée --}}
                            <div id="selectedCardInfo" class="hidden mb-4 p-3 rounded-xl text-center" style="background: rgba(255,215,0,0.1); border: 1px solid rgba(255,215,0,0.3);">
                                <div class="text-2xl mb-1" id="selectedCardIcon">⚔️</div>
                                <div class="text-white font-bold text-sm" id="selectedCardName">—</div>
                                <div class="text-gray-400 text-xs" id="selectedCardRarity">—</div>
                            </div>

                            <div id="noCardMsg" class="text-gray-500 text-sm text-center py-4">
                                ← Sélectionnez une carte
                            </div>

                            <div id="priceFields" class="hidden space-y-4">
                                <input type="hidden" name="card_id" id="cardIdInput">

                                <div>
                                    <label class="text-white text-sm font-bold block mb-1">Prix de départ 🪙</label>
                                    <input type="number" name="starting_price" id="startingPrice"
                                           class="price-input" min="1" placeholder="Ex: 500"
                                           oninput="updateBuyoutMin()" value="{{ old('starting_price') }}">
                                    <p class="price-hint" id="minPriceHint">Minimum : — 🪙</p>
                                </div>

                                <div>
                                    <label class="text-white text-sm font-bold block mb-1">Prix d'achat immédiat 🪙</label>
                                    <input type="number" name="buyout_price" id="buyoutPrice"
                                           class="price-input" min="1" placeholder="Ex: 1500"
                                           value="{{ old('buyout_price') }}">
                                    <p class="price-hint">Doit être supérieur au prix de départ</p>
                                </div>

                                <div class="bg-white/5 rounded-xl p-3 text-xs text-gray-400 space-y-1">
                                    <div>⏱️ Durée : <span class="text-white font-bold">48 heures</span></div>
                                    <div>💸 Commission : <span class="text-white font-bold">10%</span> prélevée sur la vente</div>
                                    <div id="payoutPreview" class="hidden">
                                        💰 Vous recevrez : <span class="text-yellow-400 font-bold" id="payoutAmount">—</span> 🪙
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition text-sm">
                                    🏛️ Mettre en vente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </div>

    <script>
    var selectedRarity = null;
    var activeRarityFilter = null;
    var minPrices = @json($minPrices);

    function selectCard(el) {
        document.querySelectorAll('.card-pick').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');

        var id      = el.dataset.cardId;
        var name    = el.dataset.name;
        var rarity  = el.dataset.rarity;
        var min     = parseInt(el.dataset.min);

        document.getElementById('cardIdInput').value = id;
        document.getElementById('selectedCardName').textContent = name;
        document.getElementById('selectedCardRarity').textContent = rarity.charAt(0).toUpperCase() + rarity.slice(1);
        document.getElementById('selectedCardInfo').classList.remove('hidden');
        document.getElementById('noCardMsg').classList.add('hidden');
        document.getElementById('priceFields').classList.remove('hidden');

        document.getElementById('startingPrice').min = min;
        document.getElementById('minPriceHint').textContent = 'Minimum : ' + min.toLocaleString('fr-FR') + ' 🪙';

        updateBuyoutMin();
    }

    function updateBuyoutMin() {
        var start   = parseInt(document.getElementById('startingPrice').value) || 0;
        var buyout  = document.getElementById('buyoutPrice');
        buyout.min  = start + 1;

        // Aperçu du payout vendeur
        var buyoutVal = parseInt(buyout.value) || 0;
        if (buyoutVal > start && start > 0) {
            var payout = Math.floor(buyoutVal * 0.9);
            document.getElementById('payoutAmount').textContent = payout.toLocaleString('fr-FR');
            document.getElementById('payoutPreview').classList.remove('hidden');
        } else {
            document.getElementById('payoutPreview').classList.add('hidden');
        }
    }

    function filterRarity(rarity) {
        activeRarityFilter = rarity;
        document.querySelectorAll('.rarity-filter').forEach(b => b.classList.remove('active', 'bg-white/10', 'text-white'));
        event.target.classList.add('active', 'bg-white/10', 'text-white');

        document.querySelectorAll('.card-pick').forEach(card => {
            card.style.display = (!rarity || card.dataset.rarity === rarity) ? '' : 'none';
        });
    }

    document.getElementById('buyoutPrice')?.addEventListener('input', updateBuyoutMin);
    </script>
</x-app-layout>
