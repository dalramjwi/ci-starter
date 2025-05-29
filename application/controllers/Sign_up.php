<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
            echo "<script>alert('{$result['message']}'); location.href = '" . base_url('sign_up') . "';</script>";
            exit;
        }
    }
}
