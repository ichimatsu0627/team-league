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
                $this->_redirect("/team/register_form");
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
        $this->view["is_team_member"] = $this->team_lib->is_team_member($this->member_id, $team);
        $this->view["is_admin"] = $this->team_lib->is_admin($this->member_id, $team);
        $this->view["is_already_request"] = $this->team_lib->is_already_request($team->id, $this->member_id);

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

        $requests = $this->team_lib->get_requests_by_team_id($team->id);
        $members = $this->member_lib->get_members(array_column($requests, "t_member_id"));

        $this->view["requests"] = $requests;
        $this->view["members"]  = $members;
        $this->layout->view("team/request_list", $this->view);
    }

    /**
     * register form
     */
    public function register_form()
    {
        $this->layout->view("team/register_form", $this->view);
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
    public function register()
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

            $id = $this->team_lib->register($team_data, [$this->member_id]);
            $this->team_lib->update_member_role($id, $this->member_id, T_team_members::ROLE_LEADER);

            $this->team_lib->commit();
        }
        catch (Exception $e)
        {
            $this->team_lib->rollback();
            $c = $e->getCode();
            $this->_redirect("/team/register_form?c=".$c);
        }

        $this->_redirect("/team/detail?c=".Page::CODE_REGISTED);
    }

    /**
     * 編集 アクション
     */
    public function edit()
    {
        $id = $this->input->post("id");
        $team_data = $this->input_team_data();

        $team = $this->team_lib->get_team($id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        if (!$this->team_lib->is_team_member($this->member_id, $team))
        {
            $this->_redirect("/team/edit_form/".$id."?c=".Page::CODE_FAILED);
        }

        if (empty($team_data["name"]))
        {
            $this->_redirect("/team/edit_form/".$id."?c=".Page::CODE_FAILED);
        }

        try
        {
            $this->team_lib->begin();

            $this->team_lib->lock($id);

            $this->team_lib->update($team, $team_data);

            $this->team_lib->commit();
        }
        catch(Exception $e)
        {
            $this->team_lib->rollback();
            $this->_redirect("/team/edit_form/".$id."?c=".Page::CODE_FAILED);
        }

        $this->_redirect("/team/detail/".$id."?c=".Page::CODE_SUCCESS);
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

            $this->team_lib->register_request($id, $this->member_id);

            $this->member_lib->commit();
        }
        catch (Exception $e)
        {
            $this->member_lib->rollback();
        }

        $this->_redirect("/team/detail/".$id."?c=".Page::CODE_REQUESTS);
    }

    /**
     * 参加申請承認
     * @param $id
     */
    public function accept_request($id)
    {
        $request = $this->team_lib->get_request_by_id($id);

        if (empty($request))
        {
            $this->_redirect("/err/not_found");
        }

        $team = $this->team_lib->get_team($request->t_team_id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        if (!$this->team_lib->is_admin($this->member_id, $team))
        {
            $this->_redirect("/team/request_list?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        try
        {
            $this->team_lib->begin();

            $this->team_lib->lock($team->id);

            $this->team_lib->register_member($team->id, [$request->t_member_id]);
            $this->team_lib->update_request($id, T_team_requests::STATUS_TYPE_RECEIVED);

            $this->team_lib->commit();
        }
        catch (Exception $e)
        {
            $this->team_lib->rollback();
        }

        $this->_redirect("/team/request_list/".$team->id."?c=".Page::CODE_ACCEPT);
    }

    /**
     * 参加申請却下
     * @param $id
     */
    public function refuse_request($id)
    {
        $request = $this->team_lib->get_request_by_id($id);

        if (empty($request))
        {
            $this->_redirect("/err/not_found");
        }

        $team = $this->team_lib->get_team($request->t_team_id);

        if (empty($team))
        {
            $this->_redirect("/err/not_found");
        }

        if (!$this->team_lib->is_admin($this->member_id, $team))
        {
            $this->_redirect("/team/request_list/".$team->id."?c=".Page::CODE_FAILED_BY_INVALID_VALUE);
        }

        try
        {
            $this->team_lib->begin();

            $this->team_lib->lock($team->id);

            $this->team_lib->update_request($id, T_team_requests::STATUS_TYPE_LOST);

            $this->team_lib->commit();
        }
        catch (Exception $e)
        {
            $this->team_lib->rollback();
        }

        $this->_redirect("/team/request_list/".$team->id."?c=".Page::CODE_REFUSE);
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
