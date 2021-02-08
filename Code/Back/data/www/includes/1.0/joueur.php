<?php

namespace joueur;

/* ------------------------ Informations d'un joueur ------------------------ */
/* ------------------ Endpoint : /api/1.0/joueur/{idJoueur} ----------------- */
/* ------------------------------ Method : GET ------------------------------ */
/* ------------------------- Auteur : Maxime Sidoit ------------------------- */

$app->get('/api/1.0/joueur/{id}', function ($req, $resp, $args) {
   //  global $__player_id;

   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
      $stmt = $pdo->prepare('SELECT `id`, `login`, `town_level`, `level`, `xp`, `power`, `gold`, `gems`, `last_cnx` FROM `players` WHERE `id` = :id');
      $stmt->execute(["id" => $args['id']]);

      $items = [];
      while ($row = $stmt->fetchObject()) {
         $items[] = [
            'id' => $row->id,
            'login' => $row->login,
            'town_level' => $row->town_level,
            'level' => $row->level,
            'xp' => $row->xp,
            'power' => $row->power,
            'gold' => $row->gold,
            'gems' => $row->gems,
            'last_cnx' => $row->last_cnx,
         ];
      }
      $ret = array(
         'joueur' => (array) $items,
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Erreur lors de la récupération des stats du joueur' . $id, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
/* -------------------------------------------------------------------------- */
