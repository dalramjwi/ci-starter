<?php
class Users_model extends CI_Model {
  protected $table = 'users';  // 테이블 이름

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  // 회원가입 데이터 저장 함수
  public function insert($data)
  {
    return $this->db->insert($this->table, $data);
  }
    public function get_by_user_id($user_id)
  {
      return $this->db->get_where('users', ['user_id' => $user_id])->row();
  }

}
