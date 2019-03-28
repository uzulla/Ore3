<?php
declare(strict_types=1);

// defines
define("START_MICRO_SEC", microtime(true)); // measure consume time.
define("BASE_DIR", __DIR__);
if(getenv('DEBUG_MODE')) define("DEBUG_MODE", true);

// error handling settings
ini_set("display_errors", "0");
ini_set("display_startup_errors", "0");
ini_set('html_errors', "0");
error_reporting(E_ALL);
// Noticeを含むすべてのエラーをキャッチしてExceptionに変換
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
// FatalErrorなどリカバリできないエラーをキャッチ
register_shutdown_function(function () {
    $error = error_get_last();
    if (!is_array($error) || !($error['type'] & (E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR))) {
        // 処理時間ログ
        if (getenv('LOGGING_CONSUME_TIME') !== false) {
            $consume_ms = (microtime(true) - START_MICRO_SEC) * 1000;
            \MyApp\Service\LogService::debug("consume time: " . sprintf("%.2f ms", $consume_ms));
            $memory = memory_get_peak_usage(false);
            \MyApp\Service\LogService::debug("consume memory: " . sprintf("%.2f kbyte", $memory / 1024));
        }
        return; // 正常終了系
    }

    // 異常終了系

    // Logging un-excepted output buffer(debug|error messages)
    $something = ob_get_contents();
    if (strlen($something) > 0) {
        error_log($something);
    }
    ob_end_clean();

    // Error Logging
    error_log("Uncaught Fatal Error: {$error['type']}:{$error['message']} in {$error['file']}:{$error['line']}");

    // response error
    if (!headers_sent()) {
        http_response_code(500);
        header("Content-type: application/json");
    }
    echo json_encode([
        "id" => $_POST['id'] ?? null, // framework convention. response `id`.
        "code" => 500,
        "message" => "internal server error"
    ]);
});

try {
    // enable output buffer
    ob_start();

    require(__DIR__ . "/vendor/autoload.php");

    // loading .env
    try {
        $dotenv = Dotenv\Dotenv::create([__DIR__]);
        $dotenv->load();
    } catch (\Exception $e) {
        // ロードできないなら、それでもよい
    }

    // setup logger
    if (getenv('ERROR_LOG_FILE') !== false) {
        ini_set('error_log', getenv("ERROR_LOG_FILE"));
    }

    // router
    $request_uri = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $http_origin_header = $_SERVER["HTTP_ORIGIN"] ?? null;

    $base_uri = "/api";
    $path = substr($request_uri, strlen($base_uri));
    $path = explode("?", $path, 2)[0];

    \MyApp\Service\LogService::debug("Request: {$method} {$path}", ["request_uri" => $request_uri, "http_origin" => $http_origin_header, "cookie" => $_COOKIE]);

    if ($method === "OPTIONS" && !is_null($http_origin_header)) {
        $res = new \MyApp\Api\Response\CORSPreFlightResponse();

    } else if ($method === "GET" && $path === "/hello") {
        $req = new \MyApp\Api\Request\HelloRequest($_GET);
        $res = \MyApp\Api\Action\HelloAction::run($req);

    } else {
        $res = new \MyApp\Api\Response\Response();
        $res->code = 404;
    }

    \MyApp\Service\LogService::debug("Response:" . get_class($res), ['req' => $req ?? null, 'res' => $res ?? null]);

    // OBの掃除
    $something = ob_get_contents();
    if (strlen($something) > 0) {
        error_log($something);
    }
    ob_end_clean();

    // emit
    ob_start();
    $res->writeHeader($http_origin_header);
    $res->writeBody();
    ob_end_flush();

} catch (\Throwable $e) {
    // Uncaught Exception

    // Logging un-excepted output buffer(debug|error messages)
    $something = ob_get_contents();
    if (strlen($something) > 0) {
        error_log($something);
    }
    ob_end_clean();

    // Stack trace Logging
    $error_class_name = get_class($e);
    error_log("Uncaught Exception {$error_class_name}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}\n{$e->getTraceAsString()}");

    // response error
    if (!headers_sent()) {
        http_response_code(500);
        header("Content-type: application/json");
    }
    echo json_encode([
        "id" => $_POST['id'] ?? null, // framework convention. response `id`.
        "code" => 500,
        "message" => "internal server error"
    ]);
}
