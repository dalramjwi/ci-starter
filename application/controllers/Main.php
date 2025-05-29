<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Main 컨트롤러 클래스
 *
 * 이 컨트롤러는 메인 페이지의 게시글 목록, 검색 기능, AJAX 기반 게시글 페이징 요청을 처리합니다.
 * 
 * 각 메서드는 요청별로 공통된 로직은 MainService에 위임하며, 
 * 컨트롤러는 요청 유형에 따른 컨텍스트 정보를 만들어 서비스에 전달합니다.
 * 
 * 주요 메서드:
 * - index(): 초기 메인 페이지 로드 (기본 게시글 목록, 카테고리 포함)
 * - search(): 검색어 기반 게시글 필터링 및 결과 출력
 * - fetch_posts(): AJAX 호출로 페이지별 게시글 데이터를 JSON 형태로 반환
 * 
 * 공통적으로 카테고리 목록을 가져와서 뷰에 전달하며,
 * 페이징, 검색어, 카테고리 정보 등도 함께 세팅합니다.
 */
class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->model('Posts_model');
        $this->load->model('Categories_model');
        $this->load->helper('utility_helper');
        $this->load->library('MainService');
    }

    public function index()
    {
        $context = [
            'source' => 'index',
            'input' => [],
            'user_id' => $this->session->userdata('user_id')
        ];
        $result = $this->mainservice->handle($context);

        $result['categories'] = $this->Categories_model->get_all_categories();
        $result['limit'] = 10;
        $result['current_page'] = 1;
        $result['keyword'] = '';
        $result['category_id'] = 2;

        $this->render('main/index', $result);
    }

    public function search()
    {
        $context = [
            'source' => 'search',
            'input' => $this->input->get(),
            'user_id' => $this->session->userdata('user_id')
        ];
        $result = $this->mainservice->handle($context);

        $result['categories'] = $this->Categories_model->get_all_categories();
        $result['limit'] = 10;
        $result['current_page'] = $context['input']['page'] ?? 1;
        $result['keyword'] = $context['input']['q'] ?? '';
        $result['category_id'] = 2;

        $this->render('main/index', $result);
    }

    public function fetch_posts()
    {
        $json = json_decode(file_get_contents('php://input'), true);

        $context = [
            'source' => 'fetch_posts',
            'input' => $json,
            'user_id' => $this->session->userdata('user_id')
        ];
        $result = $this->mainservice->handle($context);

        $html = $this->load->view('main/post_list', [
            'posts' => $result['posts'],
            'category_id' => $result['category_id'] ?? 2
        ], true);

        echo json_encode([
            'html' => $html,
            'total_pages' => $result['total_pages'],
            'current_page' => $result['current_page']
        ]);
    }
}
