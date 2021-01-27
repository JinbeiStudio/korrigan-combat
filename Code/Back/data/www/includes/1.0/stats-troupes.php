<?php

namespace statsTroupes;

$app->get('/api/1.0/stats-troupes', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idStats`, `niveau`, `idTroupe`, `vie`, `degat`, `agilite`, `portee`, `vitesse`  FROM `stats`');
        $stmt->execute();

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idStats' => $row->idStats,
                'niveau' => $row->niveau,
                'idTroupe' => $row->idTroupe,
                'vie' => $row->vie,
                'degat' => $row->degat,
                'agilite' => $row->agilite,
                'portee' => $row->portee,
                'vitesse' => $row->vitesse,
            ];
        }
        $ret = array(
            'statsTroupes' => (array) $items,        // Cast as array for security
        );
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération des stats des troupes', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
