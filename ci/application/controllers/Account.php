<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Account Controller
 *
 * @property Cloud_storage cloud_storage
 */
class Account extends Base_controller
{
    /**
     * profile
     */
    public function profile($member_id = null)
    {
        if (empty($member_id))
        {
            $this->_redirect("/err/not_found");
        }
        $member = $this->member_lib->get_member($member_id);

        if (empty($member))
        {
            $this->_redirect("/err/not_found");
        }

        $this->load->library("team_lib");
        $teams = $this->team_lib->get_teams_by_member_id($member_id);

        $this->view["member"] = $member;
        $this->view["teams"]  = $teams;
        $this->view["platforms"] = $this->platform_lib->platforms;
        $this->layout->view('account/profile', $this->view);
    }

    /**
     * login_form
     */
    public function login_form()
    {
        $this->view["login_id"] = $this->session->userdata(SESSION_KEY_LOGIN_ID);
        $this->layout->view('account/login_form', $this->view);
    }

    /**
     * register_form
     */
    public function register_form()
    {
        $this->view["platforms"] = $this->platform_lib->platforms;
        $this->layout->view('account/register_form', $this->view);
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
     * edit_platform_form
     */
    public function edit_platform_form()
    {
        $member = $this->member_lib->get_member($this->member_id);

        $steam_profile = $this->session->userdata("steam_profile") ?? null;

        $this->view["member"]        = $member;
        $this->view["platforms"]     = $this->platform_lib->platforms;
        $this->view["steam_profile"] = $steam_profile;
        $this->layout->view('account/edit_platform_form', $this->view);
    }

    /**
     * register
     */
    public function register()
    {
        $member_data   = $this->input_member("post");
        $platform_data = $this->input_platform("post");
        $check         = $this->input->post('check');

        if (!$this->member_lib->validate_register_memberdata($member_data))
        {
            $this->_redirect("/account/register_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        if (empty($check))
        {
            $this->_redirect("/account/register_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        if (!$check)
        {
            $this->_redirect("/account/register_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        try
        {
            $this->member_lib->begin();

            if ($this->member_lib->exists_member_by_login_id($member_data["login_id"]))
            {
                throw new Exception("duplicate used id", Page::CODE_FAILED_BY_EXISTS_USER_ID);
            }

            if(!password_verify($this->input->post('conf_password'), $member_data["password"]))
            {
                throw new Exception("invalid password", Page::CODE_FAILED_BY_NOT_MATCH_PASSWORD);
            }

            // データ作成
            $id = $this->member_lib->register($member_data, $platform_data);

            if (empty($id))
            {
                throw new Exception("Fail register", Page::CODE_FAILED);
            }

            // ログインしとく
            $this->login_lib->save($id);
            $this->session->set_userdata(SESSION_KEY_LOGIN_ID, $member_data["login_id"]);

            $this->member_lib->commit();
        }
        catch (Exception $e)
        {
            $this->member_lib->rollback();
            $c = $e->getCode();
            $this->_redirect("/account/register_form?c=".$c);
        }

        $this->_redirect("/top/index?c=".Page::CODE_REGISTED);
    }

    /**
     * edit_profile
     */
    public function edit()
    {
        $id = $this->input->post("id");

        if (empty($id))
        {
            $this->_redirect("/err/not_found");
        }

        if ($id != $this->member_id)
        {
            $this->_redirect("/account/profile/".$id);
        }

        $member = $this->member_lib->get_member($this->member_id);

        $member_data   = $this->input_member("post");
        $platform_data = $this->input_platform("post");

        try
        {
            $this->member_lib->begin();

            $this->member_lib->lock($this->member_id);

            $this->member_lib->update($member, $member_data, $platform_data);

            $this->member_lib->commit();
        }
        catch(Exception $e)
        {
            $this->member_lib->rollback();
            $c = $e->getCode() ?? Page::CODE_FAILED_BY_INVALID_VALUE;
            $this->_redirect("/account/edit_form?c=".$c);
        }

        $this->_redirect("/account/profile/".$id."?c=".Page::CODE_EDITED);
    }

    /**
     * edit_platform
     */
    public function edit_platform()
    {
        $id = $this->input->post("id");

        if (empty($id))
        {
            $this->_redirect("/err/not_found");
        }

        if ($id != $this->member_id)
        {
            $this->_redirect("/account/profile/".$id);
        }

        $member = $this->member_lib->get_member($this->member_id);

        $platform_data = $this->input_platform("post");
        $steam_avatar = $this->input->post("steam_avatar");

        $member_data = [];
        if (!empty($steam_avatar))
        {
            $member_data = ["steam_avatar" => $steam_avatar];
        }

        try
        {
            $this->member_lib->begin();

            $this->member_lib->lock($this->member_id);

            $this->member_lib->update($member, $member_data, $platform_data);

            $this->member_lib->commit();
        }
        catch(Exception $e)
        {
            $this->member_lib->rollback();
            $c = $e->getCode() ?? Page::CODE_FAILED_BY_INVALID_VALUE;
            $this->_redirect("/account/edit_form?c=".$c);
        }

        $this->_redirect("/account/profile/".$id."?c=".Page::CODE_EDITED);
    }

    /**
     * Image Update
     */
    public function edit_image()
    {
        try
        {
            if (!isset($_FILES["image"]) || empty($_FILES["image"]) || $_FILES["image"]["size"] <= 0)
            {
                throw new Exception("Not found file.", Page::CODE_FAILED_BY_IMAGE_NOT_FOUND);
            }

            $image = $_FILES["image"];

            if (!in_array($image["type"], ["image/jpeg", "image/png", "image/vnd.microsoft.icon"]))
            {
                throw new Exception("File format error.", Page::CODE_FAILED_BY_IMAGE_FORMAT);
            }

            if ($image["size"] > 516000)
            {
                throw new Exception("File size is larger than 516KB.", Page::CODE_FAILED_BY_IMAGE_SIZE);
            }

            // Cloud Storage
            $this->load->library("google/Cloud_storage");
            $this->cloud_storage->upload_object(ENVIRONMENT."/".$this->member_id.".jpg", $image["tmp_name"]);
        }
        catch(Exception $e)
        {
            $c = $e->getCode() ?? Page::CODE_FAILED_BY_INVALID_VALUE;
            $this->_redirect("/account/edit_form?c=".$c);
        }

        $this->_redirect("/account/edit_form");
    }

    /**
     * login
     */
    public function login()
    {
        $login_id = $this->input->post('login_id');
        $remember_id = $this->input->post('remember_id');

        if (empty($this->input->post('password')))
        {
            $this->_redirect("/account/login_form?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        try
        {
            $t_member = $this->member_lib->get_by_login_id($login_id);

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

            // ログインIDをセッションに保存
            if ($remember_id)
            {
                $this->session->set_userdata(SESSION_KEY_LOGIN_ID, $login_id);
            }
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

        $this->login_lib->refresh($this->member_id);

        $this->_redirect("/account/login_form?c=".Page::CODE_LOGOUT);
    }

    /**
     * auth steam
     */
    public function auth_steam()
    {
        $this->input_member("post");

        $this->load->library('steam');

        $this->steam->set_url_param();
        $this->steam->get_auth_mode();
    }

    /**
     * 会員データPOST取得
     * @return array
     */
    private function input_member($method = "get")
    {
        $member_data = [
            "login_id"  => $this->input->$method('login_id'),
            "name"     => $this->input->$method('name'),
            "password" => password_hash($this->input->$method('password'), PASSWORD_BCRYPT)
        ];

        $twitter = $this->input->$method('twitter');
        $discord = $this->input->$method('discord');

        if (isset($twitter))
        {
            $member_data["twitter"] = $twitter;
        }

        if (isset($discord))
        {
            $member_data["discord"] = $discord;
        }

        return $member_data;
    }

    /**
     * 会員プラットフォームデータPOST取得
     * @return array
     */
    private function input_platform($method = "get")
    {
        $platform_data = [];
        foreach($this->platform_lib->platforms as $pf)
        {
            $pfid = $this->input->$method("pf-".$pf->id);
            if (isset($pfid))
            {
                $platform_data[$pf->id] = $pfid;
            }
        }
        return $platform_data;
    }
}
