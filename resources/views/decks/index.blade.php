<x-app-layout>
    <style>
        /* Fond Cosmos */
        .cosmos-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(ellipse at 20% 80%, rgba(120, 0, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 0, 100, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(0, 100, 255, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #0a0a1a 0%, #1a0a2a 50%, #0a1a2a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* Deck Card Hover Effect */
        .deck-card {
            transition: all 0.3s ease;
        }

        .deck-card:hover {
            transform: translateY(-8px);
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        <!-- Fond Cosmos -->
        <div class="cosmos-bg">
            <div class="stars"></div>
        </div>

        <!-- Contenu -->
        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            <!-- Header de page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <span class="text-4xl">📚</span>
                        Mes Decks
                    </h1>
                    <p class="text-gray-400 mt-1">Gérez vos decks de combat</p>
                </div>
                <a href="{{ route('decks.create') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Deck
                </a>
            </div>

            @if($decks->isEmpty())
                <!-- État vide -->
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-12 text-center">
                    <div class="text-7xl mb-6">📚</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Aucun deck créé</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Créez votre premier deck pour commencer à affronter vos adversaires et brûler votre cosmos !
                    </p>
                    <a href="{{ route('decks.create') }}" 
                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer mon premier deck
                    </a>
                </div>
            @else
                <!-- Grille des decks -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($decks as $deck)
                        <div class="deck-card">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 overflow-hidden transition-all duration-300 hover:shadow-2xl
                                        {{ $deck->is_active 
                                            ? 'border-green-500/50 shadow-lg shadow-green-500/20 hover:border-green-400/70' 
                                            : 'border-white/10 hover:border-purple-500/50 hover:shadow-purple-500/20' }}">
                                
                                <!-- Header du deck -->
                                <div class="p-5 bg-gradient-to-r {{ $deck->is_active ? 'from-green-600 to-emerald-600' : 'from-purple-600 to-indigo-600' }} relative overflow-hidden">
                                    <!-- Effet brillant -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    
                                    <div class="relative flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-bold text-white">{{ $deck->name }}</h3>
                                            <p class="text-white/70 text-sm mt-1">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                    </svg>
                                                    {{ $deck->cards->sum('pivot.quantity') }} carte(s)
                                                </span>
                                            </p>
                                        </div>
                                        @if($deck->is_active)
                                            <span class="px-3 py-1 bg-white/20 text-white text-xs font-bold rounded-full flex items-center gap-1 shadow-lg">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                ACTIF
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Contenu du deck -->
                                <div class="p-5">
                                    @if($deck->description)
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $deck->description }}</p>
                                    @endif

                                    <!-- Aperçu des cartes -->
                                    <div class="flex -space-x-3 mb-5">
                                        @forelse($deck->cards->take(6) as $card)
                                            <div class="w-12 h-12 rounded-full border-2 border-gray-800 shadow-lg overflow-hidden transform hover:scale-125 hover:z-10 transition-all duration-200 cursor-pointer"
                                                 style="background: linear-gradient(135deg, {{ $card->faction->color_primary ?? '#6366f1' }}, {{ $card->faction->color_secondary ?? '#8b5cf6' }});"
                                                 title="{{ $card->name }} x{{ $card->pivot->quantity }}">
                                                @if($card->image_primary)
                                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-white text-xs font-bold">
                                                        {{ strtoupper(substr($card->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-gray-500 text-sm italic">Aucune carte dans ce deck</span>
                                        @endforelse
                                        
                                        @if($deck->cards->count() > 6)
                                            <div class="w-12 h-12 rounded-full border-2 border-gray-800 bg-gray-700/80 flex items-center justify-center text-white text-xs font-bold shadow-lg">
                                                +{{ $deck->cards->count() - 6 }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        <a href="{{ route('decks.show', $deck) }}" 
                                           class="flex-1 flex items-center justify-center gap-1 py-2.5 bg-blue-500/20 text-blue-400 font-semibold rounded-lg hover:bg-blue-500/30 transition-all duration-200 border border-blue-500/20 hover:border-blue-500/40">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Voir
                                        </a>
                                        <a href="{{ route('decks.edit', $deck) }}" 
                                           class="flex-1 flex items-center justify-center gap-1 py-2.5 bg-yellow-500/20 text-yellow-400 font-semibold rounded-lg hover:bg-yellow-500/30 transition-all duration-200 border border-yellow-500/20 hover:border-yellow-500/40">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Modifier
                                        </a>
                                        <form action="{{ route('decks.destroy', $deck) }}" method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce deck ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="flex items-center justify-center gap-1 px-4 py-2.5 bg-red-500/20 text-red-400 font-semibold rounded-lg hover:bg-red-500/30 transition-all duration-200 border border-red-500/20 hover:border-red-500/40">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
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