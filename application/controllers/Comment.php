<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comment extends MY_Controller
{
    protected $commentService;

    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->library('CommentService');
        $this->commentService = $this->commentservice; // CI 소문자 로드 주의
    }

    public function create($post_id)
    {
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('comment');

        $result = $this->commentService->create_comment($post_id, $user_id, $content);

        echo $result ? "ok" : "fail";
    }

    public function delete($comment_id)
    {
        $user_id = $this->session->userdata('user_id');
        $result = $this->commentService->delete_comment($comment_id, $user_id);

        if ($result['status'] === 'success') {
            echo "<script>
                alert('댓글이 삭제되었습니다.');
                location.href = '" . base_url('post/view/' . $result['post_id']) . "';
            </script>";
        } else {
            echo "<script>
                alert('{$result['message']}');
                history.back();
            </script>";
        }
    }

    public function update($comment_id)
    {
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('content', true);

        $result = $this->commentService->update_comment($comment_id, $user_id, $content);

        if ($result['status'] === 'success') {
            echo "ok";
        } else {
            echo "<script>
                alert('{$result['message']}');
                history.back();
            </script>";
        }
    }
}
