<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Team Library
 */
class Team_lib extends Base_lib
{
    protected $_models = [
        "T_teams",
        "T_team_members",
        "T_team_requests",
        "T_team_locks",
    ];

    protected $_libraries = [
        "member_lib",
    ];

    const MAX_JOIN_TEAM_PER_MEMBER = 5;

    /**
     * チーム情報を取得
     * @param int $id
     * @return obj|null
     */
    public function get_team($id, $add_members = true)
    {
        $team = $this->CI->T_teams->get_by_id($id);
        $team->members = [];
        $team->standard_mmr_avr = 0;
        if (!empty($team) && $add_members)
        {
            $team = $this->add_detail($team);
        }

        return $team;
    }

    /**
     * 複数チーム取得
     * @param array $ids
     * @return array
     */
    public function get_teams($ids, $add_members = false)
    {
        $teams = [];
        foreach($ids as $id)
        {
            $teams[$id] = $this->get_team($id, $add_members);
        }
        return $teams;
    }

    /**
     * @param $keyword
     * @param $limit
     * @param $offset
     * @return array
     * @throws Exception
     */
    public function get_teams_by_keyword($keyword, $limit = DEFAULT_PAGER_PER, $offset = 0)
    {
        $teams = $this->CI->T_teams->get_by_keyword($keyword, $limit, $offset);

        foreach($teams as $key => $team)
        {
            $teams[$key] = $this->add_detail($team);
        }

        return $teams;
    }

    /**
     * チーム情報を取得
     * @param int $id
     * @return array
     */
    public function get_teams_by_member_id($member_id, $add_members = false)
    {
        $t_team_members = $this->CI->T_team_members->get_by_member_id($member_id);
        if (empty($t_team_members))
        {
            return [];
        }
        return $this->get_teams(array_keys($t_team_members), $add_members);
    }

    /**
     * チームメンバー取得
     * @param $id
     */
    public function get_members($id)
    {
        $members = $this->CI->T_team_members->get_by_team_id($id);

        foreach($members as $key => $member)
        {
            $members[$key]->detail = $this->CI->member_lib->get_member($member->t_member_id);
        }
        $members = array_column($members, null, "t_member_id");

        return $members;
    }

    /**
     * 指定された会員がチームメンバーかどうか
     * @param $member_id
     * @param $team
     * @return bool
     */
    public function is_team_member($member_id, $team)
    {
        return isset($team->members[$member_id]);
    }

    /**
     * 管理者かどうか
     * @param $member_id
     * @param $team
     * @return bool
     */
    public function is_admin($member_id, $team)
    {
        return $this->is_leader($member_id, $team) || $this->is_sub_leader($member_id, $team);
    }

    /**
     * リーダーかどうか
     * @param $member_id
     * @param $team
     * @return bool
     */
    public function is_leader($member_id, $team)
    {
        if (!$this->is_team_member($member_id, $team))
        {
            return false;
        }

        return $team->members[$member_id]->role == T_team_members::ROLE_LEADER;
    }

    /**
     * サブリーダーかどうか
     * @param $member_id
     * @param $team
     * @return bool
     */
    public function is_sub_leader($member_id, $team)
    {
        if (!$this->is_team_member($member_id, $team))
        {
            return false;
        }

        return $team->members[$member_id]->role == T_team_members::ROLE_SUB_LEADER;
    }


    /**
     * @param $id
     * @return mixed|null
     */
    public function get_request_by_id($id)
    {
        return $this->CI->T_team_requests->get_by_id($id);
    }

    /**
     * @param $team_id
     * @param $status
     */
    public function get_requests_by_team_id($team_id, $status = T_team_requests::STATUS_TYPE_NONE)
    {
        $requests = $this->CI->T_team_requests->get_by_team_id($team_id, $status);
        return array_column($requests, null, "t_member_id");
    }

    /**
     * @param $team_id
     * @param $member_id
     * @return obj|null
     */
    public function get_requests_by_team_member($team_id, $member_id)
    {
        $request = $this->CI->T_team_requests->get_by_team_member($team_id, $member_id);
        return $request;
    }

