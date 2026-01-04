<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Inscription</h2>
        <p class="text-purple-300 text-sm">Deviens un Chevalier</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-purple-200">Nom de Chevalier</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    ⚔️
                </span>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="Seiya">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-purple-200">Email</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    📧
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="chevalier@sanctuaire.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-purple-200">Mot de passe</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    🔒
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-purple-200">Confirmer le mot de passe</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    🔐
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-gray-900 font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                🛡️ Créer mon compte
            </button>
        </div>

        <!-- Login Link -->
        <div class="mt-6 text-center">
            <span class="text-gray-400">Déjà un compte ?</span>
            <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 font-semibold ml-1 transition">
                Se connecter
            </a>
        </div>
    </form>
</x-guest-layout>