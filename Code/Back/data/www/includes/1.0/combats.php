<?php

namespace combats;

$app->get('/api/1.0/combats/{idJoueur}', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idCombat`, `idAttaquant`, `idDefenseur`, `dateCombat`, `gagnant`, `gold` FROM `combats` WHERE idAttaquant=:idJoueur OR idDefenseur=:idJoueur');
        $stmt->execute(['idJoueur' => $args['idJoueur']]);

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idCombat' => $row->idCombat,
                'idAttaquant' => $row->idAttaquant,
                'idDefenseur' => $row->idDefenseur,
                'dateCombat' => $row->dateCombat,
                'gagnant' => $row->gagnant,
                'gold' => $row->gold,
            ];
        }
        $ret = array(
            'listeCombats' => (array) $items,        // Cast as array for security
        );
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération des combats du joueur', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
