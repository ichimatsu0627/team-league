<?php

/**
 * Class Base_controller
 *
 * ---- CI_Class ----
 * @property CI_Session       $session
 */
class Base_controller extends CI_Controller
{
    public $view = [];

    public $member_id;
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
        $this->set_csrf_token();
        $this->set_uri();
        $this->login_validate();
        $this->set_member_id();
        $this->set_alerts();
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
        $this->load->library("session");
        $this->load->library("layout");
        $this->load->library("page");
        $this->load->library("login_lib");
        $this->load->library("member_lib");
        $this->load->library("platform_lib");
        $this->load->library("notice_lib");
    }

    /**
     * URI設定
     */
    private function set_uri()
    {
        $RTR                   =& load_class('Router', 'core');
        $this->controller_name =  $RTR->fetch_class();
        $this->action_name     =  $RTR->fetch_method();
    }

    /**
     * CSRF対策
     * POST時には、下記フォームを使うこと
     * <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
     */
    public function set_csrf_token()
    {
        $this->view["csrf"] = [
            "name" => $this->security->get_csrf_token_name(),
            "hash" => $this->security->get_csrf_hash(),
        ];
    }

    /**
     * ログイン チェック
     */
    private function login_validate()
    {
        if ($this->login_lib->is_need_login($this->controller_name, $this->action_name) && !$this->login_lib->validate())
        {
            $this->_redirect("/account/login_form");
        }
    }

    /**
     * 通知情報を設定
     */
    private function set_alerts()
    {
        $c = $this->input->get("c");
        $this->view["alerts"] = $this->page->get_alerts($this->controller_name, $this->action_name, $c);
    }

    /**
     * ベルによる通知
     */
    private function set_notification()
    {
        $this->view["notification"] = $this->notice_lib->get($this->member_id);
    }

    /**
     * 会員情報をviewにセットする
     */
    private function set_member_id()
    {
        $this->member_id = $this->login_lib->get_id();
        $this->view["member_id"] = $this->member_id;
    }

    /**
     * requireする
     * @param $filepath
     */
    public function require_lib($filepath)
    {
        require_once(APPPATH."library/".$filepath.".php");
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
