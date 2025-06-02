<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PostService
 * 
 * 게시글 상세 조회, 수정, 삭제에 대한 핵심 로직 처리
 */
class PostService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Posts_model');
        $this->CI->load->model('Comments_model');
        $this->CI->load->model('Categories_model');
        $this->CI->load->model('Posts_closure_model');
    }

    // 게시글 + 댓글 + 카테고리 정보 가져오기
    public function get_post_detail($post_id)
    {
        $post = $this->CI->Posts_model->get_post($post_id);
        $comments = $this->CI->Comments_model->get_comments_by_post($post_id);
        $category = null;

        if ($post) {
            $category = $this->CI->Categories_model->get_category($post->category_id);
        }

        return [
            'post' => $post,
            'comments' => $comments,
            'category' => $category
        ];
    }

    // 게시글 단건 가져오기
    public function get_post($post_id)
    {
        return $this->CI->Posts_model->get_post($post_id);
    }

    // 게시글 수정
    public function update_post($post_id, $title, $content)
    {
        $data = [
            'title' => $title,
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->CI->Posts_model->update_post($post_id, $data);
    }

    // 게시글 삭제
    public function delete_post($post_id)
    {
        $post = $this->CI->Posts_model->get_post($post_id);
        if (!$post) {
            return ['status' => 'fail', 'message' => '게시글이 존재하지 않습니다.'];
        }

        $descendants = $this->CI->Posts_model->get_descendants($post_id);

        // 자식글이 있는 경우
        if (!empty($descendants)) {
            foreach ($descendants as $descendant) {
                if ($descendant->user_id !== $post->user_id) {
                    return [
                        'status' => 'fail',
                        'message' => '자식 글 작성자가 다르므로 삭제할 수 없습니다.'
                    ];
                }
            }

            return ['status' => 'need_confirm']; // 자식글이 있고 모두 동일 작성자면 확인창 띄움
        }

        // 실제 삭제
        $this->CI->Posts_model->delete_post($post_id);
        return ['status' => 'success'];
    }
        // 자식 글 포함 전체 삭제 처리
    public function delete_post_with_descendants($post_id)
    {
        $descendants = $this->CI->Posts_closure_model->get_descendants($post_id);

        foreach ($descendants as $descendant) {
            $this->CI->Posts_model->delete_post($descendant->descendant);
        }

        $this->CI->Posts_model->delete_post($post_id);
    }

}
