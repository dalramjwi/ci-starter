<?php $this->load->view('common/toast'); ?>

<p class = "title"><?php echo $sign_up; ?></p>
<div class="login-form">
    <form action="<?php echo base_url('sign_up/submit'); ?>" method="post">
        <label for="user_id">아이디:</label>
        <input type="text" name="user_id" id="user_id" required>

        <label for="user_pw">비밀번호:</label>
        <input type="password" name="user_pw" id="user_pw" required>

        <button type="submit"><?php echo $sign_up; ?></button>
    </form>

</div>
