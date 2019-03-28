<?php
declare(strict_types=1);

namespace MyApp\Model;

class UserAccount
{
    public $id = 0;
    public $email = "";
    public $hashed_password = "";
    public $name = "";

    public function __construct()
    {
        if ($this->id === 0) return; // PDO fetchなら以降も実行される

        $this->id = (int)$this->id;
    }

    public function setPassword(string $new_plain_password): void
    {
        $this->hashed_password = password_hash($new_plain_password, PASSWORD_DEFAULT);
    }

    public function passwordVerification(string $plain_pass): bool
    {
        return password_verify($plain_pass, $this->hashed_password);
    }

    public function isValid(): bool
    {
        return count($this->validate()) === 0;
    }

    public function validate(): array
    {
        /* ... 省略 ... */
        return []; // is OK
    }

    public function getPublicData(): array
    {
        $list = get_object_vars($this);
        unset($list['id']);
        unset($list['hashed_password']);
        return $list;
    }
}
