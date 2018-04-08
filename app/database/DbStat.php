<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 08-Apr-18
 * Time: 7:15 PM
 */

namespace app\database;

use MongoDB;

class DbStat extends db
{
    function get_count_orders()
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $filter = ['_id' => $id];
        $projection = ['projection' => ['_id' => 0, 'stats.nbOrders' => 1]];
        $stats = $this->db_query_one($filter, $projection);
        if (empty($stats)) {
            $collection = $this->db_connect();
            $update = ['$set' => array('stats' => array('nbOrders' => 0))];
            $collection->updateOne($filter, $update);
        } else {
            return $stats['nbOrders'];
        }
    }

    function raise_count_orders()
    {
        $id = new MongoDB\BSON\ObjectId($_SESSION['id']);
        $collection = $this->db_connect();
        $filter = ['_id' => $id];
        $update = ['$set' => array('stats' => array('nbOrders' => $this->get_count_orders() + 1))];
        $collection->updateOne($filter, $update);
        return $this->get_count_orders();
    }
}