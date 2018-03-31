<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 30-Mar-18
 * Time: 8:42 PM
 */

namespace App\controllers;

use app\database\DbAuth;
use app\utils\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController
{
    /**
     * Function called by the route %server%/auth/signin
     * Use to create an account
     */
    public function auth_signin(Request $request, Response $response)
    {
        $data = $request->getParsedBody();
        $db = new DbAuth();
        if ($db->db_signin($data)) {
            return $response
                ->withStatus(201)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(array(
                    "inserted" => "true",
                    "message" => "successfully inserted")));
        } else {
            return $response
                ->withStatus(400)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(array(
                    "inserted" => "false",
                    "message" => "login already exist")));
        }
    }

    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     */
    public function auth(Request $request, Response $response)
    {
        $db = new DbAuth();
        if ($db->db_auth($request->getParam('login'), $request->getParam('password')) == true) {
            return $response
                ->withStatus(200)
                ->withHeader('Content-type', 'application/json')
                ->write(json_encode(array('id' => $_SESSION['id'])));
        } else {
            return $response->withStatus(400);
        }
    }

    /**
     * Function called by the route %server%/auth/current
     * Use to know if user is authentified
     * send result function check auth
     */
    public function auth_current(Request $request, Response $response)
    {
        $u = new Utils();
        return $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode(array("authentified" => $u->check_auth())));
    }
}