<?php 
class Posts_model extends CI_Model {
  public function __construct()
  {
    $this->load->database();
  }
  public function get_all()
  {
      return $this->db->order_by('created_at', 'DESC')->get('posts')->result();
  }
   public function insert($data)
  {
      $this->db->insert('posts', $data);
      return $this->db->insert_id();  // 방금 insert 된 post_id 리턴
  }
  public function update_post($post_id, $data)
    {
        $this->db->where('post_id', $post_id);
        return $this->db->update('posts', $data);
    }
    public function get_post($post_id)
    {
        $this->db->where('post_id', $post_id);
        return $this->db->get('posts')->row();
    }
}