<?php

class Layout
{
    public $obj;
    public $layout;

    function __construct($layout = "layout_main")
    {
        $this->obj = get_instance();
        $this->layout = $layout;
    }

    function set_layout($layout)
    {
        $this->layout = $layout;
    }

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
