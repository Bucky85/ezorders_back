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
}