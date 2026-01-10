<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Nom -->
        <div>
            <x-input-label for="name" :value="__('Nom')" class="!text-gray-300 mb-2" />
            <x-text-input 
                id="name" 
                name="name" 
                type="text" 
                class="w-full !bg-gray-800/50 !border-gray-700 !text-white !rounded-lg focus:!border-purple-500 focus:!ring-purple-500 placeholder:!text-gray-500" 
                :value="old('name', $user->name)" 
                required 
                autofocus 
                autocomplete="name" 
            />
            <x-input-error class="mt-2 !text-red-400" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="!text-gray-300 mb-2" />
            <x-text-input 
                id="email" 
                name="email" 
                type="email" 
                class="w-full !bg-gray-800/50 !border-gray-700 !text-white !rounded-lg focus:!border-purple-500 focus:!ring-purple-500 placeholder:!text-gray-500" 
                :value="old('email', $user->email)" 
                required 
                autocomplete="username" 
            />
            <x-input-error class="mt-2 !text-red-400" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                    <p class="text-sm text-yellow-400">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" class="underline text-yellow-300 hover:text-yellow-200 ml-1">
                            {{ __('Renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-400">
                            {{ __('Un nouveau lien de vérification a été envoyé.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Submit -->
        <div class="flex items-center gap-4">
            <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg hover:from-blue-500 hover:to-indigo-500 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Enregistrer') }}
            </button>

            @if (session('status') === 'profile-updated')
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
                    {{ __('Enregistré !') }}
                </p>
            @endif
        </div>
    </form>
</section>