<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Post extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->setCommonVars();
        $this->load->model('Posts_model');
        $this->load->model('Comments_model');
        $this->load->model('Categories_model');
    }

    // 게시글 상세 보기
    public function view($post_id)
    {
        $post = $this->Posts_model->get_post($post_id);
        $data['post'] = $post;
        $data['comments'] = $this->Comments_model->get_comments_by_post($post_id);

        if ($post) {
            $data['category'] = $this->Categories_model->get_category($post->category_id);
        }

        $this->render('post/view', $data);
    }

    // 게시글 수정 폼
    public function edit($post_id)
    {
        $data['post'] = $this->Posts_model->get_post($post_id);
        $this->render('post/edit', $data);
    }

    // 게시글 수정 처리
    public function update($post_id)
    {
        $title = $this->input->post('title');
        $content = $this->input->post('content');

        $data = [
            'title' => $title,
            'content' => $content,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->Posts_model->update_post($post_id, $data);

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
}
