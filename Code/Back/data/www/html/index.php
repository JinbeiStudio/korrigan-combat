<?php
	header('Access-Control-Allow-Origin:http://korrigans-team2.lpweb-lannion.fr:3001');
	header('Access-Control-Allow-Credentials:true');

	use Psr\Http\Message\ResponseInterface as Response;
	use Psr\Http\Message\ServerRequestInterface as Request;
	use Slim\Factory\AppFactory;
	require __DIR__ . '/../slim/vendor/autoload.php';

	$app = AppFactory::create();

	require_once __DIR__ . '/../includes/api_1.0.php';

	// Fix "bug" (?) avec PUT vide (body non parsé)
	$app->addBodyParsingMiddleware();
	/*
	$app->options('/{routes:.+}', function ($request, $response, $args) {
		return $response;
	});

	$app->add(function ($req, $res, $next) {
		$response = $next($req, $res);
		return $response
			->withHeader('Access-Control-Allow-Origin', 'http://korrigans-team2.lpweb-lannion.fr:3001')
			->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
			->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
	});
	$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function($req, $res) {
		$handler = $this->notFoundHandler; // handle using the default Slim page not found handler
		return $handler($req, $res);
	});
	 */
	$app->run();
?>
