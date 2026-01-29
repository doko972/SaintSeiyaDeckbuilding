@props([
    'card',
    'size' => 'normal', // small, normal, large
    'interactive' => true,
])

@php
    $sizeClasses = [
        'small' => 'w-[180px] h-[252px]',
        'normal' => 'w-[280px] h-[392px]',
        'large' => 'w-[350px] h-[490px]',
    ];
    
    $cardSize = $sizeClasses[$size] ?? $sizeClasses['normal'];
@endphp

<div class="card-wrapper {{ $interactive ? 'interactive' : '' }}">
    <div class="holo-card {{ $cardSize }} rarity-{{ $card->rarity }}"
         style="--color1: {{ $card->faction->color_primary }}; --color2: {{ $card->faction->color_secondary }};"
         data-rarity="{{ $card->rarity }}">
        
        <!-- Contenu de la carte -->
        <div class="card-content">
            <!-- Header -->
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-name">{{ $card->name }}</h3>
                    <span class="card-faction">{{ $card->faction->name }}</span>
                </div>
                <div class="card-cost">
                    <span>{{ $card->cost }}</span>
                </div>
            </div>

            <!-- Image -->
            <div class="card-image-container">
                @if($card->image_primary)
                    <img src="{{ Storage::url($card->image_primary) }}" alt="{{ $card->name }}" class="card-image">
                @else
                    <div class="card-image-placeholder">
                        <span>🃏</span>
                    </div>
                @endif
                
                <!-- Badges sur l'image -->
                <div class="card-badge card-badge-grade">Grade {{ $card->grade }}</div>
                <div class="card-badge card-badge-rarity">
                    @switch($card->rarity)
                        @case('common') Commune @break
                        @case('rare') Rare @break
                        @case('epic') Épique @break
                        @case('legendary') Légendaire @break
                        @case('mythic') Mythique @break
                    @endswitch
                </div>
            </div>

            <!-- Tags -->
            <div class="card-tags">
                <!-- Armure -->
                <span class="card-tag tag-armor tag-{{ $card->armor_type }}">
                    @switch($card->armor_type)
                        @case('bronze') 🥉 Bronze @break
                        @case('silver') 🥈 Argent @break
                        @case('gold') 🥇 Or @break
                        @case('divine') 👑 Divin @break
                    @endswitch
                </span>
                <!-- Element -->
                <span class="card-tag tag-element tag-{{ $card->element }}">
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

            <!-- Stats -->
            <div class="card-stats">
                <div class="stat stat-hp">
                    <span class="stat-value">{{ $card->health_points }}</span>
                    <span class="stat-label">PV</span>
                </div>
                <div class="stat stat-end">
                    <span class="stat-value">{{ $card->endurance }}</span>
                    <span class="stat-label">END</span>
                </div>
                <div class="stat stat-def">
                    <span class="stat-value">{{ $card->defense }}</span>
                    <span class="stat-label">DEF</span>
                </div>
                <div class="stat stat-pwr">
                    <span class="stat-value">{{ $card->power }}</span>
                    <span class="stat-label">PWR</span>
                </div>
                <div class="stat stat-cos">
                    <span class="stat-value">{{ $card->cosmos }}</span>
                    <span class="stat-label">COS</span>
                </div>
            </div>

            <!-- Passive -->
            @if($card->passive_ability_name)
                <div class="card-passive">
                    <span class="passive-label">Capacité Passive</span>
                    <h4 class="passive-name">{{ $card->passive_ability_name }}</h4>
                    <p class="passive-desc">{{ $card->passive_ability_description }}</p>
                </div>
            @endif
        </div>

        <!-- Overlay pour effets holo -->
        <div class="holo-overlay"></div>
        <div class="holo-sparkle"></div>
        <div class="holo-shine"></div>
    </div>
</div>