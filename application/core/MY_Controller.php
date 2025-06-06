<?php

class MY_Controller extends CI_Controller
{
    # Parameter reference
    public $params = array();

    public $cookies = array();

    public function __construct()
    {

        parent::__construct();
        date_default_timezone_set('Asia/Seoul');
        # Parameter
        $this->params = $this->getParams();
        $this->cookies = $this->getCookies();
    }

    private function getParams()
    {

        $aParams = array_merge($this->doGet(), $this->doPost());

        //$this->sql_injection_filter($aParams);

        return $aParams;
    }


    private function getCookies()
    {

        return $this->doCookie();
    }


    private function doGet()
    {
        $aGetData = $this->input->get(NULL, TRUE);
        return (empty($aGetData)) ? array() : $aGetData;
    }

    private function doPost()
    {
        $aPostData = $this->input->post(NULL, TRUE);
        return (empty($aPostData)) ? array() : $aPostData;
    }

    private function doCookie()
    {
        $aCookieData = $this->input->cookie(NULL, TRUE);

        return (empty($aCookieData)) ? array() : $aCookieData;
    }

    public function js($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setJs($sValue, $v);
            }
        } else {
            $this->optimizer->setJs($file, $v);
        }
    }

    public function externaljs($file)
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setExternalJs($sValue);
            }
        } else {
            $this->optimizer->setExternalJs($file);
        }
    }

    public function css($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setCss($sValue, $v);
            }
        } else {
            $this->optimizer->setCss($file, $v);
        }
    }

    /**
     *  변수 셋팅
     */
    public function setVars($arr = array())
    {
        foreach ($arr as $val) {
            $aVars;
        }

        $this->load->vars($aVars);
    }

    /**
     *  공통 전역 변수 셋팅
     */
    public function setCommonVars()
    {
        $aVars = array(
            'company_name' => '예람',
            'site_name' => '게시판 페이지',
            'writepagename' => '글쓰기 페이지',
            'login' => '로그인',
            'logout' => '로그아웃',
            'sign_up' => '회원가입'
        );

        $this->load->vars($aVars);
    }
    /**
     * 공통 페이지 랜더링 세팅
     * @param $view 경로,$data 데이터
     */
    public function render($view, $data = array())
    {
        $this->load->view('templates/header');
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }

}
