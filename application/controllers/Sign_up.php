<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sign_up extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->model('Users_model');
    }

    // 회원가입 폼을 보여주는 메서드
    public function index()
    {
        $this->render('sign_up/index');
    }

    // 회원가입 요청 처리 (폼 제출 처리)
    public function submit()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');
        //아이디 중복 체크
        $exists = $this->Users_model->get_by_user_id($user_id);
        if ($exists) {
            $existing_id = $exists->user_id;  // 또는 배열이면 $exists['user_id']
            echo "<script>alert('$existing_id 는 이미 존재하는 아이디입니다.'); location.href = '" . base_url('sign_up') . "';</script>";
            exit;
        }

        $data = [
            'user_id' => $user_id,
            'user_pw' => $user_pw
        ];

        $result = $this->Users_model->insert($data);

        if ($result) {
            redirect('/login/index');
        } else {
        echo "<script>alert('회원가입이 실패했습니다.'); location.href = '" . base_url('sign_up') . "';</script>";
        exit;
        }
    }

}
