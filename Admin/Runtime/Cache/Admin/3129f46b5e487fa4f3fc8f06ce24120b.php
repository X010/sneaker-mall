<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <title>99云仓业务管理系统</title>
    <link rel="stylesheet" href="/Assets/Admin/Public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Assets/Admin/Public/css/login.css">
    <script src="/Assets/Admin/Public/js/jquery.min.js"></script>
    <script src="/Assets/Admin/Public/js/bootstrap.min.js"></script>
    <script src="/Assets/Admin/Public/js/templatemo_script.js"></script>
    <script src="/Assets/Admin/Public/Layer/layer.js"></script>
    <script>
        var _interval;
        var _i = 60;

        function showTime(){
            $('#smgbutton').text(_i+' 秒后重新获取');
            _i = _i - 1;
            if(_i <=0 ){
                clearInterval(_interval);
                _i = 60;
                $('#smgbutton').attr('disabled',false);
                $('#smgbutton').text('发送短信验证码');
            }
        }
        $().ready(function(){
            //$('#smgbutton').attr('disabled',false);
            $('#smgbutton').on('click',function(){
                $.post(
                        "<?php echo U('login/smssend');?>",
                        {username:$('#username').val()},
                        function(data){
                            if(data.error == 0){
                                $('#smgbutton').attr('disabled',true);
                                _interval = setInterval('showTime()', 1000);
                                alert(data.msg);
                            }else{
                                alert(data.msg);
                            }
                        }
                )
            });

            $('#submitbutton').on('click',function(){
                var un = $('#username').val();
                var pw = $('#password').val();
                var sc = $('#smscode').val();

                if(un == ''){
                    alert('请输入账号');
                }else if(pw == ''){
                    alert('请输入密码');
                }else if(sc == ''){
                    alert('请输入短信验证码');
                }else if(un != '' && pw !='' && sc!='')
                    $('#loginform').submit();
            })
        })
    </script>
<body>
<div class="bg"></div>

<div class="login-page">
    <div class="login-body">
        <div class="login-logo"><img class="img-responsive" src="/Assets/Admin/Public/images/login_logo.svg"></div>
        <div class="login-title"><img src="/Assets/Admin/Public/images/login_title.gif" alt="业务管理系统"></div>
        <div class="panel-login clearfix">
            <form class="form-horizontal templatemo-signin-form" role="form" id="loginform" name='login-form' action="<?php echo U('login/login');?>" method="post">
                <div class="panel-wrap">
                    <ul class="alert alert-warning" id="errorPlace" style="display: none;"></ul>
                    <ul class="login-form">
                        <li class="item">
                            <label for="username" class="item-name icon-user"></label>
                            <input type="text" class="f-input" id="username" name="username" data-rule-required="true" placeholder="请输入账号" value="">
                        </li>
                        <li class="item">
                            <label for="password" class="item-name icon-password"></label>
                            <input type="password" class="f-input" id="password" name="password" data-rule-required="true" placeholder="请输入密码" value="">
                        </li>
                        <li class="item">
                            <label for="smscode" class="item-name icon-vcode"></label>
                            <input type="text" class="f-input" id="smscode" name="smscode" data-rule-required="true" placeholder="请输入短信验证码" style="width: 200px; margin-right: 10px;" value=""><button type="button" id="smgbutton" class="f-btn">发送短信验证码</button>
                        </li>
                    </ul>
                    <div style="margin: 12px;" id="msg">
                        <span class="bg-danger"></span>
                    </div>
                    <div class="login-btn">
                        <input class="btn btn-primary btn-block btn-lg" value="登 录" id="submitbutton" readonly type="button">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="login-footer">
        <div class="copyright">
            <script>
                var domain =  window.location.host || document.domain;
                var cp = {"99yuncang":['99yuncang.com','苏ICP备15010324号-3'], "99yc":["99yc.net",''], "ms9d":["ms9d.com",""]};
                if(domain){var md = domain.split('.');var len = md.length;}
                if(len && cp[md[len-2]]){
                    document.write("<strong>Copyright &copy; 2016 北京亚信通科技有限公司</strong> All rights reserved.  "+cp[md[len-2]][1]+'<div>版本号:1.0</div>');
                }else{
                    document.write("<strong>Copyright &copy; 2016 北京亚信通科技有限公司</strong> All rights reserved.<div>版本号:1.0</div>");
                }
            </script>
        </div>
    </div>
</div>
<div class="mask" id="overlay"></div>
<div class="mask-msg" id="overlayMsg"></div>
</body>
</html>