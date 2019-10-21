<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_member_platforms
 */
class T_member_platforms extends Tran_model
{
    const MMR_CACHE_HOUR = 4;

    /**
     * get_by_member_id
     * @param int $member_id
     * @return array
     */
    public function get_by_member_id($member_id)
    {
        $sql = "
            SELECT
                *
            FROM
                `{$this->_table}`
            WHERE
                `t_member_id` = ? AND del_flg = ?;
        ";

        return $this->query($sql, [$member_id, FLG_OFF]);
    }

    /**
     * 指定されたプラットフォームIDを取得
     * @param $m_platform_id
     * @param $pfid
     * @return mixed
     * @throws Exception
     */
    public function get_by_platform_id($m_platform_id, $pfid)
    {
        $sql = "
            SELECT
                *
            FROM
                `{$this->_table}`
            WHERE
                `m_platform_id` = ? AND `pfid` = ? AND del_flg = ?
            LIMIT 1;
        ";

        return $this->query_one($sql, [$m_platform_id, $pfid, FLG_OFF]);
    }

    /**
     * レート検索用
     * @param $conditions
     * @return array
     * @throws Exception
     */
    public function get_by_conditions($conditions)
    {
        $params = [];
        $conditions_where = "";

        if (isset($conditions["ranks"]) && !empty($conditions["ranks"]))
        {
            $conditions_where .= "(`standard_rank` IN(\"".implode('","', $conditions["ranks"])."\")) AND ";
        }

        $conditions_where .= " `del_flg` = ?";
        $params[] = FLG_OFF;

        $sql = "
            SELECT
              *
            FROM
                `{$this->_table}`
            WHERE
              {$conditions_where}
        ";

        return $this->query($sql, $params);
    }

    /**
     * 複数レコード登録
     * @param $member_id
     * @param $platform_ids
     */
    public function register($member_id, $platform_ids)
    {
        if (empty($platform_ids))
        {
            return;
        }

        foreach($platform_ids as $m_platform_id => $pfid)
        {
            if (empty($pfid))
            {
                continue;
            }

            // 同じプラットフォームで同じIDのユーザーを許可しない場合は、コメントアウトを外す
            // if (!empty($this->get_by_platform_id($m_platform_id, $pfid)))
            // {
            //     throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
            // }

            $this->insert([
                "t_member_id"   => $member_id,
                "m_platform_id" => $m_platform_id,
                "pfid"          => $pfid,
                "casual_mmr"    => "0",
                "duel_mmr"      => "0",
                "duel_rank"     => "Unranked",
                "doubles_mmr"   => "0",
                "doubles_rank"  => "Unranked",
                "standard_mmr"  => "0",
                "standard_rank" => "Unranked",
                "created"       => now(),
                "modified"      => now(),
            ]);
        }
    }

    /**
     * プラットフォームデータ更新
     * @param $member_id
     * @param $m_platform_id
     * @param $pfid
     * @throws Exception
     */
    public function update_platform($member_id, $m_platform_id, $pfid)
    {
        // 同じプラットフォームで同じIDのユーザーを許可しない場合は、コメントアウトを外す
        // if (!empty($this->get_by_platform_id($m_platform_id, $pfid)))
        // {
        //     throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
        // }

        $sql = "
            UPDATE
                `{$this->_table}`
            SET
                `pfid` = ?,
                `modified` = ?
            WHERE
                `t_member_id` = ? AND
                `m_platform_id` = ? AND
                `del_flg` = ?
        ";

        $this->query_to_master($sql, [$pfid, now(), $member_id, $m_platform_id, FLG_OFF]);
    }

    /**
     * @param $member_id
     * @param $m_platform_id
     * @param $mmr
     * @throws Exception
     */
    public function update_mmr($member_id, $m_platform_id, $mmr)
    {
        $sql = "
            UPDATE
                `{$this->_table}`
            SET
                `casual_mmr`    = ?,
                `duel_mmr`      = ?,
                `duel_rank`     = ?,
                `doubles_mmr`   = ?,
                `doubles_rank`  = ?,
                `standard_mmr`  = ?,
                `standard_rank` = ?,
                `modified`      = ?
            WHERE
                `t_member_id` = ? AND
                `m_platform_id` = ? AND
                `del_flg` = ?
        ";

        $params = [
            $mmr["casual"]["mmr"],
            $mmr["duel"]["mmr"],
            $mmr["duel"]["rank"],
            $mmr["doubles"]["mmr"],
            $mmr["doubles"]["rank"],
            $mmr["standard"]["mmr"],
            $mmr["standard"]["rank"],
            now(),
            $member_id,
            $m_platform_id,
            FLG_OFF
        ];

        $this->query_to_master($sql, $params);
    }
}
