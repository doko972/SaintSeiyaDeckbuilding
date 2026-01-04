<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Résultat du combat') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12 relative overflow-hidden">
        
        <div class="fixed inset-0 z-0 pointer-events-none">
            <img src="{{ asset('images/baniere.webp') }}" alt="" class="w-full h-full object-cover opacity-[0.15]">
        </div>

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 relative z-10">

            @php
                $isWinner = $battle->winner_id === auth()->id();
            @endphp

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden text-center">
                
                <div class="p-8 {{ $isWinner ? 'bg-gradient-to-r from-yellow-600 to-orange-600' : 'bg-gradient-to-r from-gray-600 to-gray-700' }}">
                    <div class="text-6xl mb-4">{{ $isWinner ? '🏆' : '💀' }}</div>
                    <h1 class="text-3xl font-bold text-white">
                        {{ $isWinner ? 'Victoire !' : 'Défaite...' }}
                    </h1>
                </div>

                <div class="p-8">
                    <!-- Versus -->
                    <div class="flex items-center justify-center gap-6 mb-8">
                        <div class="text-center {{ $battle->winner_id === $battle->player1_id ? 'text-yellow-400' : 'text-gray-400' }}">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-2xl font-bold text-white mx-auto mb-2">
                                {{ strtoupper(substr($battle->player1->name, 0, 1)) }}
                            </div>
                            <div class="font-bold">{{ $battle->player1->name }}</div>
                            @if($battle->winner_id === $battle->player1_id)
                                <div class="text-yellow-400 text-sm">🏆 Vainqueur</div>
                            @endif
                        </div>

                        <div class="text-2xl text-gray-500">VS</div>

                        <div class="text-center {{ $battle->winner_id === $battle->player2_id ? 'text-yellow-400' : 'text-gray-400' }}">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-orange-500 flex items-center justify-center text-2xl font-bold text-white mx-auto mb-2">
                                {{ strtoupper(substr($battle->player2->name, 0, 1)) }}
                            </div>
                            <div class="font-bold">{{ $battle->player2->name }}</div>
                            @if($battle->winner_id === $battle->player2_id)
                                <div class="text-yellow-400 text-sm">🏆 Vainqueur</div>
                            @endif
                        </div>
                    </div>

                    <!-- Récompense -->
                    <div class="bg-black/30 rounded-xl p-6 mb-8">
                        <div class="text-gray-400 text-sm mb-2">Récompense</div>
                        <div class="text-3xl font-bold text-yellow-400">
                            🪙 +{{ $isWinner ? '150' : '25' }} pièces
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('pvp.lobby') }}" class="w-full py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold text-lg rounded-xl hover:from-purple-500 hover:to-indigo-500 transition">
                            🎮 Retour au lobby
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-full py-3 bg-gray-600 text-white font-bold rounded-xl hover:bg-gray-500 transition">
                            🏠 Accueil
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>