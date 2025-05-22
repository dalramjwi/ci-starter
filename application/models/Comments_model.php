<?php 
class Comments_model extends CI_Model
{
    public function insert_comment($data)
    {
        return $this->db->insert('comments', $data);
    }

    public function get_comments_by_post($post_id)
    {
        return $this->db->get_where('comments', ['post_id' => $post_id])->result();
    }

public function delete_comment($comment_id, $user_id)
{
    return $this->db
                ->where('comment_id', $comment_id)  // 여기!
                ->where('user_id', $user_id)
                ->delete('comments');
}


    public function update_comment($comment_id, $data)
    {
        $this->db->where('comment_id', $comment_id);
        return $this->db->update('comments', $data);
    }
public function get_comment($comment_id)
{
    return $this->db
                ->get_where('comments', ['comment_id' => $comment_id])
                ->row();
}


}
