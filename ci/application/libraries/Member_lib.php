<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Member Library
 */
class Member_lib extends Base_lib
{
    protected $_models = [
        "T_members",
        "T_member_platforms",
        "T_member_locks",
    ];

    protected $_libraries = [
        "scraping",
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
     * @param int $id
     * @return mixed
     */
    public function get_member($id)
    {
        $member = $this->CI->T_members->get_by_id($id);
        $member = $this->add_platform($member);

        return $member;
    }

    /**
     * 会員情報をまとめて取得
     * @param $ids
     * @return array
     */
    public function get_members($ids)
    {
        if (empty($ids))
        {
            return [];
        }

        $members = $this->CI->T_members->get_by_ids($ids);

        if (empty($members))
        {
            return [];
        }

        foreach($members as $k => $member)
        {
            $members[$k] = $this->add_platform($member);
        }

        return array_column($members, null, "id");
    }

    /**
     * ユーザーIDで会員データを取得
     * @param int $login_id
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
     * @return object|null
     */
    public function get_id_by_session()
    {
        return $this->CI->session->userdata(self::SESSION_KEY);
    }

    /**
     * 入力値 チェック
     * @param array $data
     * @return bool
     */
    public function validate_register_memberdata($data)
    {
        foreach($data as $key => $value)
        {
            if (in_array($key, T_members::REQUIRED_COLUMNS))
            {
                if (empty($value))
                {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * ユーザーIDが重複してないか
     * @param int $login_id
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
     * @param string $email
     * @return bool
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
     * @param array $platforms
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
     * @param object $member
     * @param array  $member_data
     * @param array  $platform_data
     */
    public function update($member, $member_data, $platform_data)
    {
        $update_member_data = array_filter($member_data, function($v, $k) use($member) {
            if (in_array($k, T_members::REQUIRED_COLUMNS) && empty($v)) return false;
            if ($k == "password") return false; // 専用の更新にする
            if ($member->{$k} == $v) return false;
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $update_platform_data = array_filter($platform_data, function($v, $k) use($member) {
            if (!isset($member->platforms[$k])) return false;
            if ($member->platforms[$k]->pfid == $v) return false;
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
                    throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
                }

                $this->CI->T_member_platforms->update_platform($member->id, $m_platform_id, $pfid);
            }
        }

        $this->CI->T_member_platforms->register($member->id, $insert_platform_data, $this->CI->scraping);
    }

    /**
     * 登録
     * @param array $member_data
     * @param array $platform_data
     * @return int
     */
    public function register($member_data, $platform_data)
    {
        // 会員データ作成
        $id = $this->CI->T_members->insert([
            "login_id" => $member_data["login_id"],
            "name"     => $member_data["name"],
            "email"    => $member_data["email"] ?? "",
            "password" => $member_data["password"],
            "twitter"  => $member_data["twitter"] ?? "",
            "discord"  => $member_data["discord"] ?? "",
            "created"  => now(),
            "modified" => now(),
        ]);

        // プラットフォームデータ作成
        $this->CI->T_member_platforms->register($id, $platform_data, $this->CI->scraping);

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
        $member->platforms = array_column($platforms, null, "m_platform_id");

        return $member;
    }
}
