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
<!-- 검색 필드 -->
<form id="searchForm" method="get" action="<?php echo base_url('main/search'); ?>">
    <input type="text" name="q" placeholder="검색어 입력" value="<?php echo htmlspecialchars($keyword ?? ''); ?>">
    <button type="submit">검색</button>
</form>
<!-- 게시판 카테고리 설정 -->
<div class="category_list_container">
    <?php foreach ($categories as $category): ?>
            <div class="category_item"
                 data-id="<?= $category->category_id ?>">
                <?= htmlspecialchars($category->name) ?>
            </div>
    <?php endforeach; ?>
</div>

<!-- 게시글 랜더링 -->
<div id="post_list">
    <?php $this->load->view('main/post_list', ['posts' => $posts]); ?>
</div>
<!-- 페이지 표시 랜더링 -->
<div id="pagination"></div>

<script>
    window.currentPage = <?php echo $current_page; ?>;
    window.currentLimit = <?php echo $limit; ?>;
    window.totalCount = <?php echo $total_count; ?>;
    window.fetchPostsUrl = "<?php echo base_url('main/fetch_posts'); ?>";
    window.keyword = "<?= isset($keyword) ? htmlspecialchars($keyword) : '' ?>";
</script>

