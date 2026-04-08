<?php

namespace App\Services;

/**
 * Service centralisant toute la logique des effets de combat :
 * effets d'attaque, effets passifs au déploiement, et traitement en début de tour.
 *
 * Convention dans le state :
 *   $state[$attackerSide]['field'][$attackerIdx]  = carte attaquante
 *   $state[$targetSide]['field'][$targetIdx]       = carte cible
 *
 * Structure d'un status_effect sur une carte :
 *   ['type' => 'burn',        'value' => 20,  'turns' => 2]
 *   ['type' => 'freeze',                       'turns' => 2]
 *   ['type' => 'stun',                         'turns' => 1]
 *   ['type' => 'buff_attack', 'value' => 15,  'turns' => 3]
 *   ['type' => 'buff_defense','value' => 50,  'turns' => 3]
 *   ['type' => 'debuff',      'value_removed' => 25, 'turns' => 2]
 */
class BattleEffectService
{
    // =====================================================
    // APPLICATION D'UN EFFET D'ATTAQUE
    // =====================================================

    /**
     * Applique l'effet d'une attaque après calcul des dégâts.
     *
     * @param array  $state         État du combat (modifié par référence)
     * @param string $attackerSide  Clé dans $state (ex: 'player', 'player1')
     * @param int    $attackerIdx   Index dans $state[$attackerSide]['field']
     * @param string $targetSide    Clé dans $state pour la cible
     * @param int    $targetIdx     Index dans $state[$targetSide]['field']
     * @param array  $attack        Données de l'attaque (effect_type, effect_value, damage)
     * @param int    $damageDealt   Dégâts réellement infligés (pour drain)
     * @param bool   $targetDied    La cible vient-elle de mourir ?
     * @return array                Messages décrivant les effets appliqués
     */
    public static function applyAttackEffect(
        array &$state,
        string $attackerSide,
        int $attackerIdx,
        string $targetSide,
        int $targetIdx,
        array $attack,
        int $damageDealt,
        bool $targetDied
    ): array {
        $effectType  = $attack['effect_type']  ?? 'none';
        $effectValue = (int) ($attack['effect_value'] ?? 0);

        if (!$effectType || $effectType === 'none' || $effectValue === 0) {
            return [];
        }

        $messages = [];

        // Sécurité : s'assurer que status_effects existe sur attaquant et cible
        if (!isset($state[$attackerSide]['field'][$attackerIdx]['status_effects'])) {
            $state[$attackerSide]['field'][$attackerIdx]['status_effects'] = [];
        }
        if (!$targetDied && !isset($state[$targetSide]['field'][$targetIdx]['status_effects'])) {
            $state[$targetSide]['field'][$targetIdx]['status_effects'] = [];
        }

        $attackerName = $state[$attackerSide]['field'][$attackerIdx]['name'];
        $targetName   = $targetDied ? 'cible' : $state[$targetSide]['field'][$targetIdx]['name'];

        switch ($effectType) {

            // ── SOIN : l'attaquant récupère des PV ──────────────────────
            case 'heal':
                $maxHp = $state[$attackerSide]['field'][$attackerIdx]['health_points'];
                $before = $state[$attackerSide]['field'][$attackerIdx]['current_hp'];
                $healed = min($effectValue, $maxHp - $before);
                $state[$attackerSide]['field'][$attackerIdx]['current_hp'] += $healed;
                if ($healed > 0) {
                    $messages[] = "💚 {$attackerName} récupère {$healed} PV !";
                }
                break;

            // ── DRAIN : vol de vie ───────────────────────────────────────
            case 'drain':
                $drained = min($effectValue, $damageDealt);
                if ($drained > 0) {
                    $maxHp = $state[$attackerSide]['field'][$attackerIdx]['health_points'];
                    $state[$attackerSide]['field'][$attackerIdx]['current_hp'] = min(
                        $maxHp,
                        $state[$attackerSide]['field'][$attackerIdx]['current_hp'] + $drained
                    );
                    $messages[] = "🩸 {$attackerName} draine {$drained} PV de {$targetName} !";
                }
                break;

            // ── BRÛLURE : dégâts au fil des tours ───────────────────────
            case 'burn':
                if ($targetDied) break;
                // Ne pas cumuler plusieurs brûlures sur la même carte
                foreach ($state[$targetSide]['field'][$targetIdx]['status_effects'] as $e) {
                    if ($e['type'] === 'burn') break 2;
                }
                $state[$targetSide]['field'][$targetIdx]['status_effects'][] =
                    ['type' => 'burn', 'value' => $effectValue, 'turns' => 2];
                $messages[] = "🔥 {$targetName} est en feu ! (-{$effectValue} PV/tour pendant 2 tours)";
                break;

            // ── GEL / ÉTOURDISSEMENT : la cible ne peut plus attaquer ───
            case 'freeze':
            case 'stun':
                if ($targetDied) break;
                // Ne pas empiler : remplacer si déjà présent
                $found = false;
                foreach ($state[$targetSide]['field'][$targetIdx]['status_effects'] as &$e) {
                    if ($e['type'] === $effectType) {
                        $e['turns'] = max($e['turns'], $effectValue);
                        $found = true;
                        break;
                    }
                }
                unset($e);
                if (!$found) {
                    $state[$targetSide]['field'][$targetIdx]['status_effects'][] =
                        ['type' => $effectType, 'turns' => $effectValue];
                }
                $state[$targetSide]['field'][$targetIdx]['has_attacked'] = true;
                $label = $effectType === 'freeze' ? 'gelé' : 'étourdi';
                $messages[] = ($effectType === 'freeze' ? '❄️' : '💤')
                    . " {$targetName} est {$label} ! ({$effectValue} tour(s))";
                break;

            // ── BUFF ATTAQUE : booster sa propre puissance ───────────────
            case 'buff_attack':
                // Ne pas cumuler
                foreach ($state[$attackerSide]['field'][$attackerIdx]['status_effects'] as $e) {
                    if ($e['type'] === 'buff_attack') break 2;
                }
                $state[$attackerSide]['field'][$attackerIdx]['power'] += $effectValue;
                $state[$attackerSide]['field'][$attackerIdx]['status_effects'][] =
                    ['type' => 'buff_attack', 'value' => $effectValue, 'turns' => 3];
                $messages[] = "⬆️ {$attackerName} gagne +{$effectValue} Puissance ! (3 tours)";
                break;

            // ── BUFF DÉFENSE : booster sa propre défense ─────────────────
            case 'buff_defense':
                foreach ($state[$attackerSide]['field'][$attackerIdx]['status_effects'] as $e) {
                    if ($e['type'] === 'buff_defense') break 2;
                }
                $state[$attackerSide]['field'][$attackerIdx]['defense'] += $effectValue;
                $state[$attackerSide]['field'][$attackerIdx]['status_effects'][] =
                    ['type' => 'buff_defense', 'value' => $effectValue, 'turns' => 3];
                $messages[] = "🛡️ {$attackerName} gagne +{$effectValue} Défense ! (3 tours)";
                break;

            // ── REGEN : soigne l'attaquant ET tous ses alliés sur le terrain ─
            case 'regen':
                $totalHealed = 0;
                foreach ($state[$attackerSide]['field'] as &$ally) {
                    $maxHp  = $ally['health_points'];
                    $before = $ally['current_hp'];
                    $gain   = min($effectValue, $maxHp - $before);
                    $ally['current_hp'] += $gain;
                    $totalHealed += $gain;
                }
                unset($ally);
                if ($totalHealed > 0) {
                    $messages[] = "💚 {$attackerName} canalise le Cosmos ! Toutes les cartes alliées récupèrent {$effectValue} PV !";
                }
                break;

            // ── DÉBUFF : réduire la puissance de la cible ────────────────
            case 'debuff':
                if ($targetDied) break;
                foreach ($state[$targetSide]['field'][$targetIdx]['status_effects'] as $e) {
                    if ($e['type'] === 'debuff') break 2;
                }
                $currentPower  = $state[$targetSide]['field'][$targetIdx]['power'];
                $powerRemoved  = (int) round($currentPower * $effectValue / 100);
                $state[$targetSide]['field'][$targetIdx]['power'] = max(0, $currentPower - $powerRemoved);
                $state[$targetSide]['field'][$targetIdx]['status_effects'][] =
                    ['type' => 'debuff', 'value_removed' => $powerRemoved, 'turns' => 2];
                $messages[] = "⬇️ {$targetName} perd {$powerRemoved} Puissance ! (2 tours)";
                break;
        }

        return $messages;
    }

