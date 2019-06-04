<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Notice Library
 */
class Notice_lib extends Base_lib
{
    protected $_libraries = [
        "team_lib",
    ];

    /**
     * 通知取得
     * @param $member_id
     * @return array
     */
    public function get($member_id)
    {
        log_message('m', $member_id);

        $notices = [];

        $notices[] = $this->get_notice_by_join_requests($member_id);

        return array_filter($notices);
    }

    /**
     * 承認申請の通知
     * @param $member_id
     * @return array
     */
    private function get_notice_by_join_requests($member_id)
    {
        $teams = $this->CI->team_lib->get_teams_by_member_id($member_id);

        foreach ($teams as $team)
        {
            if (!empty($this->CI->team_lib->get_requests_by_team_id($team->id)))
            {
                return ["text" => "Join Request", "link" => "/team/request_list/".$team->id];
            }
        }

        return [];
    }
}
