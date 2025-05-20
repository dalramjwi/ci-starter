<?php
class News extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');  // 모델 불러오기
    }

    public function index()
    {
        $data['news'] = $this->news_model->get_news();  // 전체 뉴스 가져오기
        $data['title'] = 'News archive';                 // 페이지 제목 설정

        $this->load->view('templates/header', $data);
        $this->load->view('news/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($slug = NULL)
    {
        $data['news_item'] = $this->news_model->get_news($slug);  // 특정 뉴스 가져오기

        if (empty($data['news_item']))
        {
            show_404();  // 뉴스가 없으면 404 페이지 표시
        }

        $data['title'] = $data['news_item']['title'];  // 페이지 제목 설정

        $this->load->view('templates/header', $data);
        $this->load->view('news/view', $data);
        $this->load->view('templates/footer');
    }
    public function create()
{
    $this->load->helper('form');
    $this->load->library('form_validation');

    $data['title'] = 'Create a news item';

    $this->form_validation->set_rules('title', 'Title', 'required');
    $this->form_validation->set_rules('text', 'Text', 'required');

    if ($this->form_validation->run() === FALSE)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('news/create');
        $this->load->view('templates/footer');
    }
    else
    {
        $this->news_model->set_news();
        $this->load->view('news/success');
    }
}

}