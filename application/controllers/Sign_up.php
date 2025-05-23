<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign_up extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model'); // 회원 관련된 데이터 모델
    }

    // 회원가입 폼을 보여주는 메서드
    public function index()
    {
        $data['title'] = '회원가입';
        $this->load->view('templates/header', $data);
        $this->load->view('sign_up/index', $data);
        $this->load->view('templates/footer');
    }

    // 회원가입 요청 처리 (폼 제출 처리)
    public function submit()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');

        $data = [
            'user_id' => $user_id,
            'user_pw' => $user_pw
        ];

        $result = $this->Users_model->insert($data);

        if ($result) {
            redirect('/login/index');
        } else {
            echo "회원가입 실패";
        }
    }

}
