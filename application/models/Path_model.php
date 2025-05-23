<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_model extends CI_Model
{
    // path 테이블에 경로 저장
    public function insert($post_id, $path)
    {
        $data = [
            'post_id' => $post_id,
            'path' => $path
        ];
        $this->db->insert('path', $data);
    }

    // post_id로 path 조회
    public function get_path($post_id)
    {
        $query = $this->db->get_where('path', ['post_id' => $post_id]);
        if ($query->num_rows() > 0) {
            return $query->row()->path;
        }
        return null;
    }
}
