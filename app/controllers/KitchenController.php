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
    /**
     * Function called by the route %server%/product (POST)
     * Use to create product
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function create_product(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\product.json'))) {
            if ($this->check_auth()) {
                $db = new DbKitchen();
                $data = $request->getParsedBody();
                if ($db->db_create_product($data)) {
                    return $this->response($response, 201, $db->db_get_product($db->last_product_id_generated));
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

    /**
     * Function called by the route %server%/product/{id}(optionnal) (GET)
     * Use to get product / products
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function get_product(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_product = $request->getAttribute('id');
            $db = new DbKitchen();
            return $this->response($response, 200, $db->db_get_product($id_product));
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    /**
     * Function called by the route %server%/product/{id} (PUT)
     * Use to update product
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function update_product(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\product.json'))) {
            if ($this->check_auth()) {
                $data = $request->getParsedBody();
                $id_product = $request->getAttribute('id');
                $db = new DbKitchen();
                if ($db->db_update_product($id_product, $data)) {
                    return $this->response($response, 200, $db->db_get_product($id_product));
                } else {
                    return $this->response($response, 200, array("message" => "no data to updated"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }


    /**
     * Function called by the route %server%/product/{id} (DELETE)
     * Use to delete product
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function delete_product(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_product = $request->getAttribute('id');
            $db = new DbKitchen();
            if ($db->db_delete_product($id_product)) {
                return $this->response($response, 200, array('message' => 'product deleted'));
            } else {
                return $this->response($response, 200, array("message" => "no data to deleted"));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }
}