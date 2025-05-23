<?php if (empty($posts)): ?>
    <p>검색 결과가 없습니다.</p>
<?php else: ?>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li style="margin-left: <?php echo $post->depth * 20; ?>px">
                <a href="<?php echo base_url('main/view/' . $post->post_id); ?>">
                    <?php echo htmlspecialchars($post->title); ?>
                </a>
                <p>작성자: <?php echo htmlspecialchars($post->user_id); ?> | 작성일: <?php echo $post->created_at; ?></p>
            </li>
        <?php endforeach; ?>
    </ul>

    <div>
        <?php
        $pages = ceil($total / $limit);
        for ($i = 1; $i <= $pages; $i++) {
            $active = ($i - 1) == $offset ? 'style="font-weight:bold;"' : '';
            echo '<a href="' . base_url("main/search?q=" . urlencode($query) . "&page=" . ($i - 1)) . '" ' . $active . '>' . $i . '</a> ';
        }
        ?>
    </div>
<?php endif; ?>

<form method="get" action="<?php echo base_url('main/search'); ?>">
    <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="검색어 입력">
    <button type="submit">검색</button>
</form>
