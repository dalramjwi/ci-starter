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
        $all_posts = $this->Posts_model->get_all(); // 정렬되지 않은 모든 게시글
        $data['posts'] = $this->build_post_tree($all_posts); // 계층 구조로 정렬
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
    public function edit ($post_id)
    {
        // 게시글 수정 폼
        $data['title'] = '게시글 수정';
        $data['post'] = $this->Posts_model->get_post($post_id);

        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/edit', $data);
        $this->load->view('templates/footer');
    }
    public function update($post_id)
    {
        // 게시글 수정 처리
        $title = $this->input->post('title');
        $content = $this->input->post('content');

        $data = [
            'title' => $title,
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->Posts_model->update_post($post_id, $data);

        redirect('/main/view/' . $post_id);
    }
    public function delete($post_id)
    {
        // 게시글 삭제 처리
        $this->Posts_model->delete_post($post_id);

        redirect('/main');
    }
    public function comment($post_id)
    {
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('comment');

        // 부모 글 가져오기
        $parent_post = $this->Posts_model->get_post($post_id);

        // 부모 글이 없으면 depth 0, 있으면 부모 depth + 1
        $depth = $parent_post ? $parent_post->depth + 1 : 0;

        $data = [
            'user_id' => $user_id,
            'title' => null,          // 댓글에는 제목이 없으니까 null 처리
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'parent_id' => $post_id,
            'depth' => $depth,
            'is_popular' => false
        ];

        // 새 댓글/답글 저장
        $this->Posts_model->insert($data);

        // 댓글이 달린 게시글로 리다이렉트 (부모 글)
        redirect('/main/view/' . $post_id);
    }
    private function build_post_tree($posts, $parent_id = null, $depth = 0)
    {
        $tree = [];

        foreach ($posts as $post) {
            if ($post->parent_id === $parent_id) {
                $post->depth = $depth;
                $tree[] = $post;

                // 자식도 재귀적으로 트리에 붙이기
                $children = $this->build_post_tree($posts, $post->post_id, $depth + 1);
                $tree = array_merge($tree, $children);
            }
        }

        return $tree;
    }


}
  