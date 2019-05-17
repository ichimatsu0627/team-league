<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Account Controller
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
    public function profile($member_id = null)
    {
        if (empty($member_id))
        {
            $member_id = $this->member_id;
        }
        $member = $this->member_lib->get_member($member_id);

        if (empty($member))
        {
            $this->_redirect("/err/not_found");
        }

        $this->view["member"] = $member;
        $this->view["platforms"] = $this->platform_lib->platforms;
        $this->layout->view('account/profile', $this->view);
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
        $this->view["platforms"] = $this->platform_lib->platforms;
        $this->layout->view('account/regist_form', $this->view);
    }

    /**
     * edit_form
     */
    public function edit_form()
    {
        $member = $this->member_lib->get_member($this->member_id);

        $this->view["member"] = $member;
        $this->view["platforms"] = $this->platform_lib->platforms;
        $this->layout->view('account/edit_form', $this->view);
    }

    /**
     * regist
     */
    public function regist()
    {
        $member_data = [
            "user_id"  => $this->input->post('user_id'),
            "name"     => $this->input->post('name'),
            "email"    => $this->input->post('email'),
            "password" => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
        ];

        $platform_data = [];
        foreach($this->platform_lib->platforms as $pf)
        {
            $pfid = $this->input->post("pf-".$pf->id);
            if (isset($pfid))
            {
                $platform_data[$pf->id] = $pfid;
            }
        }

        $check = $this->input->post('check');

        if (!$this->member_lib->validate_regist_memberdata($member_data))
        {
            $this->_redirect("/account/regist_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        if (empty($check))
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

            if ($this->member_lib->exists_member_by_user_id($member_data["user_id"]))
            {
                $c = Page::CODE_FAILED_BY_EXISTS_USER_ID;
                throw new Exception("duplicate used id");
            }

            if ($this->member_lib->exists_member_by_email($member_data["email"]))
            {
                $c = Page::CODE_FAILED_BY_EXISTS_EMAIL;
                throw new Exception("duplicate email");
            }

            if(!password_verify($this->input->post('conf_password'), $member_data["password"]))
            {
                $c = Page::CODE_FAILED_BY_INVALID_VALUE;
                throw new Exception("invalid password");
            }

            // データ作成
            $id = $this->member_lib->regist($member_data, $platform_data);

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
            $this->login_lib->save($t_member->id);
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
    public function edit()
    {

    }
}
