<h2 class="post-title"><?php echo htmlspecialchars($post->title); ?></h2>
<p class="post-author">작성자: <?php echo htmlspecialchars($post->user_id); ?></p>
<p class="post-date">작성일: <?php echo $post->created_at; ?></p>
<p class="post-content"><?php echo nl2br(htmlspecialchars($post->content)); ?></p>

<?php if ($this->session->userdata('user_id') == $post->user_id): ?>
    <div class="post-actions">
        <a href="<?php echo base_url('main/edit/' . $post->post_id); ?>" class="btn-edit">수정</a>
        <a href="<?php echo base_url('main/delete/' . $post->post_id); ?>" class="btn-delete">삭제</a>
    </div>
<?php endif; ?>

//이미 달려진 답글이 보일 장소. ajax 처리
<?php if ($this->session->userdata('user_id')): ?>
    <form action="<?php echo base_url('main/reply/' . $post->post_id); ?>" method="post" class="reply-form">
        <input type="text" name="title" placeholder="제목 미작성 시 자동으로 작성됩니다." class="reply-input-title">
        <textarea name="reply" required placeholder="답글 내용을 입력하세요." class="reply-textarea"></textarea>
        <button type="submit" class="reply-submit">답글 작성</button>
    </form>
<?php endif; ?>
