<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CommentService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Comments_model');
    }

    // 댓글 작성
    public function create_comment($post_id, $user_id, $content)
    {
        $data = [
            'post_id' => $post_id,
            'user_id' => $user_id,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->CI->Comments_model->insert_comment($data);
    }

    // 댓글 삭제 권한 확인 후 삭제
    public function delete_comment($comment_id, $user_id)
    {
        $comment = $this->CI->Comments_model->get_comment($comment_id);

        if (!$comment) {
            return ['status' => 'fail', 'message' => '댓글이 존재하지 않습니다.'];
        }

        if ($comment->user_id !== $user_id) {
            return ['status' => 'fail', 'message' => '삭제 권한이 없습니다.'];
        }

        $deleted = $this->CI->Comments_model->delete_comment($comment_id, $user_id);

        if ($deleted) {
            return ['status' => 'success', 'post_id' => $comment->post_id];
        } else {
            return ['status' => 'fail', 'message' => '댓글 삭제 실패'];
        }
    }

    // 댓글 수정 권한 확인 후 수정
    public function update_comment($comment_id, $user_id, $content)
    {
        if (empty(trim($content))) {
            return ['status' => 'fail', 'message' => '댓글 내용을 입력하세요.'];
        }

        $comment = $this->CI->Comments_model->get_comment($comment_id);

        if (!$comment) {
            return ['status' => 'fail', 'message' => '댓글이 존재하지 않습니다.'];
        }

        if ($comment->user_id !== $user_id) {
            return ['status' => 'fail', 'message' => '수정 권한이 없습니다.'];
        }

        $updated = $this->CI->Comments_model->update_comment($comment_id, [
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            return ['status' => 'success'];
        } else {
            return ['status' => 'fail', 'message' => '댓글 수정 실패'];
        }
    }
}
