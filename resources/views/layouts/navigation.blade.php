<nav x-data="{ open: false, gameMenuOpen: false }"
    class="bg-gradient-to-r from-gray-900/95 via-purple-900/90 to-gray-900/95 backdrop-blur-md border-b border-purple-500/20 sticky top-0 z-50">
    <!-- Effet de brillance subtil -->
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-purple-500/5 to-transparent pointer-events-none">
    </div>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        @php
                            $logoPath = public_path('images/logo.webp');
                        @endphp
                        @if (file_exists($logoPath))
                            <img src="{{ asset('images/logo.webp') }}" alt="Saint Seiya Logo"
                                class="h-10 w-auto group-hover:scale-110 transition-transform duration-300">
                        @else
                            <span class="text-2xl group-hover:scale-110 transition-transform duration-300">⚔️</span>
                        @endif
                        <span
                            class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-300 to-yellow-400 group-hover:from-yellow-300 group-hover:to-amber-200 transition-all duration-300">
                            Saint Seiya
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex items-center">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('dashboard')
                           ? 'bg-purple-500/30 text-yellow-400 shadow-lg shadow-purple-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    <!-- Menu déroulant "Jouer" -->
                    <div class="relative" @click.away="gameMenuOpen = false">
                        <button @click="gameMenuOpen = !gameMenuOpen"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                                {{ request()->routeIs('game.*') || request()->routeIs('pvp.*')
                                    ? 'bg-red-500/30 text-yellow-400 shadow-lg shadow-red-500/20'
                                    : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Jouer
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200"
                                :class="{ 'rotate-180': gameMenuOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="gameMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute left-0 top-full mt-2 w-64 rounded-xl shadow-2xl bg-gray-900/95 backdrop-blur-md border border-purple-500/30 overflow-hidden"
                            style="display: none;">
                            <div class="py-2">
                                <!-- Combat PvE -->
                                <a href="{{ route('game.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('game.*')
                                       ? 'bg-gradient-to-r from-blue-600/30 to-indigo-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        🤖
                                    </span>
                                    <div>
                                        <div class="font-semibold">Combat PvE</div>
                                        <div class="text-xs text-gray-500">Affrontez l'IA</div>
                                    </div>
                                </a>

                                <!-- Arena PvP -->
                                <a href="{{ route('pvp.lobby') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('pvp.*')
                                       ? 'bg-gradient-to-r from-red-600/30 to-orange-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        🆚
                                    </span>
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2">
                                            Arena PvP
                                            <span class="relative flex h-2 w-2">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500">Joueurs en temps réel</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Collection -->
                    <a href="{{ route('collection.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('collection.*')
                           ? 'bg-purple-500/30 text-yellow-400 shadow-lg shadow-purple-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Collection
                    </a>

                    <!-- Mes Decks -->
                    <a href="{{ route('decks.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('decks.*')
                           ? 'bg-indigo-500/30 text-yellow-400 shadow-lg shadow-indigo-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        {{-- <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg> --}}
                        Mes Decks
                    </a>

                    <!-- Boutique -->
                    <a href="{{ route('shop.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('shop.*')
                           ? 'bg-yellow-500/30 text-yellow-400 shadow-lg shadow-yellow-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Boutique
                    </a>

                    <!-- Factions -->
                    <a href="{{ route('factions.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('factions.*')
                           ? 'bg-cyan-500/30 text-yellow-400 shadow-lg shadow-cyan-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Factions
                    </a>


                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                <!-- Coins Display -->
                <div
                    class="px-4 py-2 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 border border-yellow-500/30 rounded-full flex items-center gap-2 shadow-lg shadow-yellow-500/10">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.736 6.979C9.208 6.193 9.696 6 10 6c.304 0 .792.193 1.264.979a1 1 0 001.715-1.029C12.279 4.784 11.232 4 10 4s-2.279.784-2.979 1.95c-.285.475-.507 1-.67 1.55H6a1 1 0 000 2h.013a9.358 9.358 0 000 1H6a1 1 0 100 2h.351c.163.55.385 1.075.67 1.55C7.721 15.216 8.768 16 10 16s2.279-.784 2.979-1.95a1 1 0 10-1.715-1.029c-.472.786-.96.979-1.264.979-.304 0-.792-.193-1.264-.979a4.265 4.265 0 01-.264-.521H10a1 1 0 100-2H8.017a7.36 7.36 0 010-1H10a1 1 0 100-2H8.472c.08-.185.167-.36.264-.521z" />
                    </svg>
                    <span class="font-bold text-yellow-400">{{ number_format(auth()->user()->coins ?? 0) }}</span>
                </div>

                <!-- User Dropdown -->
                <!-- User Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-300 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 hover:border-purple-500/30 transition-all duration-300">
                            <div class="flex items-center gap-2">
                                <!-- Avatar placeholder -->
                                <div
                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span>{{ Auth::user()->name }}</span>
                                @if (auth()->user()->isAdmin())
                                    <span
                                        class="px-2 py-0.5 text-xs bg-red-500/30 text-red-400 rounded border border-red-500/30">Admin</span>
                                @endif
                            </div>
                            <svg class="ms-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="bg-gray-900 border border-purple-500/30 rounded-lg overflow-hidden">
                            <!-- Profil -->
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-300 hover:bg-purple-500/20 hover:text-white transition">
                                👤 Profil
                            </a>

                            <!-- Admin Section -->
                            @if (auth()->user()->isAdmin())
                                <div class="border-t border-purple-500/20 mt-1 pt-1">
                                    <div class="px-4 py-1 text-xs font-semibold text-red-400 uppercase tracking-wider">
                                        👑 Administration
                                    </div>
                                    <a href="{{ route('cards.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        🃏 Cartes (CRUD)
                                    </a>
                                    <a href="{{ route('attacks.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        ⚔️ Attaques (CRUD)
                                    </a>
                                    <a href="{{ route('admin.cards.create') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        ➕ Créer une carte
                                    </a>
                                </div>
                            @endif

                            <!-- Déconnexion -->
                            <div class="border-t border-purple-500/20 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-red-400 transition">
                                        🚪 Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Mobile -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Mobile Coins -->
                <div
                    class="mr-2 px-3 py-1 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 border border-yellow-500/30 rounded-full flex items-center gap-1">
                    <span class="text-yellow-400 text-sm">🪙</span>
                    <span class="font-bold text-yellow-400 text-sm">{{ auth()->user()->coins ?? 0 }}</span>
                </div>

                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition duration-300">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden bg-gray-900/98 backdrop-blur-md border-t border-purple-500/20">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('dashboard') ? 'bg-purple-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">🏠</span>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Jouer Section -->
            <div class="py-2">
                <div class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-2 px-4">⚔️ Jouer</div>
                <a href="{{ route('game.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('game.*') ? 'bg-blue-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-xl">🤖</span>
                    <span class="font-medium">Combat PvE</span>
                </a>
                <a href="{{ route('pvp.lobby') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
                   {{ request()->routeIs('pvp.*') ? 'bg-red-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-xl">🆚</span>
                    <div class="flex items-center gap-2">
                        <span class="font-medium">Arena PvP</span>
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                    </div>
                </a>
            </div>

            <a href="{{ route('collection.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('collection.*') ? 'bg-purple-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">🎴</span>
                <span class="font-medium">Collection</span>
            </a>

            <a href="{{ route('decks.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('decks.*') ? 'bg-indigo-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">📚</span>
                <span class="font-medium">Mes Decks</span>
            </a>

            <a href="{{ route('shop.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('shop.*') ? 'bg-yellow-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">🛒</span>
                <span class="font-medium">Boutique</span>
            </a>

            <a href="{{ route('factions.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200
               {{ request()->routeIs('factions.*') ? 'bg-cyan-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                <span class="text-xl">🏛️</span>
                <span class="font-medium">Factions</span>
            </a>

            @if (auth()->user()->isAdmin())
                <div class="pt-2 mt-2 border-t border-purple-500/20">
                    <div class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-2 px-4">👑
                        Administration</div>
                    <a href="{{ route('cards.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                        <span class="text-xl">🃏</span>
                        <span class="font-medium">Cartes (CRUD)</span>
                    </a>
                    <a href="{{ route('attacks.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                        <span class="text-xl">⚔️</span>
                        <span class="font-medium">Attaques (CRUD)</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Mobile User Section -->
        <div class="pt-4 pb-3 border-t border-purple-500/20 px-4">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-white flex items-center gap-2">
                        {{ Auth::user()->name }}
                        @if (auth()->user()->isAdmin())
                            <span class="px-2 py-0.5 text-xs bg-red-500/30 text-red-400 rounded">Admin</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition">
                    <span class="text-xl">👤</span>
                    <span class="font-medium">Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-red-400 transition">
                        <span class="text-xl">🚪</span>
                        <span class="font-medium">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
