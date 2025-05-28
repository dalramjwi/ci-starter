<html>
<head>
  <link rel="stylesheet" href="<?php echo assets_url();?>css/start.css">
  <script type="module" src="<?php echo assets_url();?>js/start.js"></script>
</head>
<body>
  <div class="layout">
    <div>계층형 게시판</div>
    <div id="stars"></div>
    <div id="stars2"></div>
    <div id="stars3"></div>
    <div class="tv-wrapper">
      <div class="tv">
        <canvas id="buffer"
                width="640"
                height="480"
                hidden></canvas>
        <div class="tv__screen">
          <div class="main-background">
            <div class="main-wrapper">
              <div class="noise-wrapper">
                <div class="noise"></div>
              </div>
              <div class="loader">
                <div class="text">CONNECT</div>
              </div>
              <div id="view-container">
                <div id="view-container2">
                  <div class="text2">비로그인</div>
                  <div class="text-container">
                    <div class="text2">로그인</div>
                    <div class="text2">회원가입</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tv__panel">
          <div class="tv__speaker"></div>
          <div class="tv__switch"></div>
          <div class="tv__switch"></div>
          <div class="tv__switch"></div>
          <div class="tv__speaker"></div>
        </div>
        <div class="tv__holders">
          <div class="tv__holder"></div>
          <div class="tv__holder"></div>
        </div>
      </div>
    </div>

  </div>
</body>
</html>