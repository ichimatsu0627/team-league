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

    const ALL_PAGE_LIMIT = 3;

    /**
     * Search constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library("member_lib");
        $this->load->library("team_lib");

        $this->view["mine"] = $this->member_lib->get_member($this->member_id);
        $this->view["css"] = "search/common";
        $this->view["javascript"] = "search/common";
    }

    /**
     * 通常ページ
     */
    public function all()
    {
        $keyword = $this->input->post("keyword");

        $limit = self::ALL_PAGE_LIMIT;

        $members = $this->member_lib->search_members(["keyword" => $keyword], $limit);
        $teams   = $this->team_lib->search_teams(["keyword" => $keyword], $limit);

        $this->view["keyword"] = $keyword;
        $this->view["members"] = $members;
        $this->view["teams"]   = $teams;
        $this->view["limit"]   = $limit;
        $this->layout->view('search/all', $this->view);
    }

    /**
     * メンバーサーチ
     * @param int $page
     * @throws Exception
     */
    public function member($page = 1)
    {
        $keyword = $this->input->post("keyword");
        $ranks = $this->input->post("ranks");

        $conditions = [];

        if (!empty($keyword))
        {
            $conditions["keyword"] = $keyword;
        }

        if (!empty($ranks))
        {
            $ranks = array_filter($ranks, "strlen");
            $ranks = array_unique($ranks);
            if (!empty($ranks))
            {
                $conditions["ranks"] = $ranks;
            }
        }

        $this->view["members"]    = $this->member_lib->search_members($conditions, DEFAULT_PAGER_PER, ($page - 1) * DEFAULT_PAGER_PER);
        $this->view["all"]        = $this->member_lib->count_search_members($conditions);
        $this->view["page"]       = $page;
        $this->view["start"]      = $this->page->get_pager_start($page, $this->view["all"], DEFAULT_PAGER_PER);
        $this->view["conditions"] = $conditions;

        $this->layout->view('search/member', $this->view);
    }

    /**
     * チームサーチ
     * @param int $page
     * @throws Exception
     */
    public function team($page = 1)
    {
        $keyword = $this->input->post("keyword");

        $conditions = [];

        if (!empty($keyword))
        {
            $conditions["keyword"] = $keyword;
        }

        $this->view["teams"]      = $this->team_lib->search_teams($conditions, DEFAULT_PAGER_PER, ($page - 1) * DEFAULT_PAGER_PER);
        $this->view["all"]        = $this->team_lib->count_search_teams($conditions);
        $this->view["page"]       = $page;
        $this->view["start"]      = $this->page->get_pager_start($page, $this->view["all"], DEFAULT_PAGER_PER);
        $this->view["conditions"] = $conditions;

        $this->layout->view('search/team', $this->view);
    }
}
