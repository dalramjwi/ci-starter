<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // 공통 변수 설정
        $this->setCommonVars(); 
        
        // 게시판 관련 모델 로드
        $this->load->model('Posts_model');
    }

    public function index()
    {
        // GET, POST, COOKIE에서 넘어온 값 사용 예시
        // $userName = isset($this->params['name']) ? $this->params['name'] : '익명';
        
        // 게시글 불러오기
        $data['title'] = '계층형 게시판 테스트';
        $data['posts'] = $this->Posts_model->get_all();
        // $data['userName'] = $userName;

        // JS, CSS 파일 등록 (필요할 경우)
        // $this->css('board.css');
        // $this->js('board.js');

        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/index', $data);
        $this->load->view('templates/footer');
    }
    public function view($post_id)
    {
        // 게시글 상세보기
        $data['title'] = '게시글 상세보기';
        $data['post'] = $this->Posts_model->get_post($post_id);

        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/view', $data);
        $this->load->view('templates/footer');
    }
}
