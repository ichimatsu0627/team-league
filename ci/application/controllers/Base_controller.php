<?php

/**
 * Class Base_controller
 * @property Layout           $layout
 * @property Page             $page
 * @property CI_Session       $session
 * @property Login_lib        $login_lib
 */
class Base_controller extends CI_Controller
{
    public $view = [];

    public $t_member;
    public $controller_name;
    public $action_name;

    /**
     * Base_controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load_db();
        $this->load_libraries();
        $this->set_uri();
        $this->login();
        $this->set_notification();
    }

    /**
     * DB設定読み込み
     */
    private function load_db()
    {
        $this->db = $this->load->database("db", TRUE);
    }

    /**
     * ライブラリ読み込み
     */
    private function load_libraries()
    {
        $this->load->library("layout");
        $this->load->library("page");
        $this->load->library("session");
        $this->load->library("login_lib");
    }

    /**
     * URI設定
     */
    private function set_uri()
    {
        $RTR                   =& load_class('Router', 'core');
        $this->controller_name = $RTR->fetch_class();
        $this->action_name     = $RTR->fetch_method();
    }

    /**
     * ログイン
     */
    private function login()
    {
        $this->t_member = $this->login_lib->get();

        if (empty($this->t_member) && $this->login_lib->is_need_login($this->controller_name, $this->action_name))
        {
            $this->_redirect("/login/index");
        }
        $this->view["t_member"] = $this->t_member;
    }

    /**
     * 通知情報を設定
     */
    private function set_notification()
    {
        $c = $this->input->get("c");
        $this->view["notification"] = $this->page->get_notification($this->controller_name, $this->action_name, $c);
    }

    /**
     * リダイレクト
     * @param string $path
     */
    public function _redirect($path)
    {
        header("Location:".SITE_URL.$path);
        exit;        
    }
}
