<?php

/**
 * Class Base_controller
 *
 */
class Base_controller extends CI_Controller
{
    /**
     * Base_controller constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load_db();
        $this->load->library("layout");
    }

    /**
     * DB設定読み込み
     */
    private function load_db()
    {
        $this->db = $this->load->database("db", TRUE);
    }

    public function _redirect($path)
    {
        header("Location:".SITE_URL.$path);
        exit;        
    }
}
