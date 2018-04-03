<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 30-Mar-18
 * Time: 8:42 PM
 */

namespace app\controllers;

use app\database\DbAuth;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthController extends Controller
{
    /**
     * Function called by the route %server%/auth/register
     * Use to create an account
     */
    function register(Request $request, Response $response)
    {

        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\register.json'))) {
            $db = new DbAuth();
            $data = $request->getParsedBody();
            if ($db->db_register($data)) {
                return $this->response($response, 201, array("message" => "user successfully created"));
            } else {
                return $this->response($response, 400, array("message" => "the login already exists"));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }

    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     * @Return id of user or nothing
     */
    function auth(Request $request, Response $response)
    {
        $db = new DbAuth();
        if ($db->db_auth($request->getParam('login'), $request->getParam('password'))) {
            return $this->response($response, 200, array('message' => 'user successfully connect'));
        } else {
            return $this->response($response, 400, array('message' => 'bad login or password'));
        }
    }

    /**
     * Function called by the route %server%/auth/current
     * Use to know if user is authentified
     * send result function check auth
     */
    function auth_current(Request $request, Response $response)
    {
        return $this->response($response, 200, array("authentified" => $this->check_auth()));
    }

    /**
     * Use to know info of user logged
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    function auth_info(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $db = new DbAuth();
            return $this->response($response, 200, $db->db_auth_info());
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }

    }

    /**
     * Function called by the route %server%/auth/logout
     * Use to disconnect a user on a user
     * @param Request $request
     * @param Response $response
     * @return response
     */
    function logout(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            session_destroy();
            return $this->response($response, 200, array('message' => 'user disconnected'));
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }
}