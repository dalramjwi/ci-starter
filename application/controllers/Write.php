<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Write 컨트롤러 클래스
 *
 * 게시글 작성 화면을 보여주고, 작성된 글을 처리하는 역할을 담당합니다.
 *
 * 주요 기능:
 * - index(): 글쓰기 화면에 카테고리 목록 데이터를 넘겨 렌더링합니다.
 * - wrote(): 사용자가 작성한 글 데이터를 받아 WriteService를 호출하여 저장합니다.
 *             작성 성공 시 메인 페이지로, 실패 시 글쓰기 페이지로 리디렉션합니다.
 *
 * 사용 라이브러리 및 모델:
 * - Categories_model: 카테고리 목록 조회에 사용.
 * - WriteService: 게시글 작성 로직 처리 (글쓰기 서비스 레이어).
 */
class Write extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->model('Categories_model');
        $this->load->library('WriteService');
    }

    public function index()
    {
        $categories = $this->Categories_model->get_all_categories();
        $data = ['categories' => $categories];
        $this->render('write/index', $data);
    }

    public function wrote()
    {
        $user_id = $this->session->userdata('user_id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $parent_id = $this->input->post('parent_id');
        $category_id = $this->input->post('category');

        $result = $this->writeservice->write_post($user_id, $title, $content, $parent_id, $category_id);

        if ($result['success']) {
            redirect('/main');
        } else {
            redirect('/write/index');
        }
    }
}
