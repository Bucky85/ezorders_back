<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 31-Mar-18
 * Time: 5:12 PM
 */

namespace App\database;
use MongoDB\Client;

class Db
{
    /** Use to connect to collection shop on database
     * @return \MongoDB\Collection
     */
    function db_connect()
    {
        $data = null;
        $m = new Client(); // connexion
        $collection = $m->ezorders->shop;
        return $collection;
    }

    /**
     * Use to search something on the database
     * @param $query
     * @param null $projection
     * @return null
     */
    function db_query($query, $projection = [])
    {
        $data = null;
        $collection = $this->db_connect();
        $cursor = $collection->find($query, $projection);
        //fetch data
        foreach ($cursor as $doc) {
            $data = $doc;
        }
        return $data;
    }

    /**
     * Use to search something on the database with one result
     * @param $query
     * @param null $projection
     * @return null
     */
    function db_query_one($query, $projection = [])
    {
        $data = null;
        $collection = $this->db_connect();
        $cursor = $collection->findOne($query, $projection);
        //fetch data
        foreach ($cursor as $doc) {
            $data = $doc;
        }
        return $data;
    }
}