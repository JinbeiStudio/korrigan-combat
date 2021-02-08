<?php

namespace listeTroupes;

/* ------------------------ Liste des troupes du jeu ------------------------ */
/* ----------------------- Endpoint : /api/1.0/troupe ----------------------- */
/* ------------------------------ Method : GET ------------------------------ */
/* ------------------------- Auteur : Julien Gabriel ------------------------ */

$app->get('/api/1.0/troupe', function ($req, $resp, $args) {
   try {
      //global $__player_id;

      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */

      $stmt = $pdo->prepare('SELECT `idTroupe`, `nomTroupe`, `niveauDebloquage`, `poids`, `tempsFormation`, `capaciteTransport`,`portee`, `vitesse` FROM `listeTroupe`');
      $stmt->execute();

      $items = [];
      while ($row = $stmt->fetchObject()) {
         $items[] = [
            'idTroupe' => $row->idTroupe,
            'nomTroupe' => $row->nomTroupe,
            'niveauDebloquage' => $row->niveauDebloquage,
            'poids' => $row->poids,
            'tempsFormation' => $row->tempsFormation,
            'capaciteTransport' => $row->capaciteTransport,
            'portee' => $row->portee,
            'vitesse' => $row->vitesse,
         ];
      }
      $ret = array(
         'listeTroupes' => (array) $items,        // Cast as array for security
      );
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Erreur lors de la récupération des troupes du jeu', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
/* -------------------------------------------------------------------------- */

/* --------------------- Get liste troupes après un post -------------------- */
function getResource($id)
{
   // try..catch.. is already done in caller function
   // Security check is done in caller function
   $pdo = getPDO();
   $stmt = $pdo->prepare('SELECT `idTroupe`, `nomTroupe`, `niveauDebloquage`, `poids`, `tempsFormation`, `capaciteTransport`, `portee`, `vitesse` FROM `listeTroupe` WHERE `idTroupe` = :idTroupe');
   $stmt->execute(['idTroupe' => $id]);

   if (!($row = $stmt->fetchObject())) {
      return FALSE;
   } else {
      return [
         'idTroupe' => $row->idTroupe,
         'nomTroupe' => $row->nomTroupe,
         'niveauDebloquage' => $row->niveauDebloquage,
         'poids' => $row->poids,
         'tempsFormation' => $row->tempsFormation,
         'capaciteTransport' => $row->capaciteTransport,
         'portee' => $row->portee,
         'vitesse' => $row->vitesse,
      ];
   }
}
/* -------------------------------------------------------------------------- */

/* ------------------------ Liste des troupes du jeu ------------------------ */
/* ----------------------- Endpoint : /api/1.0/troupe ----------------------- */
/* ------------------------------ Method : POST ----------------------------- */
/* ------------------------- Auteur : Julien Gabriel ------------------------ */
$app->post('/api/1.0/troupe', function ($req, $resp, $args) {
   //global $__player_id;

   $params = $req->getParsedBody();
   // print_r($params);
   $nomTroupe = $params['nomTroupe'];
   $niveauDebloquage = $params['niveauDebloquage'];
   $poids = $params['poids'];
   $tempsFormation = $params['tempsFormation'];
   $capaciteTransport = $params['capaciteTransport'];
   $portee = $params['portee'];
   $vitesse = $params['vitesse'];

   // Example of checks...
   if (empty($nomTroupe)) {
      __log('Problème Post Liste Troupe - Nom de la troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($niveauDebloquage)) {
      __log('Problème Post Liste Troupe - Niveau débloquage troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($poids)) {
      __log('Problème Post Liste Troupe - Poids troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($tempsFormation)) {
      __log('Problème Post Liste Troupe - Temps formation troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($capaciteTransport)) {
      __log('Problème Post Liste Troupe - Capacite de transport troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($portee)) {
      __log('Problème Post Liste Troupe - Portée troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }
   if (empty($vitesse)) {
      __log('Problème Post Liste Troupe - Vitesse troupe manquant');
      return $resp->withStatus(400);   // Bad request
   }

   try {
      /** SECURITY CHECK - MANDATORY */
      $pdo = getPDO();
      if (!checkToken()) {
         return $resp->withStatus(401);   // Unauthorized
      }
      /** END OF SECURITY CHECK */

      $stmt = $pdo->prepare('INSERT INTO `listeTroupe` SET `nomTroupe` = :nomTroupe, `niveauDebloquage` = :niveauDebloquage, `poids` = :poids, `tempsFormation`= :tempsFormation, `capaciteTransport`=:capaciteTransport, `portee`=:portee, `vitesse`=:vitesse');
      $stmt->execute([
         'nomTroupe' => $nomTroupe,
         'niveauDebloquage' => $niveauDebloquage,
         'poids' => $poids,
         'tempsFormation' => $tempsFormation,
         'capaciteTransport' => $capaciteTransport,
         'portee' => $portee,
         'vitesse' => $vitesse,
      ]);

      $id = $pdo->lastInsertId();
      // Always return the new resource created like in a GET
      $ret = getResource($id);

      if (!$ret) {
         __log('Problème post liste troupe. Aucun enregistrement trouvé #' . $id);      // Impossible case, but...
         return $resp->withStatus(500);   // Not found, but treated as Internal Server Error
      }
      return buildResponse($resp, $ret);
   } catch (Exception $e) {
      __logException('Problème lors du post', $e);
      return $resp->withStatus(500);   // Internal Server Error
   }
});
/* -------------------------------------------------------------------------- */
