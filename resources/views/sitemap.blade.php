<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- ===== Pages statiques ===== --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ url('/login') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ url('/register') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/leaderboard') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ url('/shop') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>{{ url('/marketplace') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- ===== Catalogue des cartes ===== --}}
    <url>
        <loc>{{ url('/cards') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @foreach ($cards as $card)
    <url>
        <loc>{{ url('/cards/' . $card->id) }}</loc>
        <lastmod>{{ $card->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- ===== Catalogue des attaques ===== --}}
    <url>
        <loc>{{ url('/attacks') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($attacks as $attack)
    <url>
        <loc>{{ url('/attacks/' . $attack->id) }}</loc>
        <lastmod>{{ $attack->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- ===== Factions ===== --}}
    <url>
        <loc>{{ url('/factions') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($factions as $faction)
    <url>
        <loc>{{ url('/factions/' . $faction->id) }}</loc>
        <lastmod>{{ $faction->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

    {{-- ===== Tournois (hors brouillons et annulés) ===== --}}
    <url>
        <loc>{{ url('/tournaments') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach ($tournaments as $tournament)
    <url>
        <loc>{{ url('/tournaments/' . $tournament->id) }}</loc>
        <lastmod>{{ $tournament->updated_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>
