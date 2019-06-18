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
    protected $_models = [];

    // ライブラリ
    protected $_libraries = [];

    // 読み込みファイル
    protected $_requires = [];

    public function __construct()
    {
        $this->CI = get_instance();
        $this->require_files();
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

    private function require_files()
    {
        foreach($this->_requires as $file)
        {
            require_once $file;
        }
    }

}
