@props([
    'card',
    'size' => 'normal', // small, normal, large
    'interactive' => true,
    'fusionLevel' => 1,
    'boostedStats' => null,
])

@php
    $sizeClasses = [
        'small' => ['card' => 'width: 180px; height: 270px;', 'name' => '0.8rem', 'stat' => '0.7rem', 'label' => '0.4rem', 'header' => '55px', 'cost' => '28px'],
        'normal' => ['card' => 'width: 280px; height: 420px;', 'name' => '1rem', 'stat' => '0.9rem', 'label' => '0.5rem', 'header' => '75px', 'cost' => '36px'],
        'large' => ['card' => 'width: 350px; height: 525px;', 'name' => '1.2rem', 'stat' => '1rem', 'label' => '0.55rem', 'header' => '90px', 'cost' => '42px'],
    ];

    $sizes = $sizeClasses[$size] ?? $sizeClasses['normal'];

    // Use boosted stats if provided, otherwise use card base stats
    $displayHp = $boostedStats ? $boostedStats['health_points'] : $card->health_points;
    $displayEnd = $boostedStats ? $boostedStats['endurance'] : $card->endurance;
    $displayDef = $boostedStats ? $boostedStats['defense'] : $card->defense;
    $displayPwr = $boostedStats ? $boostedStats['power'] : $card->power;
    $displayCos = $card->cosmos; // Cosmos is never boosted
    $hasFusion = $fusionLevel > 1;
@endphp

