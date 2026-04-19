<?php

namespace App\Http\Controllers;

use App\Models\Attack;
use App\Models\Card;
use App\Models\Faction;
use App\Models\Tournament;

class SitemapController extends Controller
{
    public function index()
    {
        $cards       = Card::select('id', 'updated_at')->orderBy('id')->get();
        $attacks     = Attack::select('id', 'updated_at')->orderBy('id')->get();
        $factions    = Faction::select('id', 'updated_at')->orderBy('id')->get();
        $tournaments = Tournament::select('id', 'updated_at')
            ->whereNotIn('status', [Tournament::STATUS_CANCELLED, Tournament::STATUS_DRAFT])
            ->orderBy('id')
            ->get();

        return response()
            ->view('sitemap', compact('cards', 'attacks', 'factions', 'tournaments'))
            ->header('Content-Type', 'application/xml');
    }
}
