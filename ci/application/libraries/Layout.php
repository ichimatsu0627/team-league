<?php

/**
 * Layout Library
 * @package libraries
 * @property CI_Controller $obj
 * @property $layout
 */
class Layout
{
    public $obj;
    public $layout;

    /**
     * Layout constructor.
     * @param string $layout
     */
    function __construct($layout = "layout_main")
    {
        $this->obj = get_instance();
        $this->layout = $layout;
    }

    /**
     * set_layout
     * @param $layout
     */
    function set_layout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @param $view
     * @param array $data
     * @param bool $return
     * @return object|string
     */
    function view($view, $data = [], $return = FALSE)
    {
        $loadedData = [];
        $loadedData['content_for_layout'] = $this->obj->load->view($view, $data, TRUE);

        $output = $this->obj->load->view($this->layout, $loadedData, $return);

        if ($return)
        {
            return $output;
        }
    }
}