<style>
    .pro-card-wrapper {
        perspective: 1000px;
        display: inline-block;
    }

    .pro-card-wrapper.interactive .pro-card {
        cursor: pointer;
    }

    .pro-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #1a1a2e;
        transform-style: preserve-3d;
        transition: transform 0.4s ease, box-shadow 0.3s ease;
    }

    .pro-card:hover {
        transform: translateY(-10px) scale(1.02);
    }

    /* Bordures par rareté */
    .pro-card.rarity-common {
        border: 4px solid #6B7280;
    }

    .pro-card.rarity-rare {
        border: 4px solid #3B82F6;
        box-shadow: 0 0 25px rgba(59, 130, 246, 0.5), 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .pro-card.rarity-epic {
        border: 4px solid #8B5CF6;
        box-shadow: 0 0 30px rgba(139, 92, 246, 0.6), 0 20px 40px rgba(0, 0, 0, 0.4);
    }

    .pro-card.rarity-legendary {
        border: 4px solid #FFD700;
        box-shadow: 0 0 40px rgba(255, 215, 0, 0.7), 0 0 80px rgba(255, 100, 0, 0.4), 0 20px 40px rgba(0, 0, 0, 0.4);
        animation: legendaryCardPulse 2s ease-in-out infinite;
    }

    .pro-card.rarity-mythic {
        border: 4px solid transparent;
        background: linear-gradient(#1a1a2e, #1a1a2e) padding-box,
                    linear-gradient(135deg, #FF006E, #8338EC, #3A86FF, #FF006E) border-box;
        box-shadow: 0 0 50px rgba(131, 56, 236, 0.7), 0 0 100px rgba(255, 0, 110, 0.5), 0 20px 40px rgba(0, 0, 0, 0.4);
        animation: mythicCardPulse 3s ease-in-out infinite;
    }

    @keyframes legendaryCardPulse {
        0%, 100% { box-shadow: 0 0 30px rgba(255, 215, 0, 0.5), 0 0 60px rgba(255, 100, 0, 0.3); }
        50% { box-shadow: 0 0 50px rgba(255, 215, 0, 0.9), 0 0 100px rgba(255, 100, 0, 0.6); }
    }

    @keyframes mythicCardPulse {
        0%, 100% { box-shadow: 0 0 50px rgba(131, 56, 236, 0.6), 0 0 100px rgba(255, 0, 110, 0.4); }
        50% { box-shadow: 0 0 70px rgba(58, 134, 255, 0.9), 0 0 120px rgba(131, 56, 236, 0.7); }
    }

    .pro-card:hover.rarity-rare {
        box-shadow: 0 25px 50px rgba(59, 130, 246, 0.5), 0 0 40px rgba(59, 130, 246, 0.4);
    }

    .pro-card:hover.rarity-epic {
        box-shadow: 0 25px 50px rgba(168, 85, 247, 0.6), 0 0 50px rgba(139, 92, 246, 0.5);
    }

    .pro-card:hover.rarity-legendary {
        box-shadow: 0 25px 50px rgba(255, 215, 0, 0.7), 0 0 70px rgba(255, 215, 0, 0.6);
    }

    .pro-card:hover.rarity-mythic {
        box-shadow: 0 25px 50px rgba(255, 0, 110, 0.7), 0 0 80px rgba(131, 56, 236, 0.6);
    }

    /* Contenu */
    .pro-card-content {
        position: relative;
        width: 100%;
        height: 100%;
    }

    /* Image plein écran */
    .pro-card-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
    }

    .pro-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        transition: transform 0.4s ease;
    }

    .pro-card:hover .pro-card-image img {
        transform: scale(1.05);
    }

    .pro-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        background: linear-gradient(180deg, var(--color1), var(--color2));
    }

    /* Overlay dégradé */
    .pro-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            180deg,
            rgba(0, 0, 0, 0.6) 0%,
            transparent 15%,
            transparent 45%,
            rgba(0, 0, 0, 0.85) 100%
        );
        z-index: 2;
        pointer-events: none;
    }

    /* Badge rareté */
    .pro-card-rarity {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }

    .pro-rarity-common {
        background: linear-gradient(135deg, #6B7280, #4B5563);
        color: white;
        border: 1px solid #9CA3AF;
    }

    .pro-rarity-rare {
        background: linear-gradient(135deg, #3B82F6, #1D4ED8);
        color: white;
        border: 1px solid #60A5FA;
    }

    .pro-rarity-epic {
        background: linear-gradient(135deg, #8B5CF6, #6D28D9);
        color: white;
        border: 1px solid #A78BFA;
    }

    .pro-rarity-legendary {
        background: linear-gradient(135deg, #FFD700, #FF8C00, #FF4500);
        color: white;
        border: 1px solid #FBBF24;
        animation: legendaryBadge 1.5s ease-in-out infinite;
    }

    .pro-rarity-mythic {
        background: linear-gradient(135deg, #FF006E, #8338EC, #3A86FF);
        background-size: 200% 200%;
        color: white;
        border: 1px solid #FF006E;
        animation: mythicBadge 3s ease infinite;
    }

    @keyframes legendaryBadge {
        0%, 100% { box-shadow: 0 0 15px rgba(255, 215, 0, 0.7); }
        50% { box-shadow: 0 0 30px rgba(255, 215, 0, 1), 0 0 40px rgba(255, 140, 0, 0.7); }
    }

    @keyframes mythicBadge {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Badge grade */
    .pro-card-grade {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 800;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(4px);
    }

    /* Badge niveau de fusion */
    .pro-card-fusion {
        position: absolute;
        top: 50px;
        right: 12px;
        z-index: 10;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 800;
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
        border: 1px solid rgba(16, 185, 129, 0.5);
        backdrop-filter: blur(4px);
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
    }

    .pro-card-fusion.high-level {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        border-color: rgba(245, 158, 11, 0.5);
        box-shadow: 0 0 12px rgba(245, 158, 11, 0.5);
    }

    .pro-card-fusion.max-level {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        border-color: rgba(239, 68, 68, 0.5);
        box-shadow: 0 0 15px rgba(239, 68, 68, 0.6);
        animation: fusionPulse 1.5s ease-in-out infinite;
    }

    @keyframes fusionPulse {
        0%, 100% { box-shadow: 0 0 10px rgba(239, 68, 68, 0.4); }
        50% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.8); }
    }

    /* Stats boostées */
    .pro-stat.boosted .pro-stat-value {
        color: #34D399;
    }

    /* Tags (armure et élément) */
    .pro-card-tags {
        position: absolute;
        top: 50px;
        left: 12px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .pro-card-tag {
        padding: 4px 10px;
        border-radius: 10px;
        font-size: 0.6rem;
        font-weight: 700;
        backdrop-filter: blur(8px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }

    .pro-tag-bronze { background: linear-gradient(135deg, rgba(205, 127, 50, 0.9), rgba(139, 69, 19, 0.9)); color: white; }
    .pro-tag-silver { background: linear-gradient(135deg, rgba(192, 192, 192, 0.9), rgba(128, 128, 128, 0.9)); color: #1a1a2e; }
    .pro-tag-gold { background: linear-gradient(135deg, rgba(255, 215, 0, 0.9), rgba(218, 165, 32, 0.9)); color: #1a1a2e; }
    .pro-tag-divine { background: linear-gradient(135deg, rgba(139, 92, 246, 0.9), rgba(236, 72, 153, 0.9)); color: white; }

    .pro-tag-fire { background: rgba(239, 68, 68, 0.9); color: white; }
    .pro-tag-water { background: rgba(59, 130, 246, 0.9); color: white; }
    .pro-tag-ice { background: rgba(6, 182, 212, 0.9); color: white; }
    .pro-tag-thunder { background: rgba(234, 179, 8, 0.9); color: #1a1a2e; }
    .pro-tag-darkness { background: rgba(31, 41, 55, 0.9); color: white; }
    .pro-tag-light { background: rgba(252, 211, 77, 0.9); color: #1a1a2e; }

    /* Header avec nom et coût */
    .pro-card-header {
        position: absolute;
        left: 0;
        right: 0;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .pro-card-name {
        font-weight: 800;
        color: white;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.6);
        line-height: 1.2;
    }

    .pro-card-faction {
        color: rgba(255, 255, 255, 0.85);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 2px;
    }

    .pro-card-cost {
        background: linear-gradient(145deg, var(--color1), var(--color2));
        border: 3px solid rgba(255, 255, 255, 0.6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.6);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5), inset 0 2px 0 rgba(255,255,255,0.4);
        flex-shrink: 0;
    }

    /* Stats en overlay */
    .pro-card-stats-wrapper {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 10;
        padding: 10px;
    }

    .pro-card-stats {
        display: flex;
        justify-content: space-between;
        gap: 6px;
    }

    .pro-stat {
        flex: 1;
        text-align: center;
        padding: 8px 4px;
        border-radius: 8px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
    }

    .pro-stat.stat-hp {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.9), rgba(153, 27, 27, 0.9));
        border-color: rgba(248, 113, 113, 0.4);
    }
    .pro-stat.stat-end {
        background: linear-gradient(135deg, rgba(217, 119, 6, 0.9), rgba(180, 83, 9, 0.9));
        border-color: rgba(251, 191, 36, 0.4);
    }
    .pro-stat.stat-def {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(29, 78, 216, 0.9));
        border-color: rgba(96, 165, 250, 0.4);
    }
    .pro-stat.stat-pwr {
        background: linear-gradient(135deg, rgba(234, 88, 12, 0.9), rgba(194, 65, 12, 0.9));
        border-color: rgba(251, 146, 60, 0.4);
    }
    .pro-stat.stat-cos {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.9), rgba(91, 33, 182, 0.9));
        border-color: rgba(167, 139, 250, 0.4);
    }

    .pro-stat-value {
        display: block;
        font-weight: 900;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        line-height: 1;
    }

    .pro-stat-label {
        display: block;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    /* Passive ability */
    .pro-card-passive {
        position: absolute;
        left: 10px;
        right: 10px;
        z-index: 10;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        padding: 10px 12px;
        border: 1px solid rgba(255, 215, 0, 0.3);
    }

    .pro-passive-label {
        font-size: 0.55rem;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pro-passive-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #FCD34D;
        margin: 3px 0;
    }

    .pro-passive-desc {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.4;
        margin: 0;
    }

    /* Effets holo */
    .pro-holo-overlay,
    .pro-holo-sparkle {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        border-radius: inherit;
        z-index: 5;
    }

    .pro-card.rarity-legendary .pro-holo-sparkle {
        background:
            url("https://assets.codepen.io/13471/sparkles.gif"),
            linear-gradient(125deg,
                rgba(255, 0, 132, 0.2) 15%,
                rgba(252, 164, 0, 0.2) 30%,
                rgba(255, 255, 0, 0.15) 40%,
                rgba(0, 255, 138, 0.1) 60%,
                rgba(0, 207, 255, 0.2) 70%,
                rgba(204, 76, 250, 0.2) 85%
            );
        background-size: 160%;
        mix-blend-mode: color-dodge;
        opacity: 0.7;
        animation: holoSparkle 4s ease infinite;
    }

    .pro-card.rarity-epic .pro-holo-overlay {
        background: linear-gradient(
            115deg,
            transparent 0%,
            rgba(139, 92, 246, 0.25) 25%,
            transparent 50%,
            rgba(168, 85, 247, 0.25) 75%,
            transparent 100%
        );
        mix-blend-mode: color-dodge;
        opacity: 0.6;
    }

    .pro-card.rarity-mythic .pro-holo-sparkle {
        background:
            url("https://assets.codepen.io/13471/sparkles.gif"),
            linear-gradient(125deg,
                rgba(255, 0, 128, 0.3) 0%,
                rgba(255, 140, 0, 0.3) 25%,
                rgba(64, 224, 208, 0.3) 50%,
                rgba(123, 104, 238, 0.3) 75%,
                rgba(255, 0, 128, 0.3) 100%
            );
        background-size: 200%;
        mix-blend-mode: color-dodge;
        opacity: 0.8;
        animation: mythicSparkle 5s ease infinite;
    }

    @keyframes holoSparkle {
        0%, 100% { opacity: 0.5; background-position: 50% 50%; }
        50% { opacity: 0.8; background-position: 60% 60%; }
    }

    @keyframes mythicSparkle {
        0%, 100% { opacity: 0.6; background-position: 0% 50%; }
        50% { opacity: 0.9; background-position: 100% 50%; }
    }

    /* Shine effect on hover */
    .pro-holo-shine {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 6;
        background: linear-gradient(
            135deg,
            transparent 0%,
            rgba(255, 255, 255, 0.1) 45%,
            rgba(255, 255, 255, 0.4) 50%,
            rgba(255, 255, 255, 0.1) 55%,
            transparent 100%
        );
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pro-card:hover .pro-holo-shine {
        opacity: 1;
        animation: shineMove 0.8s ease;
    }

    @keyframes shineMove {
        0% { transform: translateX(-100%) rotate(25deg); }
        100% { transform: translateX(100%) rotate(25deg); }
    }
</style>

<div class="pro-card-wrapper {{ $interactive ? 'interactive' : '' }}">
    <div class="pro-card rarity-{{ $card->rarity }}"
         style="{{ $sizes['card'] }} --color1: {{ $card->faction->color_primary }}; --color2: {{ $card->faction->color_secondary }};">

        <div class="pro-card-content">
            <!-- Image de fond -->
            <div class="pro-card-image">
                @if($card->image_primary)
                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}">
                @else
                    <div class="pro-card-placeholder">🃏</div>
                @endif
            </div>

            <!-- Overlay dégradé -->
            <div class="pro-card-overlay"></div>

            <!-- Effets holo -->
            <div class="pro-holo-overlay"></div>
            <div class="pro-holo-sparkle"></div>
            <div class="pro-holo-shine"></div>

            <!-- Badge Grade -->
            <div class="pro-card-grade">Grade {{ $card->grade }}</div>

            <!-- Badge Rareté -->
            <div class="pro-card-rarity pro-rarity-{{ $card->rarity }}">
                @switch($card->rarity)
                    @case('common') Commune @break
                    @case('rare') Rare @break
                    @case('epic') Épique @break
                    @case('legendary') Légendaire @break
                    @case('mythic') Mythique @break
                @endswitch
            </div>

            <!-- Badge Niveau de Fusion -->
            @if($hasFusion)
                <div class="pro-card-fusion {{ $fusionLevel >= 7 ? ($fusionLevel >= 10 ? 'max-level' : 'high-level') : '' }}"
                     title="+{{ $boostedStats['bonus_percent'] ?? 0 }}% stats">
                    +{{ $fusionLevel - 1 }}
                </div>
            @endif

            <!-- Tags Armure et Élément -->
            <div class="pro-card-tags">
                <span class="pro-card-tag pro-tag-{{ $card->armor_type }}">
                    @switch($card->armor_type)
                        @case('bronze') 🥉 Bronze @break
                        @case('silver') 🥈 Argent @break
                        @case('gold') 🥇 Or @break
                        @case('divine') 👑 Divin @break
                    @endswitch
                </span>
                <span class="pro-card-tag pro-tag-{{ $card->element }}">
                    @switch($card->element)
                        @case('fire') 🔥 Feu @break
                        @case('water') 💧 Eau @break
                        @case('ice') ❄️ Glace @break
                        @case('thunder') ⚡ Foudre @break
                        @case('darkness') 🌑 Ténèbres @break
                        @case('light') ✨ Lumière @break
                    @endswitch
                </span>
            </div>

            <!-- Passive (si présente) -->
            @if($card->passive_ability_name)
                <div class="pro-card-passive" style="bottom: {{ $size === 'large' ? '160px' : ($size === 'normal' ? '130px' : '100px') }};">
                    <span class="pro-passive-label">Capacité Passive</span>
                    <h4 class="pro-passive-name">{{ $card->passive_ability_name }}</h4>
                    <p class="pro-passive-desc">{{ $card->passive_ability_description }}</p>
                </div>
            @endif

            <!-- Header avec nom et coût -->
            <div class="pro-card-header" style="bottom: {{ $card->passive_ability_name ? ($size === 'large' ? '85px' : ($size === 'normal' ? '70px' : '55px')) : $sizes['header'] }};">
                <div>
                    <div class="pro-card-name" style="font-size: {{ $sizes['name'] }};">{{ $card->name }}</div>
                    <div class="pro-card-faction" style="font-size: {{ $size === 'large' ? '0.7rem' : '0.6rem' }};">{{ $card->faction->name }}</div>
                </div>
                <div class="pro-card-cost" style="width: {{ $sizes['cost'] }}; height: {{ $sizes['cost'] }}; font-size: {{ $size === 'large' ? '1.2rem' : '1rem' }};">
                    {{ $card->cost }}
                </div>
            </div>

            <!-- Stats en overlay -->
            <div class="pro-card-stats-wrapper">
                <div class="pro-card-stats">
                    <div class="pro-stat stat-hp {{ $hasFusion ? 'boosted' : '' }}">
                        <span class="pro-stat-value" style="font-size: {{ $sizes['stat'] }};">{{ $displayHp }}</span>
                        <span class="pro-stat-label" style="font-size: {{ $sizes['label'] }};">PV</span>
                    </div>
                    <div class="pro-stat stat-end {{ $hasFusion ? 'boosted' : '' }}">
                        <span class="pro-stat-value" style="font-size: {{ $sizes['stat'] }};">{{ $displayEnd }}</span>
                        <span class="pro-stat-label" style="font-size: {{ $sizes['label'] }};">END</span>
                    </div>
                    <div class="pro-stat stat-def {{ $hasFusion ? 'boosted' : '' }}">
                        <span class="pro-stat-value" style="font-size: {{ $sizes['stat'] }};">{{ $displayDef }}</span>
                        <span class="pro-stat-label" style="font-size: {{ $sizes['label'] }};">DEF</span>
                    </div>
                    <div class="pro-stat stat-pwr {{ $hasFusion ? 'boosted' : '' }}">
                        <span class="pro-stat-value" style="font-size: {{ $sizes['stat'] }};">{{ $displayPwr }}</span>
                        <span class="pro-stat-label" style="font-size: {{ $sizes['label'] }};">PWR</span>
                    </div>
                    <div class="pro-stat stat-cos">
                        <span class="pro-stat-value" style="font-size: {{ $sizes['stat'] }};">{{ $displayCos }}</span>
                        <span class="pro-stat-label" style="font-size: {{ $sizes['label'] }};">COS</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
