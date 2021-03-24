<?php
namespace palierJoueur; 

$app->get('/api/1.0/paliers-joueur', function ($req, $resp, $args) {
   //global $__player_id;

   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $stmt = $pdo->prepare('SELECT `niveauJoueur`, `experienceRequise`, `limiteChargement` FROM `paliersNiveauJoueur`'); 
      $stmt->execute();
      
      $items= [];
      while ($row = $stmt->fetchObject()){
         $items[] = [
            'niveauJoueur' => $row->niveauJoueur,
            'experienceRequise' => $row->experienceRequise,
            'limiteChargement' => $row->limiteChargement,
         ];
      }

      $ret = array(
         'paliers-joueur' => (array) $items,
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Erreur lors de la récupération des données des paliers des joueurs');
      return $resp->withStatus(500);   // Internal Server Error
   }
});