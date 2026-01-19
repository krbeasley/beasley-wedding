<?php

namespace App\Http\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    public static function index() : Response
    {
        $c = new PageController();
        return new Response($c->twig()->render("pages/index.html.twig"), 200);
    }

    public static function venue() : Response
    {
        $c = new PageController();
        return new Response($c->twig()->render("pages/venue.html.twig"), 200);
    }

    public static function error(Request $request, mixed $params) : Response
    {
        $c = new PageController();
        $code = $params['code'];
    }
}
