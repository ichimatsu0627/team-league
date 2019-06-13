<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Login Library
 */
class Login_lib extends Base_lib
{
    protected $_libraries = [
        "member_lib",
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
     * save
     * @param $t_member
     */
    public function save($member_data)
    {
        // セッション保存
        $this->CI->session->set_userdata(Member_lib::SESSION_KEY, $member_data);
    }

    /**
     * validate
     * @return boolean
     */
    public function validate()
    {
        return $this->CI->session->has_userdata(Member_lib::SESSION_KEY);
    }

    /**
     * refresh
     */
    public function refresh()
    {
        $this->CI->session->unset_userdata(Member_lib::SESSION_KEY);
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
