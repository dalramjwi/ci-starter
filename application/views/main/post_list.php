<style>
    .postlist-container {
        /* width: 100%; */
        margin-top: 20px;
        padding: 0px 10px 0px 10px;
    }

.postlist-header {
    display: flex;
    padding: 0 0 10px 10px;
    border-bottom: 2px solid #ccc;
    font-weight: bold;
}

.postlist-header > div:nth-child(1) {
    flex: 8; /* 60% */
    text-align: left;
}
.postlist-header > div:nth-child(2){

    padding-right: 15px;
}
.postlist-header > div:nth-child(2),
.postlist-header > div:nth-child(3) {
    flex: 1; /* 20% */
    text-align: center;
}

    .post-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .post-title {
        flex: 1;
        font-size: 14px;
        color: #333;
        display: flex;
        align-items: center;
    }

    .post-title a {
        color: inherit;
        text-decoration: none;
    }

    .post-author,
    .post-time {
        width: 100px;
        font-size: 12px;
        color: #777;
        text-align: center;

    }
</style>

<div class="postlist-container">
    <div class="postlist-header">
        <div>제목</div>
        <div>작성자</div>
        <div>작성일</div>
    </div>

    <?php if (empty($posts)): ?>
        <p>게시물이 없습니다.</p>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
            <?php
                $prefix = str_repeat('RE:', $post->depth);
                $marginLeft = $post->depth * 20;
            ?>
            <div class="post-item" style="margin-left: <?= $marginLeft ?>px;">
                <div class="post-title">
                    <?= $prefix ?>
                    <a href="<?= base_url('post/view/' . $post->post_id) ?>">
                        <?= htmlspecialchars($post->title) ?>
                    </a>
                </div>
                
                <?php if ($category_id == 1): ?>
                    <div class="post-author">관리자</div>
                <?php elseif ($category_id == 3): ?>
                    <div class="post-author"></div>
                <?php else: ?>
                    <div class="post-author"><?= htmlspecialchars($post->user_id) ?></div>
                <?php endif; ?>

                <div class="post-time">
                    <?= date('Y-m-d H:i', strtotime($post->created_at)) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
