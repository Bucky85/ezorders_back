<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 28-Mar-18
 * Time: 11:02 AM
 */

namespace app\database;

use app\utils\Utils;
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
        //ENCRYPT THE PASSWORD
        $data["auth"]["password"] = hash('SHA512', $data["auth"]["password"]);
        //GET LOGIN SEND
        $query = ['auth.login' => $data["auth"]["login"]];
        $projection = ['projection' => ['login' => 1]];
        //IF LOGIN ALREADY EXIST
        if (empty($this->db_query($query, $projection))) {
            $collection->insertOne($data);
            return true;
        }
        return false;
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
        if (!empty($login) && !empty($password)) {
            // SEARCH IN DATABASE IF $PASSWORD AND $LOGIN ARE GIVEN
            $passwordEncrypt = hash('SHA512', $password);
            $query = ['auth.login' => $login, 'auth.password' => $passwordEncrypt];
            $projection = ['projection' => ['oid' => 1]];
            $id = $this->db_query($query, $projection);
            // IF DATA NOT FOUND RETURN FALSE
            if (empty($id)) {
                return false;
                //IF ID FOUND SEND BACK
            } else {
                //CREATE ID SESSION
                if (Utils::check_auth()) {
                    session_destroy();
                }
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

    function db_auth_info()
    {
        $query = ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id'])];
        $projection = ['projection' => ['_id' => 0, 'auth.login' => 1, 'info' => 1]];
        $data = $this->db_query($query, $projection);

        return $data;
    }
}
