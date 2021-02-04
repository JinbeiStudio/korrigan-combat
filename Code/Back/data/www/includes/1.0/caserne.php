<?php
namespace caserne; 

$app->get('/api/1.0/caserne/{idCaserne}', function ($req, $resp, $args) {
   //global $__player_id;

   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $stmt = $pdo->prepare('SELECT `idCaserne`, `idJoueur`, `niveauCaserne` FROM `caserne` WHERE `idCaserne` = :idCaserne'); 
      $stmt->execute(['idCaserne' => $args[`idCaserne`]]);
      
      $items= [];
      while ($row = $stmt->fetchObject()){
         $items[] = [
            'idCaserne' =>$row->idCaserne,
            'idJoueur' => $row->idJoueur,
            'niveauCaserne' => $row->niveauCaserne,
         ];
      }

      $ret = array(
         'caserne' => (array) $items,
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Erreur lors de la récupération des données de la caserne' . $id, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});