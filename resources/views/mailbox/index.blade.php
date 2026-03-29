<x-app-layout>
    <style>
        .msg-row {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 1rem 1.2rem;
            transition: border-color 0.2s, background 0.2s;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        .msg-row.unread {
            background: rgba(255,255,255,0.07);
            border-color: rgba(251,191,36,0.2);
        }
        .msg-row:hover { border-color: rgba(255,255,255,0.2); }
        .msg-icon {
            width: 2.5rem; height: 2.5rem; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; shrink: 0; flex-shrink: 0;
        }
        .unread-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #fbbf24; flex-shrink: 0; margin-top: 6px;
        }
        .type-bid_received   { background: rgba(59,130,246,0.2); }
        .type-outbid         { background: rgba(239,68,68,0.2); }
        .type-auction_won    { background: rgba(245,158,11,0.2); }
        .type-auction_sold   { background: rgba(34,197,94,0.2); }
        .type-buyout         { background: rgba(34,197,94,0.2); }
        .type-offer_received { background: rgba(139,92,246,0.2); }
        .type-offer_accepted { background: rgba(34,197,94,0.2); }
        .type-offer_rejected { background: rgba(239,68,68,0.2); }
        .type-listing_expired{ background: rgba(107,114,128,0.2); }
        .msg-body { font-size: 0.82rem; color: #9ca3af; line-height: 1.5; }
        .msg-body strong { color: #e5e7eb; }
    </style>

    <div class="min-h-screen relative">
        <div class="fixed inset-0 z-0 pointer-events-none">
            <div class="absolute inset-0 bg-gradient-to-b from-gray-800 via-gray-900 to-black"></div>
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-[0.10]" loading="eager">
        </div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 sm:px-6 py-10">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white">📬 Boîte aux lettres</h1>
                    @php $unreadCount = $messages->getCollection()->where('read_at', null)->count(); @endphp
                    <p class="text-gray-400 mt-1 text-sm">
                        {{ $messages->total() }} message{{ $messages->total() > 1 ? 's' : '' }}
                        @if($unreadCount > 0)
                            · <span class="text-yellow-400 font-semibold">{{ $unreadCount }} non lu{{ $unreadCount > 1 ? 's' : '' }}</span>
                        @endif
                    </p>
                </div>

                @if($messages->getCollection()->where('read_at', null)->isNotEmpty())
                <form method="POST" action="{{ route('mailbox.read-all') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-white/10 text-white text-sm rounded-xl hover:bg-white/20 transition">
                        ✅ Tout marquer comme lu
                    </button>
                </form>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-500/20 border border-green-500/40 rounded-xl text-green-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Messages --}}
            @if($messages->isEmpty())
                <div class="text-center py-24 text-gray-500">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-lg">Votre boîte aux lettres est vide.</p>
                    <a href="{{ route('marketplace.index') }}"
                       class="inline-block mt-4 px-6 py-2 bg-yellow-500 text-gray-900 font-bold rounded-xl hover:bg-yellow-400 transition">
                        Aller au Hall des Enchères
                    </a>
                </div>
            @else
                <div class="space-y-2 mb-6">
                    @foreach($messages as $msg)
                    <div class="msg-row {{ is_null($msg->read_at) ? 'unread' : '' }}">
                        {{-- Unread indicator --}}
                        @if(is_null($msg->read_at))
                            <div class="unread-dot mt-1"></div>
                        @else
                            <div style="width:8px; flex-shrink:0;"></div>
                        @endif

                        {{-- Icon --}}
                        <div class="msg-icon type-{{ $msg->type }}">
                            {{ $msg->icon() }}
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="font-semibold text-white text-sm {{ is_null($msg->read_at) ? '' : 'opacity-70' }}">
                                    {{ $msg->title }}
                                </div>
                                <div class="text-gray-600 text-xs whitespace-nowrap shrink-0">
                                    {{ $msg->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="msg-body mt-0.5">
                                {!! nl2br(preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', e($msg->body))) !!}
                            </div>

                            {{-- Actions --}}
                            @php
                                $listingId = $msg->data['listing_id'] ?? null;
                            @endphp
                            <div class="flex items-center gap-3 mt-2">
                                @if($listingId)
                                    @php
                                        $listing = \App\Models\MarketplaceListing::find($listingId);
                                    @endphp
                                    @if($listing && $listing->status === 'active')
                                    <a href="{{ route('marketplace.show', $listing) }}"
                                       class="text-xs text-yellow-400 hover:text-yellow-300 transition font-semibold">
                                        → Voir l'annonce
                                    </a>
                                    @endif
                                @endif

                                @if(is_null($msg->read_at))
                                <form method="POST" action="{{ route('mailbox.read', $msg) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-gray-500 hover:text-gray-300 transition">
                                        Marquer comme lu
                                    </button>
                                </form>
                                @endif

                                <form method="POST" action="{{ route('mailbox.destroy', $msg) }}" class="inline"
                                      onsubmit="return confirm('Supprimer ce message ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500/60 hover:text-red-400 transition">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{ $messages->links() }}
            @endif
        </div>
    </div>
</x-app-layout>
