<?php

declare(strict_types=1);

namespace App;

use DateInterval;
use DateTime;

class General
{
    public static function isLocal() : bool
    {
      if (!empty($_SERVER) && ($_SERVER["SERVER_NAME"] == "localhost" || $_SERVER["SERVER_ADDR"] == "127.0.0.1")) {
          return true;
      }

      return false;
    }

    public static function isCLI() : bool
    {
        return (php_sapi_name() === 'cli');
    }


    public static function isDev() : bool
    {
        //todo: implement this
        return false;
    }

    public static function setSessionSetting(string $key, string $value) : void
    {
        $_SESSION[$key] = $value;
    }

    public static function getSessionSetting(string $key) : ?string
    {
        return $_SESSION[$key] ?? null;
    }

    public static function clearSessionSetting(string $key) : void
    {
        unset($_SESSION[$key]);
    }

    public static function setRateLimit(string $route, int $numSeconds) : void
    {
        $limit = new DateTime('now', new \DateTimeZone('UTC'))
        ->add(new DateInterval('PT' . $numSeconds . 'S'));

        self::setSessionSetting("rl_$route", $limit->format('Y-m-d H:i:s'));
    }

    public static function isRateLimited(string $route) : bool
    {
        $l = self::getSessionSetting("rl_$route");
        if (is_null($l)) {
            return false;
        }

        $limit = new DateTime($l, new \DateTimeZone('UTC'));
        $now = new DateTime('now', new \DateTimeZone('UTC'));

        return $now <= $limit;
    }

    public static function verifyCSRF(?string $token) : bool
    {
      if (self::isDev() || self::isLocal()) {
          return $token === "my_super_secret_key" || $token === $_SESSION["csrf_token"];
      }

      return $_SESSION['csrf_token'] === $token;
    }

}
