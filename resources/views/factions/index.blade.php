<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Factions') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.factions.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    ➕ Nouvelle Faction
                </a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
    <!-- Fond Sanctuaire -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.10]">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
    </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Intro -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 mb-8 text-center">
                <h3 class="text-2xl font-bold text-white mb-2">🏛️ Les Factions du Sanctuaire</h3>
                <p class="text-gray-400">Découvrez les différentes factions et leurs chevaliers légendaires.</p>
            </div>

            <!-- Grille des factions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($factions as $faction)
                    <div class="faction-card group">
                        <a href="{{ route('factions.show', $faction) }}" class="block">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 border-white/10 overflow-hidden transition-all duration-300 hover:border-opacity-50 hover:shadow-2xl hover:transform hover:-translate-y-2"
                                 style="--faction-color: {{ $faction->color_primary }};"
                                 onmouseover="this.style.borderColor='{{ $faction->color_primary }}'; this.style.boxShadow='0 25px 50px -12px {{ $faction->color_primary }}40';"
                                 onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.boxShadow='none';">
                                
                                <!-- Header avec dégradé -->
                                <div class="h-32 relative overflow-hidden"
                                     style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});">
                                    
                                    <!-- Image de faction -->
                                    @if($faction->image)
                                        <img src="{{ Storage::url($faction->image) }}" 
                                             alt="{{ $faction->name }}"
                                             class="absolute inset-0 w-full h-full object-cover opacity-50 group-hover:opacity-70 group-hover:scale-110 transition-all duration-500">
                                    @endif

                                    <!-- Overlay brillant -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

                                    <!-- Nombre de cartes -->
                                    <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm px-3 py-1 rounded-full">
                                        <span class="text-white text-sm font-bold">{{ $faction->cards->count() }} cartes</span>
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="p-5">
                                    <h3 class="text-xl font-bold text-white mb-2 flex items-center gap-2">
                                        <span class="w-4 h-4 rounded-full" style="background: {{ $faction->color_primary }};"></span>
                                        {{ $faction->name }}
                                    </h3>
                                    
                                    @if($faction->description)
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $faction->description }}</p>
                                    @endif

                                    <!-- Aperçu des cartes -->
                                    <div class="flex -space-x-2">
                                        @foreach($faction->cards->take(5) as $card)
                                            <div class="w-10 h-10 rounded-full border-2 border-gray-800 overflow-hidden"
                                                 style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});"
                                                 title="{{ $card->name }}">
                                                @if($card->image_primary)
                                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($faction->cards->count() > 5)
                                            <div class="w-10 h-10 rounded-full border-2 border-gray-800 bg-gray-700 flex items-center justify-center">
                                                <span class="text-white text-xs font-bold">+{{ $faction->cards->count() - 5 }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Footer Admin -->
                                @if(auth()->user()->isAdmin())
                                    <div class="px-5 pb-4 flex gap-2">
                                        <a href="{{ route('admin.factions.edit', $faction) }}" 
                                           class="flex-1 text-center py-2 bg-yellow-500/20 text-yellow-400 font-semibold rounded-lg hover:bg-yellow-500/30 transition"
                                           onclick="event.stopPropagation();">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('admin.factions.destroy', $faction) }}" method="POST" class="flex-1"
                                              onsubmit="event.stopPropagation(); return confirm('Supprimer cette faction ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full py-2 bg-red-500/20 text-red-400 font-semibold rounded-lg hover:bg-red-500/30 transition">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>