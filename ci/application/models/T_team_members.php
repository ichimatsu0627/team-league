<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_team_members
 */
class T_team_members extends Tran_model
{
    const ROLE_LEADER     = 1;
    const ROLE_SUB_LEADER = 2;
    const ROLE_MEMBER     = 3;
    const ROLE_LIST = [
        1 => "Leader",
        2 => "Sub-Leader",
        0 => "Member",
    ];

    /**
     * チームメンバー一覧を取得
     * @param $t_team_id
     * @return []
     */
    public function get_by_team_id($t_team_id)
    {
        $sql = "
            SELECT
              *
            FROM
              t_team_members
            WHERE
              t_team_id = ? AND del_flg = ?
        ";

        $params = [$t_team_id, FLG_OFF];

        return $this->query($sql, $params);
    }

    /**
     * 個人のチームデータを取得
     * @param $member_id
     * @return array
     */
    public function get_by_member_id($member_id)
    {
        $sql = "
            SELECT
              *
            FROM
              t_team_members
            WHERE
              t_member_id = ? AND del_flg = ?
        ";

        $params = [$member_id, FLG_OFF];

        $t_team_members = $this->query($sql, $params);

        if (!empty($t_team_members))
        {
            $t_team_members = array_column($t_team_members, null, "t_team_id");
        }

        return $t_team_members;
    }

    /**
     * 指定したロールのチームメンバーデータ取得
     * @param $t_team_id
     * @param $role
     * @return mixed|null
     */
    public function get_by_team_role($t_team_id, $role)
    {
        $sql = "
            SELECT
              *
            FROM
              t_team_members
            WHERE
              t_team_id = ? AND role = ? AND del_flg = ?
        ";

        $params = [$t_team_id, $role, FLG_OFF];

        return $this->query($sql, $params);
    }

    /**
     * 権限更新
     * @param $member_id
     * @param $team_id
     * @param $role
     * @throws Exception
     */
    public function update_role($member_id, $team_id, $role)
    {
        $sql = "
            UPDATE
              t_team_members
            SET
              role = ?
            WHERE
              t_member_id = ? AND 
              t_team_id = ?
        ";

        $params = [$member_id, $team_id, $role];

        $this->query_to_master($sql, $params);
    }

    /**
     * 登録
     * @param int $id
     * @param array $team_member_data
     * @throws Exception
     */
    public function regist($id, $team_member_data)
    {
        if (empty($team_member_data))
        {
            return;
        }

        foreach($team_member_data as $team_member_id)
        {
            if (empty($team_member_id))
            {
                continue;
            }

            $tmp = $this->get_by_member_id($team_member_id);
            // 同じチームなら見なかったことにする
            if (isset($tmp[$id]))
            {
                continue;
            }

            $this->insert([
                "t_member_id" => $team_member_id,
                "t_team_id" => $id,
                "created" => now(),
                "modified" => now(),
            ]);
        }
    }
}
