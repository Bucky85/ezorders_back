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
$app->post('/customer/kitchen/products', \app\controllers\KitchenController::class . ':create_product');
$app->get('/customer/kitchen/products', \app\controllers\KitchenController::class . ':get_product');
$app->get('/customer/kitchen/products/{id}', \app\controllers\KitchenController::class . ':get_product');
$app->put('/customer/kitchen/products/{id}', \app\controllers\KitchenController::class . ':update_product');
$app->delete('/customer/kitchen/products/{id}', \app\controllers\KitchenController::class . ':delete_product');
//MENUS
$app->post('/customer/kitchen/menus', \app\controllers\KitchenController::class . ':create_menu');
$app->get('/customer/kitchen/menus', \app\controllers\KitchenController::class . ':get_menu');
$app->get('/customer/kitchen/menus/{id}', \app\controllers\KitchenController::class . ':get_menu');
$app->put('/customer/kitchen/menus/{id}', \app\controllers\KitchenController::class . ':update_menu');
$app->delete('/customer/kitchen/menus/{id}', \app\controllers\KitchenController::class . ':delete_menu');

//ROOM CONTROLLERS
//TABLES
$app->post('/customer/room/tables', \app\controllers\RoomController::class . ':create_table');
$app->get('/customer/room/tables', \app\controllers\RoomController::class . ':get_table');
$app->get('/customer/room/tables/{id}', \app\controllers\RoomController::class . ':get_table');
$app->put('/customer/room/tables/{id}', \app\controllers\RoomController::class . ':update_table');
$app->delete('/customer/room/tables/{id}', \app\controllers\RoomController::class . ':delete_table');
//ORDERS
$app->post('/customer/room/orders', \app\controllers\RoomController::class . ':create_order');
$app->get('/customer/room/orders', \app\controllers\RoomController::class . ':get_order');
$app->get('/customer/room/orders/{id}', \app\controllers\RoomController::class . ':get_order');
$app->put('/customer/room/orders/{id}', \app\controllers\RoomController::class . ':update_order');
$app->delete('/customer/room/orders/{id}', \app\controllers\RoomController::class . ':delete_order');




