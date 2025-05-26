<?php
class Comments_model extends CI_Model
{
    //view에서 게시글 id의 댓글을 조회하는 함수
    public function get_comments_by_post($post_id)
    {
        return $this->db->get_where('comments', ['post_id' => $post_id])->result();
    }
    //comments에서 댓글을 작성하는 함수
    public function insert_comment($data)
    {
        return $this->db->insert('comments', $data);
    }
    //delete_comment, update_comment에서 댓글 정보를 가져오는 함수
    public function get_comment($comment_id)
    {
        return $this->db
                    ->get_where('comments', ['comment_id' => $comment_id])
                    ->row();
    }
    //delete_comment에서 댓글을 삭제하는 함수
    public function delete_comment($comment_id, $user_id)
    {
        return $this->db
                    ->where('comment_id', $comment_id)  // 여기!
                    ->where('user_id', $user_id)
                    ->delete('comments');
    }
    //update_comment에서 댓글을 수정하는 함수
    public function update_comment($comment_id, $data)
    {
        $this->db->where('comment_id', $comment_id);
        return $this->db->update('comments', $data);
    }
}
