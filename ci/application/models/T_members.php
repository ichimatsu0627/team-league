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

    /**
     * @param string $keyword
     * @param int    $limit
     * @param int    $offset
     * @return array
     * @throws Exception
     */
    public function get_by_keyword($keyword, $limit, $offset)
    {
        $params = [];
        $keyword_where = "";

        if (!empty($keyword))
        {
            $params[] = "%".$keyword."%";
            $keyword_where = "`name` LIKE ? AND ";
        }

        $sql = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              {$keyword_where} `del_flg` = ?
            ORDER BY `id` DESC 
            LIMIT ?, ?
        ";

        $params = array_merge($params, [FLG_OFF, $offset, $limit]);

        return $this->query($sql, $params);
    }
}
