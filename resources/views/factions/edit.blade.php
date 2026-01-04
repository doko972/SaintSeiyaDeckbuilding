<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Modifier') }} : {{ $faction->name }}
            </h2>
            <a href="{{ route('factions.show', $faction) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                ← Retour
            </a>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-800 via-gray-900 to-black py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 overflow-hidden">
                <div class="p-6" style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});">
                    <h3 class="text-xl font-bold text-white">✏️ Modifier la faction</h3>
                </div>

                <form method="POST" action="{{ route('admin.factions.update', $faction) }}" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom de la faction *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $faction->name) }}" required
                               class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:ring-purple-500">{{ old('description', $faction->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Couleurs -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="color_primary" class="block text-sm font-medium text-gray-300 mb-2">Couleur primaire *</label>
                            <div class="flex gap-3">
                                <input type="color" name="color_primary" id="color_primary" value="{{ old('color_primary', $faction->color_primary) }}" required
                                       class="w-16 h-12 rounded-lg border-2 border-white/30 cursor-pointer">
                                <input type="text" id="color_primary_text" value="{{ old('color_primary', $faction->color_primary) }}"
                                       class="flex-1 bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white uppercase"
                                       onchange="document.getElementById('color_primary').value = this.value">
                            </div>
                        </div>
                        <div>
                            <label for="color_secondary" class="block text-sm font-medium text-gray-300 mb-2">Couleur secondaire *</label>
                            <div class="flex gap-3">
                                <input type="color" name="color_secondary" id="color_secondary" value="{{ old('color_secondary', $faction->color_secondary) }}" required
                                       class="w-16 h-12 rounded-lg border-2 border-white/30 cursor-pointer">
                                <input type="text" id="color_secondary_text" value="{{ old('color_secondary', $faction->color_secondary) }}"
                                       class="flex-1 bg-white/10 border border-white/30 rounded-lg px-4 py-2 text-white uppercase"
                                       onchange="document.getElementById('color_secondary').value = this.value">
                            </div>
                        </div>
                    </div>

                    <!-- Prévisualisation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Prévisualisation</label>
                        <div id="preview" class="h-24 rounded-xl transition-all duration-300"
                             style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});">
                        </div>
                    </div>

                    <!-- Image actuelle -->
                    @if($faction->image)
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Image actuelle</label>
                            <img src="{{ Storage::url($faction->image) }}" alt="{{ $faction->name }}" class="w-32 h-32 object-cover rounded-xl border-2 border-white/30">
                        </div>
                    @endif

                    <!-- Nouvelle image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-300 mb-2">Nouvelle image (optionnel)</label>
                        <input type="file" name="image" id="image" accept="image/*"
                               class="w-full bg-white/10 border border-white/30 rounded-lg px-4 py-3 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white hover:file:bg-purple-700">
                        @error('image')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold rounded-lg hover:from-yellow-400 hover:to-orange-400 transition">
                            ✅ Enregistrer les modifications
                        </button>
                        <a href="{{ route('factions.show', $faction) }}" class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-500 transition">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('color_primary').addEventListener('input', function() {
            document.getElementById('color_primary_text').value = this.value;
            updatePreview();
        });
        document.getElementById('color_secondary').addEventListener('input', function() {
            document.getElementById('color_secondary_text').value = this.value;
            updatePreview();
        });

        function updatePreview() {
            const primary = document.getElementById('color_primary').value;
            const secondary = document.getElementById('color_secondary').value;
            document.getElementById('preview').style.background = `linear-gradient(135deg, ${primary}, ${secondary})`;
        }
    </script>
</x-app-layout>