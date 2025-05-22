<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // 공통 변수 설정
        $this->setCommonVars(); 
        
        // 게시판 관련 모델 로드
        $this->load->model('Posts_model');
        $this->load->model('Comments_model');
    }

    public function index()
    {
        // 게시글 불러오기
        $data['title'] = '계층형 게시판 테스트';
        $all_posts = $this->Posts_model->get_all(); // 정렬되지 않은 모든 게시글
        $data['posts'] = $this->build_post_tree($all_posts); // 계층 구조로 정렬


        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/index', $data);
        $this->load->view('templates/footer');
    }
    public function view($post_id)
    {
        // 게시글 상세보기
        $data['title'] = '게시글 상세보기';
        $data['post'] = $this->Posts_model->get_post($post_id);
        $data['comments'] = $this->Comments_model->get_comments_by_post($post_id); // 댓글 조회
        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/view', $data);
        $this->load->view('templates/footer');
    }
    public function edit ($post_id)
    {
        // 게시글 수정 폼
        $data['title'] = '게시글 수정';
        $data['post'] = $this->Posts_model->get_post($post_id);

        // 뷰 로드
        $this->load->view('templates/header', $data);
        $this->load->view('main/edit', $data);
        $this->load->view('templates/footer');
    }
    public function update($post_id)
    {
        // 게시글 수정 처리
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
    public function delete($post_id)
    {
        // 게시글 삭제 처리
        $this->Posts_model->delete_post($post_id);

        redirect('/main');
    }

public function delete_comment($comment_id)
{
    $user_id = $this->session->userdata('user_id');

    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => '로그인이 필요합니다']);
        return;
    }

    // 댓글 정보 가져오기
    $comment = $this->Comments_model->get_comment($comment_id);
    if (!$comment) {
        echo json_encode(['success' => false, 'message' => '댓글이 존재하지 않습니다']);
        return;
    }

    // 작성자만 삭제 가능
    if ($comment->user_id !== $user_id) {
        echo json_encode(['success' => false, 'message' => '삭제 권한이 없습니다']);
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

    // public function comment($post_id)
    // {
    //     $user_id = $this->session->userdata('user_id');
    //     $content = $this->input->post('comment');

    //     // 부모 글 가져오기
    //     $parent_post = $this->Posts_model->get_post($post_id);

    //     // 부모 글이 없으면 depth 0, 있으면 부모 depth + 1
    //     $depth = $parent_post ? $parent_post->depth + 1 : 0;

    //     $data = [
    //         'user_id' => $user_id,
    //         'title' => null,          // 댓글에는 제목이 없으니까 null 처리
    //         'content' => $content,
    //         'created_at' => date('Y-m-d H:i:s'),
    //         'parent_id' => $post_id,
    //         'depth' => $depth,
    //         'is_popular' => false
    //     ];

    //     // 새 댓글/답글 저장
    //     $this->Posts_model->insert($data);

    //     // 댓글이 달린 게시글로 리다이렉트 (부모 글)
    //     redirect('/main/view/' . $post_id);
    // }
    private function build_post_tree($posts, $parent_id = null, $depth = 0)
    {
        $tree = [];

        foreach ($posts as $post) {
            if ($post->parent_id === $parent_id) {
                $post->depth = $depth;
                $tree[] = $post;

                // 자식도 재귀적으로 트리에 붙이기
                $children = $this->build_post_tree($posts, $post->post_id, $depth + 1);
                $tree = array_merge($tree, $children);
            }
        }

        return $tree;
    }
    public function view_option()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $option = $input['view_option'] ?? 'total';

        // 선택 옵션에 따라 게시글 조회
        if ($option === 'base') {
            $posts = $this->Posts_model->get_only_base(); // depth 0만 조회
        } else {
            $all_posts = $this->Posts_model->get_all(); // 전체
            $posts = $this->build_post_tree($all_posts); // 계층 구조로 정리
        }

        // 뷰를 문자열로 렌더링해서 응답
        $html = $this->load->view('main/post_list', ['posts' => $posts], true);

        echo json_encode(['html' => $html]);
    }

public function reply($post_id)
{
    $user_id = $this->session->userdata('user_id');
    $content = $this->input->post('reply');
    $title = $this->input->post('title');

    $parent_post = $this->Posts_model->get_post($post_id);

    if (!$parent_post) {
        show_error('부모 게시글이 존재하지 않습니다.');
        return;
    }

    $depth = $parent_post->depth + 1;
    $group_id = $parent_post->group_id;
    // 제목이 비어있으면 자동으로 생성
    if (empty(trim($title))) {
        $title = $parent_post->title . '의 답글입니다';
    }

    $data = [
        'user_id' => $user_id,
        'title' => $title,
        'content' => $content,
        'created_at' => date('Y-m-d H:i:s'),
        'parent_id' => $post_id,
        'depth' => $depth,
        'group_id' => $group_id,
        'is_popular' => false
    ];

    $this->Posts_model->insert($data);

    redirect('/main/index');
}

    public function comments($post_id)
{
    $user_id = $this->session->userdata('user_id');
    if (!$user_id) {
        show_error('로그인이 필요합니다.');
        return;
    }

    $content = $this->input->post('comment', true);
    if (!$content) {
        show_error('댓글 내용이 비어있습니다.');
        return;
    }

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

public function update_comment($comment_id)
{
    $this->load->model('Comment_model');
    $content = $this->input->post('content', true); // XSS 필터 적용

    if (empty(trim($content))) {
        echo 'empty_content';
        return;
    }

    // 기존 댓글 내용과 같으면 변경 불가 처리도 가능
    $original = $this->Comment_model->get_comment($comment_id);
    if ($original && trim($original->content) === trim($content)) {
        echo 'no_change';
        return;
    }

    $result = $this->Comment_model->update_comment($comment_id, ['content' => $content]);

    if ($result) {
        echo 'ok';
    } else {
        echo 'fail';
    }
}


}