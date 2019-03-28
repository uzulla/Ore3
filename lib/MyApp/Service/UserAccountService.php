<?php
declare(strict_types=1);

namespace MyApp\Service;

use MyApp\Model\UserAccount;
use MyApp\Repo\UserAccountRepo;

class UserAccountService
{
    public static function getByEmail(string $email, UserAccountRepo $repo=null): ?UserAccount
    {
        $repo = $repo ?? new UserAccountRepo();
        return $repo->getByEmail($email);
    }

    public static function getByEmailAndPassword(string $email, string $password, UserAccountRepo $repo): ?UserAccount
    {
        $ua = static::getByEmail($email, $repo);
        if (is_null($ua)) {
            return null;
        }

        if (!password_verify($password, $ua->hashed_password)) {
            return null;
        }

        return $ua;
    }

    public static function getById(int $user_account_id, UserAccountRepo $repo = null): ?UserAccount
    {
        $repo = $repo ?? new UserAccountRepo();
        return $repo->getById($user_account_id);
    }

    public static function update(UserAccount $ua, UserAccountRepo $repo = null): bool
    {
        $repo = $repo ?? new UserAccountRepo();
        return $repo->update($ua);
    }

    public static function create(UserAccount $ua, UserAccountRepo $repo = null): int
    {
        $repo = $repo ?? new UserAccountRepo();
        return $repo->create($ua);
    }
}
