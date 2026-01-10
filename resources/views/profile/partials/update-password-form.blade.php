<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Mot de passe actuel -->
        <div>
            <x-input-label for="update_password_current_password" :value="__('Mot de passe actuel')" class="!text-gray-300 mb-2" />
            <x-text-input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full !bg-gray-800/50 !border-gray-700 !text-white !rounded-lg focus:!border-yellow-500 focus:!ring-yellow-500 placeholder:!text-gray-500" 
                autocomplete="current-password"
                placeholder="Entrez votre mot de passe actuel"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 !text-red-400" />
        </div>

        <!-- Nouveau mot de passe -->
        <div>
            <x-input-label for="update_password_password" :value="__('Nouveau mot de passe')" class="!text-gray-300 mb-2" />
            <x-text-input 
                id="update_password_password" 
                name="password" 
                type="password" 
                class="w-full !bg-gray-800/50 !border-gray-700 !text-white !rounded-lg focus:!border-yellow-500 focus:!ring-yellow-500 placeholder:!text-gray-500" 
                autocomplete="new-password"
                placeholder="Entrez un nouveau mot de passe"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 !text-red-400" />
        </div>

        <!-- Confirmation -->
        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmer le mot de passe')" class="!text-gray-300 mb-2" />
            <x-text-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                class="w-full !bg-gray-800/50 !border-gray-700 !text-white !rounded-lg focus:!border-yellow-500 focus:!ring-yellow-500 placeholder:!text-gray-500" 
                autocomplete="new-password"
                placeholder="Confirmez le nouveau mot de passe"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 !text-red-400" />
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-semibold rounded-lg hover:from-yellow-400 hover:to-amber-400 transition shadow-lg shadow-yellow-500/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ __('Modifier le mot de passe') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-400 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Mot de passe modifié !') }}
                </p>
            @endif
        </div>
    </form>
</section>