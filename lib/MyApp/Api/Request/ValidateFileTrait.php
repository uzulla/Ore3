<?php

namespace MyApp\Api\Request;

use MyApp\Service\LogService;

trait ValidateFileTrait
{
    public function validateFile(array $error_list, array $list, string $key, int $max_size, bool $require): ErrorListAndVal
    {
        /*
         * http://php.net/manual/ja/features.file-upload.errors.php
         * $_FILES['user_file']['name'] クライアントマシンの元のファイル名。
         * $_FILES['user_file']['type'] ファイルの MIME 型。ただし、ブラウザがこの情報を提供する場合。 例えば、"image/gif" のようになります。 この MIME 型は PHP 側ではチェックされません。そのため、 この値は信用できません。
         * $_FILES['user_file']['size'] アップロードされたファイルのバイト単位のサイズ。
         * $_FILES['user_file']['tmp_name'] アップロードされたファイルがサーバー上で保存されているテンポラ リファイルの名前。
         * $_FILES['user_file']['error'] このファイルアップロードに関する エラーコード
         */

        // 必須なのになければエラー
        if ($require && !isset($list[$key])) {
            $error_list[$key][] = "required";
            return new ErrorListAndVal($error_list, null);
        }

        // 非必須で無いならOKでリターン
        if (!$require && !isset($list[$key])) {
            return new ErrorListAndVal($error_list, null);
        }

        // なんかおかしいのでエラーでリターン
        if (!isset($list[$key]['error'])) {
            $error_list[$key][] = "invalid";
            return new ErrorListAndVal($error_list, null);
        }

        // error code check
        switch ($list[$key]['error']) {
            case UPLOAD_ERR_OK;
                break; // OK
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
            case UPLOAD_ERR_PARTIAL:
            case UPLOAD_ERR_NO_FILE:
            case UPLOAD_ERR_NO_TMP_DIR:
            case UPLOAD_ERR_CANT_WRITE:
            case UPLOAD_ERR_EXTENSION:
            default: // unknown error code.
                $error_list[$key][] = "server error. error_code {$list[$key]['error']}";
                LogService::error("file upload error. error code {$list[$key]['error']}");
                return new ErrorListAndVal($error_list, null);
        }

        // check file size
        if ($list[$key]['size'] === 0) {
            $error_list[$key][] = "required (file size is zero)";
            return new ErrorListAndVal($error_list, null);
        }

        // max upload size limiter
        if ($list[$key]['size'] > $max_size) {
            $error_list[$key][] = "max allowed file size is {$max_size} bytes";
            return new ErrorListAndVal($error_list, null);
        }

        // なぜかアップロードファイルではない(おそらく不正なリクエスト)
        if (!defined("MOCK_FILE_UPLOAD_TEST") && !is_uploaded_file($list[$key]['tmp_name'])) {
            $error_list[$key][] = "bad request";
            return new ErrorListAndVal($error_list, null);
        }

        // MIME確認(あくまでもサンプルコード)
        $suspect_mime = mime_content_type($list[$key]['tmp_name']);
        if ($list[$key]['type'] !== $suspect_mime) {
            $error_list[$key][] = "invalid mime type, claim:{$list[$key]['type']}, suspect:{$suspect_mime}";
            return new ErrorListAndVal($error_list, null);
        }

        return new ErrorListAndVal($error_list, $list[$key]); // OK、$_FILEを返す
    }
}
