<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 03-Apr-18
 * Time: 7:11 PM
 */

namespace app\controllers;


use app\database\DbKitchen;
use Slim\Http\Request;
use Slim\Http\Response;

class KitchenController extends Controller
{
    function create_product(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\product.json'))) {
            if ($this->check_auth()) {
                $db = new DbKitchen();
                $data = $request->getParsedBody();
                if ($db->db_create_product($data)) {
                    return $this->response($response, 201, array("message" => "product successfully created"));
                } else {
                    return $this->response($response, 400, array("message" => "product already exists"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }
}