<?php
require_once(APPPATH."models/Base_model.php");

class Tran_model extends Base_model
{
    /*
     * ロックを取得してSELECTする
     * @param $id プライマリキー
     * @return ロックを取得したデータのID
     */
    public function get_lock($id){

        $sql  = "SELECT id FROM ".$this->_table." WHERE id = ? LIMIT 1 FOR UPDATE";

        $args = array(
            $id,
        );
        $data = $this->query($sql, $args);
        return $data[0]->id;
    }

    public function trans_begin()
    {
        $this->CI->db->trans_begin();
    }

    public function trans_commit()
    {
        $this->CI->db->trans_commit();
    }

    public function trans_rollback()
    {
        $this->CI->db->trans_rollback();
    }

}
