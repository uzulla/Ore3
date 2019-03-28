<?php
declare(strict_types=1);

namespace MyApp\Service;

use MyApp\Model\NameSan;

class NameSanService
{
    public static function getByName($name): NameSan
    {
        return new NameSan($name);
    }
}
