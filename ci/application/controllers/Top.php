<?php
require_once(APPPATH."controllers/Base_controller.php");

class Top extends Base_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($my_param = null)
    {
        $this->layout->view('top/index', ["my_param" => $my_param]);
    }
}
