<!DOCTYPE html>
<html>
<head>
    <title>芝麻开门</title>
    <meta http-equiv="X-UA-Compatible" content="IE=7"/>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <style type="text/css">
        .bg_body{
            background-image: url('<?= CSS_IMAGE_URL ?>lay_background_repeat.jpg');
        }
        .mainPanel{
            background-color: #ddd;
            margin-top: 190px;
        }
        .mainPanel h2{
            text-align: center;
            color: rgba(0, 0, 0, 0.5);
            text-shadow: 0 1px 0 white;
            font-weight: 200;
            margin-bottom:20px;
        }
    </style>
    <link href="<?= CSS_URL ?>bootstrap.css" rel="stylesheet">
</head>
<body class="bg_body">
    <div class="container">
        <div class="row">
            <div class="span6 offset3">
                <div class="mainPanel">
                    <form class="well form-horizontal" action="login" method="post">
                        <a class="link-column" href="<?= SERVER_URL ?>">←回到首页</a>
                        <h2>Sign In</h2>
                        <div class="control-group">
                            <label class="control-label">用户名</label>
                            <div class="controls">
                                <input class="input-large" type="text" name="username"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">密码</label>
                            <div class="controls">
                                <input class="input-large" type="password" name="password"/>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-primary" type="submit">提交</button>
                            <button type="reset" class="btn">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= JS_URL ?>jquery-1.7.1.min.js"></script>
    <script src="<?= JS_URL ?>bootstrap.js"></script>
</body>
</html>