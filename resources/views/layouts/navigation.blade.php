<nav x-data="{ open: false, gameMenuOpen: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        @php
                            $logoPath = public_path('images/logo.webp');
                        @endphp
                        @if(file_exists($logoPath))
                            <img src="{{ asset('images/logo.webp') }}" alt="Saint Seiya Logo" class="h-10 w-auto">
                        @else
                            <span class="text-2xl">⚔️</span>
                        @endif
                        <span class="text-xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">
                            Saint Seiya
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Menu déroulant "Jouer" -->
                    <div class="relative inline-flex" @click.away="gameMenuOpen = false">
                        <button @click="gameMenuOpen = !gameMenuOpen" 
                                class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none
                                {{ request()->routeIs('game.*') || request()->routeIs('pvp.*') 
                                    ? 'border-indigo-400 text-gray-900' 
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <span>⚔️ Jouer</span>
                            <svg class="ml-1 h-4 w-4 transition-transform duration-200" :class="{'rotate-180': gameMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="gameMenuOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             class="absolute z-50 mt-2 w-56 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <!-- Combat PvE -->
                                <a href="{{ route('game.index') }}" 
                                   class="group flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-150
                                   {{ request()->routeIs('game.*') ? 'bg-gradient-to-r from-blue-50 to-indigo-50 text-indigo-600 font-medium' : '' }}">
                                    <span class="text-2xl group-hover:scale-110 transition-transform">🤖</span>
                                    <div>
                                        <div class="font-semibold">Combat PvE</div>
                                        <div class="text-xs text-gray-500">Affrontez l'IA</div>
                                    </div>
                                </a>
                                
                                <!-- Arena PvP -->
                                <a href="{{ route('pvp.lobby') }}" 
                                   class="group flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-orange-50 transition-all duration-150
                                   {{ request()->routeIs('pvp.*') ? 'bg-gradient-to-r from-red-50 to-orange-50 text-red-600 font-medium' : '' }}">
                                    <span class="text-2xl group-hover:scale-110 transition-transform">🆚</span>
                                    <div class="flex-1">
                                        <div class="font-semibold flex items-center gap-2">
                                            Arena PvP
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500">Joueurs en temps réel</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <x-nav-link :href="route('collection.index')" :active="request()->routeIs('collection.*')">
                        {{ __('🎴 Collection') }}
                    </x-nav-link>

                    <x-nav-link :href="route('decks.index')" :active="request()->routeIs('decks.*')">
                        {{ __('📚 Mes Decks') }}
                    </x-nav-link>

                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                        {{ __('🛒 Boutique') }}
                    </x-nav-link>

                    <x-nav-link :href="route('factions.index')" :active="request()->routeIs('factions.*')">
                        {{ __('🛡️ Factions') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-1 pt-1 border-b-2 border-red-400 text-sm font-medium leading-5 text-red-600 focus:outline-none focus:border-red-700 transition duration-150 ease-in-out">
                                    <span>👑 Admin</span>
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('cards.index')">
                                    🃏 Cartes (CRUD)
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('attacks.index')">
                                    ⚔️ Attaques (CRUD)
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('admin.cards.create')">
                                    ➕ Créer une carte
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Coins Display -->
                <div class="mr-4 px-3 py-1 bg-gradient-to-r from-yellow-400 to-amber-500 rounded-full flex items-center gap-1 shadow-md">
                    <span class="text-lg">🪙</span>
                    <span class="font-bold text-white">{{ auth()->user()->coins ?? 0 }}</span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            @if(auth()->user()->isAdmin())
                                <span class="ml-2 px-2 py-1 text-xs bg-red-500 text-white rounded">Admin</span>
                            @endif

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Jouer submenu mobile -->
            <div class="px-4 py-2">
                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">⚔️ Jouer</div>
                <x-responsive-nav-link :href="route('game.index')" :active="request()->routeIs('game.*')">
                    🤖 {{ __('Combat PvE') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('pvp.lobby')" :active="request()->routeIs('pvp.*')">
                    🆚 {{ __('Arena PvP') }}
                </x-responsive-nav-link>
            </div>

            <x-responsive-nav-link :href="route('collection.index')" :active="request()->routeIs('collection.*')">
                {{ __('🎴 Collection') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('decks.index')" :active="request()->routeIs('decks.*')">
                {{ __('📚 Mes Decks') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                {{ __('🛒 Boutique') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('factions.index')" :active="request()->routeIs('factions.*')">
                {{ __('🛡️ Factions') }}
            </x-responsive-nav-link>

            @if(auth()->user()->isAdmin())
                <div class="px-4 py-2 border-t border-gray-200 mt-2">
                    <div class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-2">👑 Admin</div>
                    <x-responsive-nav-link :href="route('cards.index')">
                        {{ __('🃏 Cartes (CRUD)') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('attacks.index')">
                        {{ __('⚔️ Attaques (CRUD)') }}
                    </x-responsive-nav-link>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 flex items-center gap-2">
                    {{ Auth::user()->name }}
                    <span class="px-2 py-1 text-xs bg-gradient-to-r from-yellow-400 to-amber-500 text-white rounded-full">
                        🪙 {{ auth()->user()->coins ?? 0 }}
                    </span>
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>