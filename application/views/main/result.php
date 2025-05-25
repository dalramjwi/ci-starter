<h2>검색 결과</h2>

<?php if (empty($posts)): ?>
    <p>검색 결과가 없습니다.</p>
<?php else: ?>
    <?php foreach ($posts as $post): ?>
        <?php $prefix = str_repeat('RE:', $post->depth); ?>
        <div class="post-item" style="margin-left: <?= $post->depth * 20 ?>px; display: flex; justify-content: space-between; align-items: center; padding: 4px 0;">
            <div>
                <?= $prefix ?>
                <a href="<?= base_url('main/view/' . $post->post_id) ?>">
                    <?= html_escape($post->title) ?>
                </a>
            </div>
            <div class="post-time" style="font-size: 0.85em; color: #777;">
                <?= date('Y-m-d H:i', strtotime($post->created_at)) ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div style="margin-top: 20px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $current_page): ?>
                <strong>[<?= $i ?>]</strong>
            <?php else: ?>
                <a href="<?= base_url('main/search') ?>?keyword=<?= urlencode($keyword) ?>&page=<?= $i ?>">[<?= $i ?>]</a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>
<?php endif; ?>