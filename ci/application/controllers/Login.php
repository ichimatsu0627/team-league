<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Login Controller
 * @package controllers
 */
class Login extends Base_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("T_members");
    }

    public function index()
    {
        $this->layout->view('login/index', $this->view);
    }

    public function exec()
    {
        $user_id = $this->input->post('user_id');

        if (empty($this->input->post('password')))
        {
            $this->_redirect("/login/index?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        try {

            $t_member = $this->T_members->get_by_userid($user_id);

            if (empty($t_member))
            {
                throw new Exception("not found t_member");
            }

            if(!password_verify($this->input->post('password'), $t_member->password))
            {
                throw new Exception("invalid password");
            }

            // ログイン
            $this->login_lib->save($t_member);
        }
        catch(Exception $e)
        {
            $this->_redirect("/login/index?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        $this->_redirect("/top/index?c=".Page::CODE_LOGIN);
    }
}
