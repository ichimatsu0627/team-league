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
        $member = $this->add_max_rate($member);

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
            $members[$k] = $this->add_max_rate($member);
        }

        return array_column($members, null, "id");
    }

    /**
     * @param $keyword
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function get_members_by_keyword($keyword, $limit = DEFAULT_PAGER_PER, $offset = 0)
    {
        $members = $this->CI->T_members->get_by_keyword($keyword, $limit, $offset);

        if (empty($members))
        {
            return [];
        }

        foreach($members as $k => $member)
        {
            $members[$k] = $this->add_platform($member);
            $members[$k] = $this->add_max_rate($member);
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
        $member = $this->add_max_rate($member);

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
                // if (!empty($this->CI->T_member_platforms->get_by_platform_id($m_platform_id, $pfid)))
                // {
                //     throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
                // }

                $this->CI->T_member_platforms->update_platform($member->id, $m_platform_id, $pfid);
                $this->update_mmr($member->id, $m_platform_id, $pfid);
            }
        }

        $this->CI->T_member_platforms->register($member->id, $insert_platform_data);
        foreach($insert_platform_data as $m_platform_id => $pfid)
        {
            $this->update_mmr($member->id, $m_platform_id, $pfid);
        }
    }

    /**
     * MMR更新
     * @param $member_id
     * @param $m_platform_id
     * @param $pfid
     * @throws Exception
     */
    public function update_mmr($member_id, $m_platform_id, $pfid)
    {
        if (!empty($pfid) && $this->CI->scraping->is_target($m_platform_id) && !$this->CI->scraping->exists($pfid, $m_platform_id))
        {
            throw new Exception("not found platform player", Page::CODE_FAILED_BY_NOT_FOUND);
        }

        $mmr = $this->CI->scraping->get_mmr($pfid, $m_platform_id);
        $this->CI->T_member_platforms->update_mmr($member_id, $m_platform_id, $mmr);
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
        $this->CI->T_member_platforms->register($id, $platform_data);

        // MMR更新
        foreach($platform_data as $m_platform_id => $pfid)
        {
            $this->update_mmr($id, $m_platform_id, $pfid);
        }

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

        foreach($platforms as $key => $platform)
        {
            if (strtotime($platform->modified." + ".T_member_platforms::MMR_CACHE_HOUR." hours") < strtotime(now()))
            {
                $this->update_mmr($member->id, $platform->m_platform_id, $platform->pfid);
                $platforms[$key] = $this->CI->T_member_platforms->get_by_id($platform->id);
            }
        }

        $member->platforms = array_column($platforms, null, "m_platform_id");

        return $member;
    }

    /**
     * 各プラットフォーム内で最大ランクをセットする
     * @param $member
     * @return obj
     */
    private function add_max_rate($member)
    {
        if (!isset($member->platforms))
        {
            return $member;
        }

        $max_rates = [
            "casual_mmr"    => 0,
            "duel_mmr"      => 0,
            "duel_rank"     => "Unranked",
            "doubles_mmr"   => 0,
            "doubles_rank"  => "Unranked",
            "standard_mmr"  => 0,
            "standard_rank" => "Unranked",
        ];
        foreach ($member->platforms as $platform)
        {
            if ($platform->casual_mmr > $max_rates["casual_mmr"])
            {
                $max_rates["casual_mmr"] = $platform->casual_mmr;
            }

            if ($platform->duel_mmr > $max_rates["duel_mmr"] && $platform->duel_rank != "Unranked")
            {
                $max_rates["duel_mmr"]  = $platform->duel_mmr;
                $max_rates["duel_rank"] = $platform->duel_rank;
            }

            if ($platform->doubles_mmr > $max_rates["doubles_mmr"] && $platform->doubles_rank != "Unranked")
            {
                $max_rates["doubles_mmr"]  = $platform->doubles_mmr;
                $max_rates["doubles_rank"] = $platform->doubles_rank;
            }

            if ($platform->standard_mmr > $max_rates["standard_mmr"] && $platform->standard_rank != "Unranked")
            {
                $max_rates["standard_mmr"]  = $platform->standard_mmr;
                $max_rates["standard_rank"] = $platform->standard_rank;
            }
        }

        $member->max_casual_mmr    = $max_rates["casual_mmr"];
        $member->max_duel_mmr      = $max_rates["duel_mmr"];
        $member->max_duel_rank     = $max_rates["duel_rank"];
        $member->max_doubles_mmr   = $max_rates["doubles_mmr"];
        $member->max_doubles_rank  = $max_rates["doubles_rank"];
        $member->max_standard_mmr  = $max_rates["standard_mmr"];
        $member->max_standard_rank = $max_rates["standard_rank"];

        return $member;
    }
}
