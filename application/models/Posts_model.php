<?php
class Posts_model extends MY_Model
{
    protected $table = 'posts';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all()
    {
        $sql = "
            SELECT * 
            FROM {$this->table} 
            ORDER BY order_in_group ASC
        ";

        return $this->excute($sql, 'rows');
    }
}
