<div class="write_div">
    <?php if ($this->session->userdata('user_id')): ?>
        <div class="write_btn">
            <a href="<?php echo base_url('write'); ?>">게시물 작성</a>
        </div>
    <?php endif; ?>
    <div class="main_select_div">
        <div class="page_select">
            <label for="page_option">페이지당 게시글 수 설정</label>
            <select name="page_option" id="page_option">
                <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10개</option>
                <option value="20" <?php echo $limit == 20 ? 'selected' : ''; ?>>20개</option>
                <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50개</option>
                <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100개</option>
            </select>
        </div>
    </div>
</div>
<form id="searchForm" method="get" action="<?php echo base_url('main/search'); ?>">
    <input type="text" name="q" placeholder="검색어 입력" value="<?php echo htmlspecialchars($query ?? ''); ?>">
    <button type="submit">검색</button>
</form>


<div id="post_list">
    <?php $this->load->view('main/post_list', ['posts' => $posts]); ?>
</div>

<div id="pagination"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    let currentPage = <?php echo $current_page; ?>;
    let currentLimit = <?php echo $limit; ?>;
    const totalCount = <?php echo $total_count; ?>;

    const postList = document.getElementById('post_list');
    const pageOption = document.getElementById('page_option');
    const pagination = document.getElementById('pagination');

function renderPagination(totalPages, currentPage) {
    const blockSize = 5; // 한 번에 보여줄 페이지 수
    const blockStart = Math.floor((currentPage - 1) / blockSize) * blockSize + 1;
    let blockEnd = blockStart + blockSize - 1;
    if (blockEnd > totalPages) blockEnd = totalPages;

    pagination.innerHTML = '';

    if (blockStart > 1) {
        const prevBlockBtn = document.createElement('button');
        prevBlockBtn.textContent = '<<';
        prevBlockBtn.addEventListener('click', () => fetchPosts(blockStart - 1, currentLimit));
        pagination.appendChild(prevBlockBtn);
    }

    for(let i = blockStart; i <= blockEnd; i++) {
        const btn = document.createElement('button');
        btn.textContent = i;
        if (i === currentPage) btn.disabled = true;
        btn.addEventListener('click', () => fetchPosts(i, currentLimit));
        pagination.appendChild(btn);
    }

    if (blockEnd < totalPages) {
        const nextBlockBtn = document.createElement('button');
        nextBlockBtn.textContent = '>>';
        nextBlockBtn.addEventListener('click', () => fetchPosts(blockEnd + 1, currentLimit));
        pagination.appendChild(nextBlockBtn);
    }
}


    function fetchPosts(page, limit) {
        fetch('<?php echo base_url('main/fetch_posts'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ page_option: limit, page: page })
        })
        .then(response => response.json())
        .then(data => {
            postList.innerHTML = data.html;
            renderPagination(data.total_pages, data.current_page);
            currentPage = data.current_page;
            currentLimit = limit;
        })
        .catch(() => alert('게시글을 불러오는 중 오류가 발생했습니다.'));
    }

    pageOption.addEventListener('change', function () {
        currentLimit = parseInt(this.value);
        fetchPosts(1, currentLimit);
    });

    // 초기 페이징 렌더링
    const totalPages = Math.ceil(totalCount / currentLimit);
    renderPagination(totalPages, currentPage);
});
</script>
