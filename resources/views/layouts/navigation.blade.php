<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
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

                    <x-nav-link :href="route('cards.index')" :active="request()->routeIs('cards.*')">
                        {{ __('Cartes') }}
                    </x-nav-link>

                    <x-nav-link :href="route('factions.index')" :active="request()->routeIs('factions.*')">
                        {{ __('Factions') }}
                    </x-nav-link>

                    <x-nav-link :href="route('attacks.index')" :active="request()->routeIs('attacks.*')">
                        {{ __('Attaques') }}
                    </x-nav-link>

                    <x-nav-link :href="route('decks.index')" :active="request()->routeIs('decks.*')">
                        {{ __('Mes Decks') }}
                    </x-nav-link>

                    <x-nav-link :href="route('game.index')" :active="request()->routeIs('game.*')">
                        {{ __('⚔️ Jouer') }}
                    </x-nav-link>

                    <x-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                        {{ __('🛒 Boutique') }}
                    </x-nav-link>

                    <x-nav-link :href="route('collection.index')" :active="request()->routeIs('collection.*')">
                        {{ __('📚 Collection') }}
                    </x-nav-link>

                    @if(auth()->user()->isAdmin())
                        <x-nav-link :href="route('admin.cards.create')" :active="request()->routeIs('admin.*')" class="text-red-600">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
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
            <x-responsive-nav-link :href="route('cards.index')" :active="request()->routeIs('cards.*')">
                {{ __('Cartes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('factions.index')" :active="request()->routeIs('factions.*')">
                {{ __('Factions') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('attacks.index')" :active="request()->routeIs('attacks.*')">
                {{ __('Attaques') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('decks.index')" :active="request()->routeIs('decks.*')">
                {{ __('Mes Decks') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('game.index')" :active="request()->routeIs('game.*')">
                {{ __('⚔️ Jouer') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shop.index')" :active="request()->routeIs('shop.*')">
                {{ __('🛒 Boutique') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('collection.index')" :active="request()->routeIs('collection.*')">
                {{ __('📚 Collection') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
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