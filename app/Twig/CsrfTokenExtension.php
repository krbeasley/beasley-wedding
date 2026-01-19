<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfTokenExtension extends AbstractExtension
{
    public function getFunctions() : array {
        return [
            new TwigFunction('csrf', [$this, 'getToken']),
        ];
    }

    // Generate a CSRF Token to prevent spam
    public function getToken() : string
    {
        // if there's no session started, start one?
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // create and set a token if there's not one already
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }
}
