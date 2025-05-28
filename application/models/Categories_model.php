<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model
{
    private $table = 'categories';

    // 카테고리 전체 목록 조회
    public function get_all_categories()
    {
        return $this->db->get($this->table)->result();
    }

    // 카테고리 하나 조회
    public function get_category($category_id)
    {
        return $this->db->get_where($this->table, ['category_id' => $category_id])->row();
    }

    // 게시판 활동 권한 확인용
    public function get_permissions($category_id)
    {
        return $this->db->select('can_write, can_comment, can_view')
                        ->get_where($this->table, ['category_id' => $category_id])
                        ->row_array();
    }
}
