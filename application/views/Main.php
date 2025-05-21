
<p>홈 화면입니다.</p>

<?php foreach($posts as $post): ?>
    <p><?php echo $post->title; ?></p>
<?php endforeach; ?>

<?php if ($this->session->userdata('user_id')): ?>
    <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
    
<?php endif; ?>