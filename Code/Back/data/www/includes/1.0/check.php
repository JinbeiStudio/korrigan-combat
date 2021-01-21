<?php
require_once __DIR__ . '/../db.php';

$__token_ok = FALSE;
$__player_id = -1;
$__player_login = '';
$__token = $_COOKIE[TOKEN_NAME];
if (empty($__token)) {
   $__token = $_GET[TOKEN_NAME];
}

function setTheCookie($val)
{
   global   $__token;

   setcookie(TOKEN_NAME, $val, 0, '/');
   $__token = $val;
}

function deleteTheCookie()
{
   global   $__token;

   setcookie(TOKEN_NAME, '', 0, '/');
   $__token = '';
}

function checkToken()
{
   global   $__token, $__token_ok, $__player_id, $__player_login;

   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT `p`.`id`, `p`.`login` FROM `tokens` AS `t` INNER JOIN `players` AS `p` ON `p`.`id` = `t`.`player_id` WHERE `t`.`token` = :token AND `t`.`expiration` > NOW() AND `p`.`enabled` = "y"');
   $stmt->execute(array('token' => $__token));
   if ($row = $stmt->fetchObject()) {
      $stmt = $pdo->prepare('UPDATE `tokens` SET `expiration` = DATE_ADD(NOW(), INTERVAL :expiration SECOND) WHERE `token` = :token');
      $stmt->execute(array('token' => $__token, 'expiration' => TOKEN_EXPIRATION));
      $__player_login = $row->login;
      $__player_id = $row->id;
      setTheCookie($__token);
      return TRUE;
   } else {
      $__player_login = '';
      $__player_id = -1;
      deleteTheCookie();
      return FALSE;
   }
};

$app->get('/api/1.0/check', function ($req, $resp, $args) {
   global   $__player_id;

   try {
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      } else {
         $ret = [
            'id' => $__player_id,
         ];
         return buildResponse($resp, $ret);
      }
   } catch (Exception $e) {
      __logException('Pb Check token', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
