<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 28-Mar-18
 * Time: 11:02 AM
 */

namespace app\database;

use MongoDB;


class DbAuth extends Db
{
    /**
     * Use to create an account
     * @return true if inserted false is not
     */
    function db_signin($data)
    {
        $collection = $this->db_connect();
        //encrypt the password
        $data["auth"]["password"] = hash('SHA512', $data["auth"]["password"]);
        //Get login send
        $login = $data["auth"]["login"];
        if ($this->check_login_in_db($collection, $login)) {
            $collection->insertOne($data);
            return true;
        }
        return false;
    }

    /**
     * Check if login is already in db
     * @collection collection in db
     * @login login checked
     * @return true if empty else false
     */
    function check_login_in_db($collection, $login)
    {
        //Check if login exist
        $queryAuth = ['auth.login' => $login];
        $projectionAuth = ['projection' => ['login' => 1]];
        $cursor = $collection->find($queryAuth, $projectionAuth);
        foreach ($cursor as $doc) {
            $loginInDb = $doc;
        }
        return empty($loginInDb);
    }

    /**
     * Function called by the route %server%/auth
     * Use to authenticate a user
     * @param null $login -> Login send by front in http body
     * @param null $password -> Password send by front in http body
     * @return true if ok or false is not
     */
    function db_auth($login = null, $password = null)
    {
        $collection = $this->db_connect();
        if (!empty($login) && !empty($password)) {
            // SEARCH IN DATABASE IF $PASSWORD AND $LOGIN ARE GIVEN
            $passwordEncrypt = hash('SHA512', $password);
            $queryAuth = ['auth.login' => $login, 'auth.password' => $passwordEncrypt];
            $projectionAuth = ['projection' => ['oid' => 1]];
            $cursor = $collection->find($queryAuth, $projectionAuth);
            //fetch data
            foreach ($cursor as $doc) {
                $id = $doc;
            }
            // IF DATA NOT FOUND RETURN FALSE
            if (empty($id)) {
                return false;
                //IF ID FOUND SEND BACK
            } else {
                if (!empty($_SESSION['id'])) {
                    session_unset();
                    session_destroy();
                }
                //CREATE ID SESSION
                session_start();
                //GET ID CORRESPOND TO LOGIN AND PASSWORD
                $id = (string)new MongoDB\BSON\ObjectId($id['_id']);
                $_SESSION['id'] = $id;
                return true;
            }
        } else {
            //IF LOGIN OR PASSWORD NOT GIVEN RETURN FALSE
            return false;
        }
    }
}
