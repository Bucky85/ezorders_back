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
        $login = $request->getAttribute('login');
        $password = $request->getAttribute('password');

        $db = new Db();
        return $db->getAuthStatus($login, $password, $response);
    }
}