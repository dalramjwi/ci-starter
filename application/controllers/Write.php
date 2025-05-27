<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Write extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars(); 
        $this->load->model('Posts_model');
        $this->load->model('Posts_closure_model');
        $this->load->model('Path_model');
        $this->load->model('Categories_model');
        $this->load->helper('utility_helper');
    }

    // 게시글 작성 화면 메서드
    public function index()
    {
        $categories = $this->Categories_model->get_all_categories();
        $data = ['categories' => $categories];
        $this->render('write/index', $data);
    }
    // 게시글 작성 요청 처리 메서드
    public function wrote()
    {
        $user_id = $this->session->userdata('user_id');
        $title = $this->input->post('title');
        $content = $this->input->post('content');
        $parent_id = $this->input->post('parent_id'); // 있으면 답글, 없으면 본글
        $category_id = $this->input->post('category'); // 추가: 카테고리 받기


        if (empty($parent_id)) {
            // === 본글 작성 ===

            $data = [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'depth' => 0,
                'group_id' => 0,
                'category_id' => $category_id
            ];

            $insert_id = $this->Posts_model->insert($data);
            $this->Posts_model->update_group_id($insert_id, $insert_id);

            // 클로저: 자기 자신
            $this->Posts_closure_model->insert($insert_id, $insert_id, 0);

            // path: 최상위 글이니까 base62(post_id)만
            $path = base62_encode($insert_id);
            $this->Path_model->insert($insert_id, $path);

        } else {
            // === 답글 작성 ===

            $parent = $this->Posts_model->get_post($parent_id);
            $parent_path = $this->Path_model->get_path($parent_id);
            $parent_depth = $parent->depth;

            $top_ancestor = $this->Posts_closure_model->get_top_ancestor($parent_id);
            $group_id = $top_ancestor->ancestor;

            // 제목 자동 생성
            if (empty(trim($title))) {
                $title = $parent->title . '의 답글입니다';
            }

            $data = [
                'user_id' => $user_id,
                'title' => $title,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s'),
                'depth' => $parent_depth + 1,
                'group_id' => $group_id,
                'category_id' => $category_id

            ];

            $insert_id = $this->Posts_model->insert($data);

            // 클로저: 자기 자신
            $this->Posts_closure_model->insert($insert_id, $insert_id, 0);

            // 클로저: 조상들 → 현재 글로 관계 생성
            $ancestors = $this->Posts_closure_model->get_ancestors($parent_id);
            foreach ($ancestors as $ancestor) {
                $this->Posts_closure_model->insert($ancestor->ancestor, $insert_id, $ancestor->depth + 1);
            }

            // path: 부모 path + '/' + base62(post_id)
            $path = $parent_path . '/' . base62_encode($insert_id);
            $this->Path_model->insert($insert_id, $path);
        }

        redirect('/main');
    }

}
