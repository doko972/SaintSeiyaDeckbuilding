<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end items-center mb-3">
                <a href="{{ route('admin.musics.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-amber-500 text-gray-900 font-bold rounded-xl hover:from-yellow-400 hover:to-amber-400 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-yellow-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>
            </div>

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-purple-600 to-indigo-600">
                    <h3 class="text-xl font-bold text-white">🎵 Modifier la musique</h3>
                </div>

                <form method="POST" action="{{ route('admin.musics.update', $music) }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom de la musique *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $music->name) }}" required
                            placeholder="Ex: Battle Theme - Sanctuary"
                            class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fichier audio actuel -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Fichier audio actuel</label>
                        <div class="bg-white/5 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                    🎵
                                </div>
                                <span class="text-gray-300">{{ basename($music->file_path) }}</span>
                            </div>
                            <audio controls class="w-full">
                                <source src="{{ Storage::url($music->file_path) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    </div>

                    <!-- Nouveau fichier audio (optionnel) -->
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-300 mb-2">Remplacer le fichier (optionnel - MP3, WAV, OGG - max 20MB)</label>
                        <div class="relative">
                            <input type="file" name="file" id="file" accept=".mp3,.wav,.ogg"
                                class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-500 file:text-white file:font-semibold hover:file:bg-purple-400 file:cursor-pointer">
                        </div>
                        @error('file')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror

                        <!-- Apercu audio -->
                        <div id="audioPreview" class="mt-3 hidden">
                            <p class="text-sm text-gray-400 mb-2">Nouveau fichier :</p>
                            <audio id="previewPlayer" controls class="w-full"></audio>
                        </div>
                    </div>

                    <!-- Type et Volume -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-300 mb-2">Type de musique *</label>
                            <select name="type" id="type" required
                                class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white focus:border-purple-500 focus:ring-purple-500">
                                <option value="battle" {{ old('type', $music->type) == 'battle' ? 'selected' : '' }}>⚔️ Combat</option>
                                <option value="menu" {{ old('type', $music->type) == 'menu' ? 'selected' : '' }}>🏠 Menu</option>
                                <option value="victory" {{ old('type', $music->type) == 'victory' ? 'selected' : '' }}>🏆 Victoire</option>
                                <option value="defeat" {{ old('type', $music->type) == 'defeat' ? 'selected' : '' }}>💀 Defaite</option>
                            </select>
                        </div>
                        <div>
                            <label for="volume" class="block text-sm font-medium text-gray-300 mb-2">Volume par defaut *</label>
                            <div class="flex items-center gap-4">
                                <input type="range" name="volume" id="volume" value="{{ old('volume', $music->volume) }}" min="0" max="100"
                                    class="flex-1 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-purple-500">
                                <span id="volumeValue" class="text-white font-semibold w-12 text-center">{{ $music->volume }}%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actif -->
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $music->is_active) ? 'checked' : '' }}
                            class="w-5 h-5 rounded bg-white/10 border-white/30 text-purple-500 focus:ring-purple-500">
                        <label for="is_active" class="text-gray-300">Activer cette musique</label>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:from-purple-500 hover:to-indigo-500 transition">
                            ✅ Enregistrer les modifications
                        </button>
                        <a href="{{ route('admin.musics.index') }}"
                            class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500 transition">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Volume slider
        const volumeSlider = document.getElementById('volume');
        const volumeValue = document.getElementById('volumeValue');
        volumeSlider.addEventListener('input', function() {
            volumeValue.textContent = this.value + '%';
        });

        // Audio preview
        const fileInput = document.getElementById('file');
        const audioPreview = document.getElementById('audioPreview');
        const previewPlayer = document.getElementById('previewPlayer');

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                previewPlayer.src = url;
                audioPreview.classList.remove('hidden');
            } else {
                audioPreview.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
