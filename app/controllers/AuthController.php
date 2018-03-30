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
     * send result check auth
     */
    public function auth_current(Request $request, Response $response)
    {
        $u = new Utils();
        $data = array("authentified" => $u->check_auth());
        return $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/json')
            ->write(json_encode($data));
    }
}