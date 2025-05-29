<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
