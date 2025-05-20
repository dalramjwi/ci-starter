<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
         $this->load->model('Posts_model');
    }

    public function index()
    {
        $data['title'] = '계층형 게시판 테스트';
          // 게시글 데이터 불러오기
        $data['posts'] = $this->Posts_model->get_all();

        // 뷰에 데이터 넘김
        $this->load->view('templates/header', $data); // 여기서 $title 전달됨
        $this->load->view('main', $data);
        $this->load->view('templates/footer');
    }
}