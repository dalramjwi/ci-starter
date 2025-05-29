<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Users_model');
        $this->CI->load->library('session'); 
    }

    // 로그인 처리
    public function login($user_id, $user_pw)
    {
        $user = $this->CI->Users_model->get_by_user_id($user_id);
        if (!$user) {
            $this->CI->session->set_flashdata('message', '아이디가 없습니다.');
            return ['success' => false];
        }

        if ($user->user_pw !== $user_pw) {
            $this->CI->session->set_flashdata('message', '비밀번호가 일치하지 않습니다.');
            return ['success' => false];
        }

        $this->CI->session->set_userdata('user_id', $user->user_id);
        $this->CI->session->set_flashdata('message', '로그인 성공했습니다.');
        return ['success' => true];
    }

    // 로그아웃 처리
        //로그아웃 시에는 토스트 출력되지 않음. (세션이 삭제되기 때문에)
    public function logout()
    {
        $this->CI->session->unset_userdata('user_id');
        $this->CI->session->sess_destroy();
    }

    // 회원가입 처리
    public function sign_up($user_id, $user_pw)
    {
        $exists = $this->CI->Users_model->get_by_user_id($user_id);
        if ($exists) {
            $this->CI->session->set_flashdata('message', "$user_id 는 이미 존재하는 아이디입니다.");
            return ['success' => false];
        }

        $data = [
            'user_id' => $user_id,
            'user_pw' => $user_pw
        ];

        $result = $this->CI->Users_model->insert($data);

        if ($result) {
            $this->CI->session->set_flashdata('message', '회원가입이 성공했습니다.');
            return ['success' => true];
        } else {
            $this->CI->session->set_flashdata('message', '회원가입이 실패했습니다.');
            return ['success' => false];
        }
    }
}
