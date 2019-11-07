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
     * 様々な条件で検索
     * @param string $conditions
     * @param int    $limit
     * @param int    $offset
     * @return array
     * @throws Exception
     */
    public function get_by_conditions($conditions, $limit, $offset)
    {

        $params = [];
        $conditions_where = "";

        if (isset($conditions["keyword"]) && !empty($conditions["keyword"]))
        {
            $params[] = "%".$conditions["keyword"]."%";
            $conditions_where .= "`name` LIKE ? AND ";
        }

        if (isset($conditions["mmr_from"]) && !empty($conditions["mmr_from"]))
        {
            $params[] = $conditions["mmr_from"];
            $conditions_where .= "`standard_mmr_avr` >= ? AND ";
        }

        if (isset($conditions["mmr_to"]) && !empty($conditions["mmr_to"]))
        {
            $params[] = $conditions["mmr_to"];
            $conditions_where .= "`standard_mmr_avr` <= ? AND ";
        }

        $sql = "
            SELECT
              *
            FROM
              `{$this->_table}`
            WHERE
              {$conditions_where} `del_flg` = ? 
            ORDER BY `id` DESC 
            LIMIT ?, ?
        ";

        $params = array_merge($params, [FLG_OFF, $offset, $limit]);

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

        if (isset($conditions["keyword"]) && !empty($conditions["keyword"]))
        {
            $params[] = "%".$conditions["keyword"]."%";
            $conditions_where .= "`name` LIKE ? AND ";
        }

        $sql = "
            SELECT
              count(id) as cnt
            FROM
              `{$this->_table}`
            WHERE
              {$conditions_where} `del_flg` = ?
        ";

        $params = array_merge($params, [FLG_OFF]);

        $result = $this->query_one($sql, $params);

        if (empty($result))
        {
            return 0;
        }

        return $result->cnt;
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
