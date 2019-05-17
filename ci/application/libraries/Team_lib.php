<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Team Library
 */
class Team_lib
{
    protected $_models = [
        "T_teams",
        "T_team_members",
    ];

    public function get_team($id)
    {
        $this->CI->T_teams->get_by_id($id);
    }
}
