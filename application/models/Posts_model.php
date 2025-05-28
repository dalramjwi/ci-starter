<?php 
class Posts_model extends CI_Model {

    public function get_posts($offset, $limit, $filters = [])
    {
        $this->db
            ->select('posts.*, path.path')
            ->from('posts')
            ->join('path', 'posts.post_id = path.post_id');

        if (!empty($filters['keyword'])) {
            $this->db->like('posts.title', trim($filters['keyword']));
        }

        if (!empty($filters['category_id']) && $filters['category_id'] != 0) {
            $this->db->where('posts.category_id', (int)$filters['category_id']);
        }

        return $this->db
            ->order_by('posts.group_id', 'DESC')
            ->order_by('path.path', 'ASC')
            ->limit($limit, $offset)
            ->get()
            ->result();
    }
    public function count_posts($filters = [])
    {
        if (!empty($filters['keyword'])) {
            $this->db->like('title', trim($filters['keyword']));
        }

        if (!empty($filters['category_id']) && $filters['category_id'] != 0) {
            $this->db->where('category_id', (int)$filters['category_id']);
        }

        return $this->db->count_all_results('posts');
    }
    // 전체 게시글 개수 구하기
    public function get_total_count()
    {
        return $this->db->count_all('posts');
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
    // 게시글 정보 가져오기
    public function get_post($post_id)
    {
        return $this->db->get_where('posts', ['post_id' => $post_id])->row();
    }
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


}

