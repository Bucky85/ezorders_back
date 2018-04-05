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
    //-----------------------------PRODUCTS----------------------------------------------------------------------//
    /**
     * Function called by the route %server%/products (POST)
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
     * Function called by the route %server%/products/{id}(optionnal) (GET)
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
            $product = $db->db_get_product($id_product);
            if ($product['product'] != null OR $product['products'] != null) {
                return $this->response($response, 200, $product);
            } else {
                return $this->response($response, 404, array('message' => 'resource not found'));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    /**
     * Function called by the route %server%/products/{id} (PUT)
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
                    return $this->response($response, 400, array("message" => "bad id or data"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }


    /**
     * Function called by the route %server%/products/{id} (DELETE)
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

    //-----------------------------MENUS----------------------------------------------------------------------//

    /**
     * Function called by the route %server%/menus (POST)
     * Use to create menu
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function create_menu(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\menu.json'))) {
            if ($this->check_auth()) {
                $db = new DbKitchen();
                $data = $request->getParsedBody();
                if ($db->db_create_menu($data)) {
                    return $this->response($response, 201, $db->db_get_menu($db->last_menu_id_generated));
                } else {
                    return $this->response($response, 400, array("message" => "data sent incorrect"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }

    /**
     * Function called by the route %server%/menus/{id}(optionnal) (GET)
     * Use to get product / products
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function get_menu(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_menu = $request->getAttribute('id');
            $db = new DbKitchen();
            $menu = $db->db_get_menu($id_menu);
            if ($menu['menu'] != null OR $menu['menus'] != null) {
                return $this->response($response, 200, $menu);
            } else {
                return $this->response($response, 404, array('message' => 'resource not found'));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    /**
     * Function called by the route %server%/menus/{id} (PUT)
     * Use to update menu
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function update_menu(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(),
            realpath(__DIR__ . '\schemas\menu.json'))) {
            if ($this->check_auth()) {
                $data = $request->getParsedBody();
                $id_menu = $request->getAttribute('id');
                $db = new DbKitchen();
                if ($db->db_update_menu($id_menu, $data)) {
                    return $this->response($response, 200, $db->db_get_menu($id_menu));
                } else {
                    return $this->response($response, 400, array("message" => "bad id or data"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }

    /**
     * Function called by the route %server%/menus/{id} (DELETE)
     * Use to delete product
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function delete_menu(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_menu = $request->getAttribute('id');
            $db = new DbKitchen();
            if ($db->db_delete_menu($id_menu)) {
                return $this->response($response, 200, array('message' => 'product deleted'));
            } else {
                return $this->response($response, 200, array("message" => "no data to deleted"));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }
}