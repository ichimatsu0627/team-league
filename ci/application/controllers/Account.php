<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Account Controller
 * @package controllers
 */
class Account extends Base_controller
{
    /**
     * Account constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * profile
     */
    public function profile()
    {
    }

    /**
     * login_form
     */
    public function login_form()
    {
        $this->layout->view('account/login_form', $this->view);
    }

    /**
     * regist_form
     */
    public function regist_form()
    {
        $this->layout->view('account/regist_form', $this->view);
    }

    /**
     * edit_form
     */
    public function edit_form()
    {
        $this->load->model("T_member_platforms");
        $this->layout->view('account/edit_form', $this->view);
    }

    /**
     * regist
     */
    public function regist()
    {
        $user_id       = $this->input->post('user_id');
        $name          = $this->input->post('name');
        $email         = $this->input->post('email');
        $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);
        $check         = $this->input->post('check');

        if (empty($user_id) ||
            empty($name) ||
            empty($email) ||
            empty($check) ||
            empty($this->input->post('password')) ||
            empty($this->input->post('conf_password')))
        {
            $this->_redirect("/account/regist_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        if (!$check)
        {
            $this->_redirect("/account/regist_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        try
        {
            $c = Page::CODE_NONE;
            $t_member = $this->T_members->get_by_userid($user_id);

            if (!empty($t_member))
            {
                $c = Page::CODE_FAILED_BY_EXISTS;
                throw new Exception("already used id");
            }

            if(!password_verify($this->input->post('conf_password'), $password_hash))
            {
                $c = Page::CODE_FAILED_BY_INVALID_VALUE;
                throw new Exception("invalid password");
            }

            // データ作成
            $id = $this->member_lib->regist([
                "user_id"  => $user_id,
                "name"     => $name,
                "email"    => $email,
                "password" => $password_hash
            ]);

            if (empty($id))
            {
                $c = Page::CODE_FAILED_BY_INVALID_VALUE;
                throw new Exception("Fail regist");
            }

            // ログインしとく
            $this->login_lib->save($id);

            $this->T_members->trans_commit();
        }
        catch (Exception $e)
        {
            $this->T_members->trans_rollback();
            $this->_redirect("/account/regist_form?c=".$c);
        }

        $this->_redirect("/top/index?c=".Page::CODE_REGISTED);
    }

    /**
     * login
     */
    public function login()
    {
        $user_id = $this->input->post('user_id');

        if (empty($this->input->post('password')))
        {
            $this->_redirect("/account/login_form?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        try
        {
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
            $this->_redirect("/account/login_form?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        $this->_redirect("/top/index?c=".Page::CODE_LOGIN);
    }

    /**
     * logout
     */
    public function logout()
    {
        if (!$this->login_lib->validate())
        {
            $this->_redirect("/account/login_form");
        }

        $this->login_lib->refresh();

        $this->_redirect("/account/login_form?c=".Page::CODE_LOGOUT);
    }

    /**
     * edit_profile
     */
    public function edit_profile()
    {

    }
}
