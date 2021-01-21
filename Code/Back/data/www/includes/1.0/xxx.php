<?php
namespace xxx; // Because each resource has a couple of functions with the same names

// Check if Resource ID exists for the current player
function playerOwnsResource($id) {
   global $__player_id;

   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT 1 FROM `xxx` WHERE `player_id` = :player_id AND `id` = :id'); 
   $stmt->execute([
      'player_id' => $__player_id,
      'id' => $id,
   ]);
   if ($stmt->fetchObject() === FALSE) {
      return FALSE;
   } else {
      return TRUE;
   }
}

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

$app->get('/api/1.0/xxx', function ($req, $resp, $args) {
   try {
      global $__player_id;

      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */

      $stmt = $pdo->prepare('SELECT `id`, `field_1`, `field_2` FROM `xxx` WHERE `player_id` = :player_id'); 
      $stmt->execute([
         'player_id' => $__player_id,
      ]);

      $items = [];
      while ($row = $stmt->fetchObject()) {
         $items[] = [
            'id' => $row->id,
            'field_1' => $row->field_1,
            'field_2' => $row->field_2,
         ];
      }
      $ret = array(
         'xxxs' => (array) $items,        // Cast as array for security
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb get xxx', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});

$app->get('/api/1.0/xxx/{id}', function ($req, $resp, $args) {
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

$app->post('/api/1.0/xxx', function ($req, $resp, $args) {
   global $__player_id;

   $params = $req->getParsedBody();
   // print_r($params);
   $field_1 = $params['field_1'];
   $field_2 = $params['field_2'];

   // Example of checks...
   if (empty($field_1)) {
      __log('Pb post xxx - Missing Field_1');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($field_2)) {
      __log('Pb post xxx - Missing Field_2');
      return $resp->withStatus(400);   // Bad request
   }
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $stmt = $pdo->prepare('INSERT INTO `xxx` SET `player_id` = :player_id, `field_1` = :field_1, `field_2` = :field_2'); 
      $stmt->execute([
         'player_id' => $__player_id,
         'field_1' => $field_1,
         'field_2' => $field_2,
         ]);
   
      $id = $pdo->lastInsertId();
      // Always return the new resource created like in a GET
      $ret = getResource($id);

      if (!$ret) {
         __log('Pb post xxx. Got ID #' . $id . ' + No record!');      // Impossible case, but...
         return $resp->withStatus(500);   // Not found, but treated as Internal Server Error
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb post xxx', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});

$app->put('/api/1.0/xxx/{id}', function ($req, $resp, $args) {
   global $__player_id;

   $params = $req->getParsedBody();
   // print_r($params);
   $id = $args['id'];
   $field_names = [     // Will contain candidate fields to be updated
      'field_1',
      'field_2',
   ];
   $fields = [];
   foreach ($field_names as $a_name) {
      if (array_key_exists($a_name, $params)) {
         $fields[$a_name] = $params[$a_name];
      }
   }
   // print_r($fields);
   if (count($fields) <= 0) {
      __log('Pb put xxx #' . $id . ' - No field to update!');
      return $resp->withStatus(400);   // Bad request
   }
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      if (!playerOwnsResource($id)) {
         return $resp->withStatus(404);   // Not found
      }

      $req = '';
      foreach ($fields as $a_name => $a_val) {
         $req .= (empty($req) ? '' : ', ') . '`' . $a_name . '` = :' . $a_name;
      }
      $req = 'UPDATE `xxx` SET ' . $req . ' WHERE `player_id` = :player_id AND `id` = :id';
      $stmt = $pdo->prepare($req); 
      $stmt->execute(
         array_merge($fields, [
            'id' => $id,
            'player_id' => $__player_id,
         ])
      );

      $ret = getResource($id);
        
      if (!$ret) {
         __log('Pb put xxx #' . $id . ' - No record');      // Impossible case, but...
         return $resp->withStatus(404);   // Not found
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb post xxx', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
?>