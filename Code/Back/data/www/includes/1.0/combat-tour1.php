<?php

namespace combatTour1;

DEFINE("BONUS_AGILITE", 0.3);
DEFINE("RANDOM_MALUS", 2);
DEFINE("DECK_ACTIF", 0);
DEFINE("DECK_ATTAQUE", 1);
DEFINE("DECK_DEFENSE", 2);
DEFINE("OR_GAGNE", 10);
DEFINE("EXPERIENCE_GAGNE", 5);

use DateTime;

/* ----------------------- Combat Tour 1 --------------------------------------- */
/* Endpoint : /api/1.0/combat-tour1/attaquant/{idJoueur}/defenseur/{idDefenseur} */
/* ------------------------------ Method : GET --------------------------------- */
/* ------------------------- Auteur : Julien Gabriel --------------------------- */

$app->get('/api/1.0/combat-tour1/attaquant/{idJoueur}/defenseur/{idDefenseur}', function ($req, $resp, $args) {
    global $__player_id;

    //Id du joueur attaquant
    $idJoueur = $args['idJoueur'];
    //Id du joueur défenseur
    $idDefenseur = $args['idDefenseur'];

    //Deck des joueurs
    $deckAttaquant = getDeck($idJoueur, DECK_ATTAQUE, DECK_ACTIF);
    $deckDefenseur = getDeck($idDefenseur, DECK_DEFENSE, DECK_ACTIF);

    /* ---------------------- Si le deck est vide on annule --------------------- */
    if (empty($deckAttaquant) || $deckAttaquant === FALSE) {
        __log('Le deck du joueur attaquant est vide');
        return $resp->withStatus(400);
    }

    if (empty($deckDefenseur) || $deckDefenseur === FALSE) {
        __log('Le deck du joueur défenseur est vide');
        return $resp->withStatus(400);
    }
    /* -------------------------------------------------------------------------- */


    // Liste des stats des unités pour chaque joueur
    $statsAttaquant = statsDeck($deckAttaquant);
    $statsDefenseur = statsDeck($deckDefenseur);


    /* ---------- Si erreur lors de la récupération des stats on annule --------- */
    if (empty($statsAttaquant) || $statsAttaquant === FALSE) {
        __log('Erreur lors de la récupération des stats de l\'attaquant');
        return $resp->withStatus(400);
    }

    if (empty($statsDefenseur) || $statsDefenseur === FALSE) {
        __log('Erreur lors de la récupération des stats du défenseur');
        return $resp->withStatus(400);
    }
    /* -------------------------------------------------------------------------- */


    //Total pour chaque stats pour l'attaquant
    $totalDegatAttaquant = totalStats($statsAttaquant, "degat");
    $totalAgiliteAttaquant = totalStats($statsAttaquant, "agilite");

    //Total pour chaque stats pour le défenseur
    $totalDegatDefenseur = totalStats($statsDefenseur, "degat");
    $totalAgiliteDefenseur = totalStats($statsDefenseur, "agilite");

    //Attaque des joueurs avec retour des unités restantes
    $attaquerAttaquant = attaque($statsAttaquant, $totalDegatDefenseur, $totalAgiliteAttaquant);
    $attaquerDefenseur = attaque($statsDefenseur, $totalDegatAttaquant, $totalAgiliteDefenseur);

    //return buildResponse($resp, $attaquerAttaquant);

    // Or éventuellement gagné pour chaque joueur
    $orAttaquant = orGagne($attaquerAttaquant);
    $orDefenseur = orGagne($attaquerDefenseur);

    //Experience éventuellement gagné pour le joueur
    $experienceAttaquant = experienceGagne($attaquerDefenseur);
    $experienceDefenseur = experienceGagne($attaquerAttaquant);


    //Détermine si il y a un gagnant au premier tour
    if ($attaquerAttaquant['perdu'] === true || $attaquerAttaquant['perdu'] === true && $attaquerDefenseur['perdu'] === true) {
        $idGagnant = $idDefenseur;
        $idPerdant = $idJoueur;
        $orGagne = $orDefenseur;
        $experienceGagne = $experienceDefenseur;
    } elseif ($attaquerAttaquant['perdu'] === false && $attaquerDefenseur['perdu'] === true) {
        $idGagnant = $idJoueur;
        $idPerdant = $idDefenseur;
        $orGagne = $orAttaquant;
        $experienceGagne = $experienceAttaquant;
    } elseif ($attaquerAttaquant['perdu'] === false && $attaquerDefenseur['perdu'] === false) {
        $idGagnant = NULL;
    }

    //return buildResponse($resp, $attaquerAttaquant);

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

        /* ----------------- Ajout d'un nouveau combat dans la table ---------------- */
        $date = new DateTime('now');
        $stmt = $pdo->prepare('INSERT INTO combats (idAttaquant, idDefenseur, dateCombat, gagnant, goldAttaquant, goldDefenseur, experienceAttaquant, experienceDefenseur)  VALUES (:idAttaquant, :idDefenseur, :dateCombat, :gagnant, :orAttaquant, :orDefenseur, :experienceAttaquant, :experienceDefenseur)');
        $stmt->execute(["idAttaquant" => $idJoueur, "idDefenseur" => $idDefenseur, "dateCombat" => $date->format("Y-m-d H:i:s"), "orAttaquant" => $orAttaquant, "orDefenseur" => $orDefenseur, "gagnant" => $idGagnant, "experienceAttaquant" => $experienceAttaquant, "experienceDefenseur" => $experienceDefenseur]);
        $idCombat = $pdo->lastInsertId();
        /* -------------------------------------------------------------------------- */

        /* ----------- Insertions des résultats du tour 1 pour l'attaquant ---------- */
        foreach ($attaquerAttaquant as $key => $value) {
            if ($key != "perdu") {
                $stmt = $pdo->prepare('INSERT INTO resultatsTours (idCombat, numeroTour, idTroupeJoueur, quantitePerdues) VALUES (:idCombat, 1, :idTroupeJoueur, :quantitePerdues)');
                $stmt->execute(["idCombat" => $idCombat, "idTroupeJoueur" => $key, "quantitePerdues" => $value['quantitePerdue']]);
            }
        }
        /* -------------------------------------------------------------------------- */

        /* ----------- Insertion des résultats du tour 1 pour le défenseur ---------- */
        foreach ($attaquerDefenseur as $key => $value) {
            if ($key != "perdu") {
                $stmt = $pdo->prepare('INSERT INTO resultatsTours (idCombat, numeroTour, idTroupeJoueur, quantitePerdues) VALUES (:idCombat, 1, :idTroupeJoueur, :quantitePerdues)');
                $stmt->execute(["idCombat" => $idCombat, "idTroupeJoueur" => $key, "quantitePerdues" => $value['quantitePerdue']]);
            }
        }
        /* -------------------------------------------------------------------------- */

        /* ------- Modification des quantités de troupe du deck de l'attaquant ------ */
        foreach ($attaquerAttaquant as $key => $value) {
            if ($key != "perdu") {
                if ($value['quantite'] > 0) {
                    $stmt = $pdo->prepare('UPDATE compositionDeck SET quantite = :quantite WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                    $stmt->execute(["quantite" => $value['quantite'], "idDeck" => $deckAttaquant[0]['idDeck'], "idTroupeJoueur" => $key]);
                } else {
                    $stmt = $pdo->prepare('DELETE FROM compositionDeck WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                    $stmt->execute(["idDeck" => $deckAttaquant[0]['idDeck'], "idTroupeJoueur" => $key]);
                }
            }
        }
        /* -------------------------------------------------------------------------- */

        /* ------- Modification des quantités de troupe du deck du défenseur -------- */
        foreach ($attaquerDefenseur as $key => $value) {
            if ($key != "perdu") {
                if ($value['quantite'] > 0) {
                    $stmt = $pdo->prepare('UPDATE compositionDeck SET quantite = :quantite WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                    $stmt->execute(["quantite" => $value['quantite'], "idDeck" => $deckDefenseur[0]['idDeck'], "idTroupeJoueur" => $key]);
                } else {
                    $stmt = $pdo->prepare('DELETE FROM compositionDeck WHERE idDeck=:idDeck AND idTroupeJoueur=:idTroupeJoueur');
                    $stmt->execute(["idDeck" => $deckDefenseur[0]['idDeck'], "idTroupeJoueur" => $key]);
                }
            }
        }
        /* -------------------------------------------------------------------------- */

        //Si il y a un gagant on ajout l'or et l'expérience
        if ($idGagnant != NULL) {
            //Ajout de l'xp et l'or au gagant
            $stmt = $pdo->prepare('UPDATE players SET gold = gold + :orGagne, xp= xp + :experienceGagne WHERE id=:idGagnant');
            $stmt->execute(["orGagne" => $orGagne, "experienceGagne" => $experienceGagne, "idGagnant" => $idGagnant]);

            //Baisse de l'or du perdant
            $stmt = $pdo->prepare('UPDATE players SET gold = GREATEST(gold - :orGagne, 0) WHERE id=:idPerdant');
            $stmt->execute(["orGagne" => $orGagne, "idPerdant" => $idPerdant]);
        }

        $pdo->commit();
        /* -------------------------------------------------------------------------- */
        /*                            Fin de la transaction                           */
        /* -------------------------------------------------------------------------- */

        $items = [];
        $items['resultat'] = $attaquerAttaquant;
        $items['resultat']['orGagne'] = $orAttaquant;
        $items['resultat']['orPerdu'] = $orDefenseur;
        if ($idGagnant == $idJoueur) {
            $items['resultat']['gagne'] = true;
        } else {
            $items['resultat']['gagne'] = false;
        }

        $ret = array(
            'resultatTour1Attaquant' => (array) $items,
        );

        if (!$ret) {
            __log('Erreur lors du calcul du tour 1');
            return $resp->withStatus(404);   // Not found
        }
        return buildResponse($resp, $ret);
    } catch (Exception $e) {
        $pdo->rollback();
        __logException('Erreur lors du calcul du tour 1', $e);
        return $resp->withStatus(500);   // Internal Server Error
    }
});
/* -------------------------------------------------------------------------- */

/* ----------------------- Obtenir le niveau du joueur ---------------------- */
function getDeck($idJoueur, $type, $numero)
{
    $pdo = getPDO();

    try {
        $stmt = $pdo->prepare('SELECT deck.idDeck, `idTroupeJoueur`, `quantite` FROM `compositionDeck` INNER JOIN `deck` ON deck.idDeck=compositionDeck.idDeck  WHERE deck.idJoueur=:idJoueur AND deck.type= :type AND deck.numeroDeck= :numero');
        $stmt->execute(["idJoueur" => $idJoueur, "type" => $type, "numero" => $numero]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        return FALSE;
    }
}
/* -------------------------------------------------------------------------- */

/* ----------------------- Stats de la troupe ---------------------- */
function statsTroupe($idTroupeJoueur)
{
    $pdo = getPDO();

    try {
        $stmt = $pdo->prepare('SELECT `vie`, `degat`, `agilite`, `portee`, `vitesse`, `capaciteTransport` FROM `listeTroupe` INNER JOIN `stats` ON listeTroupe.idTroupe=stats.idTroupe INNER JOIN `troupesJoueur` ON troupesJoueur.idTroupe=stats.idTroupe WHERE troupesJoueur.idTroupeJoueur=:idTroupeJoueur AND troupesJoueur.niveauTroupe=stats.niveau');
        $stmt->execute(["idTroupeJoueur" => $idTroupeJoueur]);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        return FALSE;
    }
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


/* ---------------- Expérience gagné par le joueur à la fin du combat --------------- */
function experienceGagne($troupesAdversaire)
{
    $quantitePerdue = 0;
    foreach ($troupesAdversaire as $key => $value) {
        $quantitePerdue += $value['quantitePerdue'];
    }

    $experienceGagne = $quantitePerdue * EXPERIENCE_GAGNE;
    return $experienceGagne;
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

        $allDead = TRUE;
        foreach ($stats as $key => $value) {
            if ($stats[$key]['quantite'] != 0) {
                $allDead = FALSE;
                break;
            }
        }

        if ($allDead) {
            $stats['perdu'] = true;
            break;
        }
    }



    if (!$allDead) {
        $stats['perdu'] = false;
    }

    foreach ($stats as $key => $value) {
        if ($key != "perdu") {
            $stats[$key]['vie'] = $storeVie[$key];
            if ($storeQuantite[$key] != NULL) {
                $stats[$key]['quantitePerdue'] = $storeQuantite[$key] - $stats[$key]['quantite'];
            } else {
                $stats[$key]['quantitePerdue'] = 0;
            }

            /* $stats['quantiteTotalRestant'] += $stats[$key]['quantite']; */
        }
    }

    /*  print_r($stats);
    die; */

    return $stats;
}
/* -------------------------------------------------------------------------- */
