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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="/Assets/Admin/Public/images/logo_mini.png"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img style="margin-left:-10px;margin-top:-5px;" src="/Assets/Admin/Public/images/logo_mini.png">网购后台</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li style="padding: 15px 10px; color: #c4e3f3;"><span><?php echo ($com["name"]); ?></span></li>
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo ($name); ?></span><span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="/Assets/Admin/Public/images/avatar.png" class="img-circle" alt="User Image">
                            <p>
                                <?php echo ($name); ?>
                                <small><?php echo ($com["name"]); ?></small>
                            </p>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#confirmModal">退出登录</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <!-- Optionally, you can add icons to the links -->
            <li name="li_menu" class="<?php if($Think.CONTROLLER_NAME == 'Index') echo ' active'; ?>"><a href="<?php echo U('index/index');?>"><i class="fa fa-tachometer"></i> <span>系统首页</span></a></li>
            <li name="li_menu" class="treeview <?php if($Think.CONTROLLER_NAME == 'Goods') echo 'open active'; ?>">
                <a href="javascript:"><i class="fa fa-cubes"></i> <span>商品管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a name="menu_link" href="<?php echo U('goods/glist');?>"><i class="fa fa-circle-o"></i>商品列表</a></li>
                    <li><a name="menu_link" href="<?php echo U('goods/pkgsize');?>"><i class="fa fa-circle-o"></i>新建大包装商品</a></li>
                    <li><a name="menu_link" href="<?php echo U('goods/tlist');?>"><i class="fa fa-circle-o"></i>商品分类管理</a></li>
                </ul>
            </li>

            <li name="li_menu" class="treeview <?php if($Think.CONTROLLER_NAME == 'Sale') echo 'open active'; ?>">
                <a href="javascript:"><i class="fa fa-yen"></i> <span>价格管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a name="menu_link" href="<?php echo U('sale/price_list');?>"><i class="fa fa-circle-o"></i>售卖定价</a></li>
                </ul>
            </li>

            <li name="li_menu" class="treeview <?php if($Think.CONTROLLER_NAME == 'Market') echo 'open active'; ?>">
                <a href="javascript:"><i class="fa fa-money"></i> <span>促销管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a name="menu_link" href="<?php echo U('goods/gift');?>"><i class="fa fa-circle-o"></i>新建买赠促销</a></li>
                    <li><a name="menu_link" href="<?php echo U('goods/new_bind');?>"><i class="fa fa-circle-o"></i>新建组合促销</a></li>
                    <li><a name="menu_link" href="<?php echo U('market/market_list');?>"><i class="fa fa-circle-o"></i>赠品列表</a></li>
                    <!--<li><a name="menu_link" href="<?php echo U('market/giving');?>"><i class="fa fa-circle-o"></i>新建赠品栏</a></li>-->
                </ul>
            </li>
            <li name="li_menu" class="treeview <?php if($Think.CONTROLLER_NAME == 'Column') echo 'active'; ?>">
                <a href="javascript:"><i class="fa fa-th-list"></i> <span>栏目管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a name="menu_link" href="<?php echo U('column/clist');?>"><i class="fa fa-circle-o"></i> 栏目列表</a></li>
                    <li><a name="menu_link" href="<?php echo U('column/column_edit');?>"><i class="fa fa-circle-o"></i> 新建栏目</a></li>
                </ul>
            </li>
            <li name="li_menu" class="treeview <?php if($Think.CONTROLLER_NAME == 'Showcase' || $Think.CONTROLLER_NAME == 'Mall') echo 'active'; ?>">
                <a href="javascript:"><i class="fa fa-weixin"></i> <span>微信商城</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a name="menu_link" href="<?php echo U('mall/edit');?>"><i class="fa fa-circle-o"></i>商城设置</a></li>
                    <li><a name="menu_link" href="<?php echo U('showcase/slist');?>"><i class="fa fa-circle-o"></i>商城装饰</a></li>
                    <li><a name="menu_link" href="<?php echo U('mall/promotion_setting');?>"><i class="fa fa-circle-o"></i>会销设置</a></li>
                    <!--<li><a name="menu_link" href="<?php echo U('showcase/preview');?>"><i class="fa fa-circle-o"></i>橱窗预览</a></li>-->
                </ul>
            </li>

            <!--<li class="treeview">
                <a href="javascript:enterOtherPlatform('yc');"><i class="fa fa-cloud"></i> <span>99云仓</span> </a>
            </li>-->
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>


