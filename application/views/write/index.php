<?php $this->load->view('common/toast'); ?>

<div class="write_container">
    <form id="write_form" action="<?php echo base_url('write/wrote'); ?>" method="post" class="write_form">
        <label>카테고리:</label>
        <div>
            <select name="category" required>
                <?php foreach ($categories as $category): ?>
                    <!-- can_write 속성이 false인 카테고리는 제외 -->
                    <?php if (!$category->can_write) continue; ?>
                    <option value="<?php echo htmlspecialchars($category->category_id); ?>"
                        <?php if ($category->category_id == 2) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <label>제목:</label>
        <input type="text" name="title" required><br>
        
        <label>내용:</label>
        <textarea name="content" required></textarea><br>
    </form>
    <div class="write_submit_btn">
        <button type="submit" form="write_form">글 작성</button>
    </div>
</div>
