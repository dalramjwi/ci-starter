<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Login 컨트롤러 클래스
 *
 * 사용자의 로그인과 로그아웃 처리를 담당합니다.
 * 
 * 주요 기능:
 * - index(): 로그인 폼을 보여줍니다.
 * - submit(): 로그인 폼에서 받은 아이디와 비밀번호로 AuthService의 로그인 처리 메서드를 호출합니다.
 *             성공 시 메인 페이지로 리다이렉트, 실패 시 다시 로그인 폼으로 리다이렉트합니다.
 * - logout(): AuthService의 로그아웃 메서드를 호출하여 세션을 정리하고 메인 페이지로 이동합니다.
 *
 * AuthService 라이브러리를 사용하여 인증 관련 로직을 분리해 관리합니다.
 */
class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->library('AuthService');
    }

    public function index()
    {
        $this->render('login/index');
    }

    public function submit()
    {
        $user_id = $this->input->post('user_id');
        $user_pw = $this->input->post('user_pw');

        $result = $this->authservice->login($user_id, $user_pw);

        if ($result['success']) {
            redirect('/main');
        } else {
            redirect('/login/index');
        }
    }

    public function logout()
    {
        $this->authservice->logout();
        redirect('/main/index');
    }
}
