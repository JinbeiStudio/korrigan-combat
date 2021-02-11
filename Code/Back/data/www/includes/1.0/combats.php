<?php

namespace combats;

/* -------------------- Liste des combats d'un joueur ------------------------*/
/* ------------------ Endpoint : /api/1.0/combats/{idJoueur} ---------------- */
/* ------------------------------ Method : GET ------------------------------ */
/* ------------------------- Auteur : Julien Gabriel ------------------------ */

$app->get('/api/1.0/combats/{idJoueur}', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idCombat`, `idAttaquant`, `idDefenseur`, `dateCombat`, `gagnant`, `goldAttaquant`, `goldDefenseur`, `experienceAttaquant`, `experienceDefenseur` FROM `combats` WHERE idAttaquant=:idJoueur OR idDefenseur=:idJoueur');
        $stmt->execute(['idJoueur' => $args['idJoueur']]);

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idCombat' => $row->idCombat,
                'idAttaquant' => $row->idAttaquant,
                'idDefenseur' => $row->idDefenseur,
                'dateCombat' => $row->dateCombat,
                'gagnant' => $row->gagnant,
                'goldAttaquant' => $row->goldAttaquant,
                'goldDefenseur' => $row->goldDefenseur,
                'experienceAttaquant' => $row->experienceAttaquant,
                'experienceDefenseur' => $row->experienceDefenseur,
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
/* -------------------------------------------------------------------------- */
