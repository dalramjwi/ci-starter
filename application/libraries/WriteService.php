<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * WriteService 클래스
 *
 * 이 클래스는 게시글(원글 또는 답글) 작성 기능을 담당합니다.
 * 
 * 주요 기능:
 * - write_post: 원글 또는 답글을 구분하여 작성 처리합니다.
 * - write_root_post: 원글 작성 처리, 그룹 ID와 경로 정보 설정 포함.
 * - write_reply_post: 답글 작성 처리, 계층 구조에 맞는 클로저 테이블 및 경로 정보 설정.
 *
 * 실패 시에는 사용자에게 전달할 수 있도록 flashdata에 실패 메시지를 설정합니다.
 * 
 * 사용 모델:
 * - Posts_model: 게시글 저장 및 그룹 ID 업데이트.
 * - Posts_closure_model: 계층 정보 관리용 클로저 테이블 조작.
 * - Path_model: 게시글 정렬 및 계층 경로 관리용 path 기록.
 * 
 * 사용 헬퍼:
 * - utility_helper: base62_encode 유틸 함수 사용.
 */
class WriteService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Posts_model');
        $this->CI->load->model('Posts_closure_model');
        $this->CI->load->model('Path_model');
        $this->CI->load->helper('utility_helper');
    }

    public function write_post($user_id, $title, $content, $parent_id = null, $category_id = null)
    {
        // 로그인 하지 않은 사용자는 작성 불가
        if (empty($user_id)) {
            $this->CI->session->set_flashdata('message', '로그인이 필요합니다.');
            return ['success' => false];
        }

        if (empty($parent_id)) {
            //원글 작성
            return $this->write_root_post($user_id, $title, $content, $category_id);
        } else {
            //답글 작성
            return $this->write_reply_post($user_id, $title, $content, $parent_id);
        }
    }
    /**
     * 루트 게시글 작성 처리
     *
     * - group_id는 자기 자신 ID로 업데이트
     * - 클로저 테이블에 자기 자신에 대한 정보 삽입
     * - path 테이블에 base62로 인코딩된 경로 기록
     *
     * @return array 성공 여부
     */
    protected function write_root_post($user_id, $title, $content, $category_id)
    {
        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'depth' => 0,
            'group_id' => 0,
            'category_id' => $category_id
        ];

        $insert_id = $this->CI->Posts_model->insert($data);
        if (!$insert_id) {
            $this->CI->session->set_flashdata('message', '게시글 작성 실패');
            return ['success' => false];
        }

        $this->CI->Posts_model->update_group_id($insert_id, $insert_id);
        $this->CI->Posts_closure_model->insert($insert_id, $insert_id, 0);

        $path = base62_encode($insert_id);
        $this->CI->Path_model->insert($insert_id, $path);
//! insert_id는 test 코드 용으로 쓰임
        return ['success' => true, 'insert_id' => $insert_id];
    }
    /**
     * 답글 게시글 작성 처리
     *
     * - 부모 글의 계층 정보, 카테고리 정보 복사
     * - 클로저 테이블에 부모 및 조상들과의 관계 삽입
     * - path 테이블에 부모 경로 + 본인 ID 경로로 기록
     *
     * @return array 성공 여부
     */
    protected function write_reply_post($user_id, $title, $content, $parent_id)
    {
        $parent = $this->CI->Posts_model->get_post($parent_id);
        if (!$parent) {
            $this->CI->session->set_flashdata('message', '원글을 찾을 수 없습니다.');
            return ['success' => false];
        }

        $parent_path = $this->CI->Path_model->get_path($parent_id);
        $parent_depth = $parent->depth;
        $parent_category_id = $parent->category_id;

        $top_ancestor = $this->CI->Posts_closure_model->get_top_ancestor($parent_id);
        $group_id = $top_ancestor->ancestor;

        if (empty(trim($title))) {
            $title = $parent->title . '의 답글입니다';
        }

        $data = [
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s'),
            'depth' => $parent_depth + 1,
            'group_id' => $group_id,
            'category_id' => $parent_category_id
        ];

        $insert_id = $this->CI->Posts_model->insert($data);
        if (!$insert_id) {
            $this->CI->session->set_flashdata('message', '답글 작성을 실패했습니다.');
            return ['success' => false];
        }

        $this->CI->Posts_closure_model->insert($insert_id, $insert_id, 0);

        $ancestors = $this->CI->Posts_closure_model->get_ancestors($parent_id);
        foreach ($ancestors as $ancestor) {
            $this->CI->Posts_closure_model->insert($ancestor->ancestor, $insert_id, $ancestor->depth + 1);
        }

        $path = $parent_path . '/' . base62_encode($insert_id);
        $this->CI->Path_model->insert($insert_id, $path);

        return ['success' => true, 'insert_id' => $insert_id];
    }
}
