<?php

namespace formationTroupes;

use DateTime;

$app->get('/api/1.0/formation-troupes/{idJoueur}', function ($req, $resp, $args) {
    try {
        //global $__player_id;

        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        $stmt = $pdo->prepare('SELECT `idFormation`, `idJoueur`, `idTroupeJoueur`, `quantiteFormation`, `dateDebutFormation`, `dateFinFormation`  FROM `formationTroupes` WHERE idJoueur=:idJoueur');
        $stmt->execute(["idJoueur" => $args['idJoueur']]);

        $items = [];
        while ($row = $stmt->fetchObject()) {
            $items[] = [
                'idFormation' => $row->idFormation,
                'idJoueur' => $row->idJoueur,
                'idTroupeJoueur' => $row->idTroupeJoueur,
                'quantiteFormation' => $row->quantiteFormation,
                'dateDebutFormation' => $row->dateDebutFormation,
                'dateFinFormation' => $row->dateFinFormation
            ];
        }
        $ret = array(
            'formationTroupes' => (array) $items,        // Cast as array for security
        );

        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors de la récupération des troupes en formation', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});

function getFormation($id)
{
    // try..catch.. is already done in caller function
    // Security check is done in caller function
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT `idFormation`, `idJoueur`, `idTroupeJoueur`, `quantiteFormation`, `dateDebutFormation`, `dateFinFormation`  FROM `formationTroupes` WHERE idFormation=:idFormation');
    $stmt->execute(["idFormation" => $id]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return [
            'idFormation' => $row->idFormation,
            'idJoueur' => $row->idJoueur,
            'idTroupeJoueur' => $row->idTroupeJoueur,
            'quantiteFormation' => $row->quantiteFormation,
            'dateDebutFormation' => $row->dateDebutFormation,
            'dateFinFormation' => $row->dateFinFormation
        ];
    }
}

function getTempsFormation($idTroupeJoueur)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT `tempsFormation` FROM listeTroupe INNER JOIN troupesJoueur ON listeTroupe.idTroupe = troupesJoueur.idTroupe WHERE idTroupeJoueur=:idTroupeJoueur');
    $stmt->execute(["idTroupeJoueur" => $idTroupeJoueur]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return $row->tempsFormation;
    }
}

function checkTroupeDeck($idJoueur, $idTroupe)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT compositionDeck.idTroupeJoueur FROM compositionDeck INNER JOIN troupesJoueur ON compositionDeck.idTroupeJoueur=troupesJoueur.idTroupeJoueur WHERE troupesJoueur.idTroupe=:idTroupe AND troupesJoueur.idJoueur=:idJoueur');
    $stmt->execute(["idTroupe" => $idTroupe, "idJoueur" => $idJoueur]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return $row->idTroupeJoueur;
    }
}

function getPoidsDeck($idJoueur, $idDeck)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT SUM(listeTroupe.poids*compositionDeck.quantite) AS result FROM listeTroupe INNER JOIN troupesJoueur ON listeTroupe.idTroupe=troupesJoueur.idTroupe INNER JOIN compositionDeck ON troupesJoueur.idTroupeJoueur = compositionDeck.idTroupeJoueur INNER JOIN deck ON compositionDeck.idDeck = deck.idDeck WHERE compositionDeck.idDeck=:idDeck AND deck.idJoueur=:idJoueur');
    $stmt->execute(["idDeck" => $idDeck, "idJoueur" => $idJoueur]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return $row->result;
    }
}

function getMaxPoidsDeck($idJoueur)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT limiteDeck FROM paliersNiveauJoueur INNER JOIN players ON paliersNiveauJoueur.niveauJoueur=players.level WHERE players.id=:idJoueur AND players.level = paliersNiveauJoueur.niveauJoueur');
    $stmt->execute(["idJoueur" => $idJoueur]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return $row->limiteDeck;
    }
}

function getPoidsUnite($idTroupeJoueur)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT poids FROM listeTroupe INNER JOIN troupesJoueur ON listeTroupe.idTroupe=troupesJoueur.idTroupe WHERE idTroupeJoueur=:idTroupeJoueur');
    $stmt->execute(["idTroupeJoueur" => $idTroupeJoueur]);

    if (!($row = $stmt->fetchObject())) {
        return FALSE;
    } else {
        return $row->poids;
    }
}

