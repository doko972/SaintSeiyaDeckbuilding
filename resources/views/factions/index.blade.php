<x-app-layout>
    <style>
        /* ========================================
           FOND COSMOS
        ======================================== */
        .cosmos-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: 
                radial-gradient(ellipse at 20% 80%, rgba(120, 0, 255, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255, 0, 100, 0.1) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 50%, rgba(0, 100, 255, 0.1) 0%, transparent 70%),
                linear-gradient(180deg, #0a0a1a 0%, #1a0a2a 50%, #0a1a2a 100%);
        }

        .stars {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255,255,255,0.9), transparent),
                radial-gradient(1px 1px at 230px 80px, #fff, transparent);
            background-size: 350px 200px;
            animation: twinkle 5s ease-in-out infinite;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }

        /* ========================================
           FACTION CARDS
        ======================================== */
        .faction-card {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .faction-card:hover {
            transform: translateY(-10px);
            border-color: var(--faction-color);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5),
                        0 0 40px var(--faction-glow);
        }

        /* Header de la faction */
        .faction-header {
            position: relative;
            height: 140px;
            overflow: hidden;
        }

        .faction-header-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--color1), var(--color2));
        }

        .faction-header-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.5;
            transition: all 0.5s ease;
        }

        .faction-card:hover .faction-header-image {
            opacity: 0.7;
            transform: scale(1.1);
        }

        /* Effet brillant au survol */
        .faction-shine {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }

        .faction-card:hover .faction-shine {
            transform: translateX(100%);
        }

        /* Badge nombre de cartes */
        .faction-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .faction-badge-text {
            color: white;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* Contenu de la faction */
        .faction-content {
            padding: 1.5rem;
        }

        .faction-name {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
        }

        .faction-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            box-shadow: 0 0 10px var(--faction-color);
        }

        .faction-description {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Aperçu des cartes */
        .faction-cards-preview {
            display: flex;
            margin-left: -8px;
        }

        .faction-card-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 3px solid rgba(15, 15, 35, 0.9);
            overflow: hidden;
            margin-left: -8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .faction-card-avatar:hover {
            transform: scale(1.2);
            z-index: 10;
            border-color: var(--faction-color);
        }

        .faction-card-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .faction-card-avatar-more {
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Actions admin */
        .faction-admin-actions {
            display: flex;
            gap: 8px;
            padding: 0 1.5rem 1.5rem;
        }

        .faction-admin-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .faction-admin-btn.edit {
            background: rgba(251, 191, 36, 0.15);
            color: #FBBF24;
            border-color: rgba(251, 191, 36, 0.3);
        }

        .faction-admin-btn.edit:hover {
            background: rgba(251, 191, 36, 0.25);
            border-color: rgba(251, 191, 36, 0.5);
        }

        .faction-admin-btn.delete {
            background: rgba(239, 68, 68, 0.15);
            color: #EF4444;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .faction-admin-btn.delete:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.5);
        }

        /* ========================================
           INTRO BANNER
        ======================================== */
        .intro-banner {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            overflow: hidden;
        }

        .intro-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, #FFD700, transparent);
        }

        .intro-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .intro-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .intro-text {
            color: rgba(255, 255, 255, 0.6);
            max-width: 500px;
            margin: 0 auto;
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        <!-- Fond Cosmos -->
        <div class="cosmos-bg">
            <div class="stars"></div>
        </div>

        <!-- Contenu -->
        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            
            <!-- Header de page -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <span class="text-4xl">🏛️</span>
                        Factions
                    </h1>
                    <p class="text-gray-400 mt-1">Les armées légendaires du Sanctuaire</p>
                </div>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.factions.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouvelle Faction
                    </a>
                @endif
            </div>

            <!-- Intro Banner -->
            <div class="intro-banner mb-8">
                <div class="intro-icon">⚔️</div>
                <h2 class="intro-title">Les Factions du Sanctuaire</h2>
                <p class="intro-text">
                    Découvrez les différentes factions qui s'affrontent pour la domination. 
                    Chaque faction possède ses propres chevaliers et pouvoirs uniques.
                </p>
            </div>

            <!-- Grille des factions -->
            @if($factions->isEmpty())
                <div class="bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 p-12 text-center">
                    <div class="text-7xl mb-6">🏛️</div>
                    <h3 class="text-2xl font-bold text-white mb-3">Aucune faction</h3>
                    <p class="text-gray-400 mb-8 max-w-md mx-auto">
                        Les factions n'ont pas encore été créées.
                    </p>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.factions.create') }}" 
                           class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-500 hover:to-indigo-500 transition-all duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Créer la première faction
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($factions as $faction)
                        <div class="faction-card" 
                             style="--faction-color: {{ $faction->color_primary }}; --faction-glow: {{ $faction->color_primary }}40; --color1: {{ $faction->color_primary }}; --color2: {{ $faction->color_secondary }};">
                            
                            <a href="{{ route('factions.show', $faction) }}" class="block">
                                <!-- Header -->
                                <div class="faction-header">
                                    <div class="faction-header-bg"></div>
                                    
                                    @if($faction->image)
                                        <img src="{{ Storage::url($faction->image) }}" 
                                             alt="{{ $faction->name }}"
                                             class="faction-header-image">
                                    @endif

                                    <div class="faction-shine"></div>

                                    <!-- Badge nombre de cartes -->
                                    <div class="faction-badge">
                                        <span class="faction-badge-text">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                            </svg>
                                            {{ $faction->cards->count() }} cartes
                                        </span>
                                    </div>
                                </div>

                                <!-- Contenu -->
                                <div class="faction-content">
                                    <h3 class="faction-name">
                                        <span class="faction-dot" style="background: {{ $faction->color_primary }};"></span>
                                        {{ $faction->name }}
                                    </h3>
                                    
                                    @if($faction->description)
                                        <p class="faction-description">{{ $faction->description }}</p>
                                    @else
                                        <p class="faction-description text-gray-500 italic">Aucune description</p>
                                    @endif

                                    <!-- Aperçu des cartes -->
                                    <div class="faction-cards-preview">
                                        @forelse($faction->cards->take(5) as $card)
                                            <div class="faction-card-avatar"
                                                 style="background: linear-gradient(135deg, {{ $faction->color_primary }}, {{ $faction->color_secondary }});"
                                                 title="{{ $card->name }}">
                                                @if($card->image_primary)
                                                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                                                @else
                                                    <span class="flex items-center justify-center w-full h-full text-white text-xs font-bold">
                                                        {{ strtoupper(substr($card->name, 0, 2)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-gray-500 text-sm italic">Aucune carte</span>
                                        @endforelse
                                        
                                        @if($faction->cards->count() > 5)
                                            <div class="faction-card-avatar faction-card-avatar-more"
                                                 style="background: rgba(255, 255, 255, 0.1);">
                                                +{{ $faction->cards->count() - 5 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>

                            <!-- Actions Admin -->
                            @if(auth()->user()->isAdmin())
                                <div class="faction-admin-actions">
                                    <a href="{{ route('admin.factions.edit', $faction) }}" 
                                       class="faction-admin-btn edit"
                                       onclick="event.stopPropagation();">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Modifier
                                    </a>
                                    <form action="{{ route('admin.factions.destroy', $faction) }}" method="POST" class="flex-1"
                                          onsubmit="event.stopPropagation(); return confirm('Êtes-vous sûr de vouloir supprimer cette faction ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="faction-admin-btn delete w-full">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>