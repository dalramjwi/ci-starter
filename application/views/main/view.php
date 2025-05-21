<h2><?php echo htmlspecialchars($post->title); ?></h2>
<p>작성자: <?php echo htmlspecialchars($post->user_id); ?></p>
<p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
<p>작성일: <?php echo $post->created_at; ?></p>
<?php if ($this->session->userdata('user_id') == $post->user_id): ?>
    <a href="<?php echo base_url('main/edit/' . $post->post_id); ?>">수정</a>
    <a href="<?php echo base_url('main/delete/' . $post->post_id); ?>">삭제</a>
<?php endif; ?>
//이미 달려진 답글이 보일 장소. 
<?php if ($this->session->userdata('user_id')): ?>
    <form action="<?php echo base_url('main/comment/' . $post->post_id); ?>" method="post">
        <textarea name="comment" required></textarea>
        <button type="submit">답글 작성</button>
    </form>
<?php endif; ?>