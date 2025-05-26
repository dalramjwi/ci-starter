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
    $limit = 10;
    $offset = 0;

    $all_posts = $this->Posts_model->get_posts($offset, $limit);
    $total_count = $this->Posts_model->get_total_count();

    $data = [
        'posts' => $all_posts,
        'total_count' => $total_count,
        'limit' => $limit,
        'current_page' => 1,
    ];

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
    // 1) 해당 게시글 정보 가져오기
    $post = $this->Posts_model->get_post($post_id);
    if (!$post) {
        // 게시글 없으면 메인으로 리다이렉트
        redirect('/main');
        return;
    }

    // 2) 클로저 테이블에서 자손 글 모두 조회(depth >= 1)
    $descendants = $this->Posts_model->get_descendants($post_id); 
    // get_descendants 함수는 post_id의 자손 리스트 반환, depth 포함

    if (!empty($descendants)) {
        // 3) 자손 글 작성자 모두 검사 (작성자 동일해야 삭제 가능)
        foreach ($descendants as $descendant) {
            if ($descendant->user_id !== $post->user_id) {
                // 작성자가 다르면 삭제 불가, 메시지 출력 후 중단
                        echo "<script>
                alert('자식 글 작성자가 다르므로 삭제할 수 없습니다.');
                location.href = '" . base_url('main/view/' . $post_id) . "';
              </script>";
                return;
            }
        }
    }

    // 4) 작성자가 모두 같으면 게시글 + 자손 글 모두 삭제 처리
    // 자손 글 먼저 삭제 후 부모 글 삭제
    foreach ($descendants as $descendant) {
        $this->Posts_model->delete_post($descendant->post_id);
    }
    $this->Posts_model->delete_post($post_id);
                        echo "<script>
                alert('게시글 및 자손 글이 삭제되었습니다.');
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



public function fetch_posts()
{
    $input = json_decode(file_get_contents('php://input'), true);

    $limit = isset($input['page_option']) ? (int)$input['page_option'] : 10;
    $page = isset($input['page']) ? (int)$input['page'] : 1;
    $offset = ($page - 1) * $limit;

    $posts = $this->Posts_model->get_all($offset, $limit);
    $total_count = $this->Posts_model->get_total_count();

    $total_pages = ceil($total_count / $limit);

    $html = $this->load->view('main/post_list', ['posts' => $posts], true);

    echo json_encode([
        'html' => $html,
        'total_pages' => $total_pages,
        'current_page' => $page,
    ]);
}
public function search() {
    $keyword = trim($this->input->get('q')); 
    $page = $this->input->get('page') ?? 1;
    $per_page = 10;
    $offset = ($page - 1) * $per_page;

    if ($keyword === '') {
        $data['posts'] = [];
        $data['total_pages'] = 0;
        $data['current_page'] = 1;
        $data['keyword'] = '';
    } else {
        // $data['posts'] = $this->Posts_model->search_by_title($keyword, $offset, $per_page);
        $data['posts'] = $this->Posts_model->get_posts($offset, $per_page, $keyword);
        $total = $this->Posts_model->search_count($keyword);
        $data['total_pages'] = ceil($total / $per_page);
        $data['current_page'] = $page;
        $data['keyword'] = $keyword;
    }

    $this->load->view('main/result', $data);
}

}