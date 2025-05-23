<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Posts_model');
        $this->load->model('Posts_closure_model');
        $this->load->model('Path_model');
        $this->load->helper('utility_helper');
    }

        // 게시글 작성 화면 메서드 
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

        // 최상위 글 작성 (답글 X)
        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'depth' => 0
        ];

        // posts 테이블에 저장 및 새 post_id 받기
        $insert_id = $this->Posts_model->insert($data);

        // 클로저 테이블에 자기 자신 관계 (depth 0)
        $this->Posts_closure_model->insert($insert_id, $insert_id, 0);

        // path 경로는 post_id를 base62로 인코딩해서 문자열 생성 (최상위 글라서 단일 path)
        $path = base62_encode($insert_id);

        // path 테이블에 저장
        $this->Path_model->insert($insert_id, $path);

        redirect('/main');
    }
}
