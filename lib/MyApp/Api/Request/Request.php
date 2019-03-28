<?php
declare(strict_types=1);

namespace MyApp\Api\Request;

class Request implements RequestInterface
{
    public $id = ""; // リクエストID

    protected $_error_list = [];

    /**
     * リクエスト時に付与されるリクエストID
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * ロードした際に、エラーが無かったか（≒error_listの長さ）
     *
     * @return bool
     */
    public function isValid(): bool
    {
        return count($this->_error_list) === 0;
    }

    /**
     * エラー内容のリスト
     *
     * @return array
     */
    public function getErrorList(): array
    {
        return $this->_error_list;
    }

}
