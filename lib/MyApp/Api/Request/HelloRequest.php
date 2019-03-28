<?php
declare(strict_types=1);

namespace MyApp\Api\Request;

class HelloRequest extends Request implements RequestInterface
{
    use ValidateRegexTrait;

    public $name;

    public function __construct(array $list)
    {
        $el = [];

        $result = $this->validateWithRegex($el, $list, 'name', "/\A[a-zA-Z0-9]{1,32}\z/u", true);
        $el = $result->error_list;
        $this->name = $result->val;

        $this->_error_list = $el;
    }
}
