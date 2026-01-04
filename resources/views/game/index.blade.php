<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Choisir un Deck pour Combattre') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
    <!-- Fond Sanctuaire -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
    </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            @if($decks->isEmpty())
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-12 text-center">
                    <div class="text-6xl mb-4">🃏</div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucun deck disponible</h3>
                    <p class="text-gray-400 mb-6">Créez un deck pour commencer à jouer !</p>
                    <a href="{{ route('decks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition transform hover:scale-105">
                        ➕ Créer un deck
                    </a>
                </div>
            @else
                <!-- Intro -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 mb-8 text-center">
                    <h3 class="text-2xl font-bold text-white mb-2">⚔️ Prêt pour le combat ?</h3>
                    <p class="text-gray-400">Sélectionnez un deck pour affronter un adversaire contrôlé par l'IA.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($decks as $deck)
                        <div class="deck-battle-card group">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 border-white/10 overflow-hidden transition-all duration-300 hover:border-red-500/50 hover:shadow-2xl hover:shadow-red-500/20 hover:transform hover:-translate-y-2">
                                
                                <!-- Header -->
                                <div class="p-5 bg-gradient-to-r from-red-600 to-orange-600 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    <h3 class="text-xl font-bold text-white relative z-10">{{ $deck->name }}</h3>
                                    <p class="text-white/70 text-sm relative z-10">{{ $deck->cards_count }} carte(s)</p>
                                </div>

                                <!-- Contenu -->
                                <div class="p-5">
                                    @if($deck->description)
                                        <p class="text-gray-400 text-sm mb-4">{{ Str::limit($deck->description, 80) }}</p>
                                    @endif

                                    <!-- Aperçu des cartes -->
                                    <div class="flex -space-x-3 mb-6">
                                        @foreach($deck->cards->take(6) as $card)
                                            <div class="w-14 h-14 rounded-full border-2 border-gray-800 shadow-lg overflow-hidden transform group-hover:scale-110 transition-transform"
                                                 style="background: linear-gradient(135deg, {{ $card->faction->color_primary }}, {{ $card->faction->color_secondary }});"
                                                 title="{{ $card->name }}">
                                                @if($card->image_primary)
                                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Bouton Combat -->
                                    @if($deck->cards_count > 0)
                                        <a href="{{ route('game.battle', $deck) }}" 
                                           class="block w-full text-center py-4 bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold text-lg rounded-xl hover:from-red-400 hover:to-orange-400 transition transform group-hover:scale-105 shadow-lg shadow-red-500/25">
                                            ⚔️ Combattre !
                                        </a>
                                    @else
                                        <div class="text-center py-4 bg-gray-700 text-gray-500 rounded-xl">
                                            Deck vide
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>