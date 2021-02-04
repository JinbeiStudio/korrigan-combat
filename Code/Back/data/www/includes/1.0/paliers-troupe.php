<?php

namespace statsTroupes;

$app->get('/api/1.0/paliers-troupe', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `niveauTroupe`, `experienceTroupe`  FROM `paliersNiveauTroupe`');
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'niveauTroupe' => $row->niveauTroupe,
                'experienceTroupe' => $row->experienceTroupe,
            ];
        }
        $ret = array(
            'paliersNiveauTroupe' => (array) $items,        // Cast as array for security
        );
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération des paliers de niveau des troupes', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
