<html>
    <head>
        <title>계층형 게시판 테스트</title>
    </head>
    <body>
    <p><?php echo $title ?></p>
    <?php if ($this->session->userdata('user_id')): ?>
    <p><?= $this->session->userdata('user_id') ?>님 환영합니다!</p>
    <a href="<?php echo base_url('login/logout'); ?>">로그아웃</a>
<?php else: ?>
    <a href="<?php echo base_url('login'); ?>">로그인</a>
    <a href="<?php echo base_url('sign_up'); ?>">회원가입</a>
<?php endif; ?>


