<?php

namespace app\controllers;

use app\database\Db;
use Slim\Http\Request;
use Slim\Http\Response;

class PagesController
{
    public function home(Request $request, Response $response)
    {
        return $response->withStatus(302)->withRedirect("http://" . $_SERVER['SERVER_NAME']);
    }

    public function auth(Request $request, Response $response)
    {
        // Get param in body
        $login = $request->getParam('login');
        $password = $request->getParam('password');

        $db = new Db();
        return $db->auth($login, $password, $response);
    }
}