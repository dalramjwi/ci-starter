<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PostList extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Posts_model');
        $this->load->model('Categories_model');
        $this->load->helper('utility_helper');

        // 공통 변수 설정
        $this->setCommonVars();
    }

    private function prepare_post_data($offset, $limit, $keyword = null, $category_id = 2)
    {
        $filters = [];
        $user_id = $this->session->userdata('user_id');

        if (!empty($keyword)) {
            $filters['keyword'] = $keyword;
        }

        if ($category_id == 4 && $user_id) {
            $filters['user_id'] = $user_id;
        }

        $filters['category_id'] = $category_id;

        $posts = $this->Posts_model->get_posts($offset, $limit, $filters);
        $total = $this->Posts_model->count_posts($filters);

        return [
            'posts' => $posts,
            'total_pages' => ceil($total / $limit),
            'total_count' => $total,
        ];
    }

    public function index()
    {
        $limit = 10;
        $offset = 0;

        $categories = $this->Categories_model->get_all_categories();
        $data = $this->prepare_post_data($offset, $limit);

        $data['categories'] = $categories;
        $data['limit'] = $limit;
        $data['current_page'] = 1;
        $data['keyword'] = '';
        $data['category_id'] = 2;

        $this->render('main/index', $data);
    }

    public function search()
    {
        $keyword = trim($this->input->get('q'));
        $page = $this->input->get('page') ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $categories = $this->Categories_model->get_all_categories();

        $data = ($keyword === '')
            ? ['posts' => [], 'total_pages' => 0, 'total_count' => 0]
            : $this->prepare_post_data($offset, $limit, $keyword);

        $data['categories'] = $categories;
        $data['limit'] = $limit;
        $data['current_page'] = $page;
        $data['keyword'] = $keyword;
        $data['category_id'] = 2;

        $this->render('main/index', $data);
    }

    public function fetch_posts()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        $limit = isset($input['page_option']) ? (int)$input['page_option'] : 10;
        $page = isset($input['page']) ? (int)$input['page'] : 1;
        $offset = ($page - 1) * $limit;
        $keyword = isset($input['keyword']) ? trim($input['keyword']) : null;
        $category_id = isset($input['category_id']) ? (int)$input['category_id'] : 2;

        $result = $this->prepare_post_data($offset, $limit, $keyword, $category_id);

        $html = $this->load->view('main/post_list', [
            'posts' => $result['posts'],
            'category_id' => $category_id
        ], true);

        echo json_encode([
            'html' => $html,
            'total_pages' => $result['total_pages'],
            'current_page' => $page
        ]);
    }
}
