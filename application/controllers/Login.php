<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model'); // 회원 관련된 데이터 모델
    }

    // 로그인 폼을 보여주는 메서드
    public function index()
    {
        $data['title'] = '로그인';
        $this->load->view('templates/header', $data);
        $this->load->view('login/index', $data);
        $this->load->view('templates/footer');
    }

    // 로그인 요청 처리 (폼 제출 처리)
    public function submit()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');

        // DB에서 user_id에 맞는 사용자 정보 가져오기
        $user = $this->Users_model->get_by_user_id($user_id);

        if ($user) {
            // DB에 저장된 비밀번호와 입력한 비밀번호 비교
            if ($user->user_pw === $user_pw) {
                $this->session->set_userdata('user_id', $user->user_id);
                redirect('/main');
            } else {
                echo "비밀번호가 틀렸습니다.";
            }
        } else {
            echo "아이디가 없습니다.";
        }
    }


}
