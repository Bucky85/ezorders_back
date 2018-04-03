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
            $data = array('_id' => (string)new MongoDB\BSON\ObjectId()) + $data;
            $filter = ['_id' => $id];
            $update = ['$push' => array("products" => $data)];
            $collection->updateOne($filter, $update);
            $_SESSION['last_product_created'] = $data;
            return true;
        }
    }

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
}