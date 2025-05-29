<?php
/**
 * Start 컨트롤러
 *
 * 사이트의 시작(인트로) 화면을 보여주는 단순한 컨트롤러입니다.
 * 실제 기능은 없고, 디자인이 중심인 첫 화면을 렌더링합니다.
 *
 * 주요 기능:
 * - index(): 시작화면 뷰(start/index)를 출력합니다.
 */
class Start extends MY_Controller
{
        public function __construct()
        {
                parent::__construct();
                $this->setCommonVars();
        }

        public function index()
        {
                $this->load->view('start/index');
        }
}
