<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dummy extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('WriteService');
        $this->load->helper('text');
        $this->load->helper('utility_helper');
    }
public function seed()
{
    $user_id = 'dfg'; // users 테이블에 반드시 존재해야 함
    $category_ids = [2, 3, 4];

    $writeService = new WriteService();

    for ($i = 0; $i < 100; $i++) {
        $title = "테스트 본글 #$i";
        $content = "이것은 본문 내용입니다. 본글 번호: $i";
        $random_category_id = $category_ids[array_rand($category_ids)];

        // 본글 작성
        $result = $writeService->write_post($user_id, $title, $content, null, $random_category_id);

        if (!$result['success']) {
            echo "본글 작성 실패 #$i\n";
            continue;
        }

        // 본글 ID 받아서 재귀 답글 시작
        $root_post_id = $result['insert_id'];

        // 랜덤 깊이 (1~10) 재귀 답글 작성
        $this->create_replies_recursive($writeService, $user_id, $root_post_id, 1, rand(1, 15));
    }

    echo "Seed 데이터 입력 완료!";
}

private function create_replies_recursive($writeService, $user_id, $parent_id, $current_depth, $max_depth)
{
    if ($current_depth > $max_depth) {
        return;
    }

    $title = "답글 (depth $current_depth)";
    $content = "이것은 답글 내용입니다. 깊이: $current_depth";

    $result = $writeService->write_post($user_id, $title, $content, $parent_id);

    if (!$result['success']) {
        return;
    }

    $reply_post_id = $result['insert_id'];

    $this->create_replies_recursive($writeService, $user_id, $reply_post_id, $current_depth + 1, $max_depth);
}


}
