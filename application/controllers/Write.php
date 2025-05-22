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
        $this->load->view('templates/header', $data);
        $this->load->view('write/index', $data);
        $this->load->view('templates/footer');
    }
    public function wrote() 
{
    $user_id = $this->session->userdata('user_id');
    $title = $this->input->post('title');
    $content = $this->input->post('content');

    $data = [
        'user_id' => $user_id,
        'title' => $title,
        'content' => $content,
        'created_at' => date('Y-m-d H:i:s'),
        'parent_id' => null,
        'depth' => 0
    ];

    // 먼저 insert
    $insert_id = $this->Posts_model->insert($data);

    // group_id를 자기 자신으로 설정
    $this->Posts_model->update_post($insert_id, ['group_id' => $insert_id]);

    redirect('/main');
}


}