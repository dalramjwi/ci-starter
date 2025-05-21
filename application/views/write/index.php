<form action="<?php echo base_url('write/wrote'); ?>" method="post">
    <label>제목:</label>
    <input type="text" name="title" required><br>

    <label>내용:</label>
    <input type="content" name="content" required><br>

    <button type="submit"><?php echo $write; ?></button>
</form>
