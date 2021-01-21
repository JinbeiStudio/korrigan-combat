<?php
	// header('Access-Control-Allow-Origin:*');

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	require __DIR__ . '/../slim/vendor/autoload.php';

	$app = AppFactory::create();

	require_once __DIR__ . '/../includes/api_1.0.php';

	// Fix "bug" (?) avec PUT vide (body non parsé)
	$app->addBodyParsingMiddleware();
	$app->run();
?>