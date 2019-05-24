<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_members
 */
class T_members extends Tran_model
{
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
}
