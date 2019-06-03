<?php
require_once(APPPATH."models/Tran_model.php");

class T_team_requests extends Tran_model
{
    const STATUS_TYPE_NONE     = 0;
    const STATUS_TYPE_RECEIVED = 1;
    const STATUS_TYPE_LOST     = 2;

    public function get_by_member_id($member_id, $status = self::STATUS_TYPE_NONE)
    {
        $sql = "
            SELECT
                *
            FROM
                t_team_requests
           WHERE
                t_member_id = ? AND
                status = ? AND 
                del_flg = ?
        ";

        return $this->query($sql, [$member_id, $status, FLG_OFF]);
    }

    public function get_by_team_id($team_id, $status = self::STATUS_TYPE_NONE)
    {
        $sql = "
            SELECT
                *
            FROM
                t_team_requests
           WHERE
                t_team_id = ? AND
                status = ? AND 
                del_flg = ?
        ";

        return $this->query($sql, [$team_id, $status, FLG_OFF]);
    }
}
