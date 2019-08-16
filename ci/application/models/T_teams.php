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
            LIMIT ?, ?
        ";

        $params = array_merge($params, [FLG_OFF, $offset, $limit]);

        return $this->query($sql, $params);
    }

    /**
     * チームの平均MMRを更新
     * @param $id
     * @param $standard_mmr_avr
     * @throws Exception
     */
    public function update_standard_mmr_avr($id, $standard_mmr_avr)
    {
        $sql = "
            UPDATE
              `{$this->_table}`
            SET
              `standard_mmr_avr` = ?,
              `modified` = ?
            WHERE
              `id` = ?
        ";

        $params = [
            $standard_mmr_avr,
            now(),
            $id
        ];

        $this->query_to_master($sql, $params);
    }
}
