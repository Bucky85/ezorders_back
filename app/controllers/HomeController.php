<?php

namespace app\controllers;


use Slim\Http\Request;
use Slim\Http\Response;

class HomeController
{
    public function home(Request $request, Response $response)
    {
        return $response->withStatus(302)->withRedirect("http://" . $_SERVER['SERVER_NAME']);
    }
}