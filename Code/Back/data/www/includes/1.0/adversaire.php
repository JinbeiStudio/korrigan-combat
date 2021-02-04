<?php
namespace adversaire; // Because each resource has a couple of functions with the same names


function getInfoJoueur($id) {
   global $__player_id;

   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT`level` FROM `players` WHERE `id` = :id'); 
   $stmt->execute(['id'=>$id]);
      
   if (!($row = $stmt->fetchObject())) {
      return FALSE;
   } else {
      return  $row->level;
   }
}


$app->get('/api/1.0/adversaire/{id}', function ($req, $resp, $args) {
   global $__player_id;

   $id = $args['id'];
   $niveauJoueur=getInfoJoueur($id);
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();


      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
      $stmt = $pdo->prepare('SELECT `id`,`level`,`login` FROM `players` WHERE `level` = :level AND `id` != :id ORDER BY rand() LIMIT 5' ); 
      $stmt->execute(['level'=>$niveauJoueur, 'id'=>$id],);
        
      $items = [];
      while ($row = $stmt->fetchObject()){
         $items[] = [
               'id' =>$row->id,
               'login' => $row->login,
               'level' => $row->level,
            ];
      }
      $ret = array(
         'adversaire' => (array) $items,
      );


      if (!$ret) {
         __log('Pas d\'adversaire trouvé #' . $id . ' - No record');
         return $resp->withStatus(404);   // Not found
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Probleme get adversaire #' . $id, $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});