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
                <a href="<?php echo base_url('main/edit/' . $post->post_id); ?>" class="btn-edit">수정</a>
                <a href="<?php echo base_url('main/delete/' . $post->post_id); ?>" class="btn-delete">삭제</a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
<!-- 답글 모달 -->
<div class="reply-modal" id="replyModal">
    <div class="reply-modal-content">
        <span class="reply-close-btn">&times;</span>
        <form action="<?php echo base_url('main/reply/' . $post->post_id); ?>" method="post" class="reply-form">
            <input type="text" name="title" placeholder="제목 미작성 시 자동으로 작성됩니다." class="reply-input-title">
            <textarea name="reply" required placeholder="답글 내용을 입력하세요." class="reply-textarea"></textarea>
            <button type="submit" class="reply-submit">답글 작성</button>
        </form>
    </div>
</div>
<!-- 댓글 필드 -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("replyModal");
        const openBtn = document.querySelector(".reply-open-btn");
        const closeBtn = document.querySelector(".reply-close-btn");

        // 열기 버튼 클릭 시 모달 표시
        openBtn.addEventListener("click", function () {
            modal.style.display = "block";
        });

        // 닫기 버튼 클릭 시 모달 닫기
        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
        });

        // 바깥 클릭 시 닫기
        window.addEventListener("click", function (event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
</script>
