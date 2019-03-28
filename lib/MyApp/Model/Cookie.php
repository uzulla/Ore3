<?php

namespace MyApp\Model;

class Cookie
{
    public $key = "";
    public $value = "";
    public $expire_at = 0;
    public $path = "/";
    public $domain = "";
    public $secure = true;
    public $httponly = true;
}