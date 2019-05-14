<?php
/**
 * Login Library
 * @package libraries
 * @property CI_Controller $CI
 */
class Login_lib
{
    private $CI;

    private $nologin_page = [
        "top" => [
            "index",
        ],
        "login" => [
            "index",
            "exec",
        ],
        "account" => [
            "regist_form",
            "regist",
        ],
    ];

    /**
     * Login constructor.
     */
    public function __construct()
    {
        $this->CI = get_instance();
    }

    /**
     * save
     * @param $t_member
     */
    public function save($t_member)
    {
        // セッション保存
        $this->CI->session->set_userdata('t_member', $t_member);
    }

    /**
     * get
     * @return mixed
     */
    public function get()
    {
        return $this->CI->session->userdata('t_member');
    }

    /**
     *refresh
     */
    public function refresh()
    {
        $this->CI->session->unsert_userdata('t_member');
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
