<?php

namespace app\controllers;


use app\utils\Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController
{
    public function home(Request $request, Response $response)
    {
        return Utils::update_response($response, 200)->withRedirect("http://" . $_SERVER['SERVER_NAME']);
    }
}