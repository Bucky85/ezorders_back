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
    var $last_product_id_generated;

    function db_create_product($data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id,
            'products.name' => $data["name"]];
        $projection = ['projection' => ['products.name' => 1]];
        if (!empty($this->db_query($filter, $projection))) {
            return false;
        } else {
            $collection = $this->db_connect();
            $this->last_product_id_generated = (string)new MongoDB\BSON\ObjectId();
            $data = array('_id' => $this->last_product_id_generated) + $data;
            $filter = ['_id' => $id];
            $update = ['$push' => array("products" => $data)];
            $collection->updateOne($filter, $update);
            return true;
        }
    }

    /**
     * Use to one or more products
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
            return array("product" => $this->db_query_one($filter, $projection));
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
        $collection = $this->db_connect();
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $data = array('_id' => $id_product) + $data;
        $filter = ['_id' => $id, 'products._id' => $id_product];
        $update = ['$set' => array("products.$" => $data)];
        $update_query = $collection->updateOne($filter, $update);
        $_SESSION['last_product_update'] = $data;
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
}