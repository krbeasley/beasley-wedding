<?php

namespace App\Http\Controller;

use App\General;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public static function login() : Response
    {
        // Check if the user is logged in
        if (General::getSessionSetting("user_id")) {
            return new RedirectResponse("/admin/dashboard");
        }

        // Copy any errors before clearing them
        $errors = General::getSessionSetting('form_error');
        General::clearSessionSetting("form_error");

        $c = new AdminController();
        return new Response($c->twig()->render("pages/admin/login.html.twig", [
            "errors" => $errors,
        ]), 200);
    }

    public static function validateLogin(Request $request) : RedirectResponse
    {
        $route = "/admin/login";
        if (General::isRateLimited($route)) {
            General::setSessionSetting("form_error", "rate limit exceeded");
            return new RedirectResponse("/admin/login");
        }
        // validate the honeypot and the csrf_token
        else if (!General::verifyCSRF($request)) {
            General::setSessionSetting("form_error", "bad token");
            return new RedirectResponse("/admin/login");
        }

        // set a rate limit
        General::setRateLimit($route, 10);

        // validate the username and password
        $email = $request->request->get("email");
        $password = $request->request->get("password");

        $sql = "SELECT ";

        // set the user_id session token

        return new RedirectResponse("/admin/dashboard");
    }

    public static function dashboard() : Response
    {
        // Make sure the user is logged in
//        if (!General::getSessionSetting("user_id")) {
//            return new RedirectResponse("/admin/login", Response::HTTP_UNAUTHORIZED);
//        }

        // you need to get the rsvp info for
        // the preview section and also the rsvp approve
        // deny flow for admin

        $c = new AdminController();
        return new Response($c->twig()->render("pages/admin/dashboard.html.twig", []));
    }

    public static function logout() : RedirectResponse
    {
        General::clearSessionSetting("user_id");
        return new RedirectResponse("/admin/login");
    }
}
