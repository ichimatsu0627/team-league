<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_teams
 */
class T_teams extends Tran_model
{
    const REQUIRED_COLUMNS = [
        "name"
    ];

    /**
     * @param string $keyword
     * @param int    $limit
     * @param int    $offset
     * @return array
     * @throws Exception
     */
    public function get_by_keyword($keyword, $limit, $offset)
    {
        $sql = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              `name` LIKE ? AND `del_flg` = ? 
            LIMIT ?, ?
        ";

        $params = [
            "%".$keyword."%",
            FLG_OFF,
            $offset,
            $limit,
        ];

        return $this->query($sql, $params);
    }
}
