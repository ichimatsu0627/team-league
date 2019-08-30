<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Auth_steam Controller
 */
class Auth extends Base_controller
{
    /**
     * steam
     */
    public function steam()
    {
        $this->load->library("steam");
        $this->steam->set_return_url("/auth/steam");

        $mode = $this->steam->get_auth_mode();

        if (empty($mode))
        {
            header("Location:".$this->steam->get_auth_url());
            exit;
        }
        else if ($mode == "cancel")
        {
            $this->_redirect("/Account/edit_platform_form?c=".Page::CODE_FAILED_BY_NOT_ENOUGH);
        }

        $steam_profile = $this->steam->get_profile();

        if (!empty($steam_profile))
        {
            $this->session->set_userdata("steam_profile", $steam_profile);
        }

        $this->_redirect("/Account/edit_platform_form");
    }
}
