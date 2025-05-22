<?php foreach($posts as $post): ?>
    <?php
        $prefix = str_repeat('RE:', $post->depth);
    ?>
    <div class="post-item" style="margin-left: <?= $post->depth * 20 ?>px;">
        <?= $prefix ?> <a href="<?= base_url('main/view/' . $post->post_id) ?>">
            <?= htmlspecialchars($post->title) ?>
        </a>
    </div>
<?php endforeach; ?>