$app->post('/api/1.0/formation-troupes/{idJoueur}', function ($req, $resp, $args) {
    //global $__player_id;

    $params = $req->getParsedBody();
    // print_r($params);
    $idTroupeJoueur = $params['idTroupeJoueur'];
    $quantite = $params['quantite'];
    $idDeck = $params['idDeck'];
    $idJoueur = $args['idJoueur'];
    $poidsDeck = getPoidsDeck($idJoueur, $idDeck);
    $maxPoidsDeck = getMaxPoidsDeck($idJoueur);
    $poidsFormation = getPoidsUnite($idTroupeJoueur) * $quantite;

    if ($poidsDeck + $poidsFormation > $maxPoidsDeck) {
        __log('Problème Post Formation Troupes - Le poids des troupes à former dépasse la limite du deck');
        return $resp->withStatus(400);   // Bad request
    }

    if (empty($quantite)) {
        __log('Problème Post Formation Troupes - Quantite de la troupe manquant');
        return $resp->withStatus(400);   // Bad request
    }

    if (empty($idDeck)) {
        __log('Problème Post Formation Troupes - Deck manquant');
        return $resp->withStatus(400);   // Bad request
    }

    if (empty($idTroupeJoueur)) {
        __log('Problème Post Formation Troupes - Id Troupe Joueur');
        return $resp->withStatus(400);   // Bad request
    }

    try {
        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();
        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        // Temps de formation des troupes
        $tempsFormation = (getTempsFormation($idTroupeJoueur) * $quantite) * 60;

        //Date actuelle
        $now = new DateTime('now');

        //Date de début de formation
        $dateDebut = new DateTime('now');

        //Date de fin de formation
        $dateFin = $now->modify("+{$tempsFormation} second");
        $dateFinFormat = $dateFin->format("Y-m-d H:i:s");

        //Nom event
        $nameEvent = "formation$idJoueur$idTroupeJoueur$idDeck$quantite";

        //Vérification si il y a déjà une troupe dans le deck
        $checkExist = checkTroupeDeck($idJoueur, $idTroupeJoueur);

        //Insertion dans la table formation des troupes
        $stmt = $pdo->prepare('INSERT INTO formationTroupes (idJoueur, idTroupeJoueur, idDeck, quantiteFormation, dateDebutFormation, dateFinFormation) VALUES(:idJoueur, :idTroupeJoueur, :idDeck, :quantiteFormation, :dateDebutFormation, :dateFinFormation)');
        $stmt->execute(["idJoueur" => $idJoueur, "idTroupeJoueur" => $idTroupeJoueur, "idDeck" => $idDeck, "quantiteFormation" => $quantite, "dateDebutFormation" => $dateDebut->format("Y-m-d H:i:s"), "dateFinFormation" => $dateFin->format("Y-m-d H:i:s")]);

        $id = $pdo->lastInsertId();

        //Création d'un event mysql pour ajouter la quantité de troupes dans le deck, et supprimer la ligne dans la table formation
        if ($checkExist == FALSE) {
            $sql = "CREATE EVENT IF NOT EXISTS $nameEvent ON SCHEDULE AT TIMESTAMP '$dateFinFormat' DO BEGIN INSERT INTO compositionDeck(idTroupeJoueur, idDeck, quantite) VALUES($idTroupeJoueur, $idDeck, $quantite); DELETE FROM formationTroupes WHERE idFormation=$id; END";
        } else {
            $sql = "CREATE EVENT IF NOT EXISTS $nameEvent ON SCHEDULE AT TIMESTAMP '$dateFinFormat' DO BEGIN UPDATE compositionDeck SET quantite=quantite+$quantite WHERE idTroupeJoueur=$idTroupeJoueur AND idDeck=$idDeck; DELETE FROM formationTroupes WHERE idFormation=$id; END";
        }
        $event = $pdo->prepare($sql);
        $event->execute();

        // Always return the new resource created like in a GET
        $ret = getFormation($id);

        if (!$ret) {
            __log('Problème post formation troupe. Aucun enregistrement trouvé #' . $id);      // Impossible case, but...
            return $resp->withStatus(500);   // Not found, but treated as Internal Server Error
        }
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Problème lors du post', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
