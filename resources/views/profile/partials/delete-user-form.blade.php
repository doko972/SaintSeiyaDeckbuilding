<section class="space-y-6">
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="!bg-gradient-to-r !from-red-600 !to-red-700 !shadow-lg !shadow-red-500/30 hover:!from-red-500 hover:!to-red-600"
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        {{ __('Supprimer mon compte') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-gray-900 rounded-xl">
            @csrf
            @method('delete')

            <!-- Header -->
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">
                        {{ __('Supprimer votre compte ?') }}
                    </h2>
                    <p class="text-red-400 text-sm">Cette action est irréversible</p>
                </div>
            </div>

            <!-- Warning message -->
            <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
                <p class="text-gray-300 text-sm">
                    {{ __('Une fois votre compte supprimé, toutes vos données seront définitivement effacées : cartes, decks, progression, statistiques... Veuillez entrer votre mot de passe pour confirmer.') }}
                </p>
            </div>

            <!-- Password input -->
            <div class="mb-6">
                <x-input-label for="password" value="{{ __('Mot de passe') }}" class="!text-gray-300 mb-2" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full !bg-gray-800 !border-gray-700 !text-white !rounded-lg focus:!border-red-500 focus:!ring-red-500"
                    placeholder="{{ __('Entrez votre mot de passe') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 !text-red-400" />
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-5 py-2.5 bg-gray-700 text-gray-300 font-semibold rounded-lg hover:bg-gray-600 transition"
                >
                    {{ __('Annuler') }}
                </button>
                <button 
                    type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-500 hover:to-red-600 transition shadow-lg shadow-red-500/30"
                >
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Supprimer définitivement') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>