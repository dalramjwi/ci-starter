<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Users_model');
    }

    // 로그인 처리
    public function login($user_id, $user_pw)
    {
        $user = $this->CI->Users_model->get_by_user_id($user_id);
        if (!$user) {
            return ['success' => false, 'message' => '아이디가 없습니다.'];
        }

        if ($user->user_pw !== $user_pw) {
            return ['success' => false, 'message' => '비밀번호가 일치하지 않습니다.'];
        }

        $this->CI->session->set_userdata('user_id', $user->user_id);
        return ['success' => true];
    }

    // 로그아웃 처리
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
            return ['success' => false, 'message' => "$user_id 는 이미 존재하는 아이디입니다."];
        }

        $data = [
            'user_id' => $user_id,
            'user_pw' => $user_pw
        ];

        $result = $this->CI->Users_model->insert($data);

        if ($result) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => '회원가입이 실패했습니다.'];
        }
    }
}
