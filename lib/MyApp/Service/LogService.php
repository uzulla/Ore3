<?php

namespace MyApp\Service;

/**
 * サンプルなので全部 error_log に流していますが、本物（？）では別途ロガーを差し込みます
 *
 * Class LogService
 * @package MyApp\Service
 */
class LogService
{
    public function __construct()
    {
    }

    public static function error(string $message, array $data = null)
    {
        if (!is_null($data)) {
            $message .= "\n" . json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);
        }
        error_log(static::colorRed($message));
    }

    public static function success(string $message, array $data = null)
    {
        if (!is_null($data)) {
            $message .= "\n" . json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);
        }
        error_log(static::colorGreen($message));
    }

    public static function debug(string $message, array $data = null)
    {
        if (!is_null($data)) {
            $message .= "\n" . json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE);
        }
        error_log(static::colorGray($message));
    }

    public static function colorRed(string $str): string
    {
        return "\033[0;31m{$str}\033[0m";
    }

    public static function colorGreen(string $str): string
    {
        return "\033[1;32m{$str}\033[0m";
    }

    public static function colorGray(string $str): string
    {
        return "\033[1;30m{$str}\033[0m";
    }

}
