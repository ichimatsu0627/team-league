<?php

/**
 * Class Base_lib
 * @property CI_Controller $CI
 * @property array $modals
 * @property array $libraries
 */
class Base_lib
{
    protected $CI;

    // モデル
    protected $_models = array(
    );

    // ライブラリ
    protected $_libraries = array(
    );

    public function __construct()
    {
        $this->CI = get_instance();
        $this->load_models();
        $this->load_libraries();
    }

    private function load_models()
    {
        $this->CI->load->model($this->_models);
    }

    private function load_libraries()
    {
        $this->CI->load->library($this->_libraries);
    }
}
