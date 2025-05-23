<div class="write_div">
    <?php if ($this->session->userdata('user_id')): ?>
        <div class="write_btn">
            <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
        </div>
    <?php endif; ?>
    <div class="main_select_div">
        <!-- <div class="view_select">
            <label for="view_option">게시글 조회 옵션:</label>
            <select name="view_option" id="view_option">
                <option value="total" selected>게시글 + 답글 조회</option>
                <option value="base">게시글만 조회</option>
            </select>
        </div> -->
        <div class="page_select">
            <label for="page_option">페이지당 게시글 수 설정</label>
            <select name="page_option" id="page_option">
                <option value="10" selected>10개</option>
                <option value="20">20개</option>
                <option value="50">50개</option> <!-- value 50 수정 -->
                <option value="100">100개</option>
            </select>
        </div>
    </div>
</div>

<div id="post_list">
    <?php $this->load->view('main/post_list', ['posts' => $posts]); ?>
</div>

<script>
function fetchPosts() {
    // const viewOption = document.getElementById('view_option').value;
    const pageOption = document.getElementById('page_option').value;

    fetch('<?php echo base_url('main/fetch_posts'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            // view_option: viewOption,
            page_option: parseInt(pageOption, 10)
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('post_list').innerHTML = data.html;
    })
    .catch(err => {
        alert('서버 요청 중 오류 발생');
        console.error(err);
    });
}

// 두 select 요소 모두 변경되면 fetchPosts 함수 실행
// document.getElementById('view_option').addEventListener('change', fetchPosts);
document.getElementById('page_option').addEventListener('change', fetchPosts);
</script>
