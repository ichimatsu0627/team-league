<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_team_members
 */
class T_team_members extends Tran_model
{
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
     * @return obj|null
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
            LIMIT 1
        ";

        $params = [$member_id, FLG_OFF];

        return $this->query_one($sql, $params);
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
            if (!empty($tmp))
            {
                // 同じチームなら見なかったことにする
                if ($tmp->t_team_id == $id)
                {
                    continue;
                }
                throw new Exception("already joined mID:".$team_member_id." teamID:".$tmp->t_team_id, Page::CODE_FAILED_BY_JOINED);
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
