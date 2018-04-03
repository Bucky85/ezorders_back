<?php
/**
 * Created by PhpStorm.
 * User: czimmer
 * Date: 03-Apr-18
 * Time: 6:04 PM
 */

namespace app\controllers;

class Controller
{
    /**
     * Use to write http response with parameters
     * @param $response
     * @param $httpStatus
     * @param null $data
     * @return $response updated
     */
    function controller_response($response, $httpStatus, $data = null)
    {
        return $response
            ->withHeader('Content-type', 'application/json')
            ->withStatus($httpStatus)
            ->write(json_encode($data));
    }

    /**
     * Use to know if user is logged
     * @return bool
     */
    function check_auth()
    {
        session_start();
        return !(empty($_SESSION['id']));
    }
}