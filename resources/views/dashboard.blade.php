<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
    
    <!-- Fond Sanctuaire -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.12]">
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900/60 via-gray-900/40 to-gray-900/80"></div>
    </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative z-10">

            <!-- Hero Stats -->
            <div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-600 rounded-2xl shadow-2xl p-8 mb-8 relative overflow-hidden">
                <!-- Effet brillant -->
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-pulse"></div>
                
                <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h3 class="text-3xl font-bold text-white">Bienvenue, {{ auth()->user()->name }} !</h3>
                        <p class="text-purple-200 text-lg">Prêt à brûler ton cosmos ? 🔥</p>
                    </div>
                    <div class="flex gap-8">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-yellow-400">🪙 {{ number_format(auth()->user()->coins) }}</div>
                            <div class="text-purple-200">Pièces</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-green-400">🏆 {{ auth()->user()->wins }}</div>
                            <div class="text-purple-200">Victoires</div>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-bold text-red-400">💀 {{ auth()->user()->losses }}</div>
                            <div class="text-purple-200">Défaites</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 text-center hover:border-purple-500/50 transition">
                    <div class="text-4xl mb-2">🃏</div>
                    <div class="text-3xl font-bold text-indigo-400">{{ \App\Models\Card::count() }}</div>
                    <div class="text-gray-400">Cartes totales</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 text-center hover:border-green-500/50 transition">
                    <div class="text-4xl mb-2">📚</div>
                    <div class="text-3xl font-bold text-green-400">{{ auth()->user()->cards()->count() }}</div>
                    <div class="text-gray-400">Ma collection</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 text-center hover:border-yellow-500/50 transition">
                    <div class="text-4xl mb-2">🎴</div>
                    <div class="text-3xl font-bold text-yellow-400">{{ auth()->user()->decks()->count() }}</div>
                    <div class="text-gray-400">Mes decks</div>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-xl border border-white/20 p-6 text-center hover:border-red-500/50 transition">
                    <div class="text-4xl mb-2">⚔️</div>
                    <div class="text-3xl font-bold text-red-400">{{ auth()->user()->wins + auth()->user()->losses }}</div>
                    <div class="text-gray-400">Combats joués</div>
                </div>
            </div>

            <!-- Bannière PvP -->
            <a href="{{ route('pvp.lobby') }}" class="block mb-6 bg-gradient-to-r from-red-600/20 via-orange-500/20 to-red-600/20 border border-red-500/40 rounded-2xl p-5 hover:from-red-600/30 hover:via-orange-500/30 hover:to-red-600/30 hover:border-red-500/60 transition group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                
                <div class="flex items-center justify-center gap-6 relative z-10">
                    <div class="text-5xl group-hover:scale-110 transition-transform duration-300">🆚</div>
                    <div class="text-left">
                        <div class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-red-400 to-orange-400">Arena PvP</div>
                        <div class="text-gray-400 text-sm">Affrontez d'autres joueurs en temps réel !</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-green-400 text-sm font-medium">En ligne</span>
                    </div>
                    <div class="text-3xl animate-pulse">⚔️</div>
                </div>
            </a>

            <!-- Actions rapides -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('game.index') }}" class="action-card bg-gradient-to-br from-red-500/20 to-orange-500/20 border border-red-500/30 rounded-xl p-4 text-center hover:from-red-500/30 hover:to-orange-500/30 hover:border-red-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🤖</div>
                    <div class="text-white font-semibold">Vs IA</div>
                </a>
                <a href="{{ route('shop.index') }}" class="action-card bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-4 text-center hover:from-yellow-500/30 hover:to-orange-500/30 hover:border-yellow-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🛒</div>
                    <div class="text-white font-semibold">Boutique</div>
                </a>
                <a href="{{ route('collection.index') }}" class="action-card bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/30 rounded-xl p-4 text-center hover:from-purple-500/30 hover:to-pink-500/30 hover:border-purple-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">📚</div>
                    <div class="text-white font-semibold">Collection</div>
                </a>
                <a href="{{ route('decks.index') }}" class="action-card bg-gradient-to-br from-indigo-500/20 to-blue-500/20 border border-indigo-500/30 rounded-xl p-4 text-center hover:from-indigo-500/30 hover:to-blue-500/30 hover:border-indigo-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎴</div>
                    <div class="text-white font-semibold">Mes Decks</div>
                </a>
                <a href="{{ route('cards.index') }}" class="action-card bg-gradient-to-br from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-xl p-4 text-center hover:from-green-500/30 hover:to-emerald-500/30 hover:border-green-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🃏</div>
                    <div class="text-white font-semibold">Cartes</div>
                </a>
                <a href="{{ route('factions.index') }}" class="action-card bg-gradient-to-br from-cyan-500/20 to-blue-500/20 border border-cyan-500/30 rounded-xl p-4 text-center hover:from-cyan-500/30 hover:to-blue-500/30 hover:border-cyan-500/50 transition group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🏛️</div>
                    <div class="text-white font-semibold">Factions</div>
                </a>
            </div>

                @if(auth()->user()->isAdmin())
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <h4 class="text-lg font-semibold text-gray-400 mb-4">⚙️ Administration</h4>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.cards.create') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                                ➕ Nouvelle carte
                            </a>
                            <a href="{{ route('admin.factions.create') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                                ➕ Nouvelle faction
                            </a>
                            <a href="{{ route('admin.attacks.create') }}" class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition">
                                ➕ Nouvelle attaque
                            </a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>