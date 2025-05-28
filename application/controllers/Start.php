<?php
class Start extends MY_Controller {
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