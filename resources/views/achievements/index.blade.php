<x-app-layout>
    <style>
        .ach-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1rem;
        }
        .ach-card {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 1rem;
            display: flex;
            gap: 0.875rem;
            align-items: flex-start;
            transition: border-color 0.2s, background 0.2s;
        }
        .ach-card.unlocked {
            background: rgba(255,215,0,0.06);
            border-color: rgba(255,215,0,0.35);
        }
        .ach-card.claimed {
            opacity: 0.5;
            border-color: rgba(255,255,255,0.07);
        }
        .ach-icon {
            font-size: 2rem;
            line-height: 1;
            flex-shrink: 0;
            width: 2.5rem;
            text-align: center;
            filter: grayscale(1);
        }
        .ach-card.unlocked .ach-icon { filter: none; }
        .ach-body { flex: 1; min-width: 0; }
        .ach-title {
            font-weight: 700;
            font-size: 0.875rem;
            color: white;
            margin-bottom: 2px;
        }
        .ach-card.locked .ach-title { color: rgba(255,255,255,0.35); }
        .ach-desc {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.45);
            margin-bottom: 0.4rem;
        }
        .ach-reward {
            font-size: 0.7rem;
            font-weight: 700;
            color: #fbbf24;
        }
        .ach-card.locked .ach-reward { color: rgba(255,255,255,0.2); }
        .ach-date {
            font-size: 0.6rem;
            color: rgba(255,255,255,0.3);
            margin-top: 2px;
        }
        .claim-btn {
            margin-top: 0.5rem;
            padding: 0.3rem 0.75rem;
            background: linear-gradient(90deg, #eab308, #f59e0b);
            color: #1a1a1a;
            font-size: 0.7rem;
            font-weight: 700;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        .claim-btn:hover { opacity: 0.85; }
        .category-label {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            margin-bottom: 0.5rem;
            margin-top: 1.5rem;
        }
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
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        🏆 Succès & Trophées
                    </h1>
                    <p class="text-gray-400 mt-1 text-sm">
                        {{ collect($achievements)->where('unlocked', true)->count() }}
                        / {{ count($achievements) }} débloqués
                        @if($unclaimedCount > 0)
                            — <span class="text-yellow-400 font-bold">{{ $unclaimedCount }} récompense{{ $unclaimedCount > 1 ? 's' : '' }} à réclamer !</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('dashboard') }}"
                   class="px-4 py-2 bg-white/10 text-white text-sm rounded-xl hover:bg-white/20 transition">
                    ← Dashboard
                </a>
            </div>

            @php
                $categories = [
                    'combat'     => ['label' => '⚔️ Combat', 'items' => []],
                    'collection' => ['label' => '📚 Collection', 'items' => []],
                    'fidelite'   => ['label' => '🔥 Fidélité', 'items' => []],
                    'rang'       => ['label' => '🎖️ Rang', 'items' => []],
                ];
                foreach ($achievements as $a) {
                    $categories[$a['category']]['items'][] = $a;
                }
            @endphp

            @foreach($categories as $cat)
                <p class="category-label">{{ $cat['label'] }}</p>
                <div class="ach-grid">
                    @foreach($cat['items'] as $a)
                    @php
                        $cls = $a['unlocked'] ? ($a['reward_claimed'] ? 'unlocked claimed' : 'unlocked') : 'locked';
                    @endphp
                    <div class="ach-card {{ $cls }}" id="ach-{{ $a['achievement_id'] ?? $a['slug'] }}">
                        <div class="ach-icon">{{ $a['icon'] }}</div>
                        <div class="ach-body">
                            <div class="ach-title">{{ $a['title'] }}</div>
                            <div class="ach-desc">{{ $a['description'] }}</div>
                            <div class="ach-reward">+{{ number_format($a['reward_coins']) }} 🪙</div>
                            @if($a['unlocked'])
                                <div class="ach-date">Débloqué le {{ \Carbon\Carbon::parse($a['unlocked_at'])->format('d/m/Y') }}</div>
                                @if(!$a['reward_claimed'])
                                    <button class="claim-btn"
                                        onclick="claimAchievement({{ $a['achievement_id'] }}, this)">
                                        Réclamer
                                    </button>
                                @else
                                    <div class="text-xs text-gray-600 mt-1">✓ Réclamé</div>
                                @endif
                            @else
                                <div class="text-xs text-gray-600 mt-1">🔒 Non débloqué</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endforeach

        </div>
    </div>

    <script>
    function claimAchievement(id, btn) {
        btn.disabled = true;
        btn.textContent = '...';

        fetch('/achievements/' + id + '/claim', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                var card = btn.closest('.ach-card');
                btn.replaceWith(Object.assign(document.createElement('div'), {
                    className: 'text-xs text-gray-600 mt-1',
                    textContent: '✓ Réclamé'
                }));
                card.classList.add('claimed');

                var balanceEl = document.getElementById('userCoins');
                if (balanceEl) balanceEl.textContent = data.new_balance.toLocaleString('fr-FR');
            } else {
                btn.disabled = false;
                btn.textContent = 'Erreur';
            }
        })
        .catch(() => { btn.disabled = false; btn.textContent = 'Erreur'; });
    }
    </script>
</x-app-layout>
