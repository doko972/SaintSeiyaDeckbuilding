<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Battle;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'coins',
        'wins',
        'losses',
        'current_rank',
        'last_daily_bonus_at',
        'login_streak',
        'last_login_date',
        'streak_reward_claimed_today',
        'last_wheel_spin_at',
        'last_activity_at',
        'has_selected_starter',
        'has_completed_first_draw',
        'starter_bronze_id',
        'current_session_id',
        'last_session_ip',
        'tournament_wins',
        'tournament_title',
        'tournament_points',
    ];

    /**
     * Duree en minutes pour considerer un joueur comme "en ligne"
     */
    public const ONLINE_THRESHOLD_MINUTES = 5;

    /**
     * Configuration des rangs et leurs seuils de victoires
     */
    public const RANKS = [
        'bronze' => ['wins' => 0, 'name' => 'Chevalier de Bronze', 'icon' => '🥉', 'reward' => 0],
        'argent' => ['wins' => 20, 'name' => 'Chevalier d\'Argent', 'icon' => '🥈', 'reward' => 500],
        'or' => ['wins' => 50, 'name' => 'Chevalier d\'Or', 'icon' => '🥇', 'reward' => 1000],
        'divin' => ['wins' => 100, 'name' => 'Chevalier Divin', 'icon' => '👑', 'reward' => 2000],
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_selected_starter' => 'boolean',
        'has_completed_first_draw' => 'boolean',
        'last_daily_bonus_at' => 'datetime',
        'last_login_date' => 'date',
        'streak_reward_claimed_today' => 'boolean',
        'last_wheel_spin_at' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Recompenses de la serie de connexion (jours 1-7)
     */
    public const STREAK_REWARDS = [
        1 => ['coins' => 50, 'card' => null],
        2 => ['coins' => 75, 'card' => null],
        3 => ['coins' => 100, 'card' => null],
        4 => ['coins' => 150, 'card' => null],
        5 => ['coins' => 200, 'card' => null],
        6 => ['coins' => 300, 'card' => null],
        7 => ['coins' => 500, 'card' => 'rare'], // Carte rare garantie
    ];

    /**
     * Relation : Un user possède plusieurs decks
     */
    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }

    /**
     * Relation : Collection de cartes du joueur
     */
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'user_cards')
            ->withPivot('quantity', 'obtained_at', 'fusion_level')
            ->withTimestamps();
    }

    /**
     * NOUVEAU : Relation avec le Bronze de départ sélectionné
     */
    public function starterBronze()
    {
        return $this->belongsTo(Card::class, 'starter_bronze_id');
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un joueur
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Ajoute des pièces au joueur
     */
    public function addCoins(int $amount): void
    {
        $this->increment('coins', $amount);
    }

    /**
     * Retire des pièces au joueur
     */
    public function spendCoins(int $amount): bool
    {
        if ($this->coins < $amount) {
            return false;
        }
        $this->decrement('coins', $amount);
        return true;
    }

    /**
     * Ajoute une carte à la collection
     */
    public function addCard(Card $card, int $quantity = 1): void
    {
        $existing = $this->cards()->where('card_id', $card->id)->first();

        if ($existing) {
            $this->cards()->updateExistingPivot($card->id, [
                'quantity' => $existing->pivot->quantity + $quantity,
            ]);
        } else {
            $this->cards()->attach($card->id, [
                'quantity' => $quantity,
                'obtained_at' => now(),
            ]);
        }
    }

    /**
     * Vérifie si le joueur possède une carte
     */
    public function hasCard(Card $card): bool
    {
        return $this->cards()->where('card_id', $card->id)->exists();
    }

    /**
     * NOUVEAU : Obtenir la quantité d'une carte spécifique
     */
    public function getCardQuantity(int $cardId): int
    {
        $card = $this->cards()->where('card_id', $cardId)->first();
        return $card ? $card->pivot->quantity : 0;
    }

    /**
     * Retire une carte de la collection
     *
     * @param int $cardId
     * @param int $quantity Quantité à retirer
     * @return bool True si réussi, false sinon
     */
    public function removeCard(int $cardId, int $quantity = 1): bool
    {
        $userCard = $this->cards()->where('card_id', $cardId)->first();

        if (!$userCard) {
            return false;
        }

        $currentQuantity = $userCard->pivot->quantity;

        if ($currentQuantity < $quantity) {
            return false;
        }

        $newQuantity = $currentQuantity - $quantity;

        if ($newQuantity <= 0) {
            $this->cards()->detach($cardId);
        } else {
            $this->cards()->updateExistingPivot($cardId, [
                'quantity' => $newQuantity,
            ]);
        }

        return true;
    }

    /**
     * Nombre total de cartes dans la collection
     */
    public function getTotalCardsAttribute(): int
    {
        return $this->cards()->sum('quantity');
    }

    /**
     * Enregistre une victoire
     * Retourne les informations de récompense de rang si un nouveau rang est atteint
     */
    public function recordWin(int $coinsReward = 100): ?array
    {
        $this->increment('wins');
        $this->refresh(); // Rafraîchir pour avoir le nouveau nombre de wins
        $this->addCoins($coinsReward);

        // Vérifier si un nouveau rang est atteint
        return $this->checkAndUpdateRank();
    }

    /**
     * Enregistre une défaite
     */
    public function recordLoss(int $coinsReward = 25): void
    {
        $this->increment('losses');
        $this->addCoins($coinsReward);
    }

    /**
     * Calcule le rang basé sur le nombre de victoires
     */
    public function calculateRank(): string
    {
        $wins = $this->wins;

        if ($wins >= self::RANKS['divin']['wins']) {
            return 'divin';
        } elseif ($wins >= self::RANKS['or']['wins']) {
            return 'or';
        } elseif ($wins >= self::RANKS['argent']['wins']) {
            return 'argent';
        }

        return 'bronze';
    }

    /**
     * Obtient les informations du rang actuel
     */
    public function getRankInfo(): array
    {
        $rank = $this->current_rank ?? 'bronze';
        return self::RANKS[$rank] ?? self::RANKS['bronze'];
    }

    /**
     * Obtient les informations du prochain rang
     */
    public function getNextRankInfo(): ?array
    {
        $rankOrder = ['bronze', 'argent', 'or', 'divin'];
        $currentIndex = array_search($this->current_rank ?? 'bronze', $rankOrder);

        if ($currentIndex < count($rankOrder) - 1) {
            $nextRank = $rankOrder[$currentIndex + 1];
            return array_merge(self::RANKS[$nextRank], ['key' => $nextRank]);
        }

        return null; // Déjà au rang max
    }

    /**
     * Vérifie et met à jour le rang si nécessaire
     * Retourne les informations de récompense si un nouveau rang est atteint
     */
    public function checkAndUpdateRank(): ?array
    {
        $newRank = $this->calculateRank();
        $currentRank = $this->current_rank ?? 'bronze';

        // Si le rang a changé (progression uniquement)
        $rankOrder = ['bronze', 'argent', 'or', 'divin'];
        $currentIndex = array_search($currentRank, $rankOrder);
        $newIndex = array_search($newRank, $rankOrder);

        if ($newIndex > $currentIndex) {
            // Nouveau rang atteint !
            $rankInfo = self::RANKS[$newRank];
            $reward = $rankInfo['reward'];

            // Mettre à jour le rang
            $this->current_rank = $newRank;
            $this->save();

            // Donner la récompense
            if ($reward > 0) {
                $this->addCoins($reward);
            }

            return [
                'new_rank' => $newRank,
                'rank_name' => $rankInfo['name'],
                'rank_icon' => $rankInfo['icon'],
                'reward' => $reward,
            ];
        }

        return null;
    }

    /**
     * Batailles en tant que joueur 1
     */
    public function battlesAsPlayer1()
    {
        return $this->hasMany(Battle::class, 'player1_id');
    }

    /**
     * Batailles en tant que joueur 2
     */
    public function battlesAsPlayer2()
    {
        return $this->hasMany(Battle::class, 'player2_id');
    }

    /**
     * Participations aux tournois
     */
    public function tournamentParticipations(): HasMany
    {
        return $this->hasMany(TournamentParticipant::class);
    }

    /**
     * Match de tournoi actif
     */
    public function activeTournamentMatch(): ?TournamentMatch
    {
        $participation = $this->tournamentParticipations()
            ->whereHas('tournament', fn($q) => $q->where('status', 'in_progress'))
            ->whereIn('status', ['active', 'registered'])
            ->first();

        if (!$participation) {
            return null;
        }

        return TournamentMatch::where('tournament_id', $participation->tournament_id)
            ->where(function ($q) use ($participation) {
                $q->where('participant1_id', $participation->id)
                    ->orWhere('participant2_id', $participation->id);
            })
            ->whereIn('status', ['ready', 'in_progress'])
            ->first();
    }

    /**
     * Récupère la bataille active du joueur
     */
    public function activeBattle()
    {
        return Battle::where(function ($query) {
            $query->where('player1_id', $this->id)
                ->orWhere('player2_id', $this->id);
        })->whereIn('status', ['waiting', 'in_progress'])->first();
    }

    /**
     * Vérifie si le joueur est dans une bataille
     */
    public function isInBattle(): bool
    {
        return $this->activeBattle() !== null;
    }

    /**
     * Vérifie si le joueur peut récupérer son bonus quotidien
     */
    public function canClaimDailyBonus(): bool
    {
        if ($this->last_daily_bonus_at === null) {
            return true;
        }

        return $this->last_daily_bonus_at->diffInHours(now()) >= 24;
    }

    /**
     * Réclame le bonus quotidien (lance le dé)
     *
     * @return array ['success' => bool, 'dice_result' => int, 'coins_earned' => int]
     */
    public function claimDailyBonus(): array
    {
        if (!$this->canClaimDailyBonus()) {
            $hoursLeft = 24 - $this->last_daily_bonus_at->diffInHours(now());
            return [
                'success' => false,
                'message' => "Vous devez attendre encore {$hoursLeft}h pour votre prochain bonus.",
                'dice_result' => 0,
                'coins_earned' => 0,
            ];
        }

        // Lance le dé (1-6)
        $diceResult = rand(1, 6);
        $coinsEarned = $diceResult * 100;

        // Met à jour le joueur
        $this->last_daily_bonus_at = now();
        $this->save();
        $this->addCoins($coinsEarned);

        return [
            'success' => true,
            'message' => "Vous avez obtenu {$coinsEarned} pièces !",
            'dice_result' => $diceResult,
            'coins_earned' => $coinsEarned,
        ];
    }

    /**
     * Obtient le temps restant avant le prochain bonus
     *
     * @return array ['hours' => int, 'minutes' => int]
     */
    public function getTimeUntilNextBonus(): array
    {
        if ($this->canClaimDailyBonus()) {
            return ['hours' => 0, 'minutes' => 0];
        }

        $nextBonusTime = $this->last_daily_bonus_at->addHours(24);
        $diffInMinutes = now()->diffInMinutes($nextBonusTime, false);

        if ($diffInMinutes <= 0) {
            return ['hours' => 0, 'minutes' => 0];
        }

        return [
            'hours' => floor($diffInMinutes / 60),
            'minutes' => $diffInMinutes % 60,
        ];
    }

    // ==========================================
    // SERIE DE CONNEXION (LOGIN STREAK)
    // ==========================================

    /**
     * Met a jour la serie de connexion
     * A appeler a chaque connexion/visite du dashboard
     */
    public function updateLoginStreak(): void
    {
        $today = now()->toDateString();
        $lastLogin = $this->last_login_date?->toDateString();

        // Meme jour : ne rien faire
        if ($lastLogin === $today) {
            return;
        }

        $yesterday = now()->subDay()->toDateString();

        if ($lastLogin === $yesterday) {
            // Jour consecutif : incrementer le streak
            $newStreak = $this->login_streak + 1;
            // Reset a 1 apres jour 7
            if ($newStreak > 7) {
                $newStreak = 1;
            }
            $this->login_streak = $newStreak;
        } else {
            // Streak casse : recommencer a 1
            $this->login_streak = 1;
        }

        $this->last_login_date = $today;
        $this->streak_reward_claimed_today = false;
        $this->save();
    }

    /**
     * Verifie si la recompense de streak peut etre reclamee
     */
    public function canClaimStreakReward(): bool
    {
        return $this->login_streak > 0 && !$this->streak_reward_claimed_today;
    }

    /**
     * Reclame la recompense de la serie de connexion
     */
    public function claimStreakReward(): array
    {
        if (!$this->canClaimStreakReward()) {
            return [
                'success' => false,
                'message' => 'Recompense deja reclamee aujourd\'hui.',
            ];
        }

        $day = $this->login_streak;
        $reward = self::STREAK_REWARDS[$day] ?? self::STREAK_REWARDS[1];

        // Donner les pieces
        $this->addCoins($reward['coins']);

        // Donner la carte si jour 7
        $cardGiven = null;
        if ($reward['card'] === 'rare') {
            $rareCard = Card::where('rarity', 'rare')->inRandomOrder()->first();
            if ($rareCard) {
                $this->addCard($rareCard);
                $cardGiven = $rareCard;
            }
        }

        $this->streak_reward_claimed_today = true;
        $this->save();

        return [
            'success' => true,
            'day' => $day,
            'coins_earned' => $reward['coins'],
            'card' => $cardGiven,
            'message' => $cardGiven
                ? "Jour {$day} : +{$reward['coins']} pieces + carte {$cardGiven->name} !"
                : "Jour {$day} : +{$reward['coins']} pieces !",
        ];
    }

    /**
     * Obtient les infos de la serie de connexion
     */
    public function getStreakInfo(): array
    {
        return [
            'current_day' => $this->login_streak,
            'can_claim' => $this->canClaimStreakReward(),
            'rewards' => self::STREAK_REWARDS,
            'today_reward' => self::STREAK_REWARDS[$this->login_streak] ?? null,
        ];
    }

    // ==========================================
    // ROUE DE LA FORTUNE HEBDOMADAIRE
    // ==========================================

    /**
     * Verifie si le joueur peut tourner la roue
     */
    public function canSpinWheel(): bool
    {
        if ($this->last_wheel_spin_at === null) {
            return true;
        }

        return $this->last_wheel_spin_at->diffInDays(now()) >= 7;
    }

    /**
     * Obtient le temps restant avant le prochain spin
     */
    public function getTimeUntilNextSpin(): array
    {
        if ($this->canSpinWheel()) {
            return ['days' => 0, 'hours' => 0];
        }

        $nextSpinTime = $this->last_wheel_spin_at->addDays(7);
        $diffInHours = now()->diffInHours($nextSpinTime, false);

        if ($diffInHours <= 0) {
            return ['days' => 0, 'hours' => 0];
        }

        return [
            'days' => floor($diffInHours / 24),
            'hours' => $diffInHours % 24,
        ];
    }

    /**
     * Tourne la roue de la fortune
     */
    public function spinWheel(): array
    {
        if (!$this->canSpinWheel()) {
            $timeLeft = $this->getTimeUntilNextSpin();
            return [
                'success' => false,
                'message' => "Vous devez attendre {$timeLeft['days']}j {$timeLeft['hours']}h.",
            ];
        }

        // Definition des segments de la roue (probabilites ponderees)
        $segments = [
            ['type' => 'coins', 'value' => 100, 'weight' => 25, 'label' => '100 po', 'color' => '#6B7280'],
            ['type' => 'coins', 'value' => 200, 'weight' => 20, 'label' => '200 po', 'color' => '#3B82F6'],
            ['type' => 'coins', 'value' => 500, 'weight' => 15, 'label' => '500 po', 'color' => '#8B5CF6'],
            ['type' => 'coins', 'value' => 1000, 'weight' => 5, 'label' => '1000 po', 'color' => '#FFD700'],
            ['type' => 'card', 'rarity' => 'common', 'weight' => 15, 'label' => 'Carte Commune', 'color' => '#9CA3AF'],
            ['type' => 'card', 'rarity' => 'rare', 'weight' => 10, 'label' => 'Carte Rare', 'color' => '#3B82F6'],
            ['type' => 'card', 'rarity' => 'epic', 'weight' => 5, 'label' => 'Carte Epique', 'color' => '#8B5CF6'],
            ['type' => 'booster', 'booster_type' => 'bronze', 'weight' => 5, 'label' => 'Booster Bronze', 'color' => '#CD7F32'],
        ];

        // Calculer le total des poids
        $totalWeight = array_sum(array_column($segments, 'weight'));

        // Tirer un nombre aleatoire
        $roll = rand(1, $totalWeight);

        // Trouver le segment gagnant
        $cumulative = 0;
        $winningSegment = null;
        $winningIndex = 0;

        foreach ($segments as $index => $segment) {
            $cumulative += $segment['weight'];
            if ($roll <= $cumulative) {
                $winningSegment = $segment;
                $winningIndex = $index;
                break;
            }
        }

        // Appliquer la recompense
        $reward = $this->applyWheelReward($winningSegment);

        // Mettre a jour le dernier spin
        $this->last_wheel_spin_at = now();
        $this->save();

        return [
            'success' => true,
            'segment_index' => $winningIndex,
            'segment' => $winningSegment,
            'reward' => $reward,
            'segments' => $segments,
            'message' => $reward['message'],
        ];
    }

    /**
     * Applique la recompense de la roue
     */
    private function applyWheelReward(array $segment): array
    {
        switch ($segment['type']) {
            case 'coins':
                $this->addCoins($segment['value']);
                return [
                    'type' => 'coins',
                    'value' => $segment['value'],
                    'message' => "Vous avez gagne {$segment['value']} pieces !",
                ];

            case 'card':
                $card = Card::where('rarity', $segment['rarity'])->inRandomOrder()->first();
                if ($card) {
                    $this->addCard($card);
                    return [
                        'type' => 'card',
                        'card' => $card,
                        'message' => "Vous avez gagne la carte {$card->name} !",
                    ];
                }
                // Fallback si pas de carte de cette rarete
                $this->addCoins(100);
                return [
                    'type' => 'coins',
                    'value' => 100,
                    'message' => "Vous avez gagne 100 pieces !",
                ];

            case 'booster':
                // Generer les cartes du booster
                $cards = $this->generateBoosterCards($segment['booster_type']);
                foreach ($cards as $card) {
                    $this->addCard($card);
                }
                return [
                    'type' => 'booster',
                    'booster_type' => $segment['booster_type'],
                    'cards' => $cards,
                    'message' => "Vous avez gagne un Booster Bronze avec " . count($cards) . " cartes !",
                ];

            default:
                return ['type' => 'nothing', 'message' => 'Rien gagne.'];
        }
    }

    /**
     * Genere les cartes d'un booster (simplifie)
     */
    private function generateBoosterCards(string $type): array
    {
        $cards = [];
        $count = 3;

        for ($i = 0; $i < $count; $i++) {
            $roll = rand(1, 100);
            if ($roll <= 80) {
                $rarity = 'common';
            } elseif ($roll <= 98) {
                $rarity = 'rare';
            } else {
                $rarity = 'epic';
            }

            $card = Card::where('rarity', $rarity)->inRandomOrder()->first();
            if ($card) {
                $cards[] = $card;
            }
        }

        return $cards;
    }

    // ==========================================
    // STATUT EN LIGNE & INVITATIONS PVP
    // ==========================================

    /**
     * Verifie si le joueur est en ligne
     */
    public function isOnline(): bool
    {
        if (!$this->last_activity_at) {
            return false;
        }

        return $this->last_activity_at->diffInMinutes(now()) <= self::ONLINE_THRESHOLD_MINUTES;
    }

    /**
     * Obtient tous les joueurs en ligne (sauf soi-meme)
     */
    public static function getOnlinePlayers(?int $excludeUserId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = static::where('last_activity_at', '>=', now()->subMinutes(self::ONLINE_THRESHOLD_MINUTES))
            ->where('has_selected_starter', true);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->orderBy('last_activity_at', 'desc')->get();
    }

    /**
     * Invitations PvP recues
     */
    public function receivedInvitations(): HasMany
    {
        return $this->hasMany(PvpInvitation::class, 'to_user_id');
    }

    /**
     * Invitations PvP envoyees
     */
    public function sentInvitations(): HasMany
    {
        return $this->hasMany(PvpInvitation::class, 'from_user_id');
    }

    /**
     * Invitations en attente recues
     */
    public function pendingReceivedInvitations()
    {
        return $this->receivedInvitations()
            ->pending()
            ->with(['fromUser', 'deck'])
            ->orderBy('created_at', 'desc');
    }

    /**
     * Invitation en attente envoyee (une seule a la fois)
     */
    public function pendingSentInvitation()
    {
        return $this->sentInvitations()
            ->pending()
            ->with(['toUser', 'deck'])
            ->first();
    }

    /**
     * Envoie une invitation PvP
     */
    public function sendPvpInvitation(User $toUser, Deck $deck): array
    {
        // Verifier que le destinataire est en ligne
        if (!$toUser->isOnline()) {
            return [
                'success' => false,
                'message' => 'Ce joueur n\'est plus en ligne.',
            ];
        }

        // Verifier qu'on n'a pas deja une invitation en attente
        $existingInvitation = $this->pendingSentInvitation();
        if ($existingInvitation) {
            return [
                'success' => false,
                'message' => 'Vous avez deja une invitation en attente.',
            ];
        }

        // Verifier que le destinataire n'est pas en combat
        if ($toUser->isInBattle()) {
            return [
                'success' => false,
                'message' => 'Ce joueur est deja en combat.',
            ];
        }

        // Verifier qu'on n'est pas en combat
        if ($this->isInBattle()) {
            return [
                'success' => false,
                'message' => 'Vous etes deja en combat.',
            ];
        }

        // Creer l'invitation
        $invitation = PvpInvitation::create([
            'from_user_id' => $this->id,
            'to_user_id' => $toUser->id,
            'deck_id' => $deck->id,
            'status' => 'pending',
            'expires_at' => now()->addSeconds(PvpInvitation::EXPIRATION_SECONDS),
        ]);

        return [
            'success' => true,
            'invitation' => $invitation,
            'message' => "Invitation envoyee a {$toUser->name} !",
        ];
    }
}