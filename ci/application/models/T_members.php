<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_members
 */
class T_members extends Tran_model
{
    const REQUIRED_COLUMNS = [
        "login_id",
        "name",
        "password",
    ];

    /**
     * get_by_login_id
     * @param int $login_id
     * @return object|null
     */
    public function get_by_login_id($login_id)
    {
        $sql = "
            SELECT
                *
            FROM
                `{$this->_table}`
            WHERE
                `login_id` = ? AND del_flg = ?
            LIMIT 1;
        ";

        return $this->query_one($sql, [$login_id, FLG_OFF]);
    }

    /**
     * get_by_login_id
     * @param int $login_id
     * @return object|null
     */
    public function get_by_email($email)
    {
        $sql = "
            SELECT
                *
            FROM
                `{$this->_table}`
            WHERE
                `email` = ? AND del_flg = ?
            LIMIT 1;
        ";

        return $this->query_one($sql, [$email, FLG_OFF]);
    }

    /**
     * get_by_ids
     * @param $ids
     * @return array
     */
    public function get_by_ids($ids)
    {
        $sql = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              `id` IN(".implode(",", $ids).") AND del_flg = ?
        ";

        return $this->query($sql, [FLG_OFF]);
    }
}
