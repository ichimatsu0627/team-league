<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Class Other
 */
class Other extends Base_controller
{
    public function profile()
    {
        $this->view["css"] = "other/profile";
        $this->layout->view('other/profile', $this->view);
    }
}
