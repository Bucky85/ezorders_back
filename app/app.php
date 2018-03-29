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
$app->get('/', \app\controllers\PagesController::class . ':home');

//Login controller
$app->post('/auth', \app\controllers\PagesController::class . ':login');
