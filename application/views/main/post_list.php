<?php foreach ($posts as $post): ?>
    <div class="post-item" style="margin-left: <?= $post->depth * 20 ?>px;">
        <strong><?= $post->title ?: '(댓글)' ?></strong><br>
        <?= nl2br($post->content) ?><br>
        <small><?= $post->created_at ?></small>
        <hr>
    </div>
<?php endforeach; ?>
