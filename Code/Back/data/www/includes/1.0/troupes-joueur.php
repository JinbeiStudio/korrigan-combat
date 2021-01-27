<?php

namespace troupesJoueur;

$app->get('/api/1.0/troupes-joueur/{idJoueur}', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idTroupeJoueur`, `idTroupe`, `idJoueur`, `niveauTroupe`, `experience`  FROM `troupesJoueur` WHERE `idJoueur`=:idJoueur');
        $stmt->execute(["idJoueur" => $args['idJoueur']]);

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idTroupeJoueur' => $row->idTroupeJoueur,
                'idTroupe' => $row->idTroupe,
                'idJoueur' => $row->idJoueur,
                'niveauTroupe' => $row->niveauTroupe,
                'experience' => $row->experience,
            ];
        }
        $ret = array(
            'troupesJoueur' . $args['idJoueur'] => (array) $items,        // Cast as array for security
        );
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération des troupes du joeur', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
