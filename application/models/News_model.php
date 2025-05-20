<?php
class News_model extends CI_Model {

        public function __construct()
        {
                // 데이터베이스 연결
                $this->load->database();
        }

        // 뉴스 전체 가져오기 또는 slug로 특정 뉴스 가져오기
        public function get_news($slug = FALSE)
        {
                if ($slug === FALSE)
                {
                        // 전체 뉴스 가져오기 (결과를 배열로 반환)
                        $query = $this->db->get('news');
                        return $query->result_array();
                }

                // 특정 slug에 해당하는 뉴스 한 개 가져오기 (결과는 배열 하나)
                $query = $this->db->get_where('news', array('slug' => $slug));
                return $query->row_array();
        }
}
