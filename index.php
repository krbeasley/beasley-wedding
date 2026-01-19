<?php

/** www.beasley.wedding
 *
 * The home page of Whitney and I's wedding. I love you beb <3 
 */

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "vendor/autoload.php";

use App\Http\Controller\AdminController;
use App\Http\Controller\ApiController;
use App\Http\Controller\PageController;
use App\Http\Controller\RegistryController;
use App\Http\Controller\RsvpController;
use Cheechstack\Routing\Route;
use Cheechstack\Routing\Router;
use Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$request = Request::createFromGlobals();

$routes = [
    // Home Page
    new Route('/', "GET", [PageController::class, "index"]),

    // RSVP
    new Route('/rsvp', "GET", [RsvpController::class, "index"]),
    new Route("/rsvp/confirm", "GET", [RsvpController::class, "confirm"]),

    // The Venue
    new Route('/venue', "GET", [PageController::class, "venue"]),

    // The Registry
    new Route('/registry', "GET", [RegistryController::class, "viewRegistry"]),

    // Errors
    new Route('/error/:code', "GET", [PageController::class, "error"]),

    // Admin Routes
    new Route('/admin/login', "GET", [AdminController::class, "login"]),
    new Route('/admin/login', "POST", [AdminController::class, "validateLogin"]),
    new Route('/admin/logout', "POST", [AdminController::class, "logout"]),
    new Route('/admin/dashboard', "GET", [AdminController::class, "dashboard"]),

    // API Routes
    new Route('/api/search-guest-list', "GET", [ApiController::class, "searchGuestList"]), // find some similar name on the guest list
    new Route('/api/get-rsvp-requests', "GET", [ApiController::class, "getRsvpRequests"]), // get all the rsvps from the database
];

$router = new Router();
$router->add($routes);

$response = $router->handle($request);
$response->send();
