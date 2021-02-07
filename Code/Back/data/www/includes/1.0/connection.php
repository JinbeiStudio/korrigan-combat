<?php
require_once __DIR__ . '/../db.php';

$app->get('/api/1.0/login', function ($req, $resp, $args) {
   try {
      global $__player_id, $__player_login;

      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      /** END OF SECURITY CHECK */
   
      $password = $_GET['password'];
      $login = $_GET['login'];
      $versions = $_GET['ver'];

      $stmt = $pdo->prepare('SELECT `id`,`login`,`level`,`gold`,`gems` FROM `players` AS `p` WHERE `p`.`login` = :login AND `p`.`password` = make_password(:password) AND `p`.`enabled` = "y"');
      $stmt->execute(array('login' => $login, 'password' => $password));
      if (!($row = $stmt->fetchObject())) {
         deleteTheCookie();
         return $resp->withStatus(401);   // Unauthorized
      }

      $token = openssl_random_pseudo_bytes(16);
      $token = bin2hex($token);
      $__player_id = $row->id;
      $__player_login = $login;
      $stmt2 = $pdo->prepare('INSERT INTO `tokens` SET `player_id` = :id, `token` = :token, `expiration` = DATE_ADD(NOW(), INTERVAL :expiration SECOND)');
      $stmt2->execute(array('id' => $__player_id, 'token' => $token, 'expiration' => TOKEN_EXPIRATION));

      setTheCookie($token);

      $ret = array(
         'token'=>$token,
         'server_time' => time(),
         'player'=>array(
            'id' => (int)$__player_id,
            'login' => utf8_encode($row->login),
            'level' => +$row->level,
            'gold' => +$row->gold,
            'gems' => +$row->gems,
         )
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Pb login', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});

$app->post('/api/1.0/password', function ($req, $resp, $args) {
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $params = $req->getParsedBody();
      if ((strlen($params['new_password']) < PASSWORD_MIN_LEN) || (strlen($params['new_password']) > PASSWORD_MAX_LEN)) {
         __log('Pb de longueur de mdp');
         return $resp->withStatus(416);   // Range not valid
      } else {
         $stmt = $pdo->prepare('UPDATE `players` AS `p` INNER JOIN `tokens` AS `t` ON `t`.`login` = `p`.`login` AND `t`.`token` = :token SET `p`.`password` = :new_password WHERE (`p`.`password` IS NULL OR `p`.`password` = PASSWORD(:old_password)) AND `p`.`enabled` = "y"');
         $stmt->execute(array('token' => $_GET['token'], 'old_password' => $params['old_password'], 'new_password' => $params['new_password']));
         $ret = (($stmt->rowCount() > 0) ? 1 : 0);
         __log('Changement mdp : Count = ' . $stmt->rowCount());
      }
      if ($ret > 0) {
         return $resp->withStatus(200);   // OK
      } else {
         return $resp->withStatus(304);   // Not Modified
      }
   } catch (Exception $e) {
      __logException('Pb password', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});

$app->get('/api/1.0/logout', function ($req, $resp, $args) {
   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */
   
      $stmt = $pdo->prepare('DELETE FROM tokens WHERE token = :token');
      $stmt->execute(array('token' => $_GET['token']));
      deleteTheCookie();
      return $resp->withStatus(200);   // OK
   } catch (Exception $e) {
      __logException('Pb Logout', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
