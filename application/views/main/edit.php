
<form method="post" action="<?php echo base_url('main/update/' . $post->post_id); ?>">

    <?php if ($post->depth == 0): ?>
        <p>게시물 수정</p>
        <label for="title">제목</label><br>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post->title); ?>" required><br><br>
    <?php else: ?>
        <p>댓글 수정</p>
    <?php endif; ?>

    <label for="content">내용</label><br>
    <textarea name="content" id="content" rows="8" cols="50" required><?php echo htmlspecialchars($post->content); ?></textarea><br><br>

    <button type="submit">수정 완료</button>
</form>
