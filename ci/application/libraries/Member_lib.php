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
        "T_member_locks",
    ];

    const SESSION_KEY = "member";

    const PASSWORD_CHARA_MIN = 6;
    const PASSWORD_CHARA_MAX = 20;

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 会員IDで会員データを取得
     * @param $id
     * @return mixed
     */
    public function get_member($id)
    {
        $member = $this->CI->T_members->get_by_id($id);
        $member = $this->add_platform($member);

        return $member;
    }

    /**
     * ユーザーIDで会員データを取得
     * @param $login_id
     * @return object
     */
    public function get_by_login_id($login_id)
    {
        $member = $this->CI->T_members->get_by_login_id($login_id);
        $member = $this->add_platform($member);

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

    /**
     * 入力値 チェック
     * @param $data
     * @return bool
     */
    public function validate_regist_memberdata($data)
    {
        if (!isset($data["login_id"]) || empty($data["login_id"]))
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
     * @param $login_id
     * @return bool
     */
    public function exists_member_by_login_id($login_id)
    {
        if (!empty($this->CI->T_members->get_by_login_id($login_id)))
        {
            return true;
        }

        return false;
    }

    /**
     * メールアドレスが重複していないか
     * @param $email
     *
     */
    public function exists_member_by_email($email)
    {
        if (!empty($this->CI->T_members->get_by_email($email)))
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
     * 更新
     * @param $member
     * @param $member_data
     * @param $platform_data
     */
    public function update($member, $member_data, $platform_data)
    {
        $update_member_data = array_filter($member_data, function($v, $k) use($member) {
            if ($v == null) return false;
            if ($k == "password") return false; // 専用の更新にする
            if ($member->{$k} == $v) return false;
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $update_platform_data = array_filter($platform_data, function($v, $k) use($member) {
            if (!isset($member->platforms[$k])) return false;
            if ($member->platforms[$k] == $v) return false;
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $insert_platform_data = array_filter($platform_data, function($v, $k) use($member) {
            if ($v == null) return false;
            if (isset($member->platforms[$k])) return false;
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        if (!empty($update_member_data))
        {
            $this->CI->T_members->update($member->id, array_merge($update_member_data, ["modified" => now()]));
        }

        if (!empty($update_platform_data))
        {
            foreach($update_platform_data as $m_platform_id => $pfid)
            {
                if (!empty($this->CI->T_member_platforms->get_by_platform_id($m_platform_id, $pfid)))
                {
                    throw new Exception("already registed", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
                }

                $this->CI->T_member_platforms->update_platform($member->id, $m_platform_id, $pfid);
            }
        }

        if (!empty($insert_platform_data))
        {
            $this->CI->T_member_platforms->regist($member->id, $insert_platform_data);
        }
    }

    /**
     * 登録
     * @param array $data
     * @return int
     */
    public function regist($member_data, $platform_data)
    {
        // 会員データ作成
        $id = $this->CI->T_members->insert([
            "login_id"  => $member_data["login_id"],
            "name"     => $member_data["name"],
            "email"    => $member_data["email"],
            "password" => $member_data["password"],
            "created"  => now(),
            "modified" => now(),
        ]);

        // プラットフォームデータ作成
        $this->CI->T_member_platforms->regist($id, $platform_data);

        // ロックデータ作成
        $this->CI->T_member_locks->insert(["id" => $id, "created" => now(), "modified" => now()]);

        return $id;
    }

    /**
     * トランザクション開始
     */
    public function begin()
    {
        return $this->CI->T_members->trans_begin();
    }

    /**
     * コミット
     */
    public function commit()
    {
        return $this->CI->T_members->trans_commit();
    }

    /**
     * ロールバック
     */
    public function rollback()
    {
        return $this->CI->T_members->trans_rollback();
    }

    /**
     * ロック
     * @param $id
     */
    public function lock($id)
    {
        $this->CI->T_member_locks->get_lock($id);
    }

    /**
     * プラットフォーム情報を追加する
     * @param $member
     * @return object
     */
    private function add_platform($member)
    {
        if (empty($member))
        {
            return $member;
        }

        $platforms = $this->CI->T_member_platforms->get_by_member_id($member->id);
        $member->platforms = array_column($platforms, "pfid", "m_platform_id");

        return $member;
    }
}
