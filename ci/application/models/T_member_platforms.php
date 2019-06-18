<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_member_platforms
 */
class T_member_platforms extends Tran_model
{
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
     * 複数レコード登録
     * @param $member_id
     * @param $platform_ids
     * @param $scrape
     */
    public function register($member_id, $platform_ids, $scrape)
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

            if (!empty($this->get_by_platform_id($m_platform_id, $pfid)))
            {
                throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
            }


            if (!$scrape->exists($pfid, $m_platform_id))
            {
                throw new Exception("not found player", Page::CODE_FAILED_BY_NOT_FOUND);
            }

            $mmr = $scrape->get_mmr($pfid, $m_platform_id);

            $this->insert([
                "t_member_id"   => $member_id,
                "m_platform_id" => $m_platform_id,
                "pfid"          => $pfid,
                "casual_mmr"    => $mmr["casual"]["mmr"] ?? "0",
                "duel_mmr"      => $mmr["duel"]["mmr"] ?? "0",
                "duel_rank"     => $mmr["duel"]["rank"] ?? "Unranked",
                "doubles_mmr"   => $mmr["doubles"]["mmr"] ?? "0",
                "doubles_rank"  => $mmr["doubles"]["rank"] ?? "Unranked",
                "standard_mmr"  => $mmr["standard"]["mmr"] ?? "0",
                "standard_rank" => $mmr["standard"]["rank"] ?? "Unranked",
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
        if (!empty($this->get_by_platform_id($m_platform_id, $pfid)))
        {
            throw new Exception("already registered", Page::CODE_FAILED_BY_EXISTS_PLATFORM_ID);
        }

        $sql = "
            UPDATE
                `{$this->_table}`
            SET
                `pfid` = ?,
                `modified` = ?
            WHERE
                `t_member_id` = ? AND `m_platform_id` = ? AND del_flg = ?
        ";

        $this->query_to_master($sql, [$pfid, now(), $member_id, $m_platform_id, FLG_OFF]);
    }
}
