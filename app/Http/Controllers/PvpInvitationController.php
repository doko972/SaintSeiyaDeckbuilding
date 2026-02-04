<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Models\Deck;
use App\Models\PvpInvitation;
use App\Models\User;
use App\Services\ComboService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PvpInvitationController extends Controller
{
    protected ComboService $comboService;

    public function __construct(ComboService $comboService)
    {
        $this->comboService = $comboService;
    }
    /**
     * Obtenir la liste des joueurs en ligne
     */
    public function getOnlinePlayers(): JsonResponse
    {
        $user = auth()->user();

        // Joueurs en ligne (sans le joueur actuel) pour la liste des défis
        $onlinePlayers = User::getOnlinePlayers($user->id)
            ->map(fn($player) => [
                'id' => $player->id,
                'name' => $player->name,
                'wins' => $player->wins,
                'losses' => $player->losses,
                'rank' => $player->current_rank ?? 'bronze',
                'in_battle' => $player->isInBattle(),
            ]);

        // Nombre total de joueurs en ligne (incluant le joueur actuel)
        $totalOnline = User::getOnlinePlayers()->count();

        return response()->json([
            'players' => $onlinePlayers,
            'count' => $onlinePlayers->count(),
            'total_online' => $totalOnline,
        ]);
    }

    /**
     * Envoyer une invitation PvP
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'to_user_id' => 'required|exists:users,id',
            'deck_id' => 'required|exists:decks,id',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $toUser = User::findOrFail($request->to_user_id);
        $deck = Deck::findOrFail($request->deck_id);

        // Verifier que le deck appartient au joueur
        if ($deck->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce deck ne vous appartient pas.',
            ], 403);
        }

        // Ne pas s'inviter soi-meme
        if ($toUser->id === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas vous inviter vous-meme.',
            ], 400);
        }

        $result = $user->sendPvpInvitation($toUser, $deck);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    /**
     * Annuler une invitation envoyee
     */
    public function cancel(PvpInvitation $invitation): JsonResponse
    {
        $user = auth()->user();

        if ($invitation->from_user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette invitation ne vous appartient pas.',
            ], 403);
        }

        if (!$invitation->cancel()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'annuler cette invitation.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation annulee.',
        ]);
    }

    /**
     * Verifier les invitations recues en attente
     */
    public function checkReceived(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // Nettoyer les invitations expirees
        PvpInvitation::expireOldInvitations();

        $invitations = $user->pendingReceivedInvitations()->get()
            ->map(fn($inv) => [
                'id' => $inv->id,
                'from_user' => [
                    'id' => $inv->fromUser->id,
                    'name' => $inv->fromUser->name,
                    'wins' => $inv->fromUser->wins,
                    'rank' => $inv->fromUser->current_rank ?? 'bronze',
                ],
                'deck_name' => $inv->deck->name,
                'expires_in' => $inv->expires_at->diffInSeconds(now()),
                'created_at' => $inv->created_at->diffForHumans(),
            ]);

        return response()->json([
            'invitations' => $invitations,
            'count' => $invitations->count(),
        ]);
    }

    /**
     * Verifier le statut d'une invitation envoyee
     */
    public function checkSent(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        // D'abord verifier s'il y a une invitation acceptee recemment (dans les 60 dernieres secondes)
        $acceptedInvitation = $user->sentInvitations()
            ->where('status', 'accepted')
            ->where('updated_at', '>=', now()->subSeconds(60))
            ->first();

        if ($acceptedInvitation) {
            $battle = $user->activeBattle();
            return response()->json([
                'has_pending' => false,
                'accepted' => true,
                'battle_id' => $battle?->id,
            ]);
        }

        // Ensuite verifier les invitations en attente
        $invitation = $user->pendingSentInvitation();

        if (!$invitation) {
            return response()->json([
                'has_pending' => false,
                'accepted' => false,
                'invitation' => null,
            ]);
        }

        return response()->json([
            'has_pending' => true,
            'invitation' => [
                'id' => $invitation->id,
                'to_user' => [
                    'id' => $invitation->toUser->id,
                    'name' => $invitation->toUser->name,
                ],
                'expires_in' => max(0, $invitation->expires_at->diffInSeconds(now(), false) * -1),
                'status' => $invitation->status,
            ],
        ]);
    }

    /**
     * Accepter une invitation
     */
    public function accept(Request $request, PvpInvitation $invitation): JsonResponse
    {
        $request->validate([
            'deck_id' => 'required|exists:decks,id',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $deck = Deck::findOrFail($request->deck_id);

        if ($invitation->to_user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette invitation ne vous est pas destinee.',
            ], 403);
        }

        if ($deck->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce deck ne vous appartient pas.',
            ], 403);
        }

        if (!$invitation->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette invitation a expire ou n\'est plus valide.',
            ], 400);
        }

        // Verifier que personne n'est en combat
        if ($user->isInBattle()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous etes deja en combat.',
            ], 400);
        }

        $fromUser = $invitation->fromUser;
        if ($fromUser->isInBattle()) {
            $invitation->update(['status' => 'expired']);
            return response()->json([
                'success' => false,
                'message' => 'L\'adversaire est deja en combat.',
            ], 400);
        }

        // Accepter l'invitation
        $invitation->accept();

        // Creer la bataille
        $battle = Battle::create([
            'player1_id' => $invitation->from_user_id,
            'player1_deck_id' => $invitation->deck_id,
            'player2_id' => $user->id,
            'player2_deck_id' => $deck->id,
            'status' => 'in_progress',
            'current_turn_user_id' => $invitation->from_user_id,
            'started_at' => now(),
        ]);

        // Initialiser l'etat du combat
        $battleState = $this->initializeBattleState($battle);
        $battle->update(['battle_state' => $battleState]);

        return response()->json([
            'success' => true,
            'message' => 'Invitation acceptee ! Le combat commence.',
            'battle_id' => $battle->id,
        ]);
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

        // Main initiale (5 cartes)
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
                    'instance_id' => uniqid('card_'),
                ];
            }
        }

        return $cards;
    }

    /**
     * Decliner une invitation
     */
    public function decline(PvpInvitation $invitation): JsonResponse
    {
        $user = auth()->user();

        if ($invitation->to_user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette invitation ne vous est pas destinee.',
            ], 403);
        }

        if (!$invitation->decline()) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de decliner cette invitation.',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Invitation declinee.',
        ]);
    }
}
