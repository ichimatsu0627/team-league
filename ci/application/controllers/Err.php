<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Error Controller
 */
class Err extends Base_controller
{
    public function general()
    {
        $this->view["message"] = $this->input->get('message');
        $this->view["return_url"] = $this->input->get('url');

        $this->layout->view("err/general", $this->view);
    }

    public function not_found()
    {
        $this->layout->view("err/not_found", $this->view);
    }

    public function under_construction()
    {
        $this->layout->view("err/under_construction", $this->view);
    }
}
