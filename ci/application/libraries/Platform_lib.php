<?php
require_once(APPPATH."libraries/Base_lib.php");

/**
 * Platform
 */
class Platform_lib extends Base_lib
{
    public $platforms = [];

    protected $_models = [
        "M_platforms",
    ];

    public function __construct()
    {
        parent::__construct();

        $this->platforms = $this->CI->M_platforms->get_all();
        if (!empty($this->platforms))
        {
            $this->platforms = array_column($this->platforms, null, "id");
        }
    }
}
