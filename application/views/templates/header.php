<html>
    <head>
        <meta charset="UTF-8">
        <title>계층형 게시판 테스트</title>
        <link rel="stylesheet" href="<?php echo assets_url();?>css/header.css">
    </head>
    <body>


<header>
    <div class="header-left">
        <a href="<?php echo base_url('/main')?>">계층형 게시판 테스트</a>
    </div>
    <div class="header-right">
        <?php if ($this->session->userdata('user_id')): ?>
            <span class="user-welcome"><?= $this->session->userdata('user_id') ?>님 환영합니다!</span>
            <a href="<?php echo base_url('login/logout'); ?>">로그아웃</a>
        <?php else: ?>
            <a href="<?php echo base_url('login'); ?>">로그인</a>
            <a href="<?php echo base_url('sign_up'); ?>">회원가입</a>
        <?php endif; ?>
    </div>
</header>
