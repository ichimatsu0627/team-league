<?php
require_once(APPPATH."models/Tran_model.php");

class T_team_requests extends Tran_model
{
    const STATUS_TYPE_NONE     = 0;
    const STATUS_TYPE_RECEIVED = 1;
    const STATUS_TYPE_LOST     = 2;

    /**
     * @param $member_id
     * @param int $status
     * @return array
     * @throws Exception
     */
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

    /**
     * @param $team_id
     * @param int $status
     * @return array
     * @throws Exception
     */
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

    /**
     * @param $team_id
     * @param $member_id
     * @param int $status
     * @return mixed|null
     */
    public function get_by_team_member($team_id, $member_id, $status = self::STATUS_TYPE_NONE)
    {
        $sql = "
            SELECT
                *
            FROM
                t_team_requests
           WHERE
                t_team_id = ? AND
                t_member_id = ? AND
                status = ? AND 
                del_flg = ?
            LIMIT
                1
        ";

        return $this->query_one($sql, [$team_id, $member_id, $status, FLG_OFF]);
    }

}
