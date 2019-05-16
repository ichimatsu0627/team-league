<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Top Controller
 */
class Top extends Base_controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->view('top/index', $this->view);
    }
}
