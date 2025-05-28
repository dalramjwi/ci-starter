<div>
    <div>제목</div>
    <div>작성자</div>
    <div>작성일</div>
</div>
<?php if (empty($posts)): ?>
    <p>게시물이 없습니다.</p>
<?php else: ?>
    <?php foreach($posts as $post): ?>
        <?php
            $prefix = str_repeat('RE:', $post->depth);
        ?>
        <div style="font-size: 0.85em; color: #777;"><?= $post->post_id ?></div>
        <div class="post-item" style="margin-left: <?= $post->depth * 20 ?>px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <?= $prefix ?>
                <a href="<?= base_url('main/view/' . $post->post_id) ?>">
                    <?= htmlspecialchars($post->title) ?>
                </a>
            </div>
            <?php if ($category_id == 1): ?>
                <div style="font-size: 0.85em; color: #777;">관리자</div>
            <?php elseif ($category_id == 3): ?>
                <div style="font-size: 0.85em; color: #777;"> </div>
            <?php else: ?>
                <div style="font-size: 0.85em; color: #777;"><?= $post->user_id ?></div>
            <?php endif; ?>

            <div class="post-time" style="font-size: 0.85em; color: #777;">
                <?= date('Y-m-d H:i', strtotime($post->created_at)) ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>