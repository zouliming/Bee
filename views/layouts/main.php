<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Keywords" content="zouliming,sae,php,javascript,jquery">
	<title>新版首页</title>
    <link href="<?=CSS_URL?>screen.css" media="screen, projection" rel="stylesheet">
    <script src="<?= JS_URL ?>jquery-1.7.1.min.js"></script>
</head>

<body>
<div id="container">
    <div id="wrap">
        <div id="wrap-inner">
            <header role="banner">
                <div id="logo">
                    <a href="http://weibo.com/julyshine" target="_blank">
                        <img alt="七月的明" src="<?=IMAGE_URL?>headpic_small.jpg" />
                    </a>
                </div>
                <nav role="navigation">
                    <ul>
                        <li class="active"><a href="/site/index"><strong>首页 <em>博客内容</em></strong></a></li>
                        <li><a href="#"><strong>经典Demo <em>技术案例分享</em></strong></a></li>
                        <li><a href="/site/photoshow" target="_blank"><strong>照片墙 <em>友情链接</em></strong></a></li>
                        <li><a href="/site/aboutme"><strong>关于我 <em>联系我</em></strong></a></li>
                    </ul>
                </nav>
            </header>
            <hr>
            <div id="main">
                <?php echo $content; ?>
                <div class="paging group">
                    <a href="#"><img alt="上一页" src="<?=CSS_IMAGE_URL?>/icon-pr.png"></a>
                </div>
            </div>
        </div>
        <div id="extra">
            <div class="mod mod-main">
                <h3>关于这个网站</h3>
                <p>
                    我只是想开发一个自己的网站，然后分享自己在技术领域接触的比较新颖的知识。
                </p>
            </div>
        </div>
    </div>
    <footer role="contentinfo">
        <p>Develop By <a id="author" target="_blank" href="http://weibo.com/julyshine">七月的明</a></p>
        <img id="head_tip" src="<?=IMAGE_URL?>headpic_small.jpg" style="display:none;opacity:0;position: absolute;padding-bottom: 10px;"/>
    </footer>
    <script type="text/javascript" language="JavaScript">
        $(document).ready(function(){
            $("#author").bind({
                mouseover:function(){
                    var pos = $.extend({}, ($(this).offset()), {
                    			     width: $(this)[0].offsetWidth
                    			     , height: $(this)[0].offsetHeight
                			     });
                	var target = $("#head_tip")[0];
                	$("#head_tip").remove().css({ top: 1400, left: 700, display: 'block' }).appendTo(document.body);
                    var actualWidth = target.offsetWidth;
                    var actualHeight = target.offsetHeight;
                    var top = pos.top-actualHeight;
                    var left = pos.left+pos.width/2-actualWidth/2;
                    $("#head_tip").css({top:top,left:left}).animate({top:'-=10px',opacity:'1'},'slow');
                },
                mouseout:function(){
                    $("#head_tip").animate({opacity:'0'},'slow').delay(800).fadeOut();
                }
        });
    });
    </script>
</div>

</body>
</html>