<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * MainService 클래스
 *
 * 게시판의 게시글 목록 조회, 검색, 페이징 처리 로직을 담당합니다.
 *
 * - 입력받은 컨텍스트 정보에 따라 페이지 번호, 검색어, 카테고리 등 파라미터를 정리
 * - 조건에 맞는 게시글 목록과 전체 개수를 모델에서 조회
 * - 결과를 가공해 컨트롤러에 반환
 * 
 * 구조:
 * - handle(array $context): 요청 유형(source)에 따라 필요한 파라미터 추출 및 게시글 데이터 반환
 * - 내부적으로 페이징 계산, 필터 조건 세팅, 모델 호출을 수행
 */
class MainService
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('Posts_model');
    }

    public function handle(array $context): array
    {
        $source = $context['source'];
        $input = $context['input'];
        $user_id = $context['user_id'] ?? null;

        // 기본값 설정
        $limit = 10;
        $page = 1;
        $offset = 0;
        $keyword = null;
        $category_id = 2;

        if ($source === 'index') {
            // 기본 값 그대로 사용
        }

        if ($source === 'search') {
            $keyword = trim($input['q'] ?? '');
            $page = isset($input['page']) ? (int)$input['page'] : 1;
        }

        if ($source === 'fetch_posts') {
            $limit = isset($input['page_option']) ? (int)$input['page_option'] : 10;
            $page = isset($input['page']) ? (int)$input['page'] : 1;
            $keyword = isset($input['keyword']) ? trim($input['keyword']) : null;
            $category_id = isset($input['category_id']) ? (int)$input['category_id'] : 2;
        }

        $offset = ($page - 1) * $limit;

        // 검색어 없고, 검색이면 빈 결과 리턴
        if ($source === 'search' && $keyword === '') {
            return [
                'posts' => [],
                'total_pages' => 0,
                'total_count' => 0,
            ];
        }

        // 필터 구성
        $filters = ['category_id' => $category_id];
        if (!empty($keyword)) {
            $filters['keyword'] = $keyword;
        }
        if ($category_id == 4 && $user_id) {
            $filters['user_id'] = $user_id;
        }

        $posts = $this->CI->Posts_model->get_posts($offset, $limit, $filters);
        $total = $this->CI->Posts_model->count_posts($filters);

        return [
            'posts' => $posts,
            'total_pages' => ceil($total / $limit),
            'total_count' => $total,
            'limit' => $limit,
            'current_page' => $page,
            'category_id' => $category_id
        ];
    }
}
