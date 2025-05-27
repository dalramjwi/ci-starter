<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dummy extends CI_Controller {
    private $maxDepth = 5;
    private $maxChildren = 5;
    private $totalPosts = 10000;
    this->load->helper('utility_helper');

    public function generate() {
        $this->load->database();


        // 2. 게시글 데이터 저장용 변수 초기화
        $posts = []; // post_id, parent_id, depth, group_id, path
        $queue = []; // 자식 대기열 (post_id, depth, group_id, path)

        // 3. 최초 글 1개 생성 (root post)
        $root_post = [
            'user_id' => 'asdf',
            'title' => 'Root Post 1',
            'content' => 'This is the root post.',
            'depth' => 0,
            'group_id' => 0, // 나중에 자기 post_id로 업데이트
        ];

        $this->db->insert('posts', $root_post);
        $root_id = $this->db->insert_id();

        // group_id는 자기 post_id로 설정
        $this->db->where('post_id', $root_id)->update('posts', ['group_id' => $root_id]);

        $root_path = $this->base62_encode_custom($root_id);
        $this->db->insert('path', ['post_id' => $root_id, 'path' => $root_path]);

        // closure 테이블 본인 자신 추가
        $this->db->insert('posts_closure', [
            'ancestor' => $root_id,
            'descendant' => $root_id,
            'depth' => 0
        ]);

        // 큐에 추가
        $queue[] = ['post_id' => $root_id, 'depth' => 0, 'group_id' => $root_id, 'path' => $root_path];

        $currentCount = 1;

        while ($currentCount < $this->totalPosts && count($queue) > 0) {
            $parent = array_shift($queue);

            if ($parent['depth'] >= $this->maxDepth) {
                continue;
            }

            // 자식 개수 랜덤 (1~maxChildren)
            $childCount = rand(1, $this->maxChildren);

            for ($i = 0; $i < $childCount && $currentCount < $this->totalPosts; $i++) {
                $user_id = (rand(0,1) == 0) ? 'asdf' : 'dfg';

                $post_data = [
                    'user_id' => $user_id,
                    'title' => "Post {$currentCount}",
                    'content' => "Content for post {$currentCount}",
                    'depth' => $parent['depth'] + 1,
                    'group_id' => $parent['group_id'],
                ];

                $this->db->insert('posts', $post_data);
                $post_id = $this->db->insert_id();

                // path = 부모 path + base62_encode(post_id)
                $post_path = $parent['path'] . $this->base62_encode_custom($post_id);

                $this->db->insert('path', ['post_id' => $post_id, 'path' => $post_path]);

                // closure 테이블 삽입
                // 1) 자기 자신과 관계
                $this->db->insert('posts_closure', [
                    'ancestor' => $post_id,
                    'descendant' => $post_id,
                    'depth' => 0
                ]);

                // 2) 부모의 조상들 전부 연결
                $ancestors = $this->db->where('descendant', $parent['post_id'])->get('posts_closure')->result();
                foreach ($ancestors as $ancestor) {
                    $this->db->insert('posts_closure', [
                        'ancestor' => $ancestor->ancestor,
                        'descendant' => $post_id,
                        'depth' => $ancestor->depth + 1
                    ]);
                }

                $queue[] = ['post_id' => $post_id, 'depth' => $post_data['depth'], 'group_id' => $post_data['group_id'], 'path' => $post_path];

                $currentCount++;
            }
        }

        echo "총 {$currentCount}개의 게시글이 생성되었습니다.\n";
    }
}