    /**
     * @param $team_id
     * @param $member_id
     * @return bool
     */
    public function is_already_request($team_id, $member_id)
    {
        return $this->get_requests_by_team_member($team_id, $member_id) ? true : false;
    }

    /**
     * 登録
     * @param array $team_data
     * @param array $team_member_data
     * @return int
     */
    public function register($team_data, $team_member_data)
    {
        $id = $this->CI->T_teams->insert([
            "name"        => $team_data["name"],
            "description" => $team_data["description"] ?: "",
            "created"     => now(),
            "modified"    => now(),
        ]);

        $this->register_member($id, $team_member_data);

        $this->CI->T_team_locks->insert(["id" => $id, "created" => now(), "modified" => now()]);

        return $id;
    }

    /**
     * @param $id
     * @param $team_member_data
     * @throws Exception
     */
    public function register_member($id, $team_member_data)
    {
        $this->CI->T_team_members->register($id, $team_member_data);
    }

    /**
     * 更新
     * @param $team
     * @param $team_data
     */
    public function update($team, $team_data)
    {
        $update_team_data = array_filter($team_data, function($v, $k) use ($team) {
            if (in_array($k, T_teams::REQUIRED_COLUMNS) && empty($v)) return false;
            if ($team->{$k} == $v) return false;
            return true;
        }, ARRAY_FILTER_USE_BOTH);

        if (!empty($update_team_data))
        {
            $this->CI->T_teams->update($team->id, $update_team_data);
        }
    }

    /**
     * 役職を更新する
     * @param $member_id
     * @param $team_id
     */
    public function update_member_role($team_id, $member_id, $role)
    {
        $this->CI->T_team_members->update_role($member_id, $team_id, $role);
    }

    /**
     * 参加申請を登録
     * @param $team_id
     * @param $member_id
     */
    public function register_request($team_id, $member_id)
    {
        $request = $this->CI->T_team_requests->get_by_member_id($member_id);
        $request = array_column($request, null, "t_team_id");

        if (!isset($request[$team_id]))
        {
            $this->CI->T_team_requests->insert([
                "t_team_id"   => $team_id,
                "t_member_id" => $member_id,
                "status"      => T_team_requests::STATUS_TYPE_NONE
            ]);
        }
    }

    /**
     * 参加申請を更新
     * @param $id
     * @param $status
     */
    public function update_request($id, $status)
    {
        $this->CI->T_team_requests->update($id, ["status" => $status]);
    }

    /**
     * トランザクション開始
     */
    public function begin()
    {
        return $this->CI->T_teams->trans_begin();
    }

    /**
     * コミット
     */
    public function commit()
    {
        return $this->CI->T_teams->trans_commit();
    }

    /**
     * ロールバック
     */
    public function rollback()
    {
        return $this->CI->T_teams->trans_rollback();
    }

    /**
     * ロック
     * @param $id
     */
    public function lock($id)
    {
        $this->CI->T_team_locks->get_lock($id);
    }

    /**
     * @param $team
     * @return mixed
     */
    private function add_detail($team)
    {
        $team->members = $this->get_members($team->id);
        $standard_mmr_avr = $this->get_average_mmr($team->members, "standard_mmr");

        if ($standard_mmr_avr != $team->standard_mmr_avr)
        {
            $team->standard_mmr_avr = $standard_mmr_avr;
            $this->CI->T_teams->update_standard_mmr_avr($team->id, $standard_mmr_avr);
        }

        return $team;
    }

    /**
     * @param array  $members
     * @param string $column
     * @return float
     */
    private function get_average_mmr($members, $column)
    {
        if (empty($members))
        {
            return 0;
        }

        $sum   = 0;
        $count = 0;
        foreach($members as $member)
        {
            // skip mmr 0 player
            if ($member->detail->$column <= 0)
            {
                continue;
            }

            $sum += $member->detail->$column;
            $count++;
        }

        $mmr_average = floor($sum / $count);

        return $mmr_average;
    }
}
