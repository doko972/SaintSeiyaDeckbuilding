<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        {{ $card->name }}
                    </h1>
                </div>
                <a href="{{ route('collection.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    ← Retour à la collection
                </a>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                <!-- CARTE AVEC EFFET HOLO -->
                <div class="flex justify-center">
                    <x-card-display :card="$card" size="large" :interactive="true" :fusionLevel="$fusionLevel" :boostedStats="$boostedStats" />
                </div>

                <!-- INFOS -->
                <div class="space-y-6">

                    <!-- Possession -->
                    @if ($owned)
                        <div class="bg-green-500/20 backdrop-blur-md border border-green-500/50 rounded-xl p-4">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">✅</span>
                                <div>
                                    <h3 class="text-green-400 font-bold text-lg">Vous possédez cette carte</h3>
                                    <p class="text-green-300/80">{{ $owned->pivot->quantity }} exemplaire(s) • Obtenue
                                        le {{ \Carbon\Carbon::parse($owned->pivot->obtained_at)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Niveau de Fusion -->
                        @if ($fusionLevel > 1)
                            <div class="bg-emerald-500/20 backdrop-blur-md border border-emerald-500/50 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-3xl">⚔️</span>
                                        <div>
                                            <h3 class="text-emerald-400 font-bold text-lg">Niveau de Fusion +{{ $fusionLevel - 1 }}</h3>
                                            <p class="text-emerald-300/80">+{{ $boostedStats['bonus_percent'] }}% sur HP, END, DEF et PWR</p>
                                        </div>
                                    </div>
                                    <div class="px-4 py-2 rounded-lg font-bold text-lg {{ $fusionLevel >= 10 ? 'bg-red-500/30 text-red-300' : ($fusionLevel >= 7 ? 'bg-amber-500/30 text-amber-300' : 'bg-emerald-500/30 text-emerald-300') }}">
                                        {{ $fusionLevel }}/10
                                    </div>
                                </div>
                            </div>

                            <!-- Stats comparées -->
                            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4">
                                <h3 class="text-white font-bold text-lg mb-4 flex items-center gap-2">
                                    <span>📊</span> Stats Améliorées
                                </h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-red-500/20">
                                        <span class="text-red-300">PV</span>
                                        <span class="font-bold">
                                            <span class="text-gray-400 line-through text-sm mr-2">{{ $card->health_points }}</span>
                                            <span class="text-green-400">{{ $boostedStats['health_points'] }}</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-amber-500/20">
                                        <span class="text-amber-300">END</span>
                                        <span class="font-bold">
                                            <span class="text-gray-400 line-through text-sm mr-2">{{ $card->endurance }}</span>
                                            <span class="text-green-400">{{ $boostedStats['endurance'] }}</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-blue-500/20">
                                        <span class="text-blue-300">DEF</span>
                                        <span class="font-bold">
                                            <span class="text-gray-400 line-through text-sm mr-2">{{ $card->defense }}</span>
                                            <span class="text-green-400">{{ $boostedStats['defense'] }}</span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 rounded-lg bg-orange-500/20">
                                        <span class="text-orange-300">PWR</span>
                                        <span class="font-bold">
                                            <span class="text-gray-400 line-through text-sm mr-2">{{ $card->power }}</span>
                                            <span class="text-green-400">{{ $boostedStats['power'] }}</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">❌</span>
                                <div>
                                    <h3 class="text-gray-300 font-bold text-lg">Vous ne possédez pas cette carte</h3>
                                    <p class="text-gray-400">Achetez des boosters pour l'obtenir !</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Attaque Principale -->
                    <div class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden border border-white/20">
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wider">Attaque Principale</p>
                                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                        ⚔️ {{ $card->mainAttack->name }}
                                    </h3>
                                </div>
                                <span
                                    class="bg-red-500/30 text-red-300 font-bold px-4 py-2 rounded-lg text-lg border border-red-500/50">
                                    {{ $card->mainAttack->damage }} DMG
                                </span>
                            </div>
                            <p class="text-gray-300 mb-3">{{ $card->mainAttack->description }}</p>
                            <div class="flex gap-4 text-sm">
                                <span class="text-yellow-400 font-semibold">⚡ {{ $card->mainAttack->endurance_cost }}
                                    END</span>
                                <span class="text-purple-400 font-semibold">🌟 {{ $card->mainAttack->cosmos_cost }}
                                    COS</span>
                                @if ($card->mainAttack->effect_type !== 'none')
                                    <span class="text-pink-400 font-semibold">✨
                                        {{ ucfirst($card->mainAttack->effect_type) }}
                                        ({{ $card->mainAttack->effect_value }})</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Attaque Secondaire 1 -->
                    @if ($card->secondaryAttack1)
                        <div class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden border border-white/20">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">Attaque Secondaire 1
                                        </p>
                                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                            ⚔️ {{ $card->secondaryAttack1->name }}
                                        </h3>
                                    </div>
                                    <span
                                        class="bg-blue-500/30 text-blue-300 font-bold px-4 py-2 rounded-lg text-lg border border-blue-500/50">
                                        {{ $card->secondaryAttack1->damage }} DMG
                                    </span>
                                </div>
                                <p class="text-gray-300 mb-3">{{ $card->secondaryAttack1->description }}</p>
                                <div class="flex gap-4 text-sm">
                                    <span class="text-yellow-400 font-semibold">⚡
                                        {{ $card->secondaryAttack1->endurance_cost }} END</span>
                                    <span class="text-purple-400 font-semibold">🌟
                                        {{ $card->secondaryAttack1->cosmos_cost }} COS</span>
                                    @if ($card->secondaryAttack1->effect_type !== 'none')
                                        <span class="text-pink-400 font-semibold">✨
                                            {{ ucfirst($card->secondaryAttack1->effect_type) }}
                                            ({{ $card->secondaryAttack1->effect_value }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Attaque Secondaire 2 -->
                    @if ($card->secondaryAttack2)
                        <div class="bg-white/10 backdrop-blur-md rounded-xl overflow-hidden border border-white/20">
                            <div class="p-5">
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider">Attaque Secondaire 2
                                        </p>
                                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                            ⚔️ {{ $card->secondaryAttack2->name }}
                                        </h3>
                                    </div>
                                    <span
                                        class="bg-green-500/30 text-green-300 font-bold px-4 py-2 rounded-lg text-lg border border-green-500/50">
                                        {{ $card->secondaryAttack2->damage }} DMG
                                    </span>
                                </div>
                                <p class="text-gray-300 mb-3">{{ $card->secondaryAttack2->description }}</p>
                                <div class="flex gap-4 text-sm">
                                    <span class="text-yellow-400 font-semibold">⚡
                                        {{ $card->secondaryAttack2->endurance_cost }} END</span>
                                    <span class="text-purple-400 font-semibold">🌟
                                        {{ $card->secondaryAttack2->cosmos_cost }} COS</span>
                                    @if ($card->secondaryAttack2->effect_type !== 'none')
                                        <span class="text-pink-400 font-semibold">✨
                                            {{ ucfirst($card->secondaryAttack2->effect_type) }}
                                            ({{ $card->secondaryAttack2->effect_value }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Lien vers les cartes -->
                    <a href="{{ route('cards.show', $card) }}"
                        class="block w-full text-center py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition">
                        📖 Voir dans le catalogue
                    </a>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
