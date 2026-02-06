<x-app-layout>
    <style>
        /* ========================================
           FOND COSMOS
        ======================================== */
        .cosmos-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background:
                radial-gradient(ellipse at 20% 80%, rgba(34, 197, 94, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(5, 150, 105, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #0a1a0a 0%, #0a2a1a 50%, #0a1a1a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(2px 2px at 20px 30px, #10B981, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(34,197,94,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #059669, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(16,185,129,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #10B981, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* ========================================
           CARTES VENTE
        ======================================== */
        .sell-card {
            position: relative;
            background: #1a1a2e;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 3px solid rgba(255, 255, 255, 0.1);
            aspect-ratio: 2.5 / 4;
            cursor: pointer;
        }

        .sell-card:hover {
            transform: translateY(-10px) scale(1.03);
        }

        /* Bordures par rarete */
        .sell-card.rarity-common { border-color: #6B7280; }
        .sell-card.rarity-rare {
            border-color: #3B82F6;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }
        .sell-card.rarity-epic {
            border-color: #8B5CF6;
            box-shadow: 0 0 25px rgba(139, 92, 246, 0.5);
        }
        .sell-card.rarity-legendary {
            border-color: #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.6);
            animation: legendaryPulse 2s ease-in-out infinite;
        }
        .sell-card.rarity-mythic {
            border: 3px solid transparent;
            background: linear-gradient(#1a1a2e, #1a1a2e) padding-box,
                        linear-gradient(135deg, #FF006E, #8338EC, #3A86FF, #FF006E) border-box;
            box-shadow: 0 0 40px rgba(131, 56, 236, 0.6);
            animation: mythicPulse 3s ease-in-out infinite;
        }

        @keyframes legendaryPulse {
            0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.8); }
        }

        @keyframes mythicPulse {
            0%, 100% { box-shadow: 0 0 40px rgba(131, 56, 236, 0.6); }
            50% { box-shadow: 0 0 60px rgba(58, 134, 255, 0.8); }
        }

        /* Image de la carte */
        .card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .card-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            background: linear-gradient(180deg, var(--color1, #333), var(--color2, #555));
        }

        /* Overlay */
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(0,0,0,0.5) 0%, transparent 30%, transparent 50%, rgba(0,0,0,0.85) 100%);
            z-index: 2;
            pointer-events: none;
        }

        /* Badge quantite */
        .quantity-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.85);
            color: #22C55E;
            font-weight: 800;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            border: 1px solid rgba(34, 197, 94, 0.5);
            z-index: 10;
        }

        /* Badge prix */
        .price-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #10B981, #059669);
            color: #fff;
            font-weight: 900;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            z-index: 10;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.5);
        }

        /* Infos en bas */
        .card-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10;
            padding: 10px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
        }

        .card-name {
            font-size: 0.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-rarity {
            font-size: 0.6rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: capitalize;
        }

        /* ========================================
           MODAL DE VENTE
        ======================================== */
        .sell-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .sell-modal.active {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(180deg, #1a2e1a 0%, #0f1a0f 100%);
            border: 2px solid rgba(34, 197, 94, 0.3);
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }

        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(255, 0, 0, 0.5);
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 900;
            color: #10B981;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* Selecteur de quantite */
        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #10B981;
            background: transparent;
            color: #10B981;
            font-size: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qty-btn:hover:not(:disabled) {
            background: #10B981;
            color: white;
        }

        .qty-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .qty-display {
            font-size: 2rem;
            font-weight: 900;
            color: white;
            min-width: 60px;
            text-align: center;
        }

        /* Recap de la vente */
        .sell-recap {
            background: rgba(0, 0, 0, 0.3);
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
        }

        .recap-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .recap-row:last-child {
            border-bottom: none;
        }

        .recap-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .recap-value {
            font-weight: 700;
            color: white;
        }

        .recap-value.coins {
            color: #10B981;
        }

        /* Bouton de vente */
        .sell-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #10B981, #059669);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 900;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .sell-btn:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 0 30px rgba(16, 185, 129, 0.6);
        }

        .sell-btn:disabled {
            background: #666;
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Section vide */
        .empty-section {
            text-align: center;
            padding: 3rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-section .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Header stats */
        .header-stats {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .stat-badge {
            background: rgba(0, 0, 0, 0.5);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-badge .value {
            font-weight: 700;
            color: #10B981;
        }

        /* Prix par rarete */
        .price-table {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .price-item {
            text-align: center;
            padding: 0.5rem;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        .price-item .rarity-name {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.6);
            text-transform: capitalize;
        }

        .price-item .rarity-price {
            font-weight: 700;
            color: #10B981;
        }

        .price-item.rarity-common .rarity-name { color: #6B7280; }
        .price-item.rarity-rare .rarity-name { color: #3B82F6; }
        .price-item.rarity-epic .rarity-name { color: #8B5CF6; }
        .price-item.rarity-legendary .rarity-name { color: #FFD700; }
        .price-item.rarity-mythic .rarity-name { color: #FF006E; }
    </style>

    <div class="cosmos-bg">
        <div class="stars"></div>
    </div>

    <div class="min-h-screen relative z-10 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="text-4xl">&#128176;</span> Vente de Cartes
                    </h1>
                    <p class="text-gray-400 mt-2">Revendez vos cartes contre des pieces d'or</p>

                    <!-- Prix par rarete -->
                    <div class="price-table mt-4">
                        @foreach($sellPrices as $rarity => $price)
                            <div class="price-item rarity-{{ $rarity }}">
                                <div class="rarity-name">{{ $rarity }}</div>
                                <div class="rarity-price">{{ $price }} po</div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="header-stats">
                    <div class="stat-badge">
                        <span class="text-gray-400">Solde:</span>
                        <span class="value" id="sell-coins">{{ number_format($userCoins) }} po</span>
                    </div>
                    <div class="stat-badge">
                        <span class="text-gray-400">Cartes possedees:</span>
                        <span class="value">{{ $sellableCards->sum('pivot.quantity') }}</span>
                    </div>
                </div>
            </div>

            <!-- Cartes vendables -->
            <div class="mb-12">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <span class="text-2xl">&#127183;</span> Votre collection
                </h2>

                @if($sellableCards->isEmpty())
                    <div class="empty-section">
                        <div class="icon">&#128194;</div>
                        <p>Vous n'avez aucune carte a vendre.</p>
                        <p class="text-sm mt-2">Achetez des boosters pour obtenir des cartes !</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                        @foreach($sellableCards as $card)
                            <div class="sell-card rarity-{{ $card->rarity }}"
                                 onclick="showSellPreview({{ $card->id }})"
                                 data-card-id="{{ $card->id }}"
                                 style="--color1: {{ $card->faction?->color_primary ?? '#333' }}; --color2: {{ $card->faction?->color_secondary ?? '#555' }}">

                                <!-- Badge prix -->
                                <div class="price-badge">
                                    {{ $sellPrices[$card->rarity] ?? 5 }} po
                                </div>

                                <!-- Badge quantite -->
                                <div class="quantity-badge">
                                    x{{ $card->pivot->quantity ?? 1 }}
                                </div>

                                <!-- Image -->
                                <div class="card-image">
                                    @if($card->image_primary)
                                        <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                    @else
                                        <div class="card-placeholder">&#9876;</div>
                                    @endif
                                </div>

                                <div class="card-overlay"></div>

                                <!-- Infos -->
                                <div class="card-info">
                                    <div class="card-name">{{ $card->name }}</div>
                                    <div class="card-rarity">{{ $card->rarity }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de vente -->
    <div id="sellModal" class="sell-modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeSellModal()">&times;</button>
            <h3 class="modal-title">&#128176; Vendre une carte</h3>

            <div id="modalContent">
                <!-- Contenu charge dynamiquement -->
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        let currentCardData = null;
        let selectedQuantity = 1;

        // Mise a jour du solde (page + navigation)
        function updateAllBalances(newBalance) {
            const formattedBalance = newBalance.toLocaleString();

            // Mettre a jour le solde sur la page
            const sellCoins = document.getElementById('sell-coins');
            if (sellCoins) sellCoins.textContent = formattedBalance + ' po';

            // Mettre a jour le solde dans la navigation (desktop)
            const navDesktop = document.getElementById('nav-coins-desktop');
            if (navDesktop) navDesktop.textContent = formattedBalance;

            // Mettre a jour le solde dans la navigation (mobile)
            const navMobile = document.getElementById('nav-coins-mobile');
            if (navMobile) navMobile.textContent = formattedBalance;
        }

        async function showSellPreview(cardId) {
            const modal = document.getElementById('sellModal');
            const content = document.getElementById('modalContent');

            content.innerHTML = '<div class="text-center text-white py-8">Chargement...</div>';
            modal.classList.add('active');

            try {
                const response = await fetch('/sell/preview', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ card_id: cardId })
                });

                const data = await response.json();

                if (data.error) {
                    content.innerHTML = `<div class="text-red-400 text-center py-8">${data.error}</div>`;
                    return;
                }

                currentCardData = data;
                selectedQuantity = 1;
                renderSellPreview();
            } catch (error) {
                content.innerHTML = `<div class="text-red-400 text-center py-8">Erreur de chargement</div>`;
            }
        }

        function renderSellPreview() {
            const content = document.getElementById('modalContent');
            const data = currentCardData;
            const totalPrice = data.price_per_card * selectedQuantity;

            content.innerHTML = `
                <div class="text-center mb-4">
                    <h4 class="text-xl font-bold text-white">${data.card.name}</h4>
                    <p class="text-gray-400">${data.card.rarity.charAt(0).toUpperCase() + data.card.rarity.slice(1)} - ${data.card.faction || 'Sans faction'}</p>
                </div>

                <div class="text-center text-gray-400 mb-4">
                    Vous possedez <span class="text-green-400 font-bold">${data.quantity}</span> exemplaire(s)
                </div>

                <div class="quantity-selector">
                    <button class="qty-btn" onclick="changeQuantity(-1)" ${selectedQuantity <= 1 ? 'disabled' : ''}>-</button>
                    <div class="qty-display">${selectedQuantity}</div>
                    <button class="qty-btn" onclick="changeQuantity(1)" ${selectedQuantity >= data.quantity ? 'disabled' : ''}>+</button>
                </div>

                <div class="sell-recap">
                    <div class="recap-row">
                        <span class="recap-label">Prix unitaire</span>
                        <span class="recap-value">${data.price_per_card} po</span>
                    </div>
                    <div class="recap-row">
                        <span class="recap-label">Quantite</span>
                        <span class="recap-value">x${selectedQuantity}</span>
                    </div>
                    <div class="recap-row">
                        <span class="recap-label">Total</span>
                        <span class="recap-value coins">+${totalPrice} po</span>
                    </div>
                </div>

                <button class="sell-btn" onclick="performSell(${data.card.id})">
                    &#128176; Vendre pour ${totalPrice} po
                </button>
            `;
        }

        function changeQuantity(delta) {
            const newQty = selectedQuantity + delta;
            if (newQty >= 1 && newQty <= currentCardData.quantity) {
                selectedQuantity = newQty;
                renderSellPreview();
            }
        }

        async function performSell(cardId) {
            const btn = document.querySelector('.sell-btn');
            btn.disabled = true;
            btn.textContent = 'Vente en cours...';

            try {
                const response = await fetch('/sell/sell', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        card_id: cardId,
                        quantity: selectedQuantity
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Mettre a jour tous les soldes (navigation + page)
                    updateAllBalances(data.new_balance);

                    const content = document.getElementById('modalContent');
                    content.innerHTML = `
                        <div class="text-center py-8">
                            <div class="text-6xl mb-4">&#127881;</div>
                            <h4 class="text-2xl font-bold text-green-400 mb-2">Vente reussie !</h4>
                            <p class="text-white">+<span class="text-green-400 font-bold">${data.coins_earned}</span> pieces d'or</p>
                            <p class="text-gray-400 mt-2">Nouveau solde: ${data.new_balance.toLocaleString()} po</p>
                            <button class="sell-btn mt-6" onclick="location.reload()">
                                Continuer
                            </button>
                        </div>
                    `;
                } else {
                    alert(data.message || 'Erreur lors de la vente');
                    btn.disabled = false;
                    btn.textContent = '&#128176; Vendre';
                }
            } catch (error) {
                alert('Erreur de connexion');
                btn.disabled = false;
                btn.textContent = '&#128176; Vendre';
            }
        }

        function closeSellModal() {
            document.getElementById('sellModal').classList.remove('active');
            currentCardData = null;
            selectedQuantity = 1;
        }

        // Fermer modal avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSellModal();
            }
        });

        // Fermer modal en cliquant en dehors
        document.getElementById('sellModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeSellModal();
            }
        });
    </script>
</x-app-layout>
