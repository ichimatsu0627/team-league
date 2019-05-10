<?php
require_once(APPPATH."controllers/Base_controller.php");

class Account extends Base_controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model("T_members");
    }

    public function regist_form()
    {
        $this->layout->view('account/regist_form', $this->view);
    }

    public function edit_form()
    {
        $this->layout->view('account/edit_form', $this->view);
    }

    public function regist()
    {
        $user_id       = $this->input->post('user_id');
        $name          = $this->input->post('name');
        $email         = $this->input->post('email');
        $password_hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

        if (empty($user_id) || empty($name) || empty($email))
        {
            $this->_redirect("/account/regist_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGHT);
        }

        try
        {
            $t_member = $this->T_members->get_by_userid($user_id);

            if (!empty($t_member))
            {
                throw new Exception("already used id");
            }

            if(!password_verify($this->input->post('conf_password'), $password_hash))
            {
                throw new Exception("invalid password");
            }

            $this->T_members->insert([
                "user_id"  => $user_id,
                "name"     => $name,
                "email"    => $email,
                "password" => $password_hash
            ]);

            $this->T_members->trans_commit();
        }
        catch (Exception $e)
        {
            $this->T_members->trans_rollback();

            $this->_redirect("/account/regist_form?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        $this->_redirect("/top/index?c=".Page::CODE_REGISTED);
    }

    public function edit()
    {

    }
}
