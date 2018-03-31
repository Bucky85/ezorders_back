<?php
/**
 * User: czimmer
 */

require '../vendor/autoload.php';

//Slim settings
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

//Home controller
$app->get('/', \app\controllers\HomeController::class . ':home');

$app->options('/[{path:.*}]', function ($request, $response, $path = null) {
    return $response->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

//Auth controller
$app->put('/auth/signin', \app\controllers\AuthController::class . ':auth_signin');
$app->post('/auth', \app\controllers\AuthController::class . ':auth');
$app->get('/auth/current', \app\controllers\AuthController::class . ':auth_current');

