<?php
require_once(APPPATH."libraries/Base_lib.php");

class Steam extends Base_lib
{
    protected $_requires = [
        APPPATH."libraries/third_party/LightOpenID/openid.php"
    ];

    const DOMAIN = "https://team-league.appspot.com";
    const OPEN_ID_URL = "http://steamcommunity.com/openid";
    const RETURN_URL = "https://team-league.appspot.com";

    private $openid = null;
    private $return_url = "/";

    /**
     * set return url
     * @param $url
     */
    public function set_return_url($url)
    {
        $this->return_url = $url;
    }

    /**
     * オープンIDを取得
     * @throws ErrorException
     */
    public function set_openid()
    {
        $this->openid            = new LightOpenID(self::DOMAIN);
        $this->openid->identity  = self::OPEN_ID_URL;
        $this->openid->returnUrl = self::RETURN_URL . $this->return_url;
    }

    /**
     * 認証用URL取得
     * @return mixed
     */
    public function get_auth_url()
    {
        if (empty($this->openid))
        {
            $this->set_openid();
        }

        return $this->openid->authUrl();
    }

    /**
     * 認証結果取得
     * @return mixed
     */
    public function get_auth_mode()
    {
        if (empty($this->openid))
        {
            $this->set_openid();
        }

        return $this->openid->mode;
    }

    /**
     * steam_id 取得
     * @return mixed
     */
    public function get_profile()
    {
        if ($this->openid->validate())
        {
            $claimed = str_replace("https://steamcommunity.com/openid/id/", "", $this->openid->identity);
            $profile = $this->get_profile_by_steam($claimed);
            if (!empty($profile))
            {
                return $profile;
            }
        }

        return null;
    }

    /**
     * 認証済みかどうか
     * (cancel以外は認証済み)
     * @return bool
     */
    public function is_authenticated()
    {
        if (empty($this->openid))
        {
            $this->set_openid();
        }

        if ($this->openid->mode)
        {
            return true;
        }

        return false;
    }

    /**
     * Steamプロフィールを取得
     * @param $id
     * @return null
     */
    private function get_profile_by_steam($id)
    {
        $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$_SERVER['STEAM_API_KEY']."&steamids=".$id;
        $res = json_decode(mb_convert_encoding(file_get_contents($url), 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN'));

        if (isset($res->response->players) && isset($res->response->players[0]))
        {
            return $res->response->players[0];
        }

        return null;
    }
}
