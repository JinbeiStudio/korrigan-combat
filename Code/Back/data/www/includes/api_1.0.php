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

$app->get('/', function ($req, $resp) {
	return buildResponse($resp, 'Welcome to Korrigans API!');
});
