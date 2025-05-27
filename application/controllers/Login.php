<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars(); 
        $this->load->model('Users_model');
    }

    // 로그인을 보여주는 메서드
    public function index()
    {
        $this->render('login/index');
    }

    // 로그인 요청 처리
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
                echo "<script>alert('비밀번호가 틀렸습니다.'); location.href = '" . base_url('login') . "';</script>";
                exit;
            }
        } else {
            echo "<script>alert('아이디가 없습니다.'); location.href = '" . base_url('login') . "';</script>";
            exit;
        }
    }

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->sess_destroy();
        redirect('/login');
    }

}
