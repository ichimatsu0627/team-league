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
              `id` IN(".implode(",", $ids).") AND `del_flg` = ?
        ";

        return $this->query($sql, [FLG_OFF]);
    }

    /**
     * キーワード検索
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

    /**
     * 様々な条件で検索
     * @param array  $conditions
     * @param int    $limit
     * @param int    $offset
     * @return array
     * @throws Exception
     */
    public function get_by_conditions($conditions, $limit = null, $offset = 0)
    {
        $params = [];
        $conditions_where = "";

        if (isset($conditions["ids"]))
        {
            if (empty($conditions["ids"]))
            {
                return [];
            }
            $conditions_where .= "`id` IN(".implode(",",$conditions["ids"]).") AND ";
        }

        if (isset($conditions["keyword"]) && !empty($conditions["keyword"]))
        {
            $params[] = "%".$conditions["keyword"]."%";
            $params[] = "%".$conditions["keyword"]."%";
            $params[] = "%".$conditions["keyword"]."%";
            $conditions_where .= "(`name` LIKE ? OR `discord` LIKE ? OR `twitter` LIKE ?) AND ";
        }

        $conditions_where .= "`del_flg` = ?";
        $params[] = FLG_OFF;

        $limit_sql = "";
        if (!empty($limit))
        {
            $limit_sql = " LIMIT ?, ?";
            $params[] = $offset;
            $params[] = $limit;
        }

        $sql = "
            SELECT
              *
            FROM
                `{$this->_table}`
            WHERE
              {$conditions_where}
            ORDER BY `id` DESC 
            {$limit_sql}
        ";

        return $this->query($sql, $params);
    }

    /**
     * キーワード検索 件数
     * @param array  $conditions
     * @return int
     * @throws Exception
     */
    public function count_by_conditions($conditions)
    {
        $params = [];
        $conditions_where = "";

        if (isset($conditions["ids"]) && !empty($conditions["ids"]))
        {
            $conditions_where .= "`id` IN(".implode(",",$conditions["ids"]).") AND ";
        }

        if (isset($conditions["keyword"]) && !empty($conditions["keyword"]))
        {
            $params[] = "%".$conditions["keyword"]."%";
            $params[] = "%".$conditions["keyword"]."%";
            $params[] = "%".$conditions["keyword"]."%";
            $conditions_where .= "(`name` LIKE ? OR `discord` LIKE ? OR `twitter` LIKE ?) AND ";
        }

        $conditions_where .= "`del_flg` = ?";
        $params[] = FLG_OFF;

        $sql = "
            SELECT
              count(id) as cnt
            FROM
              `{$this->_table}`
            WHERE
              {$conditions_where}
        ";

        $result =  $this->query_one($sql, $params);

        if (empty($result))
        {
            return 0;
        }

        return $result->cnt;
    }
}
