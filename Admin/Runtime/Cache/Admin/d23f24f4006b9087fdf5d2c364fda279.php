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

<link rel="stylesheet" href="/Assets/Admin/Public/css/daterangepicker.css">
<script src="/Assets/Admin/Public/js/moment.min.js"></script>
<script src="/Assets/Admin/Public/js/daterangepicker.js"></script>
<script>
    $().ready(function(){
        // 时间选择
        $('#begin_time').daterangepicker({
            "autoApply": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "timePickerSeconds": true,
            "singleDatePicker": true,
            "showDropdowns": true,
            "locale": {
                "format": "YYYY-MM-DD HH:mm:ss",
                //"separator": " - ",//与后端一致
                "applyLabel": "确定",
                "cancelLabel": "取消",
                //"fromLabel": "从",
                //"toLabel": "到",
                //"customRangeLabel": "自定义",
                "daysOfWeek": [
                    "日",
                    "一",
                    "二",
                    "三",
                    "四",
                    "五",
                    "六"
                ],
                "monthNames": [
                    "一月",
                    "二月",
                    "三月",
                    "四月",
                    "五月",
                    "六月",
                    "七月",
                    "八月",
                    "九月",
                    "十月",
                    "十一月",
                    "十二月"
                ],
                "firstDay": 1
            },
            "linkedCalendars": false,
        }, function(start, end, label) {
            //console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        });
        $('#end_time').daterangepicker({
            "autoApply": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "timePickerSeconds": true,
            "singleDatePicker": true,
            "showDropdowns": true,
            "locale": {
                "format": "YYYY-MM-DD HH:mm:ss",
                //"separator": " - ",//与后端一致
                "applyLabel": "确定",
                "cancelLabel": "取消",
                //"fromLabel": "从",
                //"toLabel": "到",
                //"customRangeLabel": "自定义",
                "daysOfWeek": [
                    "日",
                    "一",
                    "二",
                    "三",
                    "四",
                    "五",
                    "六"
                ],
                "monthNames": [
                    "一月",
                    "二月",
                    "三月",
                    "四月",
                    "五月",
                    "六月",
                    "七月",
                    "八月",
                    "九月",
                    "十月",
                    "十一月",
                    "十二月"
                ],
                "firstDay": 1
            },
            "linkedCalendars": false,
        }, function(start, end, label) {
            //console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
        });
        $('#begin_time').val('<?php echo ($market["start_time"]); ?>');
        $('#end_time').val('<?php echo ($market["end_time"]); ?>');

        //回车搜索
        $('#gkey').on("keyup", function (e) {
            if (e.keyCode == 13) {
                gdsearch();
            }
        });
        $('#gsearch').on('click',function(){
            gdsearch();
        });

        function gdsearch(){
            var key = $('#gkey').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或条码', { icon: 2, time: 2000 });
                return;
            }
            var url = "<?php echo U('goods/gsearch');?>&gtyp=1&key="+key;
            $.get(url,'',function(data){
                //console.log(data);
                if(data.error === 0){
                    $('#scontent').empty();
                    var tb = $('<table class="table table-striped table-hover table-bordered"></table>');
                    var thead = $('<thead> \ ' +
                            '<tr> \ ' +
                            '<th> <div align="center">ID</div></th> \ ' +
                            '<th><div align="center">名称</div></th>\ ' +
                            '<th><div align="center">条码</div></th>\ ' +
                            '<th><div align="center">规格</div></th>\ ' +
                            '</tr>\ ' +
                            '</thead>');
                    tb.append(thead);

                    var tbody = $('<tbody></tbody>');
                    $.each(data.data.data,function(k,v){
                        var tr = $('<tr data-id="'+ v.id +'" data-gcode="'+ v.gcode +'" data-isbind="'+ v.isbind+'" data-pkgsize="'+ v.pkgsize+'" data-unit="'+ v.unit +'" data-pkgspec="'+ v.pkgspec +'" data-unit="'+ v.unit+'" data-gname="'+ v.gname +'"></tr>');
                        var trid = $('<td><div align="center">'+ v.gcode +'</div></td>');
                        var trname = $('<td><div align="left">'+ v.gname +'</div></td>');
                        var trcode = $('<td><div align="center">'+ v.barcode +'</div></td>');
                        var tunit = $('<td><div align="center">'+ v.unit +'</div></td>');

                        tr.append(trid);
                        tr.append(trname);
                        tr.append(trcode);
                        tr.append(tunit);

                        tr.on('dblclick',function(){
                            var goods_info = {
                                'id': $(this).attr('data-id'),
                                'gname': $(this).attr('data-gname'),
                                'gcode': $(this).attr('data-gcode'),
                            };
                            add_goods_row(goods_info);
                            $('#scontent').empty();
                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#scontent').append(tb);
                }
            })
        }

        var goods = eval('<?php echo ($market["strategy"]); ?>');
        for(var i=0; i<goods.length; i++){
            var goods_info = {
                'id': goods[i].mgid,
                'gname': goods[i].gname,
                'gcode': goods[i].gcode,
                'num': goods[i].total,
            };
            add_goods_row(goods_info);
        }


    });


    /**
     * 删除
     */
    function deleteMarket(){
        if (confirm("确定删除该赠品栏？")) {
            $('#eform').attr('action', "<?php echo U('market/delete');?>").submit();
        }
    }

    /**
     * 检测后提交
     */
    function checkForm(){
        if ($.trim($('#title').val()) == ''){
            parent.layer.msg('请输入策略名称', { icon: 2, time: 2000 });
            $('#title').focus();
            //} else if ($.trim($('#des').val()) == ''){
            //    parent.layer.msg('请输入策略描述', { icon: 2, time: 3000 });
            //    $('#des').focus();
        } else if ($.trim($('#begin_time').val()) == ''){
            parent.layer.msg('请选择开始时间', { icon: 2, time: 2000 });
            $('#begin_time').focus();
        } else if ($.trim($('#end_time').val()) == ''){
            parent.layer.msg('请选择结束时间', { icon: 2, time: 2000 });
            $('#end_time').focus();
        } else if ($.trim($('input[name="giving_goods[]"]').val()) == ''){
            parent.layer.msg('请选择赠品', { icon: 2, time: 2000 });
            $('#giving_goods_1').focus();
        } else {
            $('#timer').val($.trim($('#begin_time').val()) + ' - ' + $.trim($('#end_time').val()));
            var doit = true;
            $('input[name="zgoods_num[]"]').each(function(){
                if ($.trim($(this).val()) == ''){
                    parent.layer.msg('请输入赠品数量', { icon: 2, time: 2000 });
                    $(this).focus();
                    doit = false;
                    return;
                }
            });

            doit && $('#eform').submit();
        }
    }




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
            <h1>
                修改赠品促销
                <small></small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo ($good["gname"]); ?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- params -->
                            <form class="form-horizontal" id="eform" method="post" action="<?php echo U('market/gone');?>">

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">赠品ID</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" value="<?php echo ($market["id"]); ?>" disabled="disabled">
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">赠品标题</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo ($market["title"]); ?>" placeholder="如：大青花送小青花">
                                        <input type="hidden" class="form-control" name="id" value="<?php echo ($market["id"]); ?>">
                                    </div>

                                </div>

                                <!--<div class="form-group ">
                                    <label class="col-sm-3 control-label">赠品栏描述</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="des" name="des" value="<?php echo ($market["description"]); ?>" placeholder="如：双十一买1箱大青花送1瓶小青花">
                                    </div>

                                </div>-->

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">角标名称</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="iconname" name="iconname" value="<?php echo ($market["iconname"]); ?>" placeholder="如：买赠">
                                    </div>

                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">开始时间</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="begin_time" name="begin_time" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">结束时间</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="end_time" name="end_time" />
                                        <input type="hidden" class="form-control" id="timer" name="timer" value="" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">添加赠品</label>
                                    <div class="col-xs-7">
                                        <span class="checkbox-inline">请到右侧搜索要添加的商品,然后双击添加</span>
                                    </div>
                                </div>

                                <div id="giving_goods_lists"></div>


                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <button type="button" onclick="checkForm();" class="btn btn-primary">确定修改</button>
                                        <button type="button" onclick="deleteMarket();" class="btn btn-danger">删除</button>
                                    </div>
                                </div>



                            </form>
                            <!-- params end -->
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">

                                        <input type="text" class="form-control" id="gkey" placeholder="请输入要添加的赠品商品">
                                        <div class="input-group-addon" id="gsearch"><i class="fa fa-search"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id='scontent' class="col-xs-12">
                                </div>
                            </div>

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
</body>
</html>