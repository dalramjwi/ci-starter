<div class="write_div">
    <div class="write_btn">
        <?php if ($this->session->userdata('user_id')): ?>
            <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
        <?php endif; ?>
    </div>
</div>
<!-- select, option을 이용해 event를 발생해 ajax 호출 -->
<script>
    //ajax를 사용해 post 형식으로, 전송
</script>