<?php

namespace app\controllers;

use app\database\db;
use Slim\Http\Request;
use Slim\Http\Response;

class PagesController
{
    public function home(Request $request, Response $response)
    {
    }

    public function login(Request $request, Response $response)
    {
        // Get param in body
        $login = $request->getParam('login');
        $password = $request->getParam('password');

        $db = new Db();
        return $db->auth($login, $password, $response);
    }
}