<nav x-data="{ open: false, gameMenuOpen: false, cardsMenuOpen: false, commerceMenuOpen: false }"
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
                            <span class="text-2xl group-hover:scale-110 transition-transform duration-300">&#9876;</span>
                        @endif
                        <span
                            class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 via-amber-300 to-yellow-400 group-hover:from-yellow-300 group-hover:to-amber-200 transition-all duration-300">
                            Saint Seiya
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex items-center">

                    <!-- Menu deroulant "Jouer" -->
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
                                        &#129302;
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
                                        &#127942;
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
                                        <div class="text-xs text-gray-500">Joueurs en temps reel</div>
                                    </div>
                                </a>

                                <!-- Tournois -->
                                <a href="{{ route('tournaments.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('tournaments.*')
                                       ? 'bg-gradient-to-r from-yellow-600/30 to-orange-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#127941;
                                    </span>
                                    <div class="flex-1">
                                        <div class="font-semibold">Tournois</div>
                                        <div class="text-xs text-gray-500">Competitions et recompenses</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Menu deroulant "Cartes" -->
                    <div class="relative" @click.away="cardsMenuOpen = false">
                        <button @click="cardsMenuOpen = !cardsMenuOpen"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                                {{ request()->routeIs('collection.*') || request()->routeIs('decks.*') || request()->routeIs('fusion.*')
                                    ? 'bg-purple-500/30 text-yellow-400 shadow-lg shadow-purple-500/20'
                                    : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            Cartes
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200"
                                :class="{ 'rotate-180': cardsMenuOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="cardsMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute left-0 top-full mt-2 w-64 rounded-xl shadow-2xl bg-gray-900/95 backdrop-blur-md border border-purple-500/30 overflow-hidden"
                            style="display: none;">
                            <div class="py-2">
                                <!-- Collection -->
                                <a href="{{ route('collection.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('collection.*')
                                       ? 'bg-gradient-to-r from-purple-600/30 to-indigo-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#127183;
                                    </span>
                                    <div>
                                        <div class="font-semibold">Collection</div>
                                        <div class="text-xs text-gray-500">Toutes vos cartes</div>
                                    </div>
                                </a>

                                <!-- Mes Decks -->
                                <a href="{{ route('decks.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('decks.*')
                                       ? 'bg-gradient-to-r from-indigo-600/30 to-blue-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#128218;
                                    </span>
                                    <div>
                                        <div class="font-semibold">Mes Decks</div>
                                        <div class="text-xs text-gray-500">Gerez vos decks</div>
                                    </div>
                                </a>

                                <!-- Fusion -->
                                <a href="{{ route('fusion.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('fusion.*')
                                       ? 'bg-gradient-to-r from-orange-600/30 to-yellow-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-orange-500 to-yellow-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#9889;
                                    </span>
                                    <div>
                                        <div class="font-semibold">Fusion</div>
                                        <div class="text-xs text-gray-500">Ameliorez vos cartes</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Menu deroulant "Commerce" -->
                    <div class="relative" @click.away="commerceMenuOpen = false">
                        <button @click="commerceMenuOpen = !commerceMenuOpen"
                            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                                {{ request()->routeIs('shop.*') || request()->routeIs('sell.*')
                                    ? 'bg-yellow-500/30 text-yellow-400 shadow-lg shadow-yellow-500/20'
                                    : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Commerce
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200"
                                :class="{ 'rotate-180': commerceMenuOpen }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="commerceMenuOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95 -translate-y-2"
                            x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute left-0 top-full mt-2 w-64 rounded-xl shadow-2xl bg-gray-900/95 backdrop-blur-md border border-purple-500/30 overflow-hidden"
                            style="display: none;">
                            <div class="py-2">
                                <!-- Boutique -->
                                <a href="{{ route('shop.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('shop.*')
                                       ? 'bg-gradient-to-r from-yellow-600/30 to-amber-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#128722;
                                    </span>
                                    <div>
                                        <div class="font-semibold">Boutique</div>
                                        <div class="text-xs text-gray-500">Achetez des boosters</div>
                                    </div>
                                </a>

                                <!-- Vente -->
                                <a href="{{ route('sell.index') }}"
                                    class="group flex items-center gap-3 px-4 py-3 transition-all duration-200
                                   {{ request()->routeIs('sell.*')
                                       ? 'bg-gradient-to-r from-green-600/30 to-emerald-600/30 text-yellow-400'
                                       : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                                    <span
                                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-lg">
                                        &#128176;
                                    </span>
                                    <div>
                                        <div class="font-semibold">Vente</div>
                                        <div class="text-xs text-gray-500">Revendez vos cartes</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recompenses -->
                    <a href="{{ route('rewards.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300
                       {{ request()->routeIs('rewards.*')
                           ? 'bg-pink-500/30 text-yellow-400 shadow-lg shadow-pink-500/20'
                           : 'text-gray-300 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                        Bonus
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
                    <span id="nav-coins-desktop" class="font-bold text-yellow-400">{{ number_format(auth()->user()->coins ?? 0) }}</span>
                </div>

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
                                &#128100; Profil
                            </a>

                            <!-- Admin Section -->
                            @if (auth()->user()->isAdmin())
                                <div class="border-t border-purple-500/20 mt-1 pt-1">
                                    <div class="px-4 py-1 text-xs font-semibold text-red-400 uppercase tracking-wider">
                                        &#128081; Administration
                                    </div>
                                    <a href="{{ route('cards.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        &#127183; Cartes (CRUD)
                                    </a>
                                    <a href="{{ route('attacks.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        &#9876; Attaques (CRUD)
                                    </a>
                                    <a href="{{ route('admin.cards.create') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        &#10133; Creer une carte
                                    </a>
                                    <a href="{{ route('admin.tournaments.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                                        &#127942; Gestion tournois
                                    </a>
                                </div>
                            @endif

                            <!-- Deconnexion -->
                            <div class="border-t border-purple-500/20 mt-1 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-red-500/20 hover:text-red-400 transition">
                                        &#128682; Deconnexion
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
                    <span class="text-yellow-400 text-sm">&#129689;</span>
                    <span id="nav-coins-mobile" class="font-bold text-yellow-400 text-sm">{{ auth()->user()->coins ?? 0 }}</span>
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
        class="hidden sm:hidden bg-gray-900/98 backdrop-blur-md border-t border-purple-500/20 max-h-[80vh] overflow-y-auto">
        <div class="pt-2 pb-3 space-y-1 px-4">

            <!-- Section Jouer -->
            <div class="py-2">
                <div class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-2 px-2">&#9876; Jouer</div>
                <a href="{{ route('game.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('game.*') ? 'bg-blue-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#129302;</span>
                    <span class="font-medium text-sm">Combat PvE</span>
                </a>
                <a href="{{ route('pvp.lobby') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('pvp.*') ? 'bg-red-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#127942;</span>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-sm">Arena PvP</span>
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                    </div>
                </a>
                <a href="{{ route('tournaments.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('tournaments.*') ? 'bg-yellow-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#127941;</span>
                    <span class="font-medium text-sm">Tournois</span>
                </a>
            </div>

            <!-- Section Cartes -->
            <div class="py-2 border-t border-purple-500/20">
                <div class="text-xs font-semibold text-purple-400 uppercase tracking-wider mb-2 px-2">&#127183; Cartes</div>
                <a href="{{ route('collection.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('collection.*') ? 'bg-purple-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#127183;</span>
                    <span class="font-medium text-sm">Collection</span>
                </a>
                <a href="{{ route('decks.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('decks.*') ? 'bg-indigo-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#128218;</span>
                    <span class="font-medium text-sm">Mes Decks</span>
                </a>
                <a href="{{ route('fusion.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('fusion.*') ? 'bg-orange-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#9889;</span>
                    <span class="font-medium text-sm">Fusion</span>
                </a>
            </div>

            <!-- Section Commerce -->
            <div class="py-2 border-t border-purple-500/20">
                <div class="text-xs font-semibold text-yellow-400 uppercase tracking-wider mb-2 px-2">&#128176; Commerce</div>
                <a href="{{ route('shop.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('shop.*') ? 'bg-yellow-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#128722;</span>
                    <span class="font-medium text-sm">Boutique</span>
                </a>
                <a href="{{ route('sell.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('sell.*') ? 'bg-green-500/30 text-green-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#128176;</span>
                    <span class="font-medium text-sm">Vente</span>
                </a>
            </div>

            <!-- Bonus -->
            <div class="py-2 border-t border-purple-500/20">
                <div class="text-xs font-semibold text-pink-400 uppercase tracking-wider mb-2 px-2">&#127873; Bonus</div>
                <a href="{{ route('rewards.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('rewards.*') ? 'bg-pink-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#127873;</span>
                    <span class="font-medium text-sm">Recompenses</span>
                </a>
            </div>

            <!-- Factions -->
            <div class="py-2 border-t border-purple-500/20">
                <a href="{{ route('factions.index') }}"
                    class="flex items-center gap-3 px-4 py-2 rounded-lg transition-all duration-200
                   {{ request()->routeIs('factions.*') ? 'bg-cyan-500/30 text-yellow-400' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <span class="text-lg">&#127984;</span>
                    <span class="font-medium text-sm">Factions</span>
                </a>
            </div>

            @if (auth()->user()->isAdmin())
                <div class="py-2 border-t border-purple-500/20">
                    <div class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-2 px-2">&#128081; Administration</div>
                    <a href="{{ route('cards.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                        <span class="text-lg">&#127183;</span>
                        <span class="font-medium text-sm">Cartes (CRUD)</span>
                    </a>
                    <a href="{{ route('attacks.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                        <span class="text-lg">&#9876;</span>
                        <span class="font-medium text-sm">Attaques (CRUD)</span>
                    </a>
                    <a href="{{ route('admin.tournaments.index') }}"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-white transition">
                        <span class="text-lg">&#127942;</span>
                        <span class="font-medium text-sm">Gestion tournois</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Mobile User Section -->
        <div class="pt-3 pb-3 border-t border-purple-500/20 px-4">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-medium text-white text-sm flex items-center gap-2">
                        {{ Auth::user()->name }}
                        @if (auth()->user()->isAdmin())
                            <span class="px-2 py-0.5 text-xs bg-red-500/30 text-red-400 rounded">Admin</span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('profile.edit') }}"
                    class="flex-1 flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-gray-300 hover:bg-white/10 hover:text-white transition text-sm">
                    <span>&#128100;</span>
                    <span>Profil</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-gray-300 hover:bg-red-500/20 hover:text-red-400 transition text-sm">
                        <span>&#128682;</span>
                        <span>Deconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
