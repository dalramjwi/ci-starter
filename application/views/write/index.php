<div class="write_container">
    <form id="write_form" action="<?php echo base_url('write/wrote'); ?>" method="post" class="write_form">
        <label>제목:</label>
        <input type="text" name="title" required><br>
        
        <label>내용:</label>
        <textarea name="content" required></textarea><br>
    </form>
    <div class="write_submit_btn">
        <button type="submit" form="write_form">글 작성</button>
    </div>
</div>
