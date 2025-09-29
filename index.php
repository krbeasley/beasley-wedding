<?php

/** www.beasley.wedding
 *
 * The home page of Whitney and I's wedding. This is a landing page to direct people to other parts of the site.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "vendor/autoload.php";

use Dotenv\Dotenv;
use Cheechstack\Routing\Router;
use Cheechstack\Routing\Route;
use App\Http\PageController;
use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$routes = [
    new Route('/', "GET", [PageController::class, "index"]),
];

$router = new Router();
$router->add($routes);

$response = $router->handle($request);
$response->send();
