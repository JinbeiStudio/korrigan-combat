<?php
namespace caserne; // Because each resource has a couple of functions with the same names


function getCaserne($idCaserne) {
   global $__player_id;

   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT `idCaserne`, `idJoueur`, `niveauCaserne` FROM `caserne` WHERE `idCaserne` = :idCaserne'); 
   $stmt->execute([
      'idCaserne' => $idCaserne,
   ]);
      
   if (!($row = $stmt->fetchObject())) {
      return FALSE;
   } else {
      return [
         'idCaserne' =>$row->idCaserne,
         'idJoueur' => $row->idJoueur,
         'niveauCaserne' => $row->niveauCaserne,
      ];
   }
}


$app->get('/api/1.0/caserne/{idCaserne}', function ($req, $resp, $args) {
   global $__player_id;

   $idCaserne = $args['idCaserne'];
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $ret = getCaserne($idCaserne);
        
      if (!$ret) {
         __log('Pas de caserne trouvé #' . $idCaserne . ' - No record');
         return $resp->withStatus(404);   // Not found
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb get caserne #' . $idCaserne, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});