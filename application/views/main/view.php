<!-- 게시글 제목 -->
<h2 class="post-title"><?php echo htmlspecialchars($post->title); ?></h2>

<!-- 작성자 + 작성일 -->
<div class="post-header">
    <div></div> <!-- 왼쪽 공간 비움 -->
    <div class="post-meta">
        <span>작성자: <?php echo htmlspecialchars($post->user_id); ?></span>
        <span>작성일: <?php echo $post->created_at; ?></span>
    </div>
</div>

<!-- 본문 내용 -->
<p class="post-content"><?php echo nl2br(htmlspecialchars($post->content)); ?></p>

<!-- 수정/삭제/답글 버튼 줄 -->
<?php if ($this->session->userdata('user_id')): ?>
    <div class="post-actions-row">
        <button class="reply-open-btn">답글 작성</button>

        <?php if ($this->session->userdata('user_id') == $post->user_id): ?>
            <div class="post-actions">
                <a href="<?php echo base_url('main/edit/' . $post->post_id); ?>" class="post-btn-edit">수정</a>
                <a href="<?php echo base_url('main/delete/' . $post->post_id); ?>" class="post-btn-delete">삭제</a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- 답글 모달 -->
<div class="reply-modal" id="replyModal">
    <div class="reply-modal-content">
        <span class="reply-close-btn">&times;</span>
        <form action="<?php echo base_url('write/wrote'); ?>" method="post" class="reply-form">
            <input type="hidden" name="parent_id" value="<?php echo $post->post_id; ?>">
            <input type="text" name="title" placeholder="제목 미작성 시 자동 생성됩니다." class="reply-input-title">
            <textarea name="content" required placeholder="답글 내용을 입력하세요." class="reply-textarea"></textarea>
            <button type="submit" class="reply-submit">답글 작성</button>
        </form>

    </div>
</div>

<!-- 댓글 필드 -->
<?php if ($this->session->userdata('user_id')): ?>
<form action="<?php echo base_url('main/comments/' . $post->post_id); ?>" method="post" class="comment-form">
    <div class="comment-field">
        <label for="comment">댓글:</label>
        <textarea name="comment" id="comment" required></textarea>
        <button type="submit" class="btn-comment">댓글 작성</button>
    </div>
</form>
<?php endif; ?>

<!-- 댓글 목록 -->
<div>
    <?php if (!empty($comments)): ?>
        <h3>댓글 목록</h3>
        <ul class="comment-list">
            <?php foreach ($comments as $comment): ?>
                <li>
                    <strong><?php echo htmlspecialchars($comment->user_id); ?></strong>:
                    <span class="comment-content"><?php echo nl2br(htmlspecialchars($comment->content)); ?></span>
                    <span class="comment-date"><?php echo $comment->created_at; ?></span>
                    <?php if ($this->session->userdata('user_id') == $comment->user_id): ?>
                        <a href="<?php echo base_url('main/edit_comment/' . $comment->comment_id); ?>" class="btn-edit">수정</a>
                        <a href="<?php echo base_url('main/delete_comment/' . $comment->comment_id); ?>" class="btn-delete">삭제</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>댓글이 없습니다.</p>
    <?php endif; ?>
</div>