    // =====================================================
    // TRAITEMENT DES EFFETS EN DÉBUT DE TOUR
    // =====================================================

    /**
     * Traite les status_effects de toutes les cartes d'une liste au début de leur tour.
     * Décrémente les compteurs, applique burn, retire les effets expirés, restaure les stats.
     *
     * @param  array $cards  Tableau de cartes (modifié par référence)
     * @return array         Événements [{type, card_name, card_index, damage?}]
     */
    public static function processStatusEffectsAtTurnStart(array &$cards): array
    {
        $events = [];

        foreach ($cards as $i => &$card) {
            if (empty($card['status_effects'])) {
                continue;
            }

            $toRemove = [];

            foreach ($card['status_effects'] as $j => &$effect) {
                // Appliquer l'effet de ce tour
                if ($effect['type'] === 'burn') {
                    $dmg = $effect['value'];
                    $card['current_hp'] = max(0, $card['current_hp'] - $dmg);
                    $events[] = ['type' => 'burn', 'card_name' => $card['name'], 'card_index' => $i, 'damage' => $dmg];
                }
                // freeze/stun/buff/debuff : pas d'application par tour, juste le décompte

                $effect['turns']--;
                if ($effect['turns'] <= 0) {
                    $toRemove[] = $j;
                }
            }
            unset($effect);

            // Retirer les effets expirés et restaurer les stats
            foreach ($toRemove as $j) {
                $exp = $card['status_effects'][$j];
                if ($exp['type'] === 'buff_attack') {
                    $card['power'] = max(0, $card['power'] - ($exp['value'] ?? 0));
                } elseif ($exp['type'] === 'buff_defense') {
                    $card['defense'] = max(0, $card['defense'] - ($exp['value'] ?? 0));
                } elseif ($exp['type'] === 'debuff') {
                    $card['power'] += ($exp['value_removed'] ?? 0);
                }
                unset($card['status_effects'][$j]);
            }
            $card['status_effects'] = array_values($card['status_effects']);

            // Recalculer has_attacked pour freeze/stun
            $stillFrozen = false;
            foreach ($card['status_effects'] as $effect) {
                if (in_array($effect['type'], ['freeze', 'stun']) && $effect['turns'] > 0) {
                    $stillFrozen = true;
                    break;
                }
            }
            // Note : has_attacked sera reset dans le controller, on le force ici si encore gelé
            if ($stillFrozen) {
                $card['has_attacked'] = true;
            }
        }
        unset($card);

        return $events;
    }

