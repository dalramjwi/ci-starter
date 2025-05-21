<h2><?php echo htmlspecialchars($post->title); ?></h2>
<p>작성자: <?php echo htmlspecialchars($post->user_id); ?></p>
<p><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
<p>작성일: <?php echo $post->created_at; ?></p>
