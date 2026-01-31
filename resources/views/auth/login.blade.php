<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Session Error (connexion depuis un autre appareil) -->
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-900/50 border border-red-500 rounded-lg text-red-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Connexion</h2>
        <p class="text-purple-300 text-sm">Entrez dans le Sanctuaire</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-purple-200">Email</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    📧
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
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
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded bg-gray-700 border-gray-600 text-purple-600 shadow-sm focus:ring-purple-500">
                <span class="ms-2 text-sm text-purple-300">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-400 hover:text-purple-300 transition" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                🌟 Se connecter
            </button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <span class="text-gray-400">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" class="text-yellow-400 hover:text-yellow-300 font-semibold ml-1 transition">
                Rejoindre les Chevaliers
            </a>
        </div>
    </form>
</x-guest-layout>