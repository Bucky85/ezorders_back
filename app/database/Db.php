<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 28-Mar-18
 * Time: 11:02 AM
 */

namespace App\database;

use MongoDB\Client;


class db

{
    function getAuthStatus($login = null, $password = null, $response)
    {
        $m = new Client(); // connexion
        // select db
        $db = $m->ezorders;
        // select collection
        $collection = $db->shop;
        // Search in database if $login and $password are given
        $queryAuth = ['auth.login' => $login, 'auth.password' => $password];
        $projectionAuth = ['projection' => ['_id' => 1, 'auth' => 1]];
        $cursor = $collection->find($queryAuth, $projectionAuth);
        //fetch data
        foreach ($cursor as $doc) {
            $auth = $doc;
        }
        // if data not found
        if (empty($auth)) {
            return $response->withStatus(400);
        } else {
            echo "Login successfully";
            return $response->withStatus(200);
        }
    }
}
