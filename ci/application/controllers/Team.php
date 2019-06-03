<?php
require_once(APPPATH."controllers/Base_controller.php");

/**
 * Team Controller
 *
 * @property Team_lib $team_lib
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
        if (empty($id))
        {
            $member_teams = $this->team_lib->get_teams_by_member_id($this->member_id, true);

            if (empty($member_teams))
            {
                $this->_redirect("/team/regist_form");
            }

            $team = array_shift($member_teams);
        }
        else
        {
            $team = $this->team_lib->get_team($id);

            if (empty($team))
            {
                $this->_redirect("/err/not_found");
            }
        }

        $this->view["team"] = $team;
        if (!empty($this->team_lib->get_requests_by_team_id($team->id)))
        {
            $this->view["information"][] = ["text" => "Join Request", "link" => "/team/request_list/".$team->id];
        }
        $this->view["is_my_team"] = $this->team_lib->is_team_member($this->member_id, $team);

        $this->layout->view("team/detail", $this->view);
    }

    public function request_list($id = null)
    {
        if (empty($id))
        {
            $this->_redirect("/err/not_found");
        }

        $team =  $this->team_lib->get_team($id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        $this->view["requests"] = $this->team_lib->get_requests_by_team_id($team->id);
        $this->layout->view("team/request_list", $this->view);
    }

    /**
     * regist form
     */
    public function regist_form()
    {
        $this->layout->view("team/regist_form", $this->view);
    }

    /**
     * edit form
     * @param int|null $id
     */
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

    /**
     * 登録 アクション
     */
    public function regist()
    {
        $team_data = $this->input_team_data();

        $teams = $this->team_lib->get_teams_by_member_id($this->member_id);
        if (count($teams) >= Team_lib::MAX_JOIN_TEAM_PER_MEMBER)
        {
            $this->_redirect("/team/detail?c=".Page::CODE_FAILED_BY_MAX_JOINED);
        }

        try
        {
            $this->team_lib->begin();

            $this->team_lib->regist($team_data, [$this->member_id]);

            $this->team_lib->commit();
        }
        catch (Exception $e)
        {
            $this->team_lib->rollback();
            $c = $e->getCode();
            $this->_redirect("/team/regist_form?c=".$c);
        }

        $this->_redirect("/team/detail?c=".Page::CODE_REGISTED);
    }

    /**
     * 編集 アクション
     */
    public function edit()
    {
        $id = $this->input->post("id");

        $team = $this->team_lib->get_team($id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

    }

    /**
     * 参加申請
     * @param $id
     */
    public function request_join($id)
    {
        $team = $this->team_lib->get_team($id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        $teams = $this->team_lib->get_teams_by_member_id($this->member_id);
        if (count($teams) >= Team_lib::MAX_JOIN_TEAM_PER_MEMBER)
        {
            $this->_redirect("/team/detail?c=".Page::CODE_FAILED_BY_MAX_JOINED);
        }

        try
        {
            $this->member_lib->begin();

            $this->member_lib->lock($this->member_id);

            $this->team_lib->regist_request($id, $this->member_id);

            $this->member_lib->commit();
        }
        catch (Exception $e)
        {
            $this->member_lib->rollback();
        }

        $this->_redirect("/team/detail/?c=".Page::CODE_REQUESTS);
    }

    /**
     * 参加申請承認
     * @param $id
     */
    public function accept_join($id)
    {

    }

    /**
     * inputデータ整理
     * @return array
     */
    private function input_team_data()
    {
        $team_data = [
            "name"     => $this->input->post('name'),
            "description" => $this->input->post('description'),
        ];

        return $team_data;
    }
}
