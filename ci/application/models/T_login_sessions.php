<?php
require_once(APPPATH."models/Tran_model.php");

/**
 * Class T_login_sessions
 */
class T_login_sessions extends Tran_model
{
    /**
     * Tokenでレコード取得
     * @param $token
     * @return array
     * @throws Exception
     */
    public function get_member_id_by_token($token)
    {
        $sql = "
            SELECT
              t_member_id
            FROM
              t_login_sessions
            WHERE
              token = ? AND expired_limit > ?
            LIMIT 1
        ";

        $param = [
            $token,
            now()
        ];

        $data = $this->query($sql, $param);

        return $data[0]->t_member_id ?? null;
    }

    /**
     * トークンデータが残ってるか
     * @param $member_id
     * @return bool
     * @throws Exception
     */
    public function has_token_by_member_id($member_id)
    {
        $sql = "
          SELECT
            id
          FROM
            t_login_sessions
          WHERE
            t_member_id = ?
          LIMIT
            1
        ";

        $param = [
            $member_id
        ];

        $data = $this->query($sql, $param);

        return !empty($data);
    }

    /**
     * @param $member_id
     * @return $token
     */
    public function register($member_id)
    {
        $expired = new DateTime();
        $expired->modify('+'.SESSION_EXPIRED_H.' hours');

        $token = sha1(uniqid(rand(), true));

        $this->insert([
            "t_member_id"   => $member_id,
            "token"         => $token,
            "expired_limit" => $expired->format('Y-m-d H:i:s'),
        ]);

        return $token;
    }

    /**
     * トークンリセット
     * @param $member_id
     * @throws Exception
     */
    public function reflesh($member_id)
    {
        $sql = "
          DELETE
          FROM
            t_login_sessions
          WHERE
            t_member_id = ?
        ";

        $param = [
            $member_id
        ];

        $this->query_to_master($sql, $param);
    }
}

