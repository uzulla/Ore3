<?php

namespace MyApp\Repo;

use MyApp\Model\UserAccount;

class UserAccountRepo extends DB
{
    public function getById(int $id): ?UserAccount
    {
        $pdo = static::getPdo();

        $stmt = $pdo->prepare("SELECT * FROM `user_account` WHERE id = :id");
        $stmt->bindValue('id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, UserAccount::class);

        return $stmt->fetch() ?: null;
    }

    public function getByEmail(string $email): ?UserAccount
    {
        $pdo = static::getPdo();

        $stmt = $pdo->prepare("SELECT * FROM `user_account` WHERE email = :email");
        $stmt->bindValue('email', $email, \PDO::PARAM_STR);
        $stmt->execute();

        $stmt->setFetchMode(\PDO::FETCH_CLASS, '\MyApp\Model\UserAccount');

        return $stmt->fetch() ?: null;
    }

    public function update(UserAccount $ua): bool
    {
        if(!$ua->isValid()){
            throw new \InvalidArgumentException("invalid user account model");
        }

        $pdo = static::getPdo();

        // email, idは変更不能
        $stmt = $pdo->prepare("UPDATE user_account SET hashed_password=:hashed_password, name=:name, WHERE id=:id");

        $stmt->bindValue('hashed_password', $ua->hashed_password, \PDO::PARAM_STR);
        $stmt->bindValue('name', $ua->name, \PDO::PARAM_STR);
        $stmt->bindValue('id', $ua->id, \PDO::PARAM_INT);
        $result = $stmt->execute();

        if (!$result) {
            throw new \RuntimeException("DB query error.");
        }

        $rows = $stmt->rowCount();
        if ($rows > 1) {// 2行以上が更新されているのはおかしい
            throw new \RuntimeException("multiple row affected(expect 1, got {$rows})");
        }

        return ($rows === 1); // 1行なら完了、0行なら該当なし
    }

    public function create(UserAccount $ua): int
    {
        if(!$ua->isValid()){
            throw new \InvalidArgumentException("invalid user account model");
        }

        $pdo = static::getPdo();

        // email, idは変更不能
        $stmt = $pdo->prepare("
        INSERT INTO user_account 
        (email, hashed_password, name) VALUES 
        (:email, :hashed_password, :name)
        ");

        $stmt->bindValue('hashed_password', $ua->hashed_password, \PDO::PARAM_STR);
        $stmt->bindValue('name', $ua->name, \PDO::PARAM_STR);
        $stmt->bindValue('email', $ua->email, \PDO::PARAM_STR);
        $result = $stmt->execute();

        if (!$result) {
            throw new \RuntimeException("DB query error.");
        }

        return $pdo->lastInsertId('id');
    }
}
