<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Mot de passe oublié</h2>
        <p class="text-purple-300 text-sm">Récupère l'accès au Sanctuaire</p>
    </div>

    <div class="mb-4 text-sm text-gray-400">
        Indique ton adresse email et nous t'enverrons un lien pour réinitialiser ton mot de passe.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-purple-200">Email</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    📧
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                       placeholder="chevalier@sanctuaire.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="mt-6">
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                ✉️ Envoyer le lien
            </button>
        </div>

        <!-- Back to Login -->
        <div class="mt-6 text-center">
            <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300 transition">
                ← Retour à la connexion
            </a>
        </div>
    </form>
</x-guest-layout>