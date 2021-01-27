<?php
namespace xxx; // Because each resource has a couple of functions with the same names


function getResource($id) {
   global $__player_id;

   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT `field_1`, `field_2` FROM `xxx` WHERE `player_id` = :player_id AND `id` = :id'); 
   $stmt->execute([
      'id' => $id,
      'player_id' => $__player_id,
   ]);
      
   if (!($row = $stmt->fetchObject())) {
      return FALSE;
   } else {
      return [
         'id' => $id,
         'field_1' => $row->field_1,
         'field_2' => $row->field_2,
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
   
      $ret = getResource($id);
        
      if (!$ret) {
         __log('Pb get xxx #' . $id . ' - No record');
         return $resp->withStatus(404);   // Not found
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb get xxx #' . $id, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});