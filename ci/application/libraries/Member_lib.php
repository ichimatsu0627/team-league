<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Member Library
 * @package libraries
 * @property CI_Controller $CI
 */
class Member_lib extends Base_lib
{
    protected $_models = [
        "T_members",
    ];

    const SESSION_KEY = "member";

    /**
     * Login constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 会員ID取得
     * @param $id
     * @return mixed
     */
    public function get_member($id)
    {
        return $this->CI->T_members->get_by_id($id);
    }

    /**
     * save
     * @param $t_member
     */
    public function get_id_by_session()
    {
        return $this->CI->session->userdata(self::SESSION_KEY);
    }

    /**
     * regist
     * @param array $data
     * @return null
     */
    public function regist($data = [])
    {
        if (empty($data))
        {
            return null;
        }

        return $this->CI->T_members->insert($data);
    }
}
