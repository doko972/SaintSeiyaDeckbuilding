<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Header de page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        {{ $attack->name }}
                    </h1>
                </div>
                <a href="{{ route('attacks.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    ← Retour
                </a>
            </div>
            <!-- Header de l'attaque -->
            <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-2xl p-8 mb-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-black/20"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-wrap justify-between items-start gap-6">
                        <div>
                            <h1 class="text-4xl font-bold text-white mb-2">⚔️ {{ $attack->name }}</h1>
                            <p class="text-white/80 text-lg max-w-xl">{{ $attack->description }}</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 text-center">
                            <div class="text-5xl font-bold text-white">{{ $attack->damage }}</div>
                            <div class="text-white/70">Dégâts</div>
                        </div>
                    </div>

                    <!-- Actions Admin -->
                    @if(auth()->user()->isAdmin())
                        <div class="flex gap-3 mt-6">
                            <a href="{{ route('admin.attacks.edit', $attack) }}" 
                               class="px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg hover:bg-yellow-600 transition">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('admin.attacks.destroy', $attack) }}" method="POST"
                                  onsubmit="return confirm('Supprimer cette attaque ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-700 text-white font-bold rounded-lg hover:bg-red-800 transition">
                                    🗑️ Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Détails -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Coûts -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-white mb-4">💰 Coûts</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between bg-yellow-500/20 border border-yellow-500/30 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">⚡</span>
                                <span class="text-gray-300">Endurance</span>
                            </div>
                            <span class="text-3xl font-bold text-yellow-400">{{ $attack->endurance_cost }}</span>
                        </div>
                        <div class="flex items-center justify-between bg-purple-500/20 border border-purple-500/30 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">🌟</span>
                                <span class="text-gray-300">Cosmos</span>
                            </div>
                            <span class="text-3xl font-bold text-purple-400">{{ $attack->cosmos_cost }}</span>
                        </div>
                    </div>
                </div>

                <!-- Effet -->
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6">
                    <h3 class="text-lg font-bold text-white mb-4">✨ Effet spécial</h3>
                    
                    @if($attack->effect_type !== 'none')
                        <div class="p-4 rounded-lg
                            @switch($attack->effect_type)
                                @case('burn') bg-red-500/20 border border-red-500/30 @break
                                @case('freeze') bg-cyan-500/20 border border-cyan-500/30 @break
                                @case('stun') bg-yellow-500/20 border border-yellow-500/30 @break
                                @case('heal') bg-green-500/20 border border-green-500/30 @break
                                @case('drain') bg-purple-500/20 border border-purple-500/30 @break
                                @case('buff_attack') bg-orange-500/20 border border-orange-500/30 @break
                                @case('buff_defense') bg-blue-500/20 border border-blue-500/30 @break
                                @case('debuff') bg-gray-500/20 border border-gray-500/30 @break
                            @endswitch">
                            
                            <div class="flex items-center gap-4">
                                <span class="text-4xl">
                                    @switch($attack->effect_type)
                                        @case('burn') 🔥 @break
                                        @case('freeze') ❄️ @break
                                        @case('stun') ⚡ @break
                                        @case('heal') 💚 @break
                                        @case('drain') 💀 @break
                                        @case('buff_attack') 💪 @break
                                        @case('buff_defense') 🛡️ @break
                                        @case('debuff') 📉 @break
                                    @endswitch
                                </span>
                                <div>
                                    <div class="text-xl font-bold text-white">{{ ucfirst(str_replace('_', ' ', $attack->effect_type)) }}</div>
                                    <div class="text-gray-400">Valeur: {{ $attack->effect_value }}</div>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-300">
                                @switch($attack->effect_type)
                                    @case('burn') Inflige {{ $attack->effect_value }} dégâts par tour pendant 3 tours. @break
                                    @case('freeze') Gèle la cible pendant {{ $attack->effect_value }} tour(s). @break
                                    @case('stun') Étourdit la cible pendant {{ $attack->effect_value }} tour(s). @break
                                    @case('heal') Restaure {{ $attack->effect_value }} PV à l'attaquant. @break
                                    @case('drain') Vole {{ $attack->effect_value }} PV à la cible. @break
                                    @case('buff_attack') Augmente la puissance de +{{ $attack->effect_value }}. @break
                                    @case('buff_defense') Augmente la défense de +{{ $attack->effect_value }}. @break
                                    @case('debuff') Réduit les stats de la cible de {{ $attack->effect_value }}. @break
                                @endswitch
                            </p>
                        </div>
                    @else
                        <div class="p-4 bg-gray-500/20 border border-gray-500/30 rounded-lg">
                            <div class="flex items-center gap-4">
                                <span class="text-4xl">🎯</span>
                                <div>
                                    <div class="text-xl font-bold text-white">Aucun effet</div>
                                    <div class="text-gray-400">Attaque basique sans effet spécial</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cartes utilisant cette attaque -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6">
                <h3 class="text-lg font-bold text-white mb-4">🃏 Cartes utilisant cette attaque</h3>
                
                @php
                    $cardsWithAttack = \App\Models\Card::where('main_attack_id', $attack->id)
                        ->orWhere('secondary_attack_1_id', $attack->id)
                        ->orWhere('secondary_attack_2_id', $attack->id)
                        ->with('faction')
                        ->get();
                @endphp

                @if($cardsWithAttack->isEmpty())
                    <p class="text-gray-400">Aucune carte n'utilise cette attaque pour le moment.</p>
                @else
                    <div class="flex flex-wrap gap-3">
                        @foreach($cardsWithAttack as $card)
                            <a href="{{ route('cards.show', $card) }}" 
                               class="flex items-center gap-2 bg-white/5 border border-white/20 rounded-lg px-4 py-2 hover:bg-white/10 transition">
                                <span class="w-8 h-8 rounded-full overflow-hidden"
                                      style="background: linear-gradient(135deg, {{ $card->faction->color_primary }}, {{ $card->faction->color_secondary }});">
                                    @if($card->image_primary)
                                        <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="w-full h-full object-cover">
                                    @endif
                                </span>
                                <span class="text-white font-semibold">{{ $card->name }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>