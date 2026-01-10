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
           PROFILE HEADER
        ======================================== */
        .profile-header {
            position: relative;
            background: linear-gradient(135deg, rgba(124, 58, 237, 0.3), rgba(99, 102, 241, 0.3));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(168, 85, 247, 0.3);
            border-radius: 24px;
            padding: 2rem;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #7C3AED, #FFD700, #7C3AED);
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7C3AED, #6366F1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            border: 4px solid rgba(255, 215, 0, 0.5);
            box-shadow: 0 0 30px rgba(124, 58, 237, 0.5);
        }

        .profile-rank {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rank-bronze {
            background: linear-gradient(135deg, #CD7F32, #8B4513);
            color: white;
            box-shadow: 0 0 15px rgba(205, 127, 50, 0.4);
        }

        .rank-argent {
            background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
            color: #1a1a2e;
            box-shadow: 0 0 15px rgba(192, 192, 192, 0.4);
        }

        .rank-or {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #1a1a2e;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
        }

        .rank-divin {
            background: linear-gradient(135deg, #E0B0FF, #9400D3, #FFD700);
            color: white;
            box-shadow: 0 0 25px rgba(148, 0, 211, 0.5);
        }

        /* ========================================
           STAT CARDS
        ======================================== */
        .profile-stat {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
        }

        .profile-stat-value {
            font-size: 1.5rem;
            font-weight: 800;
        }

        .profile-stat-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 2px;
        }

        /* ========================================
           FORM SECTIONS
        ======================================== */
        .form-section {
            position: relative;
            background: rgba(15, 15, 35, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 2rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .form-section:hover {
            border-color: rgba(168, 85, 247, 0.3);
        }

        .form-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--section-color, linear-gradient(90deg, #7C3AED, transparent));
        }

        .form-section.info::before {
            background: linear-gradient(90deg, #3B82F6, transparent);
        }

        .form-section.password::before {
            background: linear-gradient(90deg, #F59E0B, transparent);
        }

        .form-section.danger::before {
            background: linear-gradient(90deg, #EF4444, transparent);
        }

        .form-section-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .form-section.info .form-section-icon {
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid rgba(59, 130, 246, 0.3);
        }

        .form-section.password .form-section-icon {
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .form-section.danger .form-section-icon {
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.5rem;
        }

        .form-section-desc {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 1.5rem;
        }

        /* ========================================
           FORM INPUTS (Override Laravel defaults)
        ======================================== */
        .form-section label {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 600;
        }

        .form-section input[type="text"],
        .form-section input[type="email"],
        .form-section input[type="password"] {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            border-radius: 10px !important;
            color: white !important;
            padding: 0.75rem 1rem !important;
            transition: all 0.3s ease !important;
        }

        .form-section input[type="text"]:focus,
        .form-section input[type="email"]:focus,
        .form-section input[type="password"]:focus {
            background: rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(168, 85, 247, 0.5) !important;
            box-shadow: 0 0 15px rgba(168, 85, 247, 0.2) !important;
            outline: none !important;
        }

        .form-section input::placeholder {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        /* Buttons */
        .form-section button[type="submit"],
        .form-section .primary-button {
            background: linear-gradient(135deg, #7C3AED, #6366F1) !important;
            color: white !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 10px !important;
            border: none !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3) !important;
        }

        .form-section button[type="submit"]:hover,
        .form-section .primary-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 25px rgba(124, 58, 237, 0.4) !important;
        }

        .form-section.danger button[type="submit"] {
            background: linear-gradient(135deg, #EF4444, #DC2626) !important;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3) !important;
        }

        .form-section.danger button[type="submit"]:hover {
            box-shadow: 0 6px 25px rgba(239, 68, 68, 0.4) !important;
        }

        /* Text colors for partials */
        .form-section p {
            color: rgba(255, 255, 255, 0.7);
        }

        .form-section .text-gray-600 {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .form-section .text-gray-700 {
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .form-section .text-gray-800 {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .form-section .text-gray-900 {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        /* Success message */
        .form-section .text-green-600 {
            color: #34D399 !important;
        }

        /* Error messages */
        .form-section .text-red-600 {
            color: #F87171 !important;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */
        @media (max-width: 640px) {
            .profile-header {
                text-align: center;
            }

            .profile-header > div {
                flex-direction: column;
                align-items: center;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }
        }
    </style>

    <div class="min-h-screen relative overflow-hidden">
        <!-- Fond Cosmos -->
        <div class="cosmos-bg">
            <div class="stars"></div>
        </div>

        <!-- Contenu -->
        <div class="relative z-10 py-12 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            
            <!-- Header de page -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                    <span class="text-4xl">👤</span>
                    Mon Profil
                </h1>
                <p class="text-gray-400 mt-1">Gérez vos informations personnelles</p>
            </div>

            <!-- Profile Header Card -->
            <div class="profile-header mb-8">
                <div class="flex flex-wrap items-center gap-6">
                    <!-- Avatar -->
                    <div class="profile-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <!-- Infos -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h2 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h2>
                            @php
                                $wins = auth()->user()->wins;
                                if ($wins >= 100) {
                                    $rank = ['name' => 'Chevalier Divin', 'class' => 'rank-divin', 'icon' => '👑'];
                                } elseif ($wins >= 50) {
                                    $rank = ['name' => 'Chevalier d\'Or', 'class' => 'rank-or', 'icon' => '🥇'];
                                } elseif ($wins >= 20) {
                                    $rank = ['name' => 'Chevalier d\'Argent', 'class' => 'rank-argent', 'icon' => '🥈'];
                                } else {
                                    $rank = ['name' => 'Chevalier de Bronze', 'class' => 'rank-bronze', 'icon' => '🥉'];
                                }
                            @endphp
                            <span class="profile-rank {{ $rank['class'] }}">
                                {{ $rank['icon'] }} {{ $rank['name'] }}
                            </span>
                        </div>
                        <p class="text-gray-400">{{ auth()->user()->email }}</p>
                        <p class="text-gray-500 text-sm mt-1">
                            Membre depuis {{ auth()->user()->created_at->format('d/m/Y') }}
                        </p>
                    </div>

                    <!-- Stats rapides -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="profile-stat">
                            <div class="profile-stat-value text-yellow-400">{{ number_format(auth()->user()->coins) }}</div>
                            <div class="profile-stat-label">🪙 Pièces</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value text-green-400">{{ auth()->user()->wins }}</div>
                            <div class="profile-stat-label">⚔️ Victoires</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value text-red-400">{{ auth()->user()->losses }}</div>
                            <div class="profile-stat-label">💀 Défaites</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaires -->
            <div class="space-y-6">
                <!-- Informations du profil -->
                <div class="form-section info">
                    <div class="form-section-icon">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="form-section-title">Informations du profil</h3>
                    <p class="form-section-desc">Mettez à jour votre nom et votre adresse email.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="form-section password">
                    <div class="form-section-icon">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="form-section-title">Modifier le mot de passe</h3>
                    <p class="form-section-desc">Utilisez un mot de passe fort et unique pour sécuriser votre compte.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Supprimer le compte -->
                <div class="form-section danger">
                    <div class="form-section-icon">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                    <h3 class="form-section-title">Zone dangereuse</h3>
                    <p class="form-section-desc">Une fois votre compte supprimé, toutes vos données seront définitivement effacées.</p>
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>