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

//Adapt header route
$app->add(function ($request, $response, $next) {
    if ($request->getMethod() !== "OPTIONS") {
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Credentials: true');
    }
    $response = $next($request, $response);
    return $response;
});

$app->options('/[{path:.*}]', function ($request, $response, $path = null) {
    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->withHeader('Access-Control-Allow-Credentials', 'true');
});

//HOME CONTROLLER
$app->get('/', \app\controllers\HomeController::class . ':home');

//AUTH CONTROLLERS
$app->post('/register', \app\controllers\AuthController::class . ':register');
$app->delete('/logout', \app\controllers\AuthController::class . ':logout');
$app->post('/auth', \app\controllers\AuthController::class . ':auth');

$app->get('/auth/current', \app\controllers\AuthController::class . ':auth_current');
$app->get('/auth/info', \app\controllers\AuthController::class . ':auth_info');

//KITCHEN CONTROLLERS
//PRODUCT
$app->post('/customer/kitchen/product', \app\controllers\KitchenController::class . ':create_product');
$app->get('/customer/kitchen/product', \app\controllers\KitchenController::class . ':get_product');
$app->get('/customer/kitchen/product/{id}', \app\controllers\KitchenController::class . ':get_product');
$app->put('/customer/kitchen/product/{id}', \app\controllers\KitchenController::class . ':update_product');
$app->delete('/customer/kitchen/product/{id}', \app\controllers\KitchenController::class . ':delete_product');
//MENUS

