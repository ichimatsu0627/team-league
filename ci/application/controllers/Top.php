<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Top Controller
 */
class Top extends Base_controller
{
    public function index()
    {
        $this->view["css"] = "top/index";
        $this->layout->view('top/index', $this->view);
    }
}