<script>
    //自动展开当前菜单(需要API配合透传回current_menu)
    $().ready(function(){
        $('li[name="li_menu"]').removeClass('active').each(function(i){
            if (i == '<?php echo $_SESSION["current_menu"]; ?>'){
                $(this).addClass('active');
            }
        });

        $('a[name="menu_link"]').each(function(){
            var idx = $(this).parent().parent().parent().index();
            var href = $(this).attr('href');
            href = href + '&current_menu=' + idx;
            $(this).attr('href', href);
        });

    });

</script>
<!-- (没有ticket,暂不支持)
<script>
    /**
     * 打开新页面进入其他系统
     * @param subdomain
     */
    function enterOtherPlatform(subdomain){
        if (typeof(subdomain) == 'string'){
            var hosts = window.location.host.split('.');
            var url = 'http://' + subdomain + '.' + hosts[1] + '.' + hosts[2];
            if (typeof(url) == 'string'){
                var ticket = '<?php echo ($ticket); ?>';
                if (ticket) {
                    url += '?ticket=' + ticket;
                    window.open(url);
                }
            }
        }
    }
</script>
-->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                商城配置
                <small><?php if ($tab == 'base'){?>填写完整的商城信息，可以帮助加深品牌形象，并统一下游网点入口<?php }?></small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!--  Default box -->
            <div class="box box-primary">
                <div class="box-header">
                    <ul class="nav nav-tabs" id="tabs">
                        <li id="tab-base"><a href="<?php echo U('mall/edit', array('tab'=> 'base', 'id' => $mall_id));?>">企业信息</a></li>
                        <li id="tab-delivery"><a href="<?php echo U('mall/edit', array('tab' => 'delivery', 'id' => $mall_id));?>">配送信息</a></li>
                        <li id="tab-bind"><a href="<?php echo U('mall/edit', array('tab' => 'bind', 'id' => $mall_id));?>">公众号绑定</a></li>
                    </ul>
                </div>

                <div class="box-body table-responsive" style="padding-top: 0;">

                    <div class="row">
                        <div class="col-md-8">
                            <!-- params -->
                            <form class="form-horizontal" id="mall-form" method="post" action="<?php echo U(mall/edit);?>">
                                <input type="hidden" name="tab" id="tab" value="<?php echo ($tab); ?>" />
                                <input type="hidden" name="id" value="<?php echo $mall['id'];?>" />

                                <?php if ($tab == 'base') {?>
                                <!-- 企业信息 begin-->
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">品牌名称</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="如：盛世酩德" value="<?php echo $mall['name'];?>" />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">品牌简介</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <textarea class="form-control w4" id="band_intro" name="brand_intro" rows="6" cols="60"><?php echo $mall['intro'];?></textarea>        
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">客服电话</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="brand_cs_phone" id="brand_cs_phone" placeholder="如：400-8888-8888" value="<?php echo $mall['cs_phone'];?>" />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>

                               <div class="form-group">
                                    <label  class="col-sm-3 control-label">LOGO</label>

                                    <div class="col-xs-5">
                                        <div class="input-group">
                                            <input type="file" class="btn btn-default" id="picfile">
                                        </div>
                                        <div class="input-group" id="imgshow" style="margin-top:5px;">

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label"></label>

                                </div>
                                <input type="hidden" name="brand_logo" id="brand_logo" value="<?php echo $mall['logo'];?>" />

                                <div id="giving_goods_lists"></div>
                                <!-- 企业信息 end-->


                                <?php } else if ($tab == 'delivery') { ?>
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">配送时间</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="delivery_time" id="delivery_time" placeholder="例如:早09:00 - 晚21:00" value="<?php echo ($mall['delivery_time']); ?>"  />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">配送价格</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="delivery_fee" id="delivery_fee" placeholder="例如:免费" value="<?php echo ($mall['delivery_fee']); ?>"  />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>



                                <?php } else { ?>
                                <!-- 公众号绑定 begin-->
                                <div class="form-group ">
                                
                                    <label class="col-sm-3 control-label">公众号名称</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="wx_name" id="wx_name" placeholder="公众号名称/昵称" value="<?php echo ($mall['wx_name']); ?>"  />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>
 
                                 <div class="form-group ">
                                    <label class="col-sm-3 control-label">公众号原始 ID</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="wx_oid" id="wx_oid"  value="<?php echo ($mall['wx_oid']); ?>" <?php if ($mall['wx_oid'] && $mall['bind']) {?> readonly="readonly"  <?php }?> />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>
                                 
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">微信号</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="wx_id" id="wx_id" placeholder="如您未设置过微信号，进入公众号设置页：设置微信号" value="<?php echo ($mall['wx_id']); ?>" <?php if ($mall['wx_name'] && $mall['bind']) {?> readonly="readonly"  <?php }?> />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">AppID</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="app_id" id="app_id" value="<?php echo ($mall['app_id']); ?>" />

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">AppSecret</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="app_secret" id="app_secret" value="<?php echo ($mall['app_secret']); ?>"  />
                                        </div>
                                    </div>
                                </div>
                                <?php
 if (isset($url) && !empty($url) && $mall['bind']) { ?>
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">URL</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="url" id="url"  value="<?php echo $url;?>" readonly="readonly" />
                                            <div class="input-group-addon"><a id="a-copy-url" data-clipboard-action="copy" data-clipboard-target="#url">复制</a></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
 } ?>

                                <!-- 公众号绑定 end-->
                                <?php }?>
                                
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <input type="button" onclick="checkForm();" class="btn btn-primary" value="保存" />
                                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                       <?php
 if ($mall['bind']) { ?>
                                        <input type="button" id="btn-unbind-wx" class="btn" value="解绑" />
                                        <?php
 } ?>
                                    </div>
                                </div>
                            </form>
                            <!-- params end -->
                        </div>
                    </div>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
        <!-- /.box -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">您确认要退出登录么?</h4>
            </div>
            <div class="modal-footer">
                <a href="<?php echo U('login/logout');?>" class="btn btn-primary">Yes</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs"></div>

    <!-- Default to the left -->
    <script>
        var domain =  window.location.host || document.domain;
        //var cp = {"99yuncang":['99yuncang.com','苏ICP备15010324号-3'], "99yc":["99yc.net",'苏ICP备15010324号-4'], "ms9d":["ms9d.com",""]};
        var cp = {"99yuncang":['北京亚信通科技有限公司','苏ICP备15010324号-3'], "99yc":["北京亚信通科技有限公司",'苏ICP备15010324号-4'], "ms9d":["ms9d.com",""]};

        var len = 0;
        if(domain){var md = domain.split('.');len = md.length;}
        if(len && cp[md[len-2]]){
            //document.write("<strong>Copyright &copy; 2016 " +cp[md[len-2]][0]+ "</strong> All rights reserved.  <a href='http://www.miitbeian.gov.cn' target='_blank'>"+cp[md[len-2]][1]+"</a>");
            document.write("<strong>Copyright &copy; 2016 北京亚信通科技有限公司</strong> All rights reserved.  <a href='http://www.miitbeian.gov.cn' target='_blank'>"+cp[md[len-2]][1]+"</a> 版本号:1.0");
        }else{
            document.write("<strong>Copyright &copy; 2016 北京亚信通科技有限公司</strong> All rights reserved. 版本号:1.0");
        }
    </script>


    <script>
        var imageAddr = "http://" + domain + "/ms9d.png?r=" + Math.random();
        var downloadSize = 14059; //bytes

        window.onload = function() {
            var oProgress = document.getElementById("progress");
            window.setTimeout(MeasureConnectionSpeed, 1);
        };

        function MeasureConnectionSpeed() {
            var oProgress = document.getElementById("progress");
            var startTime, endTime;
            var download = new Image();
            download.onload = function () {
                endTime = (new Date()).getTime();
                showResults();
            }

            download.onerror = function (err, msg) {
                oProgress.innerHTML = "";
            }

            startTime = (new Date()).getTime();
            var cacheBuster = "?nnn=" + startTime;
            download.src = imageAddr + cacheBuster;

            function showResults() {
                var duration = (endTime - startTime) / 1000;
                var bitsLoaded = downloadSize * 8;
                var speedBps = (bitsLoaded / duration).toFixed(2);
                var speedKbps = (speedBps / 1024).toFixed(2);
                var speedMbps = (speedKbps / 1024).toFixed(2);
                oProgress.innerHTML = "Your connection speed is: " +
                        speedBps + " bps&nbsp"   +
                        speedKbps + " kbps&nbsp" +
                        speedMbps + " Mbps&nbsp";
            }
        }

    </script>
    <div id="progress" class="bg-info"></div>
