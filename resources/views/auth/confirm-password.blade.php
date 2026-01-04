<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Confirmer le mot de passe</h2>
        <p class="text-purple-300 text-sm">Zone sécurisée du Sanctuaire</p>
    </div>

    <div class="mb-4 text-sm text-gray-400">
        Cette zone est protégée. Confirme ton mot de passe pour continuer.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
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

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                🛡️ Confirmer
            </button>
        </div>
    </form>
</x-guest-layout>