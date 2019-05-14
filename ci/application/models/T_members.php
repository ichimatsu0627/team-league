<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_members
 */
class T_members extends Tran_model
{
    /**
     * get_by_userid
     * @param int $user_id
     * @return object|null
     */
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
