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
        $m = new Client(); // connexion
        $collection = $m->ezorders->shop;
        return $collection;
    }

}