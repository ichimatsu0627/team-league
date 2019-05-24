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
}
