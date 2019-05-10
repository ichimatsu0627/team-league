<?php
require_once(APPPATH."models/Tran_model.php");

class T_members extends Tran_model
{
    public function get_by_userid($user_id)
    {
        $sql = "
            SELECT
                *
            FROM
                `{$this->_table}`
            WHERE
                `user_id` = ?
            LIMIT 1;
        ";

        return $this->query_one($sql, [$user_id]);
    }
}
