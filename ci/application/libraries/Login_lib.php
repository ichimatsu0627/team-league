<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Login Library
 */
class Login_lib extends Base_lib
{
    protected $_models = [
        "T_login_sessions"
    ];

    private $nologin_page = [
        "top" => [
            "index",
        ],
        "account" => [
            "register_form",
            "register",
            "login_form",
            "login",
            "profile",
        ],
        "team" => [
            "detail",
        ],
        "err" => [
            "general",
            "not_found",
        ],
    ];

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get_id
     * @return id|null
     */
    public function get_id()
    {
        // セッションから取得
        $token = $this->CI->session->userdata(SESSION_KEY_MEMBER);

        return $this->CI->T_login_sessions->get_member_id_by_token($token);
    }

    /**
     * save
     * @param $id
     */
    public function save($id)
    {
        if ($this->CI->T_login_sessions->has_token_by_member_id($id))
        {
            $this->CI->T_login_sessions->reflesh($id);
        }

        $token = $this->CI->T_login_sessions->register($id);

        // セッション保存
        $this->CI->session->set_userdata(SESSION_KEY_MEMBER, $token);
    }

    /**
     * validate
     * @return boolean
     */
    public function validate()
    {
        $token = $this->CI->session->userdata(SESSION_KEY_MEMBER);

        if (empty($token))
        {
            return false;
        }

        $id = $this->CI->T_login_sessions->get_member_id_by_token($token);
        if (empty($id))
        {
            return false;
        }

        return true;
    }

    /**
     * refresh
     */
    public function refresh($id)
    {
        $this->CI->T_login_sessions->reflesh($id);
        $this->CI->session->unset_userdata(SESSION_KEY_MEMBER);
    }

    /**
     * ログインが必要なページであるか
     * @param $controller
     * @param $action
     */
    public function is_need_login($controller, $action)
    {
        if (!isset($this->nologin_page[$controller]))
        {
            return true;
        }

        if (!in_array($action, $this->nologin_page[$controller]))
        {
            return true;
        }

        return false;
    }
}
