<?php
/**
 * Page Library
 * @package libraries
 * @property array $messages
 */
class Page
{
    const CODE_NONE                         = 0;
    const CODE_SUCCESS                      = 1;
    const CODE_LOGIN                        = 10001;
    const CODE_LOGOUT                       = 10002;
    const CODE_REGISTED                     = 10003;
    const CODE_EDITED                       = 10004;
    const CODE_REQUESTS                     = 10005;
    const CODE_ACCEPT                       = 10006;
    const CODE_REFUSE                       = 10007;
    const CODE_FAILED                       = 20000;
    const CODE_FAILED_BY_INVALID_VALUE      = 20001;
    const CODE_FAILED_BY_NOT_ENOUGH         = 20002;
    const CODE_FAILED_BY_EXISTS_USER_ID     = 20003;
    const CODE_FAILED_BY_EXISTS_EMAIL       = 20004;
    const CODE_FAILED_BY_EXISTS_PLATFORM_ID = 20005;
    const CODE_FAILED_BY_NOT_MATCH_PASSWORD = 20006;
    const CODE_FAILED_BY_JOINED             = 20007;
    const CODE_FAILED_BY_MAX_JOINED         = 20008;
    const CODE_FAILED_BY_PERMISSION_DENIED  = 20009;
    const CODE_FAILED_BY_NOT_FOUND          = 20010;
    const CODE_FAILED_BY_IMAGE_NOT_FOUND    = 20011;
    const CODE_FAILED_BY_IMAGE_FORMAT       = 20012;
    const CODE_FAILED_BY_IMAGE_SIZE         = 20013;

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
            ],
            "edit_form" => [
                self::CODE_FAILED_BY_INVALID_VALUE      => "編集に失敗しました",
                self::CODE_FAILED_BY_EXISTS_PLATFORM_ID => "指定された Platform id の中に既に登録済みのものがあります",
                self::CODE_FAILED_BY_NOT_FOUND          => "指定されたプラットフォームのユーザーが見つかりませんでした",
                self::CODE_FAILED_BY_IMAGE_NOT_FOUND    => "ファイルが見つかりませんでした",
                self::CODE_FAILED_BY_IMAGE_FORMAT       => "このファイルは取り扱いできません(png, jpg, jpeg, ico)",
                self::CODE_FAILED_BY_IMAGE_SIZE         => "516KB以上のファイルはアップロードできません"
            ],
            "edit_platform_form" => [
                self::CODE_FAILED_BY_NOT_FOUND => "指定されたプラットフォームのユーザーが見つかりませんでした",
            ],
            "register_form" => [
                self::CODE_FAILED                    => "登録に失敗しました",
                self::CODE_FAILED_BY_NOT_ENOUGH      => "必須項目に入力漏れがありました",
                self::CODE_FAILED_BY_INVALID_VALUE   => "アカウントの作成に失敗しました",
                self::CODE_FAILED_BY_EXISTS_USER_ID  => "指定された User Id は既に登録されています",
                self::CODE_FAILED_BY_EXISTS_EMAIL    => "指定された Email は既に登録されています",
                self::CODE_FAILED_BY_EXISTS_PLATFORM_ID => "指定された Platform id の中に既に登録済みのものがあります",
                self::CODE_FAILED_BY_NOT_MATCH_PASSWORD => "パスワードが一致しませんでした",
                self::CODE_FAILED_BY_NOT_FOUND       => "指定されたプラットフォームのユーザーが見つかりませんでした",
            ],
            "login_form" => [
                self::CODE_LOGOUT                  => "ログアウトしました",
                self::CODE_FAILED_BY_INVALID_VALUE => "認証に失敗しました",
            ],
        ],
        "team" => [
            "detail" => [
                self::CODE_REQUESTS         => "申請しました",
                self::CODE_ACCEPT           => "承認しました",
                self::CODE_FAILED_BY_JOINED => "既にチームに所属しています",
                self::CODE_FAILED_BY_MAX_JOINED => "同時に掛け持ちできるチームは5チームまでです",
            ],
            "request_list" => [
                self::CODE_REFUSE                      => "却下しました",
                self::CODE_FAILED_BY_PERMISSION_DENIED => "権限がありません",
            ],
        ],
    ];

    /**
     * get_alerts
     * @param string $controller
     * @param string $action
     * @param $code
     * @return array
     */
    public function get_alerts($controller, $action, $code) : array
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

    /**
     * ページャーのスタートを取得
     * @param $page
     * @param $all
     * @param int $limit
     */
    public function get_pager_start($page, $all, $limit = DEFAULT_PAGER_PER)
    {
        if ($page <= 2)
        {
            return 1;
        }

        if ($page * $limit >= $all)
        {
            return ($page - 2);
        }

        return ($page - 1);
    }
}
