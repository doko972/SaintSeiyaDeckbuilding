<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attaques') }}
            </h2>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.attacks.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    ➕ Nouvelle Attaque
                </a>
            @endif
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Intro -->
            <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 mb-8 text-center">
                <h3 class="text-2xl font-bold text-white mb-2">⚔️ Les Attaques</h3>
                <p class="text-gray-400">Découvrez toutes les techniques de combat disponibles.</p>
            </div>

            <!-- Stats par effet -->
            <div class="flex flex-wrap justify-center gap-3 mb-8">
                <span class="px-4 py-2 bg-gray-600/50 text-gray-300 rounded-full text-sm">🎯 Normal: {{ $attacks->where('effect_type', 'none')->count() }}</span>
                <span class="px-4 py-2 bg-red-600/50 text-red-300 rounded-full text-sm">🔥 Brûlure: {{ $attacks->where('effect_type', 'burn')->count() }}</span>
                <span class="px-4 py-2 bg-cyan-600/50 text-cyan-300 rounded-full text-sm">❄️ Gel: {{ $attacks->where('effect_type', 'freeze')->count() }}</span>
                <span class="px-4 py-2 bg-yellow-600/50 text-yellow-300 rounded-full text-sm">⚡ Stun: {{ $attacks->where('effect_type', 'stun')->count() }}</span>
                <span class="px-4 py-2 bg-green-600/50 text-green-300 rounded-full text-sm">💚 Soin: {{ $attacks->where('effect_type', 'heal')->count() }}</span>
                <span class="px-4 py-2 bg-purple-600/50 text-purple-300 rounded-full text-sm">💀 Drain: {{ $attacks->where('effect_type', 'drain')->count() }}</span>
            </div>

            <!-- Grille des attaques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($attacks as $attack)
                    <div class="attack-card group">
                        <a href="{{ route('attacks.show', $attack) }}" class="block">
                            <div class="bg-white/5 backdrop-blur-md rounded-2xl border-2 border-white/10 overflow-hidden transition-all duration-300 hover:border-red-500/50 hover:shadow-2xl hover:shadow-red-500/20 hover:transform hover:-translate-y-2">
                                
                                <!-- Header -->
                                <div class="p-5 bg-gradient-to-r from-red-600 to-orange-600 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                                    
                                    <div class="relative z-10 flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-bold text-white">{{ $attack->name }}</h3>
                                            <p class="text-white/70 text-sm">{{ Str::limit($attack->description, 50) }}</p>
                                        </div>
                                        <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-2">
                                            <span class="text-2xl font-bold text-white">{{ $attack->damage }}</span>
                                            <span class="text-white/70 text-xs block">DMG</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="p-5">
                                    <!-- Coûts -->
                                    <div class="flex gap-4 mb-4">
                                        <div class="flex items-center gap-2 bg-yellow-500/20 border border-yellow-500/30 rounded-lg px-3 py-2">
                                            <span class="text-yellow-400">⚡</span>
                                            <span class="text-white font-bold">{{ $attack->endurance_cost }}</span>
                                            <span class="text-gray-400 text-sm">END</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-purple-500/20 border border-purple-500/30 rounded-lg px-3 py-2">
                                            <span class="text-purple-400">🌟</span>
                                            <span class="text-white font-bold">{{ $attack->cosmos_cost }}</span>
                                            <span class="text-gray-400 text-sm">COS</span>
                                        </div>
                                    </div>

                                    <!-- Effet -->
                                    @if($attack->effect_type !== 'none')
                                        <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg
                                            @switch($attack->effect_type)
                                                @case('burn') bg-red-500/20 text-red-400 border border-red-500/30 @break
                                                @case('freeze') bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 @break
                                                @case('stun') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 @break
                                                @case('heal') bg-green-500/20 text-green-400 border border-green-500/30 @break
                                                @case('drain') bg-purple-500/20 text-purple-400 border border-purple-500/30 @break
                                                @case('buff_attack') bg-orange-500/20 text-orange-400 border border-orange-500/30 @break
                                                @case('buff_defense') bg-blue-500/20 text-blue-400 border border-blue-500/30 @break
                                                @case('debuff') bg-gray-500/20 text-gray-400 border border-gray-500/30 @break
                                            @endswitch">
                                            <span>
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
                                            <span class="font-semibold">{{ ucfirst(str_replace('_', ' ', $attack->effect_type)) }}</span>
                                            <span class="text-white/70">({{ $attack->effect_value }})</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-2 px-3 py-2 bg-gray-500/20 text-gray-400 border border-gray-500/30 rounded-lg">
                                            <span>🎯</span>
                                            <span class="font-semibold">Aucun effet spécial</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Footer Admin -->
                                @if(auth()->user()->isAdmin())
                                    <div class="px-5 pb-4 flex gap-2">
                                        <a href="{{ route('admin.attacks.edit', $attack) }}" 
                                           class="flex-1 text-center py-2 bg-yellow-500/20 text-yellow-400 font-semibold rounded-lg hover:bg-yellow-500/30 transition"
                                           onclick="event.stopPropagation();">
                                            ✏️ Modifier
                                        </a>
                                        <form action="{{ route('admin.attacks.destroy', $attack) }}" method="POST" class="flex-1"
                                              onsubmit="event.stopPropagation(); return confirm('Supprimer cette attaque ?');">
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