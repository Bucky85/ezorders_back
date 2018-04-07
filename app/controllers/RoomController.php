<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 03-Apr-18
 * Time: 6:29 PM
 */

namespace app\controllers;

use app\database\DbRoom;
use Slim\Http\Request;
use Slim\Http\Response;

class RoomController extends Controller
{
    //-----------------------------TABLES----------------------------------------------------------------------//
    /**
     * Function called by the route %server%/tables (POST)
     * Use to create table
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function create_table(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(), realpath(__DIR__ . '/schemas/table.json'))) {
            if ($this->check_auth()) {
                $db = new DbRoom();
                $data = $request->getParsedBody();
                if ($db->db_create_table($data)) {
                    return $this->response($response, 201, $db->db_get_table($db->last_table_id_generated));
                } else {
                    return $this->response($response, 400, array("message" => "bad data sent"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }

    /**
     * Function called by the route %server%/tables/{id}(optionnal) (GET)
     * Use to get table / tables
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function get_table(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_table = $request->getAttribute('id');
            $db = new DbRoom();
            $table = $db->db_get_table($id_table);
            if ($table['table'] != null OR $table['tables'] != null) {
                return $this->response($response, 200, $table);
            } else {
                return $this->response($response, 404, array('message' => 'resource not found'));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    /**
     * Function called by the route %server%/tables/{id} (PUT)
     * Use to update table
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function update_table(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(), realpath(__DIR__ . '/schemas/table.json'))) {
            if ($this->check_auth()) {
                $data = $request->getParsedBody();
                $id_table = $request->getAttribute('id');
                $db = new DbRoom();
                if ($db->db_update_table($id_table, $data)) {
                    return $this->response($response, 200, $db->db_get_table($id_table));
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
     * Function called by the route %server%/tables/{id} (DELETE)
     * Use to delete table
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function delete_table(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_table = $request->getAttribute('id');
            $db = new DbRoom();
            if ($db->db_delete_table($id_table)) {
                return $this->response($response, 200, array('message' => 'table deleted'));
            } else {
                return $this->response($response, 200, array("message" => "no data to deleted"));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    //-----------------------------ORDERS----------------------------------------------------------------------//

    /**
     * Function called by the route %server%/orders (POST)
     * Use to create table
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function create_order(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(), realpath(__DIR__ . '/schemas/order.json'))) {
            if ($this->check_auth()) {
                $db = new DbRoom();
                $data = $request->getParsedBody();
                if ($db->db_create_order($data)) {
                    return $this->response($response, 201, $db->db_get_order($db->last_order_id_generated));
                } else {
                    return $this->response($response, 400, array("message" => "bad data sent"));
                }
            } else {
                return $this->response($response, 401, array('message' => 'user not logged'));
            }
        } else {
            return $this->response($response, 400, array("message" => "JSON format not valid"));
        }
    }

    /**
     * Function called by the route %server%/orders/{id}(optionnal) (GET)
     * Use to get table / tables
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function get_order(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_order = $request->getAttribute('id');
            $db = new DbRoom();
            $order = $db->db_get_order($id_order);
            if ($order['order'] != null OR $order['orders'] != null) {
                return $this->response($response, 200, $order);
            } else {
                return $this->response($response, 404, array('message' => 'resource not found'));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }

    /**
     * Function called by the route %server%/orders/{id} (PUT)
     * Use to update table
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function update_order(Request $request, Response $response)
    {
        if ($this->check_json($request->getBody(), realpath(__DIR__ . '/schemas/order.json'))) {
            if ($this->check_auth()) {
                $data = $request->getParsedBody();
                $id_order = $request->getAttribute('id');
                $db = new DbRoom();
                if ($db->db_update_order($id_order, $data)) {
                    return $this->response($response, 200, $db->db_get_order($id_order));
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
     * Function called by the route %server%/orders/{id} (DELETE)
     * Use to delete order
     * @param Request $request
     * @param Response $response
     * @return new response
     */
    function delete_order(Request $request, Response $response)
    {
        if ($this->check_auth()) {
            $id_order = $request->getAttribute('id');
            $db = new DbRoom();
            if ($db->db_delete_order($id_order)) {
                return $this->response($response, 200, array('message' => 'order deleted'));
            } else {
                return $this->response($response, 200, array("message" => "no data to deleted"));
            }
        } else {
            return $this->response($response, 401, array('message' => 'user not logged'));
        }
    }
}