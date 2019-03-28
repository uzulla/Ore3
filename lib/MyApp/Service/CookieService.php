<?php

namespace MyApp\Service;

use MyApp\Model\Cookie;

class CookieService
{
    public static function setCookie(Cookie $cookie): void
    {
        // write cookie
        $result = setcookie(
            $cookie->key,
            $cookie->value,
            $cookie->expire_at,
            $cookie->path,
            $cookie->domain,
            $cookie->secure,
            $cookie->httponly
        );
        if ($result === false) {
            throw new \RuntimeException("cookie sending fail.");
        }
    }
}
