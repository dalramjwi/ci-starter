<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
            echo "<script>alert('{$result['message']}'); location.href = '" . base_url('login') . "';</script>";
            exit;
        }
    }

    public function logout()
    {
        $this->authservice->logout();
        redirect('/login');
    }
}
