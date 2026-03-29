<x-app-layout>
    <style>
        .listing-card {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            overflow: hidden;
            transform: translateZ(0);
            isolation: isolate;
            transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
        }
        .listing-card:hover {
            border-color: rgba(251,191,36,0.4);
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.5);
        }
        .listing-img-wrap {
            aspect-ratio: 2.5 / 3;
            overflow: hidden;
            position: relative;
        }
        .listing-img {
            width: 100%; height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }
        .listing-img-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem;
        }
        .rarity-badge {
            display: inline-block;
            font-size: 0.55rem; font-weight: 800;
            padding: 2px 6px; border-radius: 4px; text-transform: uppercase;
        }
        .rarity-common    { background: rgba(100,100,100,0.6); color: #ccc; }
        .rarity-rare      { background: rgba(59,130,246,0.6);  color: #bfdbfe; }
        .rarity-epic      { background: rgba(139,92,246,0.6);  color: #ddd6fe; }
        .rarity-legendary { background: rgba(245,158,11,0.6);  color: #fef3c7; }
        .rarity-mythic    { background: rgba(236,72,153,0.6);  color: #fce7f3; }
        .timer { font-size: 0.7rem; color: #f87171; font-weight: 700; }
        .timer.safe { color: #6ee7b7; }
    </style>

    <div class="min-h-screen relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-800 via-gray-900 to-black"></div>
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-[0.10]" loading="eager">
        </div>

        <div class="relative z-9 max-w-7xl mx-auto px-4 sm:px-6 py-10">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">🏛️ Hall des Enchères</h1>
                    <p class="text-gray-400 mt-1 text-sm">
                        {{ $listings->total() }} annonce{{ $listings->total() > 1 ? 's' : '' }} active{{ $listings->total() > 1 ? 's' : '' }}
                    </p>
                </div>
                <a href="{{ route('marketplace.create') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition shadow-lg shadow-yellow-500/30">
                    ➕ Mettre en vente
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-500/20 border border-green-500/40 rounded-xl text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Filtres --}}
            <form method="GET" class="flex flex-wrap gap-3 mb-6 items-end">
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Rareté</label>
                    <select name="rarity" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg px-3 py-2 focus:outline-none">
                        <option value="">Toutes</option>
                        @foreach(['common','rare','epic','legendary','mythic'] as $r)
                        <option value="{{ $r }}" {{ request('rarity') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Faction</label>
                    <select name="faction" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg px-3 py-2 focus:outline-none">
                        <option value="">Toutes</option>
                        @foreach($factions as $f)
                        <option value="{{ $f->id }}" {{ request('faction') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1">Trier par</label>
                    <select name="sort" class="bg-white/10 border border-white/20 text-white text-sm rounded-lg px-3 py-2 focus:outline-none">
                        <option value="">Plus récentes</option>
                        <option value="ending_soon" {{ request('sort') === 'ending_soon' ? 'selected' : '' }}>Se termine bientôt</option>
                        <option value="price_asc"   {{ request('sort') === 'price_asc'   ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc"  {{ request('sort') === 'price_desc'  ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-white/10 text-white text-sm rounded-lg hover:bg-white/20 transition">
                    Filtrer
                </button>
                @if(request()->hasAny(['rarity','faction','sort']))
                <a href="{{ route('marketplace.index') }}" class="px-4 py-2 text-gray-400 text-sm hover:text-white transition">
                    Réinitialiser
                </a>
                @endif
            </form>

            {{-- Grille des annonces --}}
            @if($listings->isEmpty())
                <div class="text-center py-24 text-gray-500">
                    <div class="text-6xl mb-4">🏛️</div>
                    <p class="text-lg">Aucune annonce active pour le moment.</p>
                    <a href="{{ route('marketplace.create') }}"
                       class="inline-block mt-4 px-6 py-2 bg-yellow-500 text-gray-900 font-bold rounded-xl hover:bg-yellow-400 transition">
                        Soyez le premier à vendre !
                    </a>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                    @foreach($listings as $listing)
                    <a href="{{ route('marketplace.show', $listing) }}" class="listing-card block">

                        {{-- Image carte --}}
                        <div class="listing-img-wrap" style="background: linear-gradient(135deg, {{ $listing->card->faction->color_primary ?? '#2d1b69' }}, {{ $listing->card->faction->color_secondary ?? '#4c1d95' }});">
                            @if($listing->card->image_primary)
                                <img src="{{ Storage::url($listing->card->image_primary) }}"
                                     alt="{{ $listing->card->name }}"
                                     class="listing-img" loading="lazy">
                            @else
                                <div class="listing-img-placeholder">⚔️</div>
                            @endif
                        </div>

                        {{-- Infos --}}
                        <div class="p-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="rarity-badge rarity-{{ $listing->card->rarity }}">{{ $listing->card->rarity }}</span>
                                <span class="timer {{ $listing->expires_at->diffInHours() > 12 ? 'safe' : '' }}">
                                    {{ $listing->timeRemaining() }}
                                </span>
                            </div>
                            <div class="text-white text-xs font-bold truncate">{{ $listing->card->name }}</div>
                            <div class="text-gray-400 text-xs truncate">{{ $listing->seller->name }}</div>

                            <div class="mt-2 space-y-1">
                                @if($listing->current_bid > 0)
                                <div class="text-xs">
                                    <span class="text-gray-500">Enchère :</span>
                                    <span class="text-yellow-400 font-bold">{{ number_format($listing->current_bid) }} 🪙</span>
                                </div>
                                @else
                                <div class="text-xs">
                                    <span class="text-gray-500">Départ :</span>
                                    <span class="text-white font-bold">{{ number_format($listing->starting_price) }} 🪙</span>
                                </div>
                                @endif
                                <div class="text-xs">
                                    <span class="text-gray-500">Achat :</span>
                                    <span class="text-green-400 font-bold">{{ number_format($listing->buyout_price) }} 🪙</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                {{ $listings->links() }}
            @endif

        </div>
    </div>
</x-app-layout>
