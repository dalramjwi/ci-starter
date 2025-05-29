<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->model('Comments_model');
    }

    // 댓글 작성
    public function create($post_id)
    {
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('comment');

        $inserted = $this->Comments_model->insert_comment([
            'post_id' => $post_id,
            'user_id' => $user_id,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if ($inserted) {
            echo "ok";
        } else {
            log_message('error', '댓글 DB 저장 실패');
            echo "fail";
        }
    }

    // 댓글 삭제
    public function delete($comment_id)
    {
        $user_id = $this->session->userdata('user_id');
        $comment = $this->Comments_model->get_comment($comment_id);

        if (!$comment) {
            echo "<script>
                    alert('댓글이 존재하지 않습니다.');
                    history.back();
                </script>";
            return;
        }

        if ($comment->user_id !== $user_id) {
            echo "<script>
                    alert('삭제 권한이 없습니다.');
                    history.back();
                </script>";
            return;
        }

        $deleted = $this->Comments_model->delete_comment($comment_id, $user_id);
        if ($deleted) {
            echo "<script>
                    alert('댓글이 삭제되었습니다.');
                    location.href = '" . base_url('post/view/' . $comment->post_id) . "';
                </script>";
        } else {
            echo "<script>
                    alert('댓글 삭제 실패');
                    history.back();
                </script>";
        }
    }

    // 댓글 수정
    public function update($comment_id)
    {
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('content', true);

        if (empty(trim($content))) {
            echo "<script>
                    alert('댓글 내용을 입력하세요.');
                    history.back();
                </script>";
            return;
        }

        $comment = $this->Comments_model->get_comment($comment_id);
        if (!$comment) {
            echo "<script>
                    alert('댓글이 존재하지 않습니다.');
                    history.back();
                </script>";
            return;
        }

        $updated = $this->Comments_model->update_comment($comment_id, [
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($updated) {
            echo "ok";
        } else {
            echo "<script>
                    alert('댓글 수정 실패');
                    history.back();
                </script>";
        }
    }
}
