<x-guest-layout>
    <div class="text-center mb-6">
        <div class="text-5xl mb-4">📬</div>
        <h2 class="text-2xl font-bold text-white">Vérifie ton email</h2>
        <p class="text-purple-300 text-sm">Une dernière étape avant d'entrer</p>
    </div>

    <div class="mb-4 text-sm text-gray-400 text-center">
        Merci pour ton inscription ! Avant de commencer, vérifie ton adresse email en cliquant sur le lien que nous venons de t'envoyer. Si tu n'as pas reçu l'email, nous pouvons t'en renvoyer un.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 bg-green-900/50 border border-green-500 rounded-lg text-sm text-green-400 text-center">
            ✅ Un nouveau lien de vérification a été envoyé à ton adresse email.
        </div>
    @endif

    <div class="mt-6 flex flex-col gap-4">
        <!-- Resend Email -->
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded-lg shadow-lg transform hover:scale-[1.02] transition-all duration-200">
                ✉️ Renvoyer l'email de vérification
            </button>
        </form>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full py-3 px-4 bg-gray-700 hover:bg-gray-600 text-gray-300 font-semibold rounded-lg transition-all duration-200">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>