</footer>

</div>
<!-- ./wrapper -->
<script src="/Assets/Admin/Public/js/clipboard.min.js"></script>
<script type="text/javascript">
$().ready(function() {
    //    tab 激活
    $('#tab-' + '<?php echo $tab;?>').addClass('active');

    //var gphoto = parseInt('<?php echo ($good["gphoto"]); ?>');
    var logo = "<?php echo $mall['logo'];;?>"
    logo = $.trim(logo);
    
    if (logo != ""){
        var img = $('<img src="<?php echo $mall['logo'];; echo C('IMG_SPEC_SM');?>" width="100px" height="100px">');
        img.appendTo($('#imgshow'));
        $('#brand_logo').val(logo);
    }
    
    //  // 上传图片
    $('input[type="file"]').change(function(e){
        var file = $(this).get(0).files[0];
        //判断类型是不是图片
        if(!/image\/\w+/.test(file.type)){
            parent.layer.msg('不支持该图片类型', { icon: 2, time: 3000 });
            return false;
        }

        var reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = function(e){
            $.post(
                    "<?php echo U('mall/upload_img');?>",
                    {src:this.result},
                    function(data){
                        if(data.error == 0) {
                            var img = $('<img src="' + data.data + '" width="100px" height="100px">');
                            img.appendTo($('#imgshow'));
                            $('#brand_logo').val(data.data);
                        } else{
                            parent.layer.msg('上传图片失败', { icon: 2, time: 2000 });

                        }
                    }
            )
        }

    });
    
    $('#btn-unbind-wx').click(function() {
        parent.layer.confirm('确定要解除当前的绑定吗？', {
             btn: ['是的','取消'], //按钮
             shade: 0.5
         }, function(){
             $.post(
                "<?php echo U('mall/unbind_wx');?>",
                {},
                function(data) {
                    if (data.error == 0){
                        parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                        window.location.href = "<?php echo U('mall/edit?tab=bind');?>"
                    } else {
                        parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                    }
                }
             )
         });
    })

})

