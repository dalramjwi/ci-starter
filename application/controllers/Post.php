<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * 게시글 보기/수정/삭제 기능 컨트롤러
 *
 * 주요 역할:
 * - URL 요청 처리
 * - PostService 호출
 * - 뷰 렌더링
 */
class Post extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->library('PostService');
    }

    // 게시글 상세 보기
    public function view($post_id)
    {
        $data = $this->postservice->get_post_detail($post_id);
        $this->render('post/view', $data);
    }

    // 게시글 수정 폼 보기
    public function edit($post_id)
    {
        $data['post'] = $this->postservice->get_post($post_id);
        $this->render('post/edit', $data);
    }

    // 게시글 수정 처리
    public function update($post_id)
    {
        $title = $this->input->post('title');
        $content = $this->input->post('content');

        $this->postservice->update_post($post_id, $title, $content);
        redirect('/post/view/' . $post_id);
    }

    // 게시글 삭제
    public function delete($post_id)
    {
        $post = $this->Posts_model->get_post($post_id);
        if (!$post) {
            redirect('/main');
            return;
        }

        $descendants = $this->Posts_model->get_descendants($post_id);

        if (!empty($descendants)) {
            foreach ($descendants as $descendant) {
                if ($descendant->user_id !== $post->user_id) {
                    echo "<script>
                        alert('자식 글 작성자가 다르므로 삭제할 수 없습니다.');
                        location.href = '" . base_url('post/view/' . $post_id) . "';
                    </script>";
                    return;
                }
            }

            echo "<script>
                if (confirm('작성자가 동일한 자식 글이 있습니다.\\n자식 글까지 모두 삭제하시겠습니까?')) {
                    location.href = '" . base_url('post/delete_confirm/' . $post_id) . "';
                } else {
                    location.href = '" . base_url('post/view/' . $post_id) . "';
                }
            </script>";
            return;
        }

        $this->Posts_model->delete_post($post_id);
        echo "<script>
            alert('게시글이 삭제되었습니다.');
            location.href = '" . base_url('main') . "';
        </script>";
    }
        public function delete_confirm($post_id)
    {
        $this->postservice->delete_post_with_descendants($post_id);
        echo "<script>
            alert('게시글과 자식 글이 모두 삭제되었습니다.');
            location.href = '" . base_url('main') . "';
        </script>";
    }
}
