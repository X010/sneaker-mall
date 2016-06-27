<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>99云仓网购后台</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/ionicons.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/admin.css">
    <!--[if lt IE 9]>
    <script src="/Assets/Admin/Public/js/html5shiv.min.js"></script>
    <script src="/Assets/Admin/Public/js/respond.min.js"></script>
    <![endif]-->
    <!-- REQUIRED JS SCRIPTS -->
    <script src="/Assets/Admin/Public/js/jquery.min.js"></script>
    <script src="/Assets/Admin/Public/js/bootstrap.min.js"></script>
    <script src="/Assets/Admin/Public/js/app.min.js"></script>
    <script src="/Assets/Admin/Public/js/config.js"></script>
    <script src="/Assets/Admin/Public/js/utils.js"></script>

    <script src="/Assets/Admin/Public/Layer/layer.js"></script>

</head>
<body style="background:#E4E6EA;">
<div id="main-wrapper">
    <div class="template-page-wrapper">

        <div class="row">
            <div class="col-md-12">

                <div align="center" style="padding-top: 100px;">

                    <div class="error-content">
                        <h3 style="margin-bottom: 50px;"><i class="fa fa-warning text-red"></i> 系统提示:<?php echo($error); ?></h3>

                        <p>页面<i id="wait">2</i>秒后跳转...</p>
                        <p><a id="href" class="btn btn-default btn-sm" href="<?php echo($jumpUrl); ?>" role="button">立即跳转</a></p>
                    </div>

                </div>
            </div>
        </div>
        <!-- -->
    </div>
</div>
</body>
<script>
    $().ready(function(){
        var wait = $('#wait');
        var href = $('#href').attr('href');
        var tw = parseInt(wait.text());
        var interval = setInterval(function(){
            var time = parseInt(wait.text()) - 1;
            wait.text(time);

            if(time<=0){
                clearInterval(interval);
                location.href = href;
            }
        },1000);
    })
</script>
</html>