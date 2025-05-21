<div class="write_div">
    <div class="write_btn">
        <?php if ($this->session->userdata('user_id')): ?>
            <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
        <?php endif; ?>
    </div>

    <div class="view_select">
        <label for="view_option">게시글 조회 옵션:</label>
        <select name="view_option" id="view_option">
            <option value="total" selected>게시글 + 답글 조회</option>
            <option value="base">게시글만 조회</option>
        </select>
    </div>
</div>

<div id="post_list">
    <?php $this->load->view('main/post_list', ['posts' => $posts]); ?>
</div>

<script>
document.getElementById('view_option').addEventListener('change', function () {
    const selected = this.value;

    fetch('<?php echo base_url('main/view_option'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ view_option: selected })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('post_list').innerHTML = data.html;
    })
    .catch(err => {
        alert('서버 요청 중 오류 발생');
        console.error(err);
    });
});
</script>
