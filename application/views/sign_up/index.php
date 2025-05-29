<?php $this->load->view('common/toast'); ?>

<p class = "title"><?php echo $sign_up; ?></p>

<div class="login-form">

    <form action="<?php echo base_url('sign_up/submit'); ?>" method="post">
        <label>아이디:</label>
        <input type="text" name="user_id" required><br>

        <label>비밀번호:</label>
        <input type="password" name="user_pw" required><br>

        <button type="submit"><?php echo $sign_up; ?></button>
    </form>

</div>
