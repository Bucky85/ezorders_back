<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 30-Mar-18
 * Time: 8:42 PM
 */

namespace App\controllers;

use app\database\Db;
use app\utils\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class AuthController
{

    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     */
    public function auth(Request $request, Response $response)
    {
        // Get param in body
        $login = $request->getParam('login');
        $password = $request->getParam('password');

        $db = new Db();
        return $db->auth($login, $password, $response);
    }

    /**
     * Function called by the route %server%/auth/current
     * Use to know if user is authentified
     * if true : return request 200 with id
     * if false : return request 400
     */
    public function auth_current(Request $request, Response $response)
    {
        $u = new Utils();
        if ($u->check_auth()) {
            $response->write($_SESSION['id']);
            return $response->withStatus(200);
        } else {
            return $response->withStatus(401);
        }
    }
}