<?php 
class Posts_model extends CI_Model {
  public function __construct()
  {
    $this->load->database();
  }
  public function get_all()
  {
      return $this->db->order_by('order_in_group', 'ASC')->get('posts')->result();
  }
}