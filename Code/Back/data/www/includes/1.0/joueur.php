<?php
namespace joueur; // Because each resource has a couple of functions with the same names


function getJoueur($id) {
   global $__player_id;

   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT `id`, `login`, `town_level`, `level`, `xp`, `power`, `gold`, `gems`, `last_cnx` FROM `players` WHERE `id` = :id'); 
   $stmt->execute([
      'id' => $id,
   ]);
      
   if (!($row = $stmt->fetchObject())) {
      return FALSE;
   } else {
      return [
         'id' =>$row->id,
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
}


$app->get('/api/1.0/joueur/{id}', function ($req, $resp, $args) {
   global $__player_id;

   $id = $args['id'];
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $ret = getJoueur($id);
        
      if (!$ret) {
         __log('Pas de joueur trouvé #' . $id . ' - No record');
         return $resp->withStatus(404);   // Not found
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb get player #' . $id, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});