<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Search Controller
 *
 * @property Member_lib $member_lib
 * @property Team_lib   $team_lib
 */
class Search extends Base_controller
{
    const SEARCH_TYPE_ALL    = 0;
    const SEARCH_TYPE_MEMBER = 1;
    const SEARCH_TYPE_TEAM   = 2;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library("member_lib");
        $this->load->library("team_lib");
    }

    /**
     * 通常ページ
     */
    public function all()
    {
        $keyword = $this->input->post("keyword");

        $members = $this->member_lib->get_members_by_keyword($keyword, DEFAULT_PAGER_PER, 0);
        $teams   = $this->team_lib->get_teams_by_keyword($keyword, DEFAULT_PAGER_PER, 0);

        $this->view["members"] = $members;
        $this->view["teams"]   = $teams;
        $this->layout->view('search/all', $this->view);
    }
}
