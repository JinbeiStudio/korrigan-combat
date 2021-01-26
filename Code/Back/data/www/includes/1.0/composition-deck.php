<?php

namespace troupesJoueur;

$app->get('/api/1.0/deck-joueur/{idJoueur}/type/{type}/numero/{numero}', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idCompositionDeck`, `idTroupeJoueur`, compositionDeck.idDeck, compositionDeck.idJoueur  FROM `compositionDeck` INNER JOIN `deck` ON deck.idDeck=compositionDeck.idDeck  WHERE compositionDeck.idJoueur=:idJoueur AND deck.type= :type AND deck.numeroDeck= :numero');
        $stmt->execute(["idJoueur" => $args['idJoueur'], "type" => $args['type'], "numero" => $args['numero']]);

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idCompositionDeck' => $row->idCompositionDeck,
                'idTroupeJoueur' => $row->idTroupeJoueur,
                'idDeck' => $row->idDeck,
                'idJoueur' => $row->idJoueur,
            ];
        }
        $ret = array(
            'compositionDeck' => (array) $items,        // Cast as array for security
        );
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération de la composition du deck', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
