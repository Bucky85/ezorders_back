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

//Auth controller
$app->post('/auth', \app\controllers\AuthController::class . ':auth');
$app->get('/auth/current', \app\controllers\AuthController::class . ':auth_current');

