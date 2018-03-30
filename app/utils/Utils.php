<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 29-Mar-18
 * Time: 2:51 PM
 */

namespace app\utils;


class Utils
{
    /**
     * Create $_SESSION['id'] -> id of shop
     * @param $data
     */
    function create_session($id)
    {
        if (!empty($_SESSION['id'])) {
            session_unset();
            session_destroy();
        }
        session_start();
        $_SESSION['id'] = $id['_id'];
    }

    /**
     * Check if user authentified
     * @return bool -> True if authentified, false is not
     */
    function check_auth()
    {
        session_start();
        return (!empty($_SESSION['id']));
    }
}