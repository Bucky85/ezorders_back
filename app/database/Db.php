<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 28-Mar-18
 * Time: 11:02 AM
 */

namespace app\database;

use app\utils\Utils;
use MongoDB\Client;


class Db
{
    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     * @param null $login -> Login send by front in http body
     * @param null $password -> Password send by front in http body
     * @param $response -> http response use to change status code
     * @return if login and password is ok : $_SESSION['id']
     *         if login and password is nok : return status code 400
     */
    function auth($login = null, $password = null, $response)
    {
        $m = new Client(); // connexion
        // select db
        $db = $m->ezorders;
        // select collection
        $collection = $db->shop;
        if (!empty($login) && !empty($password)) {
            // Search in database if $login and $password are given
            $passwordEncrypt = hash('SHA512', $password);
            $queryAuth = ['auth.login' => $login, 'auth.password' => $passwordEncrypt];
            $projectionAuth = ['projection' => ['_id' => 1]];
            $cursor = $collection->find($queryAuth, $projectionAuth);
            //fetch data
            foreach ($cursor as $doc) {
                $id = $doc;
            }
            // if data not found
            if (empty($id)) {
                return $response->withStatus(400);
            } else {
                $u = new Utils();
                $u->create_session($id);
                $response->write($_SESSION['id']);
                return $response->withStatus(200);
            }
        } else {
            //If login or password is not given
            return $response->withStatus(400);
        }
    }
}
