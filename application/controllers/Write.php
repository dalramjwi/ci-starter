<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Write extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Posts_model');
    }

    // 게시글 목록을 보여주는 메서드
    public function index()
    {
        $data['title'] = '게시판';
        $data['write'] = '글쓰기';
        //세션으로 user_id값 가져오기, post로 title, content 가져오고 created_at 자동으로 . parent_id, group_id, depth, order_in_group 은 내부 로직으로 넣기 (계층형 게시글  구조)
        $this->load->view('templates/header', $data);
        $this->load->view('write/index', $data);
        $this->load->view('templates/footer');
    }
}