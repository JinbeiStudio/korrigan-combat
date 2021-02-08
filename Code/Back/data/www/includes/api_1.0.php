<?php
/*
	api.php : VERSION
	js : version
	html : version, css?vN, js?vN
	*/
define('VERSION', '0.1.0');
$__token_ok = FALSE;                // SECURITY CHECK !!!!

require_once 'params.php';
require_once 'db.php';
require_once 'utils.php';
require_once '1.0/check.php';       // MANDATORY !!!!
require_once '1.0/connection.php';
//require_once '1.0/xxx.php';			// Template resource
require_once '1.0/liste-troupes.php';
require_once '1.0/troupes-joueur.php';
require_once '1.0/composition-deck.php';
require_once '1.0/joueur.php';
require_once '1.0/caserne.php';
require_once '1.0/stats-troupes.php';
require_once '1.0/adversaire.php';
require_once '1.0/formation-troupes.php';
require_once '1.0/combats.php';
require_once '1.0/paliers-joueur.php';
require_once '1.0/paliers-troupe.php';
require_once '1.0/combat-tour1.php';

$app->get('/', function ($req, $resp) {
	return buildResponse($resp, 'Welcome to Korrigans API!');
});
