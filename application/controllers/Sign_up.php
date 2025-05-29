<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Sign_up 컨트롤러 클래스
 *
 * 회원가입 화면 표시와 회원가입 처리 기능을 담당합니다.
 *
 * 주요 기능:
 * - index(): 회원가입 폼을 보여줍니다.
 * - submit(): 폼에서 받은 아이디와 비밀번호를 AuthService의 sign_up 메서드로 전달해 회원가입 처리.
 *             성공 시 로그인 페이지로 리다이렉트, 실패 시 다시 회원가입 페이지로 리다이렉트합니다.
 *
 * AuthService 라이브러리를 통해 회원가입 로직과 세션 관리를 분리해 유지보수성을 높입니다.
 */
class Sign_up extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->library('AuthService');
    }

    public function index()
    {
        $this->render('sign_up/index');
    }

    public function submit()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');

        $result = $this->authservice->sign_up($user_id, $user_pw);

        if ($result['success']) {
            redirect('/login/index');
        } else {
            redirect('/sign_up/index');

        }
    }
}
