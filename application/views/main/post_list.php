<?php foreach($posts as $post): ?>
    <?php
        $prefix = str_repeat('RE:', $post->depth);
    ?>
    <div class="post-item" style="margin-left: <?= $post->depth * 20 ?>px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <?= $prefix ?>
            <a href="<?= base_url('main/view/' . $post->post_id) ?>">
                <?= htmlspecialchars($post->title) ?>
            </a>
        </div>
        <div class="post-time" style="font-size: 0.85em; color: #777;">
            <?= date('Y-m-d H:i', strtotime($post->created_at)) ?>
        </div>
    </div>
<?php endforeach; ?>
