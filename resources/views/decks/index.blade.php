<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mes Decks') }}
            </h2>
            <a href="{{ route('decks.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                ➕ Nouveau Deck
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($decks->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-12 text-center">
                    <div class="text-6xl mb-4">📚</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucun deck</h3>
                    <p class="text-gray-400 mb-6">Créez votre premier deck pour commencer à jouer !</p>
                    <a href="{{ route('decks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-105">
                        ➕ Créer un deck
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($decks as $deck)
                        <div class="deck-card group">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 overflow-hidden transition-all duration-300 hover:transform hover:-translate-y-2 hover:shadow-2xl
                                        {{ $deck->is_active ? 'border-green-500/50 shadow-lg shadow-green-500/20' : 'border-white/10 hover:border-purple-500/50 hover:shadow-purple-500/20' }}">
                                
                                <!-- Header -->
                                <div class="p-5 bg-gradient-to-r {{ $deck->is_active ? 'from-green-600 to-emerald-600' : 'from-purple-600 to-indigo-600' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-bold text-white">{{ $deck->name }}</h3>
                                            <p class="text-white/70 text-sm">{{ $deck->cards->sum('pivot.quantity') }} carte(s)</p>
                                        </div>
                                        @if($deck->is_active)
                                            <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full">
                                                ✓ ACTIF
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="p-5">
                                    @if($deck->description)
                                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($deck->description, 80) }}</p>
                                    @endif

                                    <!-- Aperçu des cartes -->
                                    <div class="flex -space-x-3 mb-4">
                                        @forelse($deck->cards->take(6) as $card)
                                            <div class="w-12 h-12 rounded-full border-2 border-gray-800 shadow-lg overflow-hidden transform hover:scale-110 hover:z-10 transition-transform"
                                                 style="background: linear-gradient(135deg, {{ $card->faction->color_primary }}, {{ $card->faction->color_secondary }});"
                                                 title="{{ $card->name }} x{{ $card->pivot->quantity }}">
                                                @if($card->image_primary)
                                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-gray-500 text-sm">Aucune carte</span>
                                        @endforelse
                                        @if($deck->cards->count() > 6)
                                            <div class="w-12 h-12 rounded-full border-2 border-gray-800 bg-gray-700 flex items-center justify-content-center text-white text-xs font-bold">
                                                +{{ $deck->cards->count() - 6 }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        <a href="{{ route('decks.show', $deck) }}" class="flex-1 text-center py-2 bg-blue-500/20 text-blue-400 font-semibold rounded-lg hover:bg-blue-500/30 transition">
                                            👁️ Voir
                                        </a>
                                        <a href="{{ route('decks.edit', $deck) }}" class="flex-1 text-center py-2 bg-yellow-500/20 text-yellow-400 font-semibold rounded-lg hover:bg-yellow-500/30 transition">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('decks.destroy', $deck) }}" method="POST" class="flex-1"
                                              onsubmit="return confirm('Supprimer ce deck ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-2 bg-red-500/20 text-red-400 font-semibold rounded-lg hover:bg-red-500/30 transition">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>