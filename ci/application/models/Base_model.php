<?php

/**
 * Class Base_model
 */
class Base_model extends CI_Model
{
    protected $CI;
    protected $_table;
    protected $primary_key = 'id';
    protected $db_type = "db";

    public function __construct()
    {
        parent::__construct();

        $this->_fetch_table();

        $this->CI = get_instance();
    }

    public static function __callStatic($name, $arguments)
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }
        return static::$instance->$name(...$arguments);
    }

    public function get_by_id($id)
    {
        $sql = "
            SELECT
                *
            FROM
                ".$this->_table."
            WHERE
                id = ?
            LIMIT 1
         ";

        $data = $this->query($sql, [$id]);

        if (empty($data))
        {
            return null;
        }

        return $data[0];
    }

    public function get_all()
    {
        $sql = "
            SELECT
                *
            FROM
                ".$this->_table."
            WHERE
                del = 0
        ";

        return $this->query($sql, []);
    }

    protected function all()
    {
        return $this->CI->{$this->db_type}->get($this->_table)->result();
    }

    protected function query($sql, $params = [])
    {
        try
        {
            $result = $this->CI->{$this->db_type}->query($sql, $params)->result();
            if (empty($result))
            {
                return [];
            }
        }
        catch (Exception $e)
        {
            throw $e;
        }
        return $result;
    }

    protected function query_to_master($sql, $params)
    {
        try
        {
            $result = $this->_get_master_db()->query($sql, $params);
        }
        catch (Exception $e)
        {
            throw $e;
        }
        return $result;
    }

    protected function insert($data)
    {
        $table_obj = $this->_table;
        $this->_get_master_db()->insert($table_obj, $data);

        return $this->_get_master_db()->insert_id();
    }

    protected function delete($id)
    {
        return $this->_get_master_db()->where($this->primary_key, $id)->delete($this->_table);
    }

    private function _fetch_table()
    {
        if ($this->_table == NULL)
        {
            $this->_table = strtolower(get_class($this));
        }
    }

    private function _get_master_db()
    {
        return $this->CI->{$this->db_type};
    }
}
