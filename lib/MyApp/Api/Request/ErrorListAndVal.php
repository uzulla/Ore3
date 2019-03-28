<?php
declare(strict_types=1);

namespace MyApp\Api\Request;

class ErrorListAndVal
{
    public $error_list = [];
    public $val;

    public function __construct(array $error_list, $val)
    {
        $this->error_list = $error_list;
        $this->val = $val;
    }
}
