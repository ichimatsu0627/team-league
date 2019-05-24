<?php
/**
 * Page Library
 * @package libraries
 * @property array $messages
 */
class Page
{
    const CODE_NONE                     = 0;
    const CODE_SUCCESS                  = 1;
    const CODE_FAILED                   = 2;
    const CODE_LOGIN                    = 10001;
    const CODE_LOGOUT                   = 10002;
    const CODE_REGISTED                 = 10003;
    const CODE_EDITED                   = 10004;
    const CODE_FAILED_BY_INVALID_VALUE  = 20001;
    const CODE_FAILED_BY_NOT_ENOUGH     = 20002;
    const CODE_FAILED_BY_EXISTS_USER_ID = 20003;
    const CODE_FAILED_BY_EXISTS_EMAIL   = 20004;

    private $messages = [
        "top" => [
            "index" => [
                self::CODE_LOGIN    => "ログインしました",
                self::CODE_REGISTED => "登録完了",
            ],
        ],
        "account" => [
            "profile" => [
                self::CODE_EDITED => "編集しました",
                self::CODE_FAILED_BY_INVALID_VALUE => "編集に失敗しました",
            ],
            "regist_form" => [
                self::CODE_FAILED_BY_NOT_ENOUGH      => "入力項目に誤りがあります",
                self::CODE_FAILED_BY_INVALID_VALUE   => "アカウントの作成に失敗しました",
                self::CODE_FAILED_BY_EXISTS_USER_ID  => "指定された User Id は既に登録されています",
                self::CODE_FAILED_BY_EXISTS_EMAIL    => "指定された Email は既に登録されています",
            ],
            "login_form" => [
                self::CODE_LOGOUT                  => "ログアウトしました",
                self::CODE_FAILED_BY_INVALID_VALUE => "認証に失敗しました",
            ],
        ],
    ];

    /**
     * get_notification
     * @param string $controller
     * @param string $action
     * @param $code
     * @return array
     */
    public function get_notification($controller, $action, $code) : array
    {
        $message = $this->get_message($controller, $action, $code);

        if (empty($message))
        {
            return [];
        }

        return [
            "class"   => $this->get_class($code),
            "message" => $message,
        ];
    }

    /**
     * get_message
     * @param string $controller
     * @param string $action
     * @param $code
     * @return array
     */
    private function get_message($controller, $action, $code) : string
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

    /**
     * get_class
     * @param $code
     * @return string
     */
    private function get_class($code) : string
    {
        if (between($code, 1, 10000))
        {
            return "alert-info";
        }

        if (between($code, 10001, 20000))
        {
            return "alert-success";
        }

        if (between($code, 20001, 30000))
        {
            return "alert-danger";
        }

        return "";
    }
}
