<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Nouveau mot de passe</h2>
        <p class="text-purple-300 text-sm">Choisis un nouveau cosmos secret</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-purple-200">Email</label>
            <div class="mt-1 relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-purple-400">
                    📧
                </span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       class="pl-10 w-full bg-gray-700 border-gray-600 text-white placeholder-gray-400 rounded-lg focus:ring-purple-500 focus:border-purple-500">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-purple-200">Nouveau mot de passe</label>
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
                    class="w-full py-3 px-4 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-400 hover:to-emerald-400 text-white font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                🔑 Réinitialiser le mot de passe
            </button>
        </div>
    </form>
</x-guest-layout>