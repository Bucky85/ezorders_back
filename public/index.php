<?php
/**
 * User: czimmer
 */


require '../app/app.php';

header('Access-Control-Allow-Origin: *');

try {
    $app->run();
} catch (\Slim\Exception\MethodNotAllowedException $e) {
} catch (\Slim\Exception\NotFoundException $e) {
} catch (Exception $e) {
}
