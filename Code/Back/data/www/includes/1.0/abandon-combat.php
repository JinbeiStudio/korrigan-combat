<?php

namespace abandonCombat;

$app->get('/api/1.0/abandon-combat/{idCombat}', function ($req, $resp, $args) {

    //id du combat
    $idCombat = $args['idCombat'];

    //id de l'attaquant, id du defenseur, or gagné par le défenseur
    $infoCombat = infoCombat($idCombat);
    $orGagne = $infoCombat[0]['goldDefenseur'];
    $idAttaquant = $infoCombat[0]['idAttaquant'];
    $idDefenseur = $infoCombat[0]['idDefenseur'];

    //return buildResponse($resp, $idDefenseur);

    try {
        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();

        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        /* -------------------------------------------------------------------------- */
        /*                           Début de la transaction                          */
        /* -------------------------------------------------------------------------- */
        $pdo->beginTransaction();

        //Ajout de l'or au défenseur
        $stmt = $pdo->prepare('UPDATE players SET gold=gold+:orGagne WHERE id=:idDefenseur');
        $stmt->execute(["idDefenseur" => $idDefenseur, "orGagne" => $orGagne]);

        //Définition du défenseur comme gagnant
        $stmt = $pdo->prepare('UPDATE combats SET gagnant=:idDefenseur WHERE idCombat=:idCombat');
        $stmt->execute(["idDefenseur" => $idDefenseur, "idCombat" => $idCombat]);

        //Diminution de l'or de l'attaquant
        $stmt = $pdo->prepare('UPDATE players SET gold=GREATEST(gold-:orGagne, 0) WHERE id=:idAttaquant');
        $stmt->execute(["idAttaquant" => $idAttaquant, "orGagne" => $orGagne]);

        $pdo->commit();
        /* -------------------------------------------------------------------------- */
        /*                            Fin de la transaction                           */
        /* -------------------------------------------------------------------------- */

        $ret = array(
            'abandonCombat' => (array) $infoCombat,
        );

        if (!$ret) {
            __log('Erreur lors de l\'abandon du combat');
            return $resp->withStatus(404);   // Not found
        }

        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        $pdo->rollback();
        __logException('Erreur lors de l\'abandon du combat', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});

function infoCombat($idCombat)
{
    $pdo = getPDO();

    try {
        $stmt = $pdo->prepare('SELECT `goldDefenseur`, `idAttaquant`, `idDefenseur` FROM `combats` WHERE idCombat=:idCombat');
        $stmt->execute(["idCombat" => $idCombat]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        return FALSE;
    }
}
