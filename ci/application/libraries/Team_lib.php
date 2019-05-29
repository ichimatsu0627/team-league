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
    public function get_team($id)
    {
        $team = $this->CI->T_teams->get_by_id($id);
        $team->members = $this->get_members($id);

        return $team;
    }

    /**
     * チーム情報を取得
     * @param int $id
     * @return obj|null
     */
    public function get_team_by_member_id($member_id)
    {
        $t_team_member = $this->CI->T_team_members->get_by_member_id($member_id);
        if (empty($t_team_member))
        {
            return null;
        }
        return $this->get_team($t_team_member->t_team_id);
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
