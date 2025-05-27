<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // 공통 변수 설정
        $this->setCommonVars();
        
        $this->load->model('Posts_model');
        $this->load->model('Comments_model');
        $this->load->model('Posts_closure_model');
        $this->load->model('Path_model');
        $this->load->helper('utility_helper');
    }
    // 게시글 데이터를 준비하는 메서드
    private function prepare_post_data($offset, $limit, $keyword = null)
    {
        $posts = $this->Posts_model->get_posts($offset, $limit, $keyword);
        $total = $keyword ? $this->Posts_model->search_count($keyword) : $this->Posts_model->get_total_count();

        return [
            'posts' => $posts,
            'total_count' => $total,
            'total_pages' => ceil($total / $limit),
        ];
    }

    // 메인 페이지
    public function index()
    {
        $limit = 10;
        $offset = 0;
        $data = $this->prepare_post_data($offset, $limit);

        $data['limit'] = $limit;
        $data['current_page'] = 1;
        $data['keyword'] = '';
        $this->render('main/index', $data);
    }
    // 게시글 검색
    public function search() 
    {
        $keyword = trim($this->input->get('q'));
        $page = $this->input->get('page') ?? 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $data = ($keyword === '')
            ? ['posts' => [], 'total_pages' => 0, 'total_count' => 0]
            : $this->prepare_post_data($offset, $limit, $keyword);

        $data['limit'] = $limit;
        $data['current_page'] = $page;
        $data['keyword'] = $keyword;

        $this->render('main/index', $data);
    }
    // 게시글 목록을 AJAX로 불러오는 메서드
    public function fetch_posts()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $limit = isset($input['page_option']) ? (int)$input['page_option'] : 10;
        $page = isset($input['page']) ? (int)$input['page'] : 1;
        $offset = ($page - 1) * $limit;
        $keyword = isset($input['keyword']) ? trim($input['keyword']) : null;

        $result = $this->prepare_post_data($offset, $limit, $keyword);

        $html = $this->load->view('main/post_list', ['posts' => $result['posts']], true);

        // JSON으로 응답
        echo json_encode([
            'html' => $html,
            'total_pages' => $result['total_pages'],
            'current_page' => $page,
        ]);
    }
    /**
     * 게시글 CRUD 기능
     */
    // 게시글 작성 조회
    public function view($post_id)
    {
        $data['post'] = $this->Posts_model->get_post($post_id);
        $data['comments'] = $this->Comments_model->get_comments_by_post($post_id);
        $this->render('main/view', $data);
    }
    //게시글 수정 메서드
    public function edit ($post_id)
    {
        $data['post'] = $this->Posts_model->get_post($post_id);
        $this->render('main/edit', $data);
    }
    //게시글 수정 처리 메서드
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

        redirect('/main/view/' . $post_id);
    }
    //게시글 삭제 메서드
    public function delete($post_id)
    {
        // 1) 해당 게시글 정보 가져오기
        $post = $this->Posts_model->get_post($post_id);
        if (!$post) {
            redirect('/main');
            return;
        }

        // 2) 클로저 테이블에서 자손 글 모두 조회
        $descendants = $this->Posts_model->get_descendants($post_id);

        if (!empty($descendants)) {
            // 3) 자손 글 작성자 모두 검사
            foreach ($descendants as $descendant) {
                if ($descendant->user_id !== $post->user_id) {
                    echo "<script>
                        alert('자식 글 작성자가 다르므로 삭제할 수 없습니다.');
                        location.href = '" . base_url('main/view/' . $post_id) . "';
                    </script>";
                    return;
                }
            }

            // 4-1) 작성자 모두 같을 경우 → 사용자에게 삭제 여부 확인
            echo "<script>
                if (confirm('작성자가 동일한 자식 글이 있습니다.\\n자식 글까지 모두 삭제하시겠습니까?')) {
                    location.href = '" . base_url('main/delete_confirm/' . $post_id) . "';
                } else {
                    location.href = '" . base_url('main/view/' . $post_id) . "';
                }
            </script>";
            return;
        }

        // 4-2) 자손이 없는 경우 → 바로 삭제
        $this->Posts_model->delete_post($post_id);
        echo "<script>
            alert('게시글이 삭제되었습니다.');
            location.href = '" . base_url('main') . "';
        </script>";
    }

    /**
     * 댓글 CRUD 기능
     */
    //댓글 작성
    public function comments($post_id)
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
    //댓글 삭제
    public function delete_comment($comment_id)
    {
        $user_id = $this->session->userdata('user_id');
        $comment = $this->Comments_model->get_comment($comment_id);
        // 댓글 미존재
        if (!$comment) {
            echo "<script>
                    alert('댓글이 존재하지 않습니다.');
                    history.back();
                </script>";
            return;
        }

        // 작성자 권한 없음
        if ($comment->user_id !== $user_id) {
            echo "<script>
                    alert('삭제 권한이 없습니다.');
                    history.back();
                </script>";
            return;
        }

        // 삭제 시도
        $deleted = $this->Comments_model->delete_comment($comment_id, $user_id);
        if ($deleted) {
            echo "<script>
                    alert('댓글이 삭제되었습니다.');
                    location.href = '" . base_url('main/view/' . $comment->post_id) . "';
                </script>";
        } else {
            echo "<script>
                    alert('댓글 삭제 실패');
                    history.back();
                </script>";
        }
    }
    //댓글 수정
    public function update_comment($comment_id)
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