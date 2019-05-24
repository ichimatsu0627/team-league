<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Team Controller
 */
class Team extends Base_controller
{
    /**
     * Team constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library("team_lib");
    }

    /**
     * detail
     * @param int $id
     */
    public function detail($id = null)
    {
        $team = $this->team_lib->get_team($id);
        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        $this->view["team"] = $team;

        $this->layout->view("team/detail", $this->view);
    }

    public function regist_form()
    {
        $this->layout->view("team/regist_form", $this->view);
    }

    public function edit_form($id = null)
    {
        $team = $this->team_lib->get_team($id);
        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        $this->view["team"] = $team;

        $this->layout->view("team/edit_form", $this->view);
    }

    public function regist()
    {

    }

    public function edit()
    {

    }

    public function request_join($id)
    {

    }

    public function accept_join($id)
    {

    }
}
