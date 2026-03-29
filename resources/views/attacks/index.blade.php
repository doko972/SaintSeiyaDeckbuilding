<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="flex justify-end items-center mb-3">
                <a href="{{ route('admin.attacks.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    ➕ Nouvelle Attaque
                </a>
            </div>

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

            <!-- Barre de recherche -->
            <div class="mb-6">
                <div class="relative max-w-md mx-auto">
                    <span class="absolute inset-y-0 left-4 flex items-center text-gray-400 pointer-events-none">🔍</span>
                    <input type="text" id="attackSearch" placeholder="Rechercher une attaque..."
                        class="w-full pl-10 pr-4 py-3 bg-white/10 backdrop-blur border border-white/20 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-red-500/60 focus:bg-white/15 transition"
                        oninput="filterAttacks(this.value)">
                </div>
                <p id="attackCount" class="text-center text-gray-500 text-sm mt-2"></p>
            </div>

            <!-- Grille des attaques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="attackGrid">
                @foreach($attacks as $attack)
                    @php $usedBy = $cardsByAttack[$attack->id] ?? []; @endphp
                    <div class="attack-card group"
                         data-name="{{ strtolower($attack->name) }}"
                         data-effect="{{ $attack->effect_type }}"
                         data-cards="{{ strtolower(implode('|', $usedBy)) }}">
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

                                <!-- Cartes qui utilisent cette attaque -->
                                <div class="px-5 pb-4">
                                    @if(count($usedBy) > 0)
                                        <p class="text-gray-500 text-xs mb-2 uppercase tracking-wide">Utilisée par</p>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($usedBy as $cardName)
                                                <span class="px-2 py-0.5 bg-white/10 text-gray-300 text-xs rounded-full border border-white/10">{{ $cardName }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-600 text-xs italic">Aucune carte ne l'utilise</p>
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

    <script>
    function filterAttacks(query) {
        var q = query.trim().toLowerCase();
        var cards = document.querySelectorAll('#attackGrid .attack-card');
        var visible = 0;
        cards.forEach(function(card) {
            var name  = card.dataset.name  || '';
            var cardNames = card.dataset.cards || '';
            var match = !q || name.includes(q) || cardNames.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });
        var countEl = document.getElementById('attackCount');
        countEl.textContent = q ? (visible + ' résultat' + (visible > 1 ? 's' : '')) : '';
    }
    </script>
</x-app-layout>