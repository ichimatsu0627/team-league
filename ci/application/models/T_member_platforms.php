<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_member_platforms
 */
class T_member_platforms extends Tran_model
{
    /**
     * get_by_userid
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
                `t_member_id` = ?;
        ";

        return $this->query($sql, [$member_id]);
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
                `m_platform_id` = ? AND `pfid` = ?
            LIMIT 1;
        ";

        return $this->query_one($sql, [$m_platform_id, $pfid]);
    }

    /**
     * 複数レコード登録
     * @param $member_id
     * @param $platform_ids
     */
    public function regist($member_id, $platform_ids)
    {
        if (empty($platform_ids))
        {
            return;
        }

        foreach($platform_ids as $m_platform_id => $pfid)
        {
            $this->insert([
                "t_member_id" => $member_id,
                "m_platform_id" => $m_platform_id,
                "pfid" => $pfid,
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
        $sql = "
            UPDATE
                `{$this->_table}`
            SET
                `pfid` = ?
            WHERE
                `t_member_id` = ? AND `m_platform_id` = ? 
        ";

        $this->query_to_master($sql, [$pfid, $member_id, $m_platform_id]);
    }
}
