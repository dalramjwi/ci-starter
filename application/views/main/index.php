
<?php if ($this->session->userdata('user_id')): ?>
    <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
<?php endif; ?>
<?php foreach($posts as $post): ?>
    <p>
        <?php
            // depth만큼 들여쓰기 접두어 만들기 (RE:, RE:RE:, ...)
            $prefix = str_repeat('RE:', $post->depth);

            // 출력 내용 결정
            if (!is_null($post->title)) {
                // 제목이 있으면 링크로 출력
                echo $prefix . ' <a href="' . base_url('main/view/' . $post->post_id) . '">'
                            . htmlspecialchars($post->title) . '</a>';
            } else {
                // 제목이 없으면 본문 내용 출력
                                echo $prefix . ' <a href="' . base_url('main/view/' . $post->post_id) . '">'
                            . htmlspecialchars($post->content) . '</a>';
            }
        ?>
    </p>
<?php endforeach; ?>