//  复制到粘贴板
var clipboard = new Clipboard('#a-copy-url');
clipboard.on('success', function(e) {
    console.info('Action:', e.action);
    console.info('Text:', e.text);
    console.info('Trigger:', e.trigger);
    parent.layer.msg('复制成功', {icon : 1, time : 1000});
    e.clearSelection();
});

clipboard.on('error', function(e) {
    console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});


function checkForm() {
    tab = $('#tab').val();
    
    base_fields = {'brand_name' : '请填写品牌名称'};
    bind_fields = {'wx_name' : '请填写微信公众号', 'wx_oid' : '请填写微信原始 ID', 'wx_id' : '请填写微信号'};
    delivery_fields = {'delivery_time' : '请填写配送时间', 'delivery_fee' : '请填写配送费用'};

    if (tab == "base") {
        return checkNotEmpty(base_fields);
    } else if (tab == 'delivery'){
        return checkNotEmpty(delivery_fields);
    } else {
        return checkNotEmpty(bind_fields);
    }
}

function checkNotEmpty(fields) {
    error = false;
    msg = '';
    
    $.each(fields, function(field, error_message) {
       obj  = $('#' + field);
       obj_value = $.trim(obj.val());
       
       if (obj_value == "") {
            obj.focus();
            msg = error_message;
            error = error || true;
       }
       
       if (error == true) {
            return false;
       }
    });
    
    if (!error) {
        $('#mall-form').submit();
        return true;
        
    } else {
        parent.layer.msg(msg, { icon: 2, time: 2000 });
        return false;
    }
}
</script>
</body>
</html>