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
                 微信商城
                <small></small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">商城装饰</h3>
<!--                    <div class="box-tools pull-right">
                        <a href="<?php echo U('column/column_edit');?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> 添加新栏目</a>
                    </div>-->
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="pull-left" id="templatemo_sort_btn" style="margin-bottom: 10px;">
                                <a class="btn btn-default btn-sm" href="javascript:location.reload();" id="refresh">刷新</a>
                                <?php
 if (empty($showcase_list)) { ?>
                                <a class="btn btn-default btn-sm" id="init">装饰初始化</a>
                                <?php
 } ?>
                            </div>
                            <div class="table-responsive">

                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th><div align="center">模块名称</div></th>
                                        <!--<th><div align="center">#</div></th>-->
                                        <!--<th><div align="center">排序(回车键更新)</div></th>-->
                                        <!--<th><div align="center">类型</div></th>-->
                                        <th><div align="center">发布</div> </th>
                                        <th><div align="center">编辑</div></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($showcase_list)): foreach($showcase_list as $key=>$vo): ?><tr>
                                            <td><div align="left"><?php echo ($vo["html"]); echo ($vo["name"]); ?></div></td>
                                            <!--<td><div align="center"><?php echo ($vo["id"]); ?></div></td>-->
                                            <!--<td><div align="center"><input class="csort" type="text" size="3" data-id="<?php echo ($vo["id"]); ?>" value="<?php echo ($vo["csort"]); ?>"></div></td>-->
                                            <!--<td>-->
                                            <!--    <div align="center">-->
                                            <!--        <?php if($vo["type"] == 1): ?><span class="">活动栏目</span><?php endif; ?>-->
                                            <!--        <?php if($vo["type"] == 2): ?><span class="">顶部焦点图</span><?php endif; ?>-->
                                            <!--    </div>-->
                                            <!--</td>-->
                                            <td class="huodong">
                                                <div align="center">
                                                    <input type="checkbox" data-id="<?php echo ($vo["id"]); ?>"  data-pub="<?php echo ($vo["publish"]); ?>" <?php if($vo['publish'] == 1): ?>checked="checked"<?php endif; ?> >
                                                </div>
                                            </td>
                                            <!--<td><div align="center"><a class="btn btn-danger btn-sm del-column" data-id="<?php echo ($vo["id"]); ?>" href="javascript:;">删除</a></div></td>-->
                                            <td><div align="center"><a class="btn btn-default btn-sm" href="<?php echo U('showcase/showcase_edit',array('id'=>$vo['id']));?>" role="button">编辑</a></div></td>
                                        </tr><?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php echo ($page); ?>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">

                </div>
                <!-- /.box-footer-->
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

<script>
//栏目列表
$().ready(function(){
    /**
     * 发布栏目
     */
    $('.huodong input').each(function(){
        $(this).on('click',function(){
            var cinput = $(this);
            var dis = $(this).prop('checked') ? 1 : 0;
            $.post(
                    "<?php echo U('showcase/publish');?>",
                    {id:$(this).attr('data-id'),dis:dis},
                    function(data){
                        if(data.error == 0){
                            parent.layer.msg(data.msg, { icon: 1, time: 2000 });
                        } else {
                            parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                            cinput.attr('checked', false);
                        }
                    }
            )
        })
    });

    <?php
 if (empty($showcase_list)) { ?>
    $('#init').click(function() {
        $.post(
               "<?php echo U('showcase/sinit');?>",
               {},
               function(data) {
                    if (typeof(data['error']) != 'undefined' && !data['error']) {
                        //$('#refresh').trigger('click');
                        window.location.reload();
                        $('#init').hide();
                    }
               }
        )
        return false;
    });
    <?php
 } ?>
    //$('#refresh').click(function() {
    //    //window.location.reload();
    //    return false;
    //})
})
</script>
</body>
</html>