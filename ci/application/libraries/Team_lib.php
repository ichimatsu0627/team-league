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
        "T_team_locks",
    ];

    protected $_libraries = [
        "member_lib",
    ];

    /**
     * チーム情報を取得
     * @param int $id
     * @return obj|null
     */
    public function get_team($id, $add_members = true)
    {
        $team = $this->CI->T_teams->get_by_id($id);
        if ($add_members)
        {
            $team->members = $this->get_members($id);
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
     * 登録
     * @param array $team_data
     * @param array $team_member_data
     * @return int
     */
    public function regist($team_data, $team_member_data)
    {
        $id = $this->CI->T_teams->insert([
            "name"        => $team_data["name"],
            "description" => $team_data["description"] ?: "",
            "created"     => now(),
            "modified"    => now(),
        ]);

        $this->CI->T_team_members->regist($id, $team_member_data);

        $this->CI->T_team_locks->insert(["id" => $id, "created" => now(), "modified" => now()]);

        return $id;
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

}
