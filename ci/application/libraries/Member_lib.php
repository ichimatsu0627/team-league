<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Member Library
 *
 */
class Member_lib extends Base_lib
{
    protected $_models = [
        "T_members",
        "T_member_platforms",
    ];

    const SESSION_KEY = "member";

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 会員ID取得
     * @param $id
     * @return mixed
     */
    public function get_member($id)
    {
        $member = $this->CI->T_members->get_by_id($id);

        $platforms = $this->CI->T_member_platforms->get_by_member_id($id);
        $member->platforms = array_column($platforms, "pfid", "m_platform_id");

        return $member;
    }

    /**
     * get_id_by_session
     * @param $t_member
     */
    public function get_id_by_session()
    {
        return $this->CI->session->userdata(self::SESSION_KEY);
    }

    public function validate_regist_memberdata($data)
    {
        if (!isset($data["user_id"]) || empty($data["user_id"]))
        {
            return false;
        }

        if (!isset($data["name"]) || empty($data["name"]))
        {
            return false;
        }

        if (!isset($data["email"]) || empty($data["email"]))
        {
            return false;
        }

        if (!isset($data["password"]) || empty($data["password"]))
        {
            return false;
        }

        return true;
    }

    /**
     * ユーザーIDが重複してないか
     * @param $user_id
     * @return bool
     */
    public function exists_member($user_id)
    {
        if (!empty($this->CI->T_members->get_by_user_id($user_id)))
        {
            return true;
        }

        return false;
    }

    /**
     * 指定されたプラットフォームのIDが重複してないか
     * @param $platforms
     * @return bool
     */
    public function exists_platform_id($platforms)
    {
        foreach($platforms as $m_platform_id => $pfid)
        {
            if (!empty($this->CI->T_member_platforms->get_by_platform_id($m_platform_id, $pfid)))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * regist
     * @param array $data
     * @return null
     */
    public function regist($member_data, $platform_data)
    {
        $id = $this->CI->T_members->insert([
            "user_id"  => $member_data["use_id"],
            "name"     => $member_data["name"],
            "email"    => $member_data["email"],
            "password" => $member_data["password"],
        ]);

        if (!empty($platform_data))
        {
            foreach($platform_data as $m_platform_id => $pfid)
            {
                $this->CI->T_member_platforms->insert([
                    "t_member_id" => $id,
                    "m_platform_id" => $m_platform_id,
                    "pfid" => $pfid
                ]);
            }
        }

        return $id;
    }
}
