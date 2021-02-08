<?php

namespace combatTour1;

DEFINE("BONUS_AGILITE", 0.3);
DEFINE("RANDOM_MALUS", 2);
DEFINE("DECK_ACTIF", 0);
DEFINE("DECK_ATTAQUE", 1);
DEFINE("DECK_DEFENSE", 2);
DEFINE("OR_GAGNE", 10);

use DateTime;

/* ----------------------- Combat Tour 1 --------------------------------------- */
/* Endpoint : /api/1.0/combat-tour1/attaquant/{idJoueur}/defenseur/{idDefenseur} */
/* ------------------------------ Method : GET --------------------------------- */
/* ------------------------- Auteur : Julien Gabriel --------------------------- */

$app->get('/api/1.0/combat-tour1/attaquant/{idJoueur}/defenseur/{idDefenseur}', function ($req, $resp, $args) {
    global $__player_id;

    $idJoueur = $args['idJoueur'];
    $idDefenseur = $args['idDefenseur'];

    $deckAttaquant = getDeck($idJoueur, DECK_ATTAQUE, DECK_ACTIF);
    $deckDefenseur = getDeck($idDefenseur, DECK_DEFENSE, DECK_ACTIF);

    if (empty($deckAttaquant)) {
        __log('Le deck du joueur attaquant est vide');
        return $resp->withStatus(400);
    }

    if (empty($deckDefenseur)) {
        __log('Le deck du joueur défenseur est vide');
        return $resp->withStatus(400);
    }

    // Liste des stats des unités pour chaque joueur
    $statsAttaquant = statsDeck($deckAttaquant);
    $statsDefenseur = statsDeck($deckDefenseur);

    //Total pour chaque stats pour l'attaquant
    $totalDegatAttaquant = totalStats($statsAttaquant, "degat");
    $totalAgiliteAttaquant = totalStats($statsAttaquant, "agilite");

    //Total pour chaque stats pour le défenseur
    $totalDegatDefenseur = totalStats($statsDefenseur, "degat");
    $totalAgiliteDefenseur = totalStats($statsDefenseur, "agilite");

    $attaquerAttaquant = attaque($statsAttaquant, $totalDegatDefenseur, $totalAgiliteAttaquant);
    $attaquerDefenseur = attaque($statsDefenseur, $totalDegatAttaquant, $totalAgiliteDefenseur);

    $orAttaquant = orGagne($attaquerAttaquant);
    $orDefenseur = orGagne($attaquerDefenseur);

    //return buildResponse($resp, $attaquerAttaquant);

    try {
        /** SECURITY CHECK - MANDATORY */
        $pdo = getPDO();

        if (!checkToken()) {
            return $resp->withStatus(401);   // Unauthorized
        }
        /** END OF SECURITY CHECK */

        /* ----------------- Ajout d'un nouveau combat dans la table ---------------- */
        $date = new DateTime('now');
        $stmt = $pdo->prepare('INSERT INTO combats (idAttaquant, idDefenseur, dateCombat, gagnant, goldAttaquant, goldDefenseur)  VALUES (:idAttaquant, :idDefenseur, :dateCombat, :gagnant, :orAttaquant, :orDefenseur)');
        $stmt->execute(["idAttaquant" => $idJoueur, "idDefenseur" => $idDefenseur, "dateCombat" => $date->format("Y-m-d H:i:s"), "orAttaquant" => $orAttaquant, "orDefenseur" => $orDefenseur, "gagnant" => $idDefenseur]);
        $idCombat = $pdo->lastInsertId();
        /* -------------------------------------------------------------------------- */

        /* ----------- Insertions des résultats du tour 1 pour l'attaquant ---------- */
        foreach ($attaquerAttaquant as $key => $value) {
            $stmt = $pdo->prepare('INSERT INTO resultatsTours (idCombat, numeroTour, idTroupeJoueur, quantitePerdues) VALUES (:idCombat, 1, :idTroupeJoueur, :quantitePerdues)');
            $stmt->execute(["idCombat" => $idCombat, "idTroupeJoueur" => $key, "quantitePerdues" => $value['quantitePerdue']]);
        }
        /* -------------------------------------------------------------------------- */

        /* ----------- Insertion des résultats du tour 1 pour le défenseur ---------- */
        foreach ($attaquerDefenseur as $key => $value) {
            $stmt = $pdo->prepare('INSERT INTO resultatsTours (idCombat, numeroTour, idTroupeJoueur, quantitePerdues) VALUES (:idCombat, 1, :idTroupeJoueur, :quantitePerdues)');
            $stmt->execute(["idCombat" => $idCombat, "idTroupeJoueur" => $key, "quantitePerdues" => $value['quantitePerdue']]);
        }
        /* -------------------------------------------------------------------------- */

        /* ------- Modification des quantités de troupe du deck de l'attaquant ------ */
        foreach ($attaquerAttaquant as $key => $value) {
            if ($value['quantite'] > 0) {
                $stmt = $pdo->prepare('UPDATE compositionDeck SET quantite = :quantite WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                $stmt->execute(["quantite" => $value['quantite'], "idDeck" => $deckAttaquant[0]['idDeck'], "idTroupeJoueur" => $key]);
            } else {
                $stmt = $pdo->prepare('DELETE FROM compositionDeck WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                $stmt->execute(["idDeck" => $deckAttaquant[0]['idDeck'], "idTroupeJoueur" => $key]);
            }
        }
        /* -------------------------------------------------------------------------- */

        /* ------- Modification des quantités de troupe du deck du défenseur -------- */
        foreach ($attaquerDefenseur as $key => $value) {
            if ($value['quantite'] > 0) {
                $stmt = $pdo->prepare('UPDATE compositionDeck SET quantite = :quantite WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                $stmt->execute(["quantite" => $value['quantite'], "idDeck" => $deckDefenseur[0]['idDeck'], "idTroupeJoueur" => $key]);
            } else {
                $stmt = $pdo->prepare('DELETE FROM compositionDeck WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                $stmt->execute(["idDeck" => $deckAttaquant[0]['idDeck'], "idTroupeJoueur" => $key]);
            }
        }
        /* -------------------------------------------------------------------------- */

        $items = [];
        $items['resultat'] = $attaquerAttaquant;
        $items['resultat']['orGagne'] = $orAttaquant;
        $items['resultat']['orPerdu'] = $orDefenseur;
        $ret = array(
            'resultatTour1Attaquant' => (array) $items,
        );

        if (!$ret) {
            __log('Erreur lors du calcul du tour 1');
            return $resp->withStatus(404);   // Not found
        }
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        __logException('Erreur lors du calcul du tour 1', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
/* -------------------------------------------------------------------------- */

/* ----------------------- Obtenir le niveau du joueur ---------------------- */
function getDeck($idJoueur, $type, $numero)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT deck.idDeck, `idTroupeJoueur`, `quantite` FROM `compositionDeck` INNER JOIN `deck` ON deck.idDeck=compositionDeck.idDeck  WHERE deck.idJoueur=:idJoueur AND deck.type= :type AND deck.numeroDeck= :numero');
    $stmt->execute(["idJoueur" => $idJoueur, "type" => $type, "numero" => $numero]);

    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
}
/* -------------------------------------------------------------------------- */

/* ----------------------- Stats de la troupe ---------------------- */
function statsTroupe($idTroupeJoueur)
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT `vie`, `degat`, `agilite`, `portee`, `vitesse`, `capaciteTransport` FROM `listeTroupe` INNER JOIN `stats` ON listeTroupe.idTroupe=stats.idTroupe INNER JOIN `troupesJoueur` ON troupesJoueur.idTroupe=stats.idTroupe WHERE troupesJoueur.idTroupeJoueur=:idTroupeJoueur');
    $stmt->execute(["idTroupeJoueur" => $idTroupeJoueur]);

    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    return $result;
}
/* -------------------------------------------------------------------------- */

/* ----------------------- Stats du deck ---------------------- */
function statsDeck($deck)
{
    foreach ($deck as $value) {
        $stats[$value['idTroupeJoueur']] = ["degat" => statsTroupe($value['idTroupeJoueur'])[0]['degat'], "vie" => statsTroupe($value['idTroupeJoueur'])[0]['vie'], "agilite" => statsTroupe($value['idTroupeJoueur'])[0]['agilite'], "portee" => statsTroupe($value['idTroupeJoueur'])[0]['portee'], "vitesse" => statsTroupe($value['idTroupeJoueur'])[0]['vitesse'], "capaciteTransport" => statsTroupe($value['idTroupeJoueur'])[0]['capaciteTransport'], "quantite" => $value['quantite']];
    }

    return $stats;
}
/* -------------------------------------------------------------------------- */

/* -------------------- Total pour une stats dans un deck ------------------- */
function totalStats($stats, $typeStats)
{
    $result = 0;
    foreach ($stats as $value) {
        $result += $value[$typeStats] * $value['quantite'];
    }
    return $result;
}
/* -------------------------------------------------------------------------- */


/* ---------------- Or gagné par le joueur à la fin du combat --------------- */
function orGagne($troupes)
{
    foreach ($troupes as $key => $value) {
        $troupes['capaciteTransportTotal'] += $troupes[$key]['capaciteTransport'] * $troupes[$key]['quantite'];
    }

    $orGagne = $troupes['capaciteTransportTotal'] * OR_GAGNE;
    return $orGagne;
}
/* -------------------------------------------------------------------------- */


/* -------------- Calcul des pertes de troupes lors d'un combat ------------- */
function attaque($stats, $totalDegat, $totalAgilite)
{
    $storeVie = [];
    $storeQuantite = [];
    $i = 0;

    //Baisse des dégats avec l'agilité et un nombre aléatoire
    $totalDegat = $totalDegat - ($totalAgilite * BONUS_AGILITE) - $totalDegat * (mt_rand(0, RANDOM_MALUS) / 10);

    //Tant qu'il reste des dégats on baisse les vies des unités, si la vie atteint on baisse la quantité
    while ($totalDegat > 0) {
        foreach ($stats as $key => $value) {
            if ($value['quantite'] > 0) {
                if ($i == 0) {
                    $storeVie[$key] = $value['vie'];
                    $storeQuantite[$key] = $value['quantite'];
                }
                $stats[$key]['vie'] = $value['vie'] - 1;

                $totalDegat = $totalDegat - 1;

                if ($stats[$key]['vie'] == 0) {
                    $stats[$key]['quantite'] = $value['quantite'] - 1;
                    $stats[$key]['vie'] = $storeVie[$key];
                }
            }
        }
        $i++;
    }

    foreach ($stats as $key => $value) {
        $stats[$key]['vie'] = $storeVie[$key];
        $stats[$key]['quantitePerdue'] = $storeQuantite[$key] - $stats[$key]['quantite'];
        /* $stats['quantiteTotalRestant'] += $stats[$key]['quantite']; */
    }

    return $stats;
}
/* -------------------------------------------------------------------------- */
