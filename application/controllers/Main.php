<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = '계층형 게시판 테스트';

        // 뷰에 데이터 넘김
        $this->load->view('templates/header', $data); // 여기서 $title 전달됨
        $this->load->view('templates/footer');
    }
}