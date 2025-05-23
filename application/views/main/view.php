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
<!-- 부모 글로 이동
<?php if (!is_null($post->parent_id)): ?>
    <div class="go-to-parent">
        <a href="<?php echo base_url('main/view/' . $post->parent_id); ?>" class="btn-goto-parent">본문으로 가기</a>
    </div>
<?php endif; ?> -->

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
        <form action="<?php echo base_url('main/reply/' . $post->post_id); ?>" method="post" class="reply-form">
            <input type="text" name="title" placeholder="제목 미작성 시 자동으로 작성됩니다." class="reply-input-title">
            <textarea name="reply" required placeholder="답글 내용을 입력하세요." class="reply-textarea"></textarea>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("replyModal");
    const openBtn = document.querySelector(".reply-open-btn");
    const closeBtn = document.querySelector(".reply-close-btn");

    // 모달 열기
    openBtn?.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // 모달 닫기
    closeBtn?.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // 바깥 클릭 시 닫기
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // 댓글 비동기 작성 처리
    const commentForm = document.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const actionUrl = this.getAttribute('action');

            fetch(actionUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text()) 
            .then(data => {
                if (data.trim() === 'ok') {
                    location.reload(); 
                } else {
                    alert('댓글 작성 실패: 서버 응답 이상\n' + data);
                }
            })
            .catch(error => {
                console.error('댓글 작성 실패:', error);
                alert('댓글 작성 중 오류가 발생했습니다.');
            });
        });
    }

  // 댓글 수정 인라인 처리
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const li = this.closest('li');
            const contentSpan = li.querySelector('.comment-content');
            const originalContent = contentSpan.textContent.trim();

            if (li.querySelector('textarea')) return; // 이미 수정 모드면 무시

            // 기존 내용 숨김
            contentSpan.style.display = 'none';

            // textarea 생성 + 기존 내용 넣기
            const textarea = document.createElement('textarea');
            textarea.classList.add('edit-textarea');
            textarea.value = originalContent;
            li.insertBefore(textarea, contentSpan.nextSibling);

            // 수정 완료 버튼 생성
            const saveBtn = document.createElement('button');
            saveBtn.textContent = '수정 완료';
            saveBtn.classList.add('btn-save');
            li.insertBefore(saveBtn, textarea.nextSibling);

            // 수정 취소 버튼 생성
            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = '수정 취소';
            cancelBtn.classList.add('btn-cancel');
            li.insertBefore(cancelBtn, saveBtn.nextSibling);

            // 수정 완료 클릭 이벤트
            saveBtn.addEventListener('click', () => {
                const newContent = textarea.value.trim();

                if (newContent === '') {
                    alert('댓글 내용을 입력하세요.');
                    return;
                }

                if (newContent === originalContent) {
                    alert('변경된 내용이 없습니다.');
                    return;
                }

                // comment_id 가져오기 (URL에서 추출)
                const commentId = button.href.split('/').pop();
                const updateUrl = button.href.replace('edit_comment', 'update_comment');

                fetch(updateUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'content=' + encodeURIComponent(newContent)
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === 'ok') {
                        // 성공 시 댓글 내용 업데이트 (줄바꿈 처리)
                        contentSpan.innerHTML = newContent.replace(/\n/g, '<br>');
                        contentSpan.style.display = 'inline';

                        textarea.remove();
                        saveBtn.remove();
                        cancelBtn.remove();
                    } else if (data.trim() === 'not_logged_in') {
                        alert('로그인 후 수정할 수 있습니다.');
                    } else if (data.trim() === 'empty_content') {
                        alert('댓글 내용을 입력하세요.');
                    } else if (data.trim() === 'not_found') {
                        alert('해당 댓글을 찾을 수 없습니다.');
                    } else if (data.trim() === 'not_author') {
                        alert('본인 댓글만 수정할 수 있습니다.');
                    } else if (data.trim() === 'no_change') {
                        alert('변경된 내용이 없습니다.');
                    } else {
                        alert('댓글 수정 실패: ' + data);
                    }
                })
                .catch(() => {
                    alert('댓글 수정 중 오류가 발생했습니다.');
                });
            });

            // 수정 취소 클릭 이벤트
            cancelBtn.addEventListener('click', () => {
                textarea.remove();
                saveBtn.remove();
                cancelBtn.remove();
                contentSpan.style.display = 'inline';
            });
        });
    });
});
</script>