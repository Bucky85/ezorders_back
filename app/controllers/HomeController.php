<?php

namespace app\controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends Controller
{
    public function home(Request $request, Response $response)
    {
        return $this->controller_response($response, 200)
            ->withRedirect("http://" . $_SERVER['SERVER_NAME']);
    }
}