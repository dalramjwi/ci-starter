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

// public function get_all($offset = 0, $limit = 10)
// {
//     return $this->db
//         ->order_by('created_at', 'DESC')
//         ->limit($limit, $offset)
//         ->get('posts')
//         ->result();
// }
// public function get_all($offset = 0, $limit = 10)
// {
//     return $this->db
//         ->select('posts.*, path.path')
//         ->from('posts')
//         ->join('path', 'posts.post_id = path.post_id')
//         ->order_by('path.path', 'ASC')  // 계층 구조 순서대로 정렬
//         ->limit($limit, $offset)
//         ->get()
//         ->result();
// }
public function get_all($offset, $limit)
{
    return $this->db
        ->select('posts.*, path.path')
        ->from('posts')
        ->join('path', 'posts.post_id = path.post_id')
        ->order_by('posts.group_id', 'DESC')  // 최신글 순서로 본글 정렬
        ->order_by('path.path', 'ASC')        // 본글 내 답글은 경로 오름차순 정렬 (계층형)
        ->limit($limit, $offset)
        ->get()
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
        return $this->db->get_where('posts', ['post_id' => $post_id])->row();
    }
    public function update_group_id($post_id, $group_id)
    {
        $this->db->where('post_id', $post_id);
        return $this->db->update('posts', ['group_id' => $group_id]);
    }
public function get_descendants($post_id)
{
    $this->db->select('posts.post_id, posts.user_id');
    $this->db->from('posts_closure AS closure_table');
    $this->db->join('posts', 'closure_table.descendant = posts.post_id');
    $this->db->where('closure_table.ancestor', $post_id);
    $this->db->where('closure_table.depth >=', 1);
    $query = $this->db->get();
    return $query->result();
}
// 전체 게시글 개수 구하기
public function count_all_posts()
{
    return $this->db->count_all('posts');
}
public function get_total_count()
{
    return $this->db->count_all('posts');
}
public function search_by_title($keyword, $offset, $limit) {
    $keyword = trim($keyword);
    if ($keyword === '') {
        return [];
    }

    return $this->db
        ->select('posts.*, path.path')
        ->from('posts')
        ->join('path', 'posts.post_id = path.post_id')
        ->like('posts.title', $keyword)
        ->order_by('posts.group_id', 'DESC')
        ->order_by('path.path', 'ASC')
        ->limit($limit, $offset)
        ->get()
        ->result();
}

public function search_count($keyword) {
    $keyword = trim($keyword);
    if ($keyword === '') {
        return 0;
    }

    return $this->db
        ->like('title', $keyword)
        ->count_all_results('posts');
}


}

