<?php

/**
 * post_controller_constructor
 *
 * 컨트롤러 메소드가 실행되기전 필요한 처리(컨트롤러 인스턴스화 직후)
 */
class post_controller_constructor
{

    private $ci = NULL;

    public function init()
    {
        $this->ci =& get_instance();
        //지금 내가 어떤 페이지(컨트롤러+메서드)를 보고 있는지 확인하기 위한 상수 정의
        define('_CONTROLLERS', $this->ci->router->fetch_class());
        define('_METHOD', $this->ci->router->fetch_method());
        //현재 library에 user_agent가 없기에 system의 user_agent를 사용
        $this->ci->load->library("user_agent");
        define('_IS_MOBILE', $this->ci->agent->is_mobile());
    }
}