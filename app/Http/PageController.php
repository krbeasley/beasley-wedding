<?php

namespace App\Http;

use App\Http\Controller;
use Symfony\Component\HttpFoundation\Response;

class PageController extends Controller
{
    private function __construct() {
        parent::__construct();
    }
    public static function index() : Response
    {
        $c = new PageController();
        return new Response($c->twig()->render("pages/index.html.twig"), 200);
    }
}