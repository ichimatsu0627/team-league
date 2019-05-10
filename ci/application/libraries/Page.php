<?php

class Page
{
    const CODE_NONE                    = 0;
    const CODE_SUCCESS                 = 1;
    const CODE_FAILED                  = 2;
    const CODE_LOGIN                   = 10001;
    const CODE_REGISTED                = 10002;
    const CODE_FAILED_BY_INVALID_VALUE = 20001;
    const CODE_FAILED_BY_NOT_ENOUGHT   = 20002;

    private $messages = [
        "top" => [
            "index" => [
                self::CODE_LOGIN    => "ログインしました",
                self::CODE_REGISTED => "登録完了",
            ],
        ],
        "login" => [
            "index" => [
                self::CODE_FAILED_BY_INVALID_VALUE => "認証に失敗しました",
            ],
        ],
        "user" => [
            "regist_form" => [
                self::CODE_FAILED_BY_INVALID_VALUE => "アカウントの作成に失敗しました",
            ],
        ],
    ];

    public function get_message($controller, $action, $code)
    {
        if (!isset($this->messages[$controller]))
        {
            return "";
        }

        if (!isset($this->messages[$controller][$action]))
        {
            return "";
        }

        if (!isset($this->messages[$controller][$action][$code]))
        {
            return "";
        }

        return $this->messages[$controller][$action][$code];
    }
}
