<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentMatch;
use App\Models\User;
use App\Models\Deck;
use App\Models\Battle;
use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TournamentService
{
    protected ComboService $comboService;

    public function __construct(ComboService $comboService)
    {
        $this->comboService = $comboService;
    }

    /**
     * Inscription d'un joueur au tournoi
     */
    public function register(Tournament $tournament, User $user, Deck $deck): array
    {
        $canJoin = $tournament->canJoin($user);
        if (!$canJoin['can_join']) {
            return ['success' => false, 'message' => $canJoin['reason']];
        }

        if ($deck->user_id !== $user->id) {
            return ['success' => false, 'message' => 'Ce deck ne vous appartient pas.'];
        }

        $deckCardCount = $deck->cards()->sum('card_deck.quantity');
        if ($deckCardCount < $tournament->min_deck_cards) {
            return ['success' => false, 'message' => "Le deck doit contenir au moins {$tournament->min_deck_cards} cartes."];
        }

        DB::beginTransaction();
        try {
            if ($tournament->entry_fee > 0) {
                if (!$user->spendCoins($tournament->entry_fee)) {
                    throw new \Exception('Pieces insuffisantes.');
                }
            }

            $participant = TournamentParticipant::create([
                'tournament_id' => $tournament->id,
                'user_id' => $user->id,
                'deck_id' => $deck->id,
                'status' => TournamentParticipant::STATUS_REGISTERED,
                'registered_at' => now(),
            ]);

            DB::commit();
            return ['success' => true, 'participant' => $participant];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Retrait d'un joueur (pendant inscription uniquement)
     */
    public function withdraw(Tournament $tournament, User $user): array
    {
        if ($tournament->status !== Tournament::STATUS_REGISTRATION) {
            return ['success' => false, 'message' => 'Retrait impossible une fois le tournoi commence.'];
        }

        $participant = $tournament->participants()->where('user_id', $user->id)->first();
        if (!$participant) {
            return ['success' => false, 'message' => 'Vous n\'etes pas inscrit.'];
        }

        DB::beginTransaction();
        try {
            if ($tournament->entry_fee > 0) {
                $user->addCoins($tournament->entry_fee);
            }

            $participant->delete();

            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generer le bracket avec seeding aleatoire
     */
    public function generateBracket(Tournament $tournament): array
    {
        if ($tournament->status !== Tournament::STATUS_REGISTRATION) {
            return ['success' => false, 'message' => 'Le tournoi n\'est pas en phase d\'inscription.'];
        }

        $participants = $tournament->participants()->get();
        $count = $participants->count();

        $validCounts = [8, 16, 32];
        if (!in_array($count, $validCounts)) {
            return ['success' => false, 'message' => "Le nombre de participants ($count) doit etre 8, 16 ou 32."];
        }

        DB::beginTransaction();
        try {
            // Seeding aleatoire
            $shuffled = $participants->shuffle()->values();
            foreach ($shuffled as $index => $participant) {
                $participant->update([
                    'seed_position' => $index + 1,
                    'bracket_position' => $index + 1,
                    'status' => TournamentParticipant::STATUS_ACTIVE,
                ]);
            }

            $totalRounds = (int) log($count, 2);
            $tournament->update([
                'total_rounds' => $totalRounds,
                'current_round' => 1,
            ]);

            $this->generateAllMatches($tournament, $shuffled->toArray(), $totalRounds);

            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generer tous les matches du bracket
     */
    private function generateAllMatches(Tournament $tournament, array $participants, int $totalRounds): void
    {
        $matchesByRound = [];

        // Tour 1 : appairer les participants
        $round1Matches = [];
        $matchNumber = 1;
        for ($i = 0; $i < count($participants); $i += 2) {
            $match = TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'round' => 1,
                'match_number' => $matchNumber,
                'bracket_code' => "R1M{$matchNumber}",
                'participant1_id' => $participants[$i]->id,
                'participant2_id' => $participants[$i + 1]->id,
                'status' => TournamentMatch::STATUS_READY,
            ]);
            $round1Matches[] = $match;
            $matchNumber++;
        }
        $matchesByRound[1] = $round1Matches;

        // Generer les tours suivants
        for ($round = 2; $round <= $totalRounds; $round++) {
            $previousMatches = $matchesByRound[$round - 1];
            $roundMatches = [];
            $matchNumber = 1;

            $roundCode = $this->getRoundCode($round, $totalRounds);

            for ($i = 0; $i < count($previousMatches); $i += 2) {
                $match = TournamentMatch::create([
                    'tournament_id' => $tournament->id,
                    'round' => $round,
                    'match_number' => $matchNumber,
                    'bracket_code' => "{$roundCode}{$matchNumber}",
                    'status' => TournamentMatch::STATUS_PENDING,
                ]);

                $previousMatches[$i]->update(['next_match_id' => $match->id]);
                $previousMatches[$i + 1]->update(['next_match_id' => $match->id]);

                $roundMatches[] = $match;
                $matchNumber++;
            }
            $matchesByRound[$round] = $roundMatches;
        }
    }

    /**
     * Obtenir le code du tour pour l'affichage
     */
    private function getRoundCode(int $round, int $totalRounds): string
    {
        $remaining = $totalRounds - $round + 1;
        return match ($remaining) {
            1 => 'F',
            2 => 'SF',
            3 => 'QF',
            default => "R{$round}M"
        };
    }

    /**
     * Demarrer le tournoi
     */
    public function start(Tournament $tournament): array
    {
        if ($tournament->status !== Tournament::STATUS_REGISTRATION) {
            return ['success' => false, 'message' => 'Le tournoi ne peut pas etre demarre.'];
        }

        if ($tournament->matches()->count() === 0) {
            $result = $this->generateBracket($tournament);
            if (!$result['success']) {
                return $result;
            }
        }

        $tournament->update([
            'status' => Tournament::STATUS_IN_PROGRESS,
            'start_at' => now(),
            'current_round' => 1,
        ]);

        return ['success' => true];
    }

    /**
     * Creer un combat pour un match de tournoi
     */
    public function createMatchBattle(TournamentMatch $match): ?Battle
    {
        if (!$match->isReady()) {
            return null;
        }

        $battle = Battle::create([
            'player1_id' => $match->participant1->user_id,
            'player2_id' => $match->participant2->user_id,
            'player1_deck_id' => $match->participant1->deck_id,
            'player2_deck_id' => $match->participant2->deck_id,
            'status' => 'in_progress',
            'current_turn_user_id' => $match->participant1->user_id,
            'started_at' => now(),
        ]);

        // Initialiser l'etat du combat
        $battleState = $this->initializeBattleState($battle);
        $battle->update(['battle_state' => $battleState]);

        $match->update([
            'battle_id' => $battle->id,
            'status' => TournamentMatch::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        return $battle;
    }

    /**
     * Initialiser l'etat du combat
     */
    private function initializeBattleState(Battle $battle): array
    {
        $player1Deck = $this->prepareDeck($battle->player1Deck);
        $player2Deck = $this->prepareDeck($battle->player2Deck);

        shuffle($player1Deck);
        shuffle($player2Deck);

        $player1Hand = array_splice($player1Deck, 0, min(5, count($player1Deck)));
        $player2Hand = array_splice($player2Deck, 0, min(5, count($player2Deck)));

        return [
            'turn' => 1,
            'player1' => [
                'deck' => $player1Deck,
                'hand' => $player1Hand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
            ],
            'player2' => [
                'deck' => $player2Deck,
                'hand' => $player2Hand,
                'field' => [],
                'cosmos' => 5,
                'max_cosmos' => 5,
            ],
            'all_combos' => $this->comboService->getAllActiveCombos(),
            'used_combos' => [
                'player1' => [],
                'player2' => [],
            ],
        ];
    }

    /**
     * Preparer le deck pour le combat
     */
    private function prepareDeck(Deck $deck): array
    {
        $deck->load('cards.faction', 'cards.mainAttack', 'cards.secondaryAttack1', 'cards.secondaryAttack2');

        $cards = [];
        foreach ($deck->cards as $card) {
            $quantity = $card->pivot->quantity ?? 1;
            for ($i = 0; $i < $quantity; $i++) {
                $cards[] = [
                    'id' => $card->id,
                    'name' => $card->name,
                    'cost' => $card->cost,
                    'health_points' => $card->health_points,
                    'max_hp' => $card->health_points,
                    'current_hp' => $card->health_points,
                    'endurance' => $card->endurance,
                    'current_endurance' => $card->endurance,
                    'defense' => $card->defense,
                    'power' => $card->power,
                    'image' => $card->image_primary ? Storage::url($card->image_primary) : null,
                    'faction' => $card->faction ? [
                        'name' => $card->faction->name,
                        'color_primary' => $card->faction->color_primary,
                        'color_secondary' => $card->faction->color_secondary,
                    ] : null,
                    'main_attack' => $card->mainAttack ? [
                        'name' => $card->mainAttack->name,
                        'damage' => $card->mainAttack->damage,
                        'endurance_cost' => $card->mainAttack->endurance_cost,
                        'cosmos_cost' => $card->mainAttack->cosmos_cost,
                    ] : null,
                    'secondary_attack_1' => $card->secondaryAttack1 ? [
                        'name' => $card->secondaryAttack1->name,
                        'damage' => $card->secondaryAttack1->damage,
                        'endurance_cost' => $card->secondaryAttack1->endurance_cost,
                        'cosmos_cost' => $card->secondaryAttack1->cosmos_cost,
                    ] : null,
                    'secondary_attack_2' => $card->secondaryAttack2 ? [
                        'name' => $card->secondaryAttack2->name,
                        'damage' => $card->secondaryAttack2->damage,
                        'endurance_cost' => $card->secondaryAttack2->endurance_cost,
                        'cosmos_cost' => $card->secondaryAttack2->cosmos_cost,
                    ] : null,
                    'rarity' => $card->rarity,
                    'can_attack' => false,
                    'has_attacked' => false,
                    'instance_id' => uniqid('card_'),
                ];
            }
        }

        return $cards;
    }

    /**
     * Traiter le resultat d'un match quand un combat se termine
     */
    public function processMatchResult(TournamentMatch $match, int $winnerUserId): array
    {
        if ($match->status !== TournamentMatch::STATUS_IN_PROGRESS) {
            return ['success' => false, 'message' => 'Ce match n\'est pas en cours.'];
        }

        $tournament = $match->tournament;

        $winnerParticipant = null;
        $loserParticipant = null;

        if ($match->participant1->user_id === $winnerUserId) {
            $winnerParticipant = $match->participant1;
            $loserParticipant = $match->participant2;
        } else {
            $winnerParticipant = $match->participant2;
            $loserParticipant = $match->participant1;
        }

        DB::beginTransaction();
        try {
            $match->update([
                'winner_participant_id' => $winnerParticipant->id,
                'status' => TournamentMatch::STATUS_FINISHED,
                'finished_at' => now(),
            ]);

            $winnerParticipant->increment('wins');
            $loserParticipant->increment('losses');
            $loserParticipant->update([
                'status' => TournamentParticipant::STATUS_ELIMINATED,
                'eliminated_at' => now(),
                'final_rank' => $this->calculateFinalRank($tournament, $match->round),
            ]);

            if ($match->next_match_id) {
                $this->advanceToNextMatch($match, $winnerParticipant);
            } else {
                $winnerParticipant->update([
                    'status' => TournamentParticipant::STATUS_WINNER,
                    'final_rank' => 1,
                ]);
                $this->finishTournament($tournament, $winnerParticipant);
            }

            $this->checkRoundComplete($tournament);

            DB::commit();
            return ['success' => true, 'winner' => $winnerParticipant];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Avancer le gagnant vers le match suivant
     */
    private function advanceToNextMatch(TournamentMatch $completedMatch, TournamentParticipant $winner): void
    {
        $nextMatch = $completedMatch->nextMatch;

        if ($completedMatch->match_number % 2 === 1) {
            $nextMatch->update(['participant1_id' => $winner->id]);
        } else {
            $nextMatch->update(['participant2_id' => $winner->id]);
        }

        if ($nextMatch->participant1_id && $nextMatch->participant2_id) {
            $nextMatch->update(['status' => TournamentMatch::STATUS_READY]);
        }
    }

    /**
     * Calculer le classement final selon le tour d'elimination
     */
    private function calculateFinalRank(Tournament $tournament, int $eliminatedInRound): int
    {
        $totalRounds = $tournament->total_rounds;
        $playersPerRound = pow(2, $totalRounds - $eliminatedInRound + 1);
        return $playersPerRound / 2 + 1;
    }

    /**
     * Verifier si le tour actuel est termine
     */
    private function checkRoundComplete(Tournament $tournament): void
    {
        $currentRound = $tournament->current_round;
        $roundMatches = $tournament->matches()->where('round', $currentRound)->get();

        $allFinished = $roundMatches->every(fn($m) => $m->status === TournamentMatch::STATUS_FINISHED);

        if ($allFinished && $currentRound < $tournament->total_rounds) {
            $tournament->update(['current_round' => $currentRound + 1]);
        }
    }

    /**
     * Terminer le tournoi
     */
    private function finishTournament(Tournament $tournament, TournamentParticipant $winner): void
    {
        $tournament->update([
            'status' => Tournament::STATUS_FINISHED,
            'finished_at' => now(),
        ]);

        $this->distributeRewards($tournament);
    }

    /**
     * Distribuer les recompenses du tournoi
     */
    public function distributeRewards(Tournament $tournament): void
    {
        $rewards = $tournament->getRewardsConfig();
        $participants = $tournament->participants;

        foreach ($participants as $participant) {
            $user = $participant->user;
            $coinsEarned = 0;

            switch ($participant->final_rank) {
                case 1:
                    $coinsEarned = $rewards['winner_coins'] ?? 0;
                    $user->increment('tournament_wins');
                    if (!empty($rewards['winner_title'])) {
                        $user->update(['tournament_title' => $rewards['winner_title']]);
                    }
                    if (!empty($rewards['exclusive_card_id'])) {
                        $card = Card::find($rewards['exclusive_card_id']);
                        if ($card) {
                            $user->addCard($card);
                        }
                    }
                    break;
                case 2:
                    $coinsEarned = $rewards['runner_up_coins'] ?? 0;
                    break;
                case 3:
                case 4:
                    $coinsEarned = $rewards['semifinalist_coins'] ?? 0;
                    break;
                default:
                    $coinsEarned = $rewards['participation_coins'] ?? 0;
            }

            if ($coinsEarned > 0) {
                $user->addCoins($coinsEarned);
            }
        }
    }

    /**
     * Annuler le tournoi et rembourser les frais d'inscription
     */
    public function cancel(Tournament $tournament): array
    {
        if ($tournament->status === Tournament::STATUS_FINISHED) {
            return ['success' => false, 'message' => 'Tournoi deja termine.'];
        }

        DB::beginTransaction();
        try {
            foreach ($tournament->participants as $participant) {
                if ($tournament->entry_fee > 0) {
                    $participant->user->addCoins($tournament->entry_fee);
                }
            }

            $tournament->update(['status' => Tournament::STATUS_CANCELLED]);

            DB::commit();
            return ['success' => true];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
