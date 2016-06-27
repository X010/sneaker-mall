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

<script>
    $().ready(function(){

        $('.level-define-1').on('click',function(){
            $('.level-show-1').attr('disabled',false);
            $('.level-show-2').attr('checked',false);
            $('.level-show-2').attr('disabled',true);
            $('.level-show-3').attr('checked',false);
            $('.level-show-3').attr('disabled',true);
            $('#orderlist').empty();
        });

        $('.level-define-2').on('click',function(){
            $('.level-show-2').attr('disabled',false);
            $('.level-show-1').attr('checked',false);
            $('.level-show-1').attr('disabled',true);
            $('.level-show-3').attr('checked',false);
            $('.level-show-3').attr('disabled',true);
            $('#orderlist').empty();
        });

        $('.level-define-3').on('click',function(){
            $('.level-show-3').attr('disabled',false);
            $('.level-show-2').attr('checked',false);
            $('.level-show-2').attr('disabled',true);
            $('.level-show-1').attr('checked',false);
            $('.level-show-1').attr('disabled',true);
            $('#orderlist').empty();
        });

        var level_show = '<?php echo ($level_setting["cvalue"]); ?>';
        if(level_show === '1'){
            $('.level-show-2').attr('disabled',true);
            $('.level-show-3').attr('disabled',true);
        }else if(level_show === '2'){
            $('.level-show-1').attr('disabled',true);
            $('.level-show-3').attr('disabled',true);
        }else if(level_show === '3'){
            $('.level-show-1').attr('disabled',true);
            $('.level-show-2').attr('disabled',true);
        }


        function tablelen(){
            return $("#orderlist").children("tr").length;
        }

        var url = "<?php echo U('goods/tsortlist');?>";

        $.ajax({
            type: "GET",
            url: url,
            data: "",
            success : function(data){
                //console.log(data);
                if(data.status=="success"){
                    if(data.data != null){

                        data.data.forEach(function(v){
                            var input = $('#input_'+ v.tid);
                            var dli = $('<tr id="'+ v.tid +'"></tr>');
                            var dname = $('<td class="col-md-2 type-name">'+ input.attr('data-name') +'</td>');
                            var dup = $('<td class="col-md-2 type-up" align="center"><i data-id="'+ v.tid +'" class="fa fa-arrow-circle-up orderup"></i></td>');
                            var ddown = $('<td class="col-md-2 type-down" align="center"><i data-id="'+ v.tid +'" class="fa fa-arrow-circle-down orderdown"></i></td>');

                            dname.appendTo(dli);
                            dup.appendTo(dli);
                            ddown.appendTo(dli);

                            dli.appendTo($('#orderlist'));
                            input.attr('checked',true);
                        });

                        //绑定排序事件
                        $('#orderlist .orderup').on('click', function () {
                            changorder($(this).parent().parent(), 1);
                        });
                        $('#orderlist .orderdown').on('click', function () {
                            changorder($(this).parent().parent(), 0);
                        });
                    }
                }else{
                    alert("数据加载失败");
                }
            }
        });
        /*
         $.post(url,'',function(data,status){
         console.log(status);
         if(status=="success"){
         if(data.data != null){
         console.log(data.data);
         data.data.forEach(function(v){
         var input = $('#input_'+ v.tid);
         var dli = $('<tr id="'+ v.tid +'"></tr>');
         var dname = $('<td class="col-md-2 type-name">'+ input.attr('data-name') +'</td>');
         var dup = $('<td class="col-md-2 type-up"><i data-id="'+ v.tid +'" class="fa fa-level-up orderup"></i></td>');
         var ddown = $('<td class="col-md-2 type-down"><i data-id="'+ v.tid +'" class="fa fa-level-down orderdown"></i></td>');

         dname.appendTo(dli);
         dup.appendTo(dli);
         ddown.appendTo(dli);

         dup.find('i').on('click',function(){
         changorder(dli,1);
         })

         ddown.find('i').on('click',function(){
         changorder(dli,0);
         })
         dli.appendTo($('#orderlist'));
         input.attr('checked',true);
         })
         }
         }else{
         alert("数据加载失败");
         }
         },"json");*/

        /**
         * 重新填充已选分类到商城分类
         */
        function findintable(obj){
            var self = obj;
            var r = true; //true:添加操作 / false:移除操作
            var newlist = $('#orderlist tr');
            $('#orderlist').empty();
            newlist.each(function(){
                if(self.val() === $(this).attr('id')){
                    r =  false; //移除该项
                }else{
                    $('#orderlist').append($(this));
                }
            });
            return r;
        }

        //勾选分类
        $('input[type=checkbox]').on('click',function(){
            if(findintable($(this))) {
                //添加勾选项到商城分类中
                var dli = $('<tr id="' + $(this).val() + '"></tr>');
                var dname = $('<td class="col-md-2 type-name">' + $(this).attr('data-name') + '</td>');
                var dup = $('<td class="col-md-2 type-up" align="center"><i data-id="' + $(this).val() + '" class="fa fa-arrow-circle-up orderup"></i></td>');
                var ddown = $('<td class="col-md-2 type-down" align="center"><i data-id="' + $(this).val() + '" class="fa fa-arrow-circle-down orderdown"></i></td>');
                dname.appendTo(dli);
                dup.appendTo(dli);
                ddown.appendTo(dli);

                dli.appendTo($('#orderlist'))
            }
            //绑定排序事件
            $('#orderlist .orderup').on('click', function () {
                changorder($(this).parent().parent(), 1);
            });
            $('#orderlist .orderdown').on('click', function () {
                changorder($(this).parent().parent(), 0);
            });
        });

        /**
         * 排序按钮
         * @param obj
         * @param op
         */
        function changorder(obj,op){
            var self = obj;
            var prev = null;
            var newtable = [];
            var postion = 0;
            $('#orderlist tr').each(function(k,v){
                if($(this).attr('id') == self.attr('id')){
                    postion = k;
                }

                newtable.push($(this));
            });
            if(op === 1) {
                if(postion > 0) {
                    var tmp = newtable[postion - 1];
                    newtable[postion - 1] = newtable[postion];
                    newtable[postion] = tmp;
                }
            }else{
                if(postion < $("#orderlist").children("tr").length-1) {
                    var tmp = newtable[postion + 1];
                    newtable[postion + 1] = newtable[postion];
                    newtable[postion] = tmp;
                }
            }



            $('#orderlist').empty();

            newtable.forEach(function(v){
                v.find('.orderup').on('click',function(){ changorder(v,1) })
                v.find('.orderdown').on('click',function(){ changorder(v,0) })
                $('#orderlist').append(v);
            });


            //}
            //}

        }

        //保存
        $('#submit-order').on('click',function(){
            var ids = [];
            var i = 1;
            $('#orderlist tr').each(function(){
                ids.push([$(this).attr('id'),i]);
                i = i+1;
            });
            var json = {};
            for(var i=0;i<ids.length;i++)
            {
                json[ids[i][0]]=ids[i][1];
            }
            var jstring = JSON.stringify(json);
            $('#order').val(jstring);
            if (ids.length){
                $('#order-form').submit();
            } else {
                parent.layer.msg('商城分类不能为空,请勾选ERP分类', { icon: 2, time: 3000 });
                return false;
            }
        });
    })
