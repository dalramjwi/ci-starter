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

public function index()
{
    $data['title'] = '계층형 게시판 테스트';

    // 기본값으로 최신글 10개 total 조회
    $data['posts'] = $this->Posts_model->get_all();

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

public function update_comment($comment_id)
{
    $user_id = $this->session->userdata('user_id');
    if (!$user_id) {
        echo json_encode(['success' => false, 'message' => '로그인이 필요합니다']);
        return;
    }

    $content = $this->input->post('content', true);
    if (empty(trim($content))) {
        echo json_encode(['success' => false, 'message' => '댓글 내용을 입력하세요']);
        return;
    }

    $comment = $this->Comments_model->get_comment($comment_id);
    if (!$comment) {
        echo json_encode(['success' => false, 'message' => '댓글이 존재하지 않습니다']);
        return;
    }

    if ($comment->user_id !== $user_id) {
        echo json_encode(['success' => false, 'message' => '수정 권한이 없습니다']);
        return;
    }

    $updated = $this->Comments_model->update_comment($comment_id, ['content' => $content, 'updated_at' => date('Y-m-d H:i:s')]);

    if ($updated) {
                echo "ok";

    } else {
        echo json_encode(['success' => false, 'message' => '댓글 수정 실패']);
    }
}



public function reply($parent_id)
{
    $user_id = $this->session->userdata('user_id');
    $title = $this->input->post('title');
    $content = $this->input->post('reply');

    // 부모 정보 가져오기 (depth, path)
    $parent = $this->Posts_model->get_post($parent_id);
    $parent_path = $this->Path_model->get_path($parent_id); // ex: "0001/0012"
    $parent_depth = $parent->depth;

    // 최상위 부모 찾기 (답글 작성 시 group_id로 넣기 위함)
    $top_ancestor = $this->Posts_closure_model->get_top_ancestor($parent_id);
    $group_id = $top_ancestor->ancestor;


    // 제목 자동 생성
    if (empty(trim($title))) {
        $title = $parent->title . '의 답글입니다';
    }
    // posts에 insert
    $data = [
        'user_id' => $user_id,
        'title' => $title,
        'content' => $content,
        'created_at' => date('Y-m-d H:i:s'),
        'depth' => $parent_depth + 1,
        'group_id' => $group_id 
    ];
    $insert_id = $this->Posts_model->insert($data);

    // closure: 자기 자신
    $this->Posts_closure_model->insert($insert_id, $insert_id, 0);

    // closure: 부모의 모든 조상을 조회해서 추가
    $ancestors = $this->Posts_closure_model->get_ancestors($parent_id); 
    foreach ($ancestors as $ancestor) {
        $this->Posts_closure_model->insert($ancestor->ancestor, $insert_id, $ancestor->depth + 1);
    }

    // path: 부모 path + '/' + base62(post_id)
    $base62_id = base62_encode($insert_id);
    $path = $parent_path . '/' . $base62_id;
    $this->Path_model->insert($insert_id, $path);

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

public function fetch_posts()
{
    $input = json_decode(file_get_contents('php://input'), true);
    // $view_option = $input['view_option'] ?? 'total';
    $limit = isset($input['page_option']) ? (int)$input['page_option'] : 10;

    // if ($view_option === 'base') {
    //     // 최신순으로 parent_id가 NULL인 게시글 n개 조회
    //     $posts = $this->Posts_model->get_only_base_limit($limit);
    // } else {
    //     // total: 최신순 전체 게시글 n개 조회 후 트리 구조 정렬
    //     $all_posts = $this->Posts_model->get_all(0, $limit);
    //     $posts = $this->build_post_tree($all_posts);
    // }
        $posts = $this->Posts_model->get_all(0, $limit);


    $html = $this->load->view('main/post_list', ['posts' => $posts], true);
    echo json_encode(['html' => $html]);
}


}