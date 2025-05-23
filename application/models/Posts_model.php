<?php 
class Posts_model extends CI_Model {

public function get_only_base_limit($limit = 10)
{
    return $this->db
        ->where('parent_id IS NULL', null, false)
        ->order_by('created_at', 'DESC')
        ->limit($limit)
        ->get('posts')
        ->result();
}

public function get_all($offset = 0, $limit = 10)
{
    return $this->db
        ->order_by('created_at', 'DESC')
        ->limit($limit, $offset)
        ->get('posts')
        ->result();
}

    // 최상위 게시글만 조회 (depth = 0)
    public function get_only_base()
    {
        return $this->db
            ->where('depth', 0)
            ->order_by('created_at', 'DESC')
            ->get('posts')
            ->result();
    }

    // // 게시글 단건 조회
    // public function get_post($post_id)
    // {
    //     return $this->db
    //         ->where('post_id', $post_id)
    //         ->get('posts')
    //         ->row();
    // }

    // 게시글 등록
    // public function insert($data)
    // {
    //     $this->db->insert('posts', $data);
    //     return $this->db->insert_id();  // insert된 ID 리턴
    // }

    // 게시글 수정
    public function update_post($post_id, $data)
    {
        return $this->db
            ->where('post_id', $post_id)
            ->update('posts', $data);
    }

    // 게시글 삭제
    public function delete_post($post_id)
    {
        return $this->db
            ->where('post_id', $post_id)
            ->delete('posts');
    }
    //!이후 최신 작성한 게시글 쿼리임
        // 게시글 insert
    public function insert($data)
    {
        $this->db->insert('posts', $data);
        return $this->db->insert_id();
    }

    // 게시글 정보 가져오기
    public function get_post($post_id)
    {
        return $this->db->get_where('posts', ['post_id' => $post_id])->row_array();
    }
}
