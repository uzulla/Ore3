<?php

namespace MyApp\Api\Request;

trait ValidateRegexTrait
{

    /**
     * 正規表現を用いて検証し、 追記したerror_listと値のセットを返す。
     *
     * @param array $error_list
     * @param array $list
     * @param string $key
     * @param string $regex
     * @param bool $require
     * @return ErrorListAndVal
     */
    public function validateWithRegex(array $error_list, array $list, string $key, string $regex, bool $require = false): ErrorListAndVal
    {
        if (!$require && !isset($list[$key])) {
            // 非必須でキーが存在しないなら検証不要でOK、（値はNULL）
            return new ErrorListAndVal($error_list, null);

        } else if ($require && !isset($list[$key])) {
            // 必須でキーが存在しないならエラー
            $error_list[$key][] = "required.";
            return new ErrorListAndVal($error_list, null);
        }

        // 文字列か確認する、違う（配列等）ならエラー
        if (!is_string($list[$key])) {
            $error_list[$key][] = "bad request.";
            return new ErrorListAndVal($error_list, null);
        }

        // 正規表現を通過できなかった
        if (!preg_match($regex, $list[$key])) {
            $error_list[$key][] = "invalid format. {$regex}";
            return new ErrorListAndVal($error_list, null);
        }

        return new ErrorListAndVal($error_list, $list[$key]); // OK
    }
}