    // =====================================================
    // PASSIF AU DÉPLOIEMENT
    // =====================================================

    /**
     * Déclenche la capacité passive d'une carte au moment où elle est posée sur le terrain.
     * Modifie directement $field (tableau des cartes alliées déjà sur le terrain).
     *
     * @param  array  $field         Cartes alliées sur le terrain (modifié par référence)
     * @param  array  $deployedCard  La carte qui vient d'être jouée
     * @return array                 Messages d'effet
     */
    public static function applyPassiveOnDeploy(array &$field, array $deployedCard): array
    {
        $passiveType  = $deployedCard['passive_effect_type']  ?? 'none';
        $passiveValue = (int) ($deployedCard['passive_effect_value'] ?? 0);

        if (!$passiveType || $passiveType === 'none' || $passiveValue === 0) {
            return [];
        }

        $messages = [];
        $name = $deployedCard['name'];

        switch ($passiveType) {

            // ── SOIN DES ALLIÉS ──────────────────────────────────────────
            case 'heal_allies':
                $healed = 0;
                foreach ($field as &$ally) {
                    if (($ally['instance_id'] ?? '') === ($deployedCard['instance_id'] ?? '')) {
                        continue; // ne pas se soigner soi-même
                    }
                    $maxHp    = $ally['health_points'];
                    $before   = $ally['current_hp'];
                    $gain     = min($passiveValue, $maxHp - $before);
                    $ally['current_hp'] += $gain;
                    $healed  += $gain;
                }
                unset($ally);
                if ($healed > 0) {
                    $messages[] = "💚 Passif de {$name} : alliés soignés de {$passiveValue} PV !";
                }
                break;

            // ── BOUCLIER PERSONNEL ───────────────────────────────────────
            case 'shield_self':
                // Chercher la carte déployée dans le terrain et lui appliquer son bouclier
                foreach ($field as &$ally) {
                    if (($ally['instance_id'] ?? '') === ($deployedCard['instance_id'] ?? '')) {
                        if (!isset($ally['status_effects'])) {
                            $ally['status_effects'] = [];
                        }
                        $ally['defense'] += $passiveValue;
                        $ally['status_effects'][] = ['type' => 'buff_defense', 'value' => $passiveValue, 'turns' => 3];
                        $messages[] = "🛡️ Passif de {$name} : +{$passiveValue} Défense ! (3 tours)";
                        break;
                    }
                }
                unset($ally);
                break;

            // ── BOOST DES ALLIÉS ─────────────────────────────────────────
            case 'boost_allies':
                foreach ($field as &$ally) {
                    if (($ally['instance_id'] ?? '') === ($deployedCard['instance_id'] ?? '')) {
                        continue;
                    }
                    if (!isset($ally['status_effects'])) {
                        $ally['status_effects'] = [];
                    }
                    // Ne pas cumuler
                    $alreadyBuffed = false;
                    foreach ($ally['status_effects'] as $e) {
                        if ($e['type'] === 'buff_attack') { $alreadyBuffed = true; break; }
                    }
                    if (!$alreadyBuffed) {
                        $ally['power'] += $passiveValue;
                        $ally['status_effects'][] = ['type' => 'buff_attack', 'value' => $passiveValue, 'turns' => 2];
                    }
                }
                unset($ally);
                $messages[] = "⬆️ Passif de {$name} : alliés boostés de +{$passiveValue} Puissance ! (2 tours)";
                break;
        }

        return $messages;
    }

    // =====================================================
    // UTILITAIRE : MESSAGES EN TEXTE POUR LES LOGS
    // =====================================================

    /**
     * Convertit des événements de brûlure en messages lisibles.
     */
    public static function burnEventsToMessages(array $events): array
    {
        $messages = [];
        foreach ($events as $e) {
            if ($e['type'] === 'burn') {
                $messages[] = "🔥 {$e['card_name']} subit {$e['damage']} dégâts de brûlure !";
            }
        }
        return $messages;
    }
}
