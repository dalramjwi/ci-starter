<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $site_name; ?></title>
        <link rel="stylesheet" href="<?php echo assets_url();?>css/header.css">
        <link rel="stylesheet" href="<?php echo assets_url();?>css/header_real.css">
        <script type="module" src="<?php echo assets_url();?>js/header.js"></script>
    </head>
    <body>


<header>
    <a href="<?php echo base_url('/start')?>" id="cloud_target">
        <div>
            <div class="container">
                        <div class="for-checkbox">
                            <div class="checkmark_style-1"></div>
                        </div>
            </div>
        </div>
    </a>     
    <a href="<?php echo base_url('/main')?>">
        <div class="header-container">
            <div class="header-left">

            </div>
            <div class="countries-container">
                <svg class="countries" width="246" height="60" viewBox="0 0 246 60" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M39 0V6H42V9H36V12H33V15H24V9H21V6H12V9H9V3H6V6H0V9H3V12H12V15H15V24H18V27H21V30H24V33H27V42H30V45H33V48H36V60H39V57H42V51H45V42H48V39H45V36H36V33H27V27H30V24H36V15H42V9H45V6H48V3H51V0H39Z" fill="white"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M90 3V9H84V6H75V9H72V6H63V9H72V12H69V15H63V12H57V24H60V27H57V30H54V33H60V36H63V45H66V51H69V48H72V45H75V30H72V27H81V30H87V33H90V30H93V33H96V36H99V27H102V21H105V15H108V9H126V12H135V15H138V24H141V27H144V30H147V33H150V42H153V45H156V48H159V60H162V57H165V51H168V42H171V39H168V36H159V33H150V27H153V24H159V15H165V9H168V6H171V3H174V0H162V6H165V9H159V12H156V15H147V9H144V6H135V9H132V3H129V6H117V3H90ZM60 24V21H63V18H66V21H69V18H81V24H78V21H72V24H60Z" fill="white"/>
            <path d="M108 19H111V25H105V22H108V19Z" fill="white"/>
            <path d="M96 39H99V42H96V39Z" fill="white"/>
            <path d="M111 42H108V39H105V42H108V45H111V42Z" fill="white"/>
            <path d="M111 48V51H114V57H108V54H102V51H105V48H111Z" fill="white"/>
            <path d="M102 54V57H99V54H102Z" fill="white"/>
            <path d="M75 48H78V51H75V48Z" fill="white"/>
            <path fill-rule="evenodd" clip-rule="evenodd" d="M186 12H180V24H183V27H180V30H177V33H183V36H186V45H189V51H192V48H195V45H198V30H195V27H204V30H210V33H213V30H216V33H219V36H222V27H225V21H228V15H231V9H246V6H240V3H213V9H207V6H198V9H195V6H186V9H195V12H192V15H186V12ZM183 24V21H186V18H189V21H192V18H204V24H201V21H195V24H183Z" fill="white"/>
            <path d="M231 19H234V25H228V22H231V19Z" fill="white"/>
            <path d="M219 39H222V42H219V39Z" fill="white"/>
            <path d="M234 42H231V39H228V42H231V45H234V42Z" fill="white"/>
            <path d="M234 48V51H237V57H231V54H225V51H228V48H234Z" fill="white"/>
            <path d="M225 54V57H222V54H225Z" fill="white"/>
            <path d="M198 48H201V51H198V48Z" fill="white"/>
            </svg>
                </div>
                <svg class="globe" width="66" height="63" viewBox="0 0 66 63" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M30 0H27H24V3H21H18H15V6H12V9H9V12H6V15H3V18V21H0V24V27V30V33V36V39V42H3V45L3 48H6L6 51H9V54H12V57H15V60H18H21H24V63H27H30H33H36H39H42V60H45H48H51V57H54V54H57V51H60V48H63V45V42H66V39V36V33V30V27V24V21H63V18V15H60V12H57V9H54V6H51V3H48H45H42V0H39H36H33H30ZM42 3V6H45H48H51V9H54V12V15H57H60V18V21H63V24V27V30V33V36V39V42H60V45V48H57H54V51H51V54V57H48H45H42V60H39H36H33H30H27H24V57H21H18H15V54V51H12V48H9H6V45V42H3L3 39V36V33V30V27V24V21H6V18H9V15V12H12V9H15V6H18H21H24V3H27H30H33H36H39H42Z" fill="white"/></svg>
        </div>
    </a>
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