</script>
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
            <h1>商品分类管理</h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <form action="<?php echo U('goods/type_show_edit');?>" method="post" id="order-form">
                    <div class="box-header with-border">
                        <h3 class="box-title">商城的分类显示基于ERP设置的分类,请选择ERP所展示的相应层级和分类</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <!----- 一级 -------->
                            <div class="col-md-6">
                                <div class="row">

                                    <div class="col-md-offset-1 col-md-10 margin-bottom-30">
                                        <label class="label label-primary">ERP分类</label>
                                        <div class="checkbox">
                                            <label>
                                                <input type="radio" name="define_level" value="1" class="level-define-1" <?php if($level_setting["cvalue"] == 1): ?>checked="checked"<?php endif; ?> >一级分类
                                            </label>
                                            <label class="col-md-offset-2">
                                                <input type="radio" name="define_level" value="2" class="level-define-2" <?php if($level_setting["cvalue"] == 2): ?>checked="checked"<?php endif; ?>>二级分类
                                            </label>
                                            <label class="col-md-offset-2">
                                                <input type="radio" name="define_level" value="3" class="level-define-3" <?php if($level_setting["cvalue"] == 3): ?>checked="checked"<?php endif; ?>>三级分类
                                            </label>
                                        </div>
                                    </div>
                                    <?php if(is_array($typelist)): foreach($typelist as $key=>$vo): ?><div class="col-md-offset-1 col-md-10 margin-bottom-30" style="border-bottom: 1px dashed #f1f1f1" >
                                            <div class="checkbox">
                                                <label>
                                                    <b style="color: #f1f1f1">------------+</b><input id="input_<?php echo ($vo["id"]); ?>" type="checkbox" class="level-show-1" value="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?>
                                                </label>
                                            </div>


                                            <?php if(is_array($vo["data"])): foreach($vo["data"] as $key=>$vvo): ?><div class="col-md-offset-2 col-md-10 margin-bottom-15" >
                                                    <div class="checkbox">
                                                        <label>
                                                            <b style="color: #f1f1f1">├------------</b><input id="input_<?php echo ($vvo["id"]); ?>" type="checkbox" class="level-show-2" value="<?php echo ($vvo["id"]); ?>" data-name="<?php echo ($vvo["name"]); ?>"> <?php echo ($vvo["name"]); ?>
                                                        </label>
                                                    </div>

                                                    <?php if(is_array($vvo["data"])): foreach($vvo["data"] as $key=>$vvvo): ?><div class="col-md-offset-2 col-md-10 margin-bottom-15" >
                                                            <div class="checkbox">
                                                                <label>
                                                                    <b style="color: #f1f1f1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;├-----------</b><input id="input_<?php echo ($vvvo["id"]); ?>" class="level-show-3" type="checkbox" value="<?php echo ($vvvo["id"]); ?>" data-name="<?php echo ($vvvo["name"]); ?>"> <?php echo ($vvvo["name"]); ?>
                                                                </label>
                                                            </div>
                                                        </div><?php endforeach; endif; ?>


                                                </div><?php endforeach; endif; ?>

                                        </div><?php endforeach; endif; ?>
                                </div>
                            </div>
                            <!----- 一级 -------->
                            <div class="col-md-offset-1 col-md-4">
                                <div class="row">

                                    <div class="col-md-offset-1 col-md-11 margin-bottom-30">
                                        <div style="margin-bottom: 10px;"><label class="label label-primary">商城分类</label></div>
                                        <table class="table table-striped table-hover table-bordered" >
                                            <thead>
                                            <th>分类</th>
                                            <th>向上</th>
                                            <th>向下</th>
                                            </thead>
                                            <tbody id="orderlist">



                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="order" name="order">
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <!--<div class="pull-left">
                            <button type="reset" class="btn btn-default" onclick="javascript:history.go(-1);">返回</button>
                        </div>-->
                        <div class="pull-right">
                            <button type="button" id="submit-order" class="btn btn-primary">保存修改</button>
                        </div>
                    </div>
                    <!-- /.box-footer-->
                </form>
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
</body>
</html>