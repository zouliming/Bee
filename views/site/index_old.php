<!DOCTYPE html>
<html>
 <head>
  <title>这是一个新的开始</title>
  <meta http-equiv="X-UA-Compatible" content="IE=7"/>
  <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
  <style type="text/css">
      .bg_body{
          background-image: url('<?=CSS_IMAGE_URL?>lay_background_repeat.jpg');
      }
      #container {
          display: block;
          width: 50%;
          margin-right: auto;
          margin-left: auto;
          margin-top: 5%;
      }
      .mainPanel {
          display: block;
          margin: 0px auto;
          width: 624px;
          height: 526px;
          background: url('<?=CSS_IMAGE_URL?>main_panel.png') center center no-repeat;
          position: relative;
      }
      .content{
          height: 360px;
          width: 500px;
          padding: 50px 62px;
      }
      .alert{
          background-image: url('<?=CSS_IMAGE_URL?>alert.png');
          width: 155px;
          height: 102px;
          postion:absolute;
          margin-left:270px;
          margin-top:-10px;
          padding-left:55px;
          padding-top:60px;
          
          font-family:"MicroSoft YaHei";
      }
      .alert a{
          color: white;
          text-decoration:none;
      }
  </style>
 </head>

 <body class="bg_body">
     <div id="container">
         <div class="mainPanel">
             <div class="content">
                 <a href="<?=Bee::app()->createUrl("site/zhimakaimen")?>"><img src="<?=CSS_IMAGE_URL?>face.png" style="position: absolute;margin-left:170px;margin-top:110px;"/></a>
                 <div class="alert">
                     <a href="<?=Bee::app()->createUrl("site/photoShow")?>" style="color:white;text-decoration:none;">点击试试?</a>
                 </div>
             </div>
         </div>
     </div>
 </body>
</html>