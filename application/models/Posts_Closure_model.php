<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_closure_model extends CI_Model
{
    // 클로저 테이블에 조상-후손 관계 삽입
    public function insert($ancestor, $descendant, $depth)
    {
        $data = [
            'ancestor' => $ancestor,
            'descendant' => $descendant,
            'depth' => $depth
        ];
        $this->db->insert('posts_closure', $data);
    }

    // // 특정 조상으로부터 모든 후손 가져오기
    public function get_descendants($ancestor)
    {
        return $this->db->get_where('posts_closure', ['ancestor' => $ancestor])->result();
    }

    // // 특정 후손의 모든 조상 가져오기
    // public function get_ancestors($descendant)
    // {
    //     return $this->db->get_where('posts_closure', ['descendant' => $descendant])->result_array();
    // }
    public function get_ancestors($post_id) 
    {
      return $this->db
        ->where('descendant', $post_id)
        ->order_by('depth', 'ASC') // 가까운 조상부터
        ->get('posts_closure')
        ->result();
    }

    public function get_top_ancestor($post_id)
{
    return $this->db
        ->select('ancestor')
        ->from('posts_closure')
        ->where('descendant', $post_id)
        ->order_by('depth', 'DESC')  
        ->limit(1)
        ->get()
        ->row();
}


}
