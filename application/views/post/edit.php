<style>
  .edit-form {
    max-width: 600px;
    margin: 40px auto;
    padding: 30px;
    border: 1px solid #ccc;
    border-radius: 12px;
    background-color: #f9f9f9;
    font-family: 'Arial', sans-serif;
  }

  .edit-form h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #2a72d6;
    text-align: center;
  }

  .edit-form label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    margin-bottom: 8px;
    color: #333;
  }

  .edit-form input[type="text"],
  .edit-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
    resize: vertical;
  }

  .edit-form button {
    display: block;
    margin: 25px auto 0;
    padding: 12px 24px;
    background-color: #2a72d6;
    color: white;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .edit-form button:hover {
    background-color: #1a4faa;
  }
</style>

<form class="edit-form" method="post" action="<?php echo base_url('post/update/' . $post->post_id); ?>">
  <h2>게시물 수정</h2>

  <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post->title); ?>" required>

  <textarea name="content" id="content" rows="8" required><?php echo htmlspecialchars($post->content); ?></textarea>

  <button type="submit">수정 완료</button>
</form>
