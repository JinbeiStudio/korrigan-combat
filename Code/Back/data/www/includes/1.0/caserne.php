<?php

namespace caserne;

/* ---------------------- Niveau de la caserne d'un joueur ------------------ */
/* ------------------ Endpoint : /api/1.0/caserne/{idJoueur} ---------------- */
/* ------------------------------ Method : GET ------------------------------ */
/* ------------------------- Auteur : Maxime Sidoit ------------------------- */

$app->get('/api/1.0/caserne/{idJoueur}', function ($req, $resp, $args) {
   //global $__player_id;

   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */

      $stmt = $pdo->prepare('SELECT `idCaserne`, `idJoueur`, `niveauCaserne` FROM `caserne` WHERE `idJoueur` = :idJoueur');
      $stmt->execute(['idJoueur' => $args[`idJoueur`]]);

      $items = [];
      while ($row = $stmt->fetchObject()) {
         $items[] = [
            'idCaserne' => $row->idCaserne,
            'idJoueur' => $row->idJoueur,
            'niveauCaserne' => $row->niveauCaserne,
         ];
      }

      $ret = array(
         'caserne' => (array) $items,
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Erreur lors de la récupération des données de la caserne', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
/* -------------------------------------------------------------------------- */
