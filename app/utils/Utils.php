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
     * Use to write http response with parameters
     * @param $response
     * @param $httpStatus
     * @param null $data
     * @return $response updated
     */
    static function update_response($response, $httpStatus, $data = null)
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus($httpStatus)
            ->write(json_encode($data));
    }

    /**
     * Use to know if user is log
     * @return bool
     */
    static function check_auth()
    {
        return !(empty($_SESSION['id']));
    }
}