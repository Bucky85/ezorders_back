<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 03-Apr-18
 * Time: 6:02 PM
 */

namespace app\database;

use MongoDB;


class DbRoom extends Db
{

//-----------------------------TABLES----------------------------------------------------------------------//

    var $last_table_id_generated;

    /**
     * Use to create table in db
     * @param $data
     * @return bool
     */
    function db_create_table($data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $collection = $this->db_connect();
        $this->last_table_id_generated = (string)new MongoDB\BSON\ObjectId();
        $data = array('_id' => $this->last_table_id_generated) + $data;
        $filter = ['_id' => $id];
        $update = ['$push' => array("tables" => $data)];
        $collection->updateOne($filter, $update);
        return true;
    }

    /**
     * Use to get one or more tables
     * @param $id_table
     * @return array|null tables
     */
    function db_get_table($id_table)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($id_table == null) {
            $filter = ['_id' => $id];
            $projection = ['projection' => ['_id' => 0, 'tables' => 1]];
            return $this->db_query($filter, $projection);
        } else {
            $filter = ['_id' => $id, 'tables._id' => $id_table];
            $projection = ['projection' => ['_id' => 0, 'tables.$' => 1]];
            return array("table" => $this->db_query_one($filter, $projection)[0]);
        }
    }

    /**
     * Use to update table
     * @param $id_table
     * @param $data json
     * @return bool
     */
    function db_update_table($id_table, $data)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $collection = $this->db_connect();
        $data = array('_id' => $id_table) + $data;
        $filter = ['_id' => $id, 'tables._id' => $id_table];
        $update = ['$set' => array("tables.$" => $data)];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Use to delete table in mongodb
     * @param $id_table
     * @return bool
     */
    function db_delete_table($id_table)
    {
        $collection = $this->db_connect();
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id, 'tables._id' => $id_table];
        $update = ['$pull' => array("tables" => array("_id" => $id_table))];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //-----------------------------ORDERS----------------------------------------------------------------------//

    var $last_order_id_generated;

    /**
     * Use to create order in db
     * @param $data
     * @return bool
     */
    function db_create_order($data)
    {
        date_default_timezone_set('Europe/Paris');
        if ($this->product_in_order_exists($data['products'])
            and $this->menu_in_order_exists($data['menus'])
            and $this->table_in_order_exists($data['table'])) {
            $collection = $this->db_connect();
            $date_cre = date('Y-m-d H:i:s');
            $stat = new DbStat();
            $this->last_order_id_generated = (string)new MongoDB\BSON\ObjectId();
            $data = array('_id' => $this->last_order_id_generated,
                    'createdAt' => $date_cre,
                    'num' => $stat->raise_count_orders()) + $data;
            $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
            $filter = ['_id' => $id];
            $update = ['$push' => array("orders" => $data)];
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
    function product_in_order_exists($products)
    {
        $db = new DbKitchen();
        for ($i = 0; $i < count($products); $i++) {
            $id_product = $products[$i]["_id"];
            $product = $db->db_get_product($id_product)['product'];
            if ($product == null OR $product['enabled'] == false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Use to know if product in parameter exist in db
     * @param $menus
     * @return bool
     */
    function menu_in_order_exists($menus)
    {
        $db = new DbKitchen();
        for ($i = 0; $i < count($menus); $i++) {
            $id_menu = $menus[$i]["_id"];
            $menu = $db->db_get_menu($id_menu)['menu'];
            if ($menu == null OR $menu['enabled'] == false) {
                return false;
            }
        }
        return true;
    }

    /**
     * Use to know if table in parameter exist in db
     * @param $table
     * @return bool
     */
    function table_in_order_exists($table)
    {
        $table = $this->db_get_table($table)['table'];
        if ($table == null) {
                return false;
            }
        return true;
    }

    /**
     * Use to get one or more orders
     * @param $id_order
     * @return array|null ordes
     */
    function db_get_order($id_order)
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        if ($id_order == null) {
            $filter = ['_id' => $id];
            $projection = ['projection' => ['_id' => 0, 'orders' => 1]];
            return $this->db_query($filter, $projection);
        } else {
            $filter = ['_id' => $id, 'orders._id' => $id_order];
            $projection = ['projection' => ['_id' => 0, 'orders.$' => 1]];
            return array("order" => $this->db_query_one($filter, $projection)[0]);
        }
    }

    /**
     * Use to update order
     * @param $id_order
     * @param $data json
     * @return bool
     */
    function db_update_order($id_order, $data)
    {
        if ($this->product_in_order_exists($data['products']) and $this->menu_in_order_exists($data['menus'])) {
            $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
            $collection = $this->db_connect();
            $filter = ['_id' => $id, 'orders._id' => $id_order];
            $old_order = $this->db_get_order($id_order)['order'];
            $data = array('_id' => $id_order) +
                array('createdAt' => $old_order["createdAt"]) +
                array('num' => $old_order["num"]) +
                $data;
            $update = ['$set' => array("orders.$" => $data)];
            $update_query = $collection->updateOne($filter, $update);
            if ($update_query->getModifiedCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Use to delete order in mongodb
     * @param $id_order
     * @return bool
     */
    function db_delete_order($id_order)
    {
        $collection = $this->db_connect();
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id, 'orders._id' => $id_order];
        $update = ['$pull' => array("orders" => array("_id" => $id_order))];
        $update_query = $collection->updateOne($filter, $update);
        if ($update_query->getModifiedCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}