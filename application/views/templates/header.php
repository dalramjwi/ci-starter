<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $site_name; ?></title>
        <link rel="stylesheet" href="<?php echo assets_url();?>css/header.css">
        <script type="module" src="<?php echo assets_url();?>js/header.js"></script>
    </head>
    <body>


<header>
    <div class="header-left">
        <a href="<?php echo base_url('/main')?>"><?php echo $site_name; ?></a>
    </div>
    <div class="header-right">
        <?php if ($this->session->userdata('user_id')): ?>
            <span class="user-welcome"><?= $this->session->userdata('user_id') ?>님 환영합니다!</span>
            <a href="<?php echo base_url('login/logout'); ?>"><?php echo $logout; ?></a>
        <?php else: ?>
            <a href="<?php echo base_url('login'); ?>"><?php echo $login; ?></a>
            <a href="<?php echo base_url('sign_up'); ?>"><?php echo $sign_up; ?></a>
        <?php endif; ?>
    </div>
</header>
