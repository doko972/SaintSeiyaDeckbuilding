<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    /**
     * Liste des musiques (admin)
     */
    public function index()
    {
        $musics = Music::orderBy('type')->orderBy('name')->get();
        return view('admin.musics.index', compact('musics'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.musics.create');
    }

    /**
     * Enregistrer une nouvelle musique
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:mp3,wav,ogg|max:20480', // 20MB max
            'type' => 'required|in:battle,menu,victory,defeat',
            'volume' => 'required|integer|min:0|max:100',
        ]);

        $path = $request->file('file')->store('musics', 'public');

        Music::create([
            'name' => $request->name,
            'file_path' => $path,
            'type' => $request->type,
            'volume' => $request->volume,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.musics.index')
            ->with('success', 'Musique ajoutée avec succès !');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(Music $music)
    {
        return view('admin.musics.edit', compact('music'));
    }

    /**
     * Mettre à jour une musique
     */
    public function update(Request $request, Music $music)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:mp3,wav,ogg|max:20480',
            'type' => 'required|in:battle,menu,victory,defeat',
            'volume' => 'required|integer|min:0|max:100',
        ]);

        $data = [
            'name' => $request->name,
            'type' => $request->type,
            'volume' => $request->volume,
            'is_active' => $request->has('is_active'),
        ];

        // Si un nouveau fichier est uploadé
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            if ($music->file_path) {
                Storage::disk('public')->delete($music->file_path);
            }
            $data['file_path'] = $request->file('file')->store('musics', 'public');
        }

        $music->update($data);

        return redirect()->route('admin.musics.index')
            ->with('success', 'Musique mise à jour avec succès !');
    }

    /**
     * Supprimer une musique
     */
    public function destroy(Music $music)
    {
        // Supprimer le fichier
        if ($music->file_path) {
            Storage::disk('public')->delete($music->file_path);
        }

        $music->delete();

        return redirect()->route('admin.musics.index')
            ->with('success', 'Musique supprimée avec succès !');
    }

    /**
     * Activer/Désactiver une musique
     */
    public function toggle(Music $music)
    {
        $music->update(['is_active' => !$music->is_active]);

        return back()->with('success', 'Statut de la musique mis à jour !');
    }
}
