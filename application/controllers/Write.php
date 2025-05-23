<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Posts_model');
        $this->load->helper('base62_helper');  
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


    // 게시글 작성 처리 메서드
    public function wrote() 
    {
        $user_id = $this->session->userdata('user_id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');

        // 최상위 글이므로 depth=0
        $depth = 0;

        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'depth' => $depth
        ];

        // posts 테이블에 새 게시글 삽입
        $post_id = $this->Posts_model->insert_post($data);

        // 클로저 테이블에 자기 자신 추가
        $this->Posts_model->insert_closure($post_id, $post_id, 0);

        // path 테이블에 path 문자열 저장 (최상위 글이므로 path는 base62 인코딩 post_id)
        $path = base62_encode($post_id);
        $this->Posts_model->insert_path($post_id, $path);

        redirect('/main');
    }
}
