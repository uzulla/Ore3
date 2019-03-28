<?php
declare(strict_types=1);

namespace MyApp\Repo;

class DB
{
    /** @var null|\PDO */
    static $pdo = null;

    public function __construct(\PDO $pdo = null)
    {
        if (!is_null($pdo)) {
            static::$pdo = $pdo;
        }
    }

    public static function getPdo()
    {
        if (is_null(static::$pdo)) {
            static::$pdo = static::getNewPdo();
        }
        return static::$pdo;
    }

    public static function getNewPdo()
    {
        $pdo = new \PDO('sqlite:' . BASE_DIR . '/sqlite.db'); // 開発用
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    }

    public static function beginTransaction(): bool
    {
        return (static::getPdo())->beginTransaction();
    }

    public static function commitTransaction(): bool
    {
        return (static::getPdo())->commit();
    }

    public static function rollbackTransaction(): bool
    {
        return (static::getPdo())->rollBack();
    }
}
