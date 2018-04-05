<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 03-Apr-18
 * Time: 7:38 PM
 */

namespace app\database;

use MongoDB;

class DbKitchen extends Db
{
    //-----------------------------PRODUCTS----------------------------------------------------------------------//

    var $last_product_id_generated;

    /**
     * Use to create product in db
     * @param $data
     * @return bool
     */
    function db_create_product($data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $collection = $this->db_connect();
        $this->last_product_id_generated = (string)new MongoDB\BSON\ObjectId();
        $data = array('_id' => $this->last_product_id_generated) + $data;
        $filter = ['_id' => $id];
        $update = ['$push' => array("products" => $data)];
        $collection->updateOne($filter, $update);
        return true;
    }

    /**
     * Use to get one or more products
     * @param $id_product
     * @return array|null products
     */
    function db_get_product($id_product)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($id_product == null) {
            $filter = ['_id' => $id];
            $projection = ['projection' => ['_id' => 0, 'products' => 1]];
            return $this->db_query($filter, $projection);
        } else {
            $filter = ['_id' => $id, 'products._id' => $id_product];
            $projection = ['projection' => ['_id' => 0, 'products.$' => 1]];
            return array("product" => $this->db_query_one($filter, $projection)[0]);
        }
    }

    /**
     * Use to update product
     * @param $id_product
     * @param $data json
     * @return bool
     */
    function db_update_product($id_product, $data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $collection = $this->db_connect();
        $data = array('_id' => $id_product) + $data;
        $filter = ['_id' => $id, 'products._id' => $id_product];
        $update = ['$set' => array("products.$" => $data)];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Use to delete product in mongodb
     * @param $id_product
     * @return bool
     */
    function db_delete_product($id_product)
    {
        $collection = $this->db_connect();
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id, 'products._id' => $id_product];
        $update = ['$pull' => array("products" => array("_id" => $id_product))];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //-----------------------------MENUS----------------------------------------------------------------------//

    var $last_menu_id_generated;

    /**
     * Use to create menu in db
     * @param $data
     * @return bool
     */
    function db_create_menu($data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($this->product_exists($data['products'])) {
            $collection = $this->db_connect();
            $this->last_menu_id_generated = (string)new MongoDB\BSON\ObjectId();
            $data = array('_id' => $this->last_menu_id_generated) + $data;
            $filter = ['_id' => $id];
            $update = ['$push' => array("menus" => $data)];
            $collection->updateOne($filter, $update);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Use to know if product in parameter exist in db
     * @param $products
     * @return bool
     */
    function product_exists($products)
    {
        foreach ($products as $id_product) {
            $product = $this->db_get_product($id_product)['product'];
            if ($product == null OR $product['enabled'] == false) {
                return false;
            }
        }
        return true;
    }


    /**
     * Use to get one or more menu
     * @param $id_menu
     * @return array|null menus
     */
    function db_get_menu($id_menu)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($id_menu == null) {
            $filter = ['_id' => $id];
            $projection = ['projection' => ['_id' => 0, 'menus' => 1]];
            return $this->db_query($filter, $projection);
        } else {
            $filter = ['_id' => $id, 'menus._id' => $id_menu];
            $projection = ['projection' => ['_id' => 0, 'menus.$' => 1]];
            return array("menu" => $this->db_query_one($filter, $projection)[0]);
        }
    }

    /**
     * Use to update menu
     * @param $id_menu
     * @param $data json
     * @return bool
     */
    function db_update_menu($id_menu, $data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($this->product_exists($data['products'])) {
            $collection = $this->db_connect();
            $data = array('_id' => $id_menu) + $data;
            $filter = ['_id' => $id, 'menus._id' => $id_menu];
            $update = ['$set' => array("menus.$" => $data)];
            $update_query = $collection->updateOne($filter, $update);
            if ($update_query->getModifiedCount() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Use to delete menu in mongodb
     * @param $id_menu
     * @return bool
     */
    function db_delete_menu($id_menu)
    {
        $collection = $this->db_connect();
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id, 'menus._id' => $id_menu];
        $update = ['$pull' => array("menus" => array("_id" => $id_menu))];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}