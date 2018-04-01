<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 30-Mar-18
 * Time: 8:42 PM
 */

namespace app\controllers;

use app\database\DbAuth;
use app\utils\Utils;
use JsonSchema;
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
        //CHECK JSON FORMAT
        $data = json_decode($request->getBody());
        $schema = realpath(__DIR__ . '/schemas/signin.json');
        $validator = new JsonSchema\Validator;
        $validator->validate($data, (object)['$ref' => $schema]);
        //IF FORMAT IS OK SET RESPONSE
        if ($validator->isValid()) {
            $db = new DbAuth();
            $data = $request->getParsedBody();
            if ($db->db_signin($data)) {
                //INSERT A USER / SHOP IN DB
                $httpStatus = 201;
                $data = array(
                    "inserted" => "true",
                    "message" => "user successfully created");
            } else {
                //GENERATE ERROR FOR A LOGIN ALREADY IN DB
                $httpStatus = 400;
                $data = array(
                    "inserted" => "false",
                    "message" => "the login already exists");
            }
        } else {
            //GENERATE ERROR FOR EACH FIELD NOT SEND
            $i = 0;
            $errors = null;
            foreach ($validator->getErrors() as $error) {
                $i++;
                //$data['message' . $i] = $error['message'];
                $errors[$i] = $error['message'];
            }
            //GENERATE DATA FOR THE REQUEST
            $httpStatus = 400;
            $data = array(
                "inserted" => "false",
                "message" => $errors);
        }
        return Utils::update_response($response, $httpStatus, $data);
    }

    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     * @Return id of user or nothing
     */
    public function auth(Request $request, Response $response)
    {
        $db = new DbAuth();
        $id = $db->db_auth($request->getParam('login'), $request->getParam('password'));
        if (!empty($id)) {
            $httpStatus = 200;
            $data = array('id' => $id);
        } else {
            $httpStatus = 400;
            $data = null;
        }
        return Utils::update_response($response, $httpStatus, $data);
    }

    /**
     * Function called by the route %server%/auth/current/%id%
     * Use to know if user is authentified
     * send result function check auth
     */
    public function auth_current($request, $response)
    {
        session_start();
        $id = $request->getAttribute('id');
        return Utils::update_response($response, 200, array("authentified" => Utils::check_auth($id)));

    }

    /**
     * Function called by the route %server%/auth/signout/%id%
     * Use to disconnect a user on a user
     * @param Request $request
     * @param Response $response
     * @return response
     */
    public function auth_signout(Request $request, Response $response)
    {
        session_start();
        $id = $request->getAttribute('id');

        if (Utils::check_auth($id)) {
            unset($_SESSION[$id]);
            $httpStatus = 200;
            $data = array('message' => "user disconnected");
        } else {
            $httpStatus = 400;
            $data = array('message' => "bad id or user not log");
        }
        return Utils::update_response($response, $httpStatus, $data);
    }
}