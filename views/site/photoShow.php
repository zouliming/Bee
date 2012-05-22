<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>Picture.Show</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="keywords" content="zouliming"/>
    <meta name="description" content="一个简单的图片展示效果"/>
    <style type="text/css">
        * {
            margin:0;
            padding:0;
            font:normal 12px/1.5em Tahoma, simsun, Verdana,Lucida, Arial, Helvetica, sans-serif;
        }
        body {
            color: #EFEFEF;
            background-color: black;
        }
        h1 {
            font-size:25px;
            width:100%;
            height:80px;
            line-height:80px;
            text-align:center;
            font-family:"MicroSoft YaHei";
        }
        ul{
            width: 573px;
            margin: 0 auto;
        }
        ul:after {
            clear:both;
            display:block;
            content:".";
            height:0;
            visibility:hidden;
            font-size:0;
            line-height:0;
        }
        li {
            position:relative;
            float:left;
            width:191px;
            height:191px;
            overflow:hidden;
            list-style:none;
            opacity:0.3;
            background: url('<?=CSS_IMAGE_URL?>frame.png');
            -webkit-transform:scale(0.8);
            -moz-transform:scale(0.8);
            -o-transform:scale(0.8);
            -ms-transform:scale(0.8);
            -webkit-transition-duration:0.5s;
            -moz-transition-duration:0.5s;
            -o-transition-duration:0.5s;
            -ms-transition-duration:0.5s;
        }
        li:hover {
            -webkit-transform:scale(1);
            -moz-transform:scale(1);
            -o-transform:scale(1);
            -ms-transform:scale(1);
            opacity:1;
            -webkit-box-shadow:0 0 10px #FFFA8E;
            -ms-box-shadow:0 0 10px #FFFA8E;
            box-shadow:0 0 10px #FFFA8E;
        }
        li a{
            width: 173px;
            height: 149px;
            margin-left: 9px;
            margin-top: 10px;
            position: absolute;
            text-decoration: none;
        }
        img{
            border:0;
        }
    </style>
</head>

<body>
    <?
    $pictureArray = array(
        'huxin'=>array(
            'pic'=>'huxin.png',
            'nickname'=>'胡胡',
            'weiboUrl'=>'http://weibo.com/winterchocolate',
        ),
        'tianjiabang'=>array(
            'pic'=>'tianjiabang.png',
            'nickname'=>'阿邦',
            'weiboUrl'=>'http://weibo.com/1707607212',
        ),
        'qiujian'=>array(
            'pic'=>'qiujian.png',
            'nickname'=>'秋天的一把剑',
            'weiboUrl'=>'http://weibo.com/1678511260',
        ),
        'zhaoyong'=>array(
            'pic'=>'zhaoyong.jpg',
            'nickname'=>'回憶de-沙漏',
            'weiboUrl'=>'http://weibo.com/makeuse',
        ),
        'liuxiaoyue'=>array(
            'pic'=>'liuxiaoyue.jpg',
            'nickname'=>'忆水鸭',
            'weiboUrl'=>'http://weibo.com/pistachio',
        ),
        'chenshaobo'=>array(
            'pic'=>'chenshaobo.jpg',
            'nickname'=>'May',
            'weiboUrl'=>'http://weibo.com/chenshaobo',
        ),
        'jiangantao'=>array(
            'pic'=>'jiangantao.jpg',
            'nickname'=>'涛弟',
            'weiboUrl'=>'http://t.qq.com/hubei_xf',
        ),
        'zouliming'=>array(
            'pic'=>'zouliming.jpg',
            'nickname'=>'七月的明',
            'weiboUrl'=>'http://weibo.com/julyshine',
        ),
        'fengwei'=>array(
            'pic'=>'fengwei.jpg',
            'nickname'=>'wilson',
            'weiboUrl'=>'http://weibo.com/zerolilly',
        ),
        
    );
    ?>
    <h1>
        <!--[if IE]>亲,你用IE浏览器看不到最好的效果哦.建议使用Chrome,Safari,Firefox,Opera.<![endif]--> 
    </h1>
    <ul>
        <? foreach($pictureArray as $info){ ?>
        <li>
            <a href="<?=$info['weiboUrl']?>" title="<?=$info['nickname']?>">
                <img src="<?=IMAGE_URL.$info['pic']?>" width="173" height="149" alt="<?=$info['nickname']?>"/>
            </a>
        </li>
        <? } ?>
    </ul>
</body>
</html>
