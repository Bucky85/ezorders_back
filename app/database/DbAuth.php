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
    function db_register($data)
    {
        $collection = $this->db_connect();
        $data["auth"]["password"] = hash('SHA512', $data["auth"]["password"]);
        $filter = ['auth.login' => $data["auth"]["login"]];
        $projection = ['projection' => ['login' => 1]];
        if (empty($this->db_query($filter, $projection))) {
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
            $passwordEncrypt = hash('SHA512', $password);
            $filter = ['auth.login' => $login, 'auth.password' => $passwordEncrypt];
            $projection = ['projection' => ['oid' => 1]];
            $id = $this->db_query($filter, $projection);
            if (empty($id)) {
                return false;
            } else {
                session_start();
                $id = (string)new MongoDB\BSON\ObjectId($id['_id']);
                $_SESSION['id'] = $id;
                return true;
            }
        } else {
            return false;
        }
    }

    /** Function called by the route %server%/auth/info
     *  Use to know info of user logged
     * @return data info of user
     */
    function db_auth_info()
    {
        $filter = ['_id' => new MongoDB\BSON\ObjectId($_SESSION['id'])];
        $projection = ['projection' => ['_id' => 0, 'auth.login' => 1, 'info' => 1]];
        $data = $this->db_query($filter, $projection);
        return $data;
    }
}
