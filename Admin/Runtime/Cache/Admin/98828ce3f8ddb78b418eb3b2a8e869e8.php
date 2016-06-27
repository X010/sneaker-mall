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
    var focus_item = '';
    var giving_num = 0;
    $().ready(function(){
        if (<?php echo ($_SESSION["business"] == "B2C" ? 1 : 0); ?>){
            $('#cctypename').html('可见会员');
            $('input[name="supplier[]"]').each(function(i){
                $(this).attr('data-name', VIP_TYPES[i]).next('span').text(VIP_TYPES[i]);
            });
        }

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


        /* ------------------------------------- 基础商品搜索 BEGIN ------------------------------------- */
        $('#gkey-base').on("keyup", function (e) {
            if (e.keyCode == 13) {
                gdsearch_base();
            }
        });
        $('#gsearch-base').on('click',function(){
            gdsearch_base();
        });

        function gdsearch_base(){
            var key = $('#gkey-base').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或编码', { icon: 2, time: 3000 });
                return;
            }
            //var url = "<?php echo U('goods/bind_search');?>&key="+key;
            var url = "<?php echo U('goods/gsearch');?>&gtyp=1&key="+key;
            $.get(url,'',function(data){
                if(data.error === 0){
                    $('#scontent-base').empty();
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
                        var tr = $('<tr data-id="'+ v.id +'" data-unit="'+ v.unit +'" data-spec="'+ v.spec +'" data-unit="'+ v.unit+'" data-gname="'+ v.gname +'"></tr>');
                        var trid = $('<td><div align="center">'+ v.gcode +'</div></td>');
                        var trname = $('<td><div align="left">'+ v.gname +'</div></td>');
                        var trcode = $('<td><div align="center">'+ v.barcode +'</div></td>');
                        var spec = $('<td><div align="center">'+ v.spec +'</div></td>');

                        tr.append(trid);
                        tr.append(trname);
                        tr.append(trcode);
                        tr.append(spec);

                        tr.on('dblclick',function(){
                            $('#basegoods').val($(this).attr('data-gname'));
                            $('#basegoodsid').val($(this).attr('data-id'));
                            $('#spec').val($(this).attr('data-spec'));
                            //$('#showunit').val($(this).attr('data-unit'));
                            $('#showunit').val('箱');
                            $('#scontent-base').empty();
                            $('#gkey-base').val('');
                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#scontent-base').append(tb);
                }
            })
        }

        /* ------------------------------------- 基础商品搜索 END ------------------------------------- */



        /* ------------------------------------- 捆绑商品搜索 BEGIN ------------------------------------- */
        $('#gkey-bind').on("keyup", function (e) {
            if (e.keyCode == 13) {
                gdsearch_bind();
            }
        });
        $('#gsearch-bind').on('click',function(){
            gdsearch_bind();
        });

        function gdsearch_bind(){
            var key = $('#gkey-bind').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或编码', { icon: 2, time: 3000 });
                return;
            }
            //var url = "<?php echo U('goods/bind_search');?>&key="+key;
            var url = "<?php echo U('goods/gsearch');?>&gtyp=1&key="+key;
            $.get(url,'',function(data){
                if(data.error === 0){
                    $('#scontent-bind').empty();
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
                        var tr = $('<tr data-id="'+ v.id +'" data-unit="'+ v.unit +'" data-pkgspec="'+ v.pkgspec +'" data-unit="'+ v.unit+'" data-gname="'+ v.gname +'"></tr>');
                        var trid = $('<td><div align="center">'+ v.gcode +'</div></td>');
                        var trname = $('<td><div align="left">'+ v.gname +'</div></td>');
                        var trcode = $('<td><div align="center">'+ v.barcode +'</div></td>');
                        var spec = $('<td><div align="center">'+ v.spec +'</div></td>');

                        tr.append(trid);
                        tr.append(trname);
                        tr.append(trcode);
                        tr.append(spec);

                        tr.on('dblclick',function(){
                            var goods_info = {
                                'id': $(this).attr('data-id'),
                                'gname': $(this).attr('data-gname'),
                                'gcode': $(this).attr('data-gcode'),
                            };
                            add_goods_row(goods_info, 'bind_', 'bind_');
                            $('#scontent-bind').empty();
                            $('#gkey-bind').val('');

                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#scontent-bind').append(tb);
                }
            })
        }

        /* ------------------------------------- 捆绑商品搜索 END ------------------------------------- */

        /* ------------------------------------- 赠送商品搜索 BEGIN ------------------------------------- */
        $('#gkey-gift').on("keyup", function (e) {
            if (e.keyCode == 13) {
                gdsearch_gift();
            }
        });
        $('#gsearch-gift').on('click',function(){
            gdsearch_gift();
        });

        function gdsearch_gift(){
            var key = $('#gkey-gift').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或编码', { icon: 2, time: 3000 });
                return;
            }
            //var url = "<?php echo U('goods/bind_search');?>&key="+key;
            var url = "<?php echo U('goods/gsearch');?>&gtyp=1&key="+key;
            $.get(url,'',function(data){
                if(data.error === 0){
                    $('#scontent-gift').empty();
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
                        var tr = $('<tr data-id="'+ v.id +'" data-unit="'+ v.unit +'" data-spec="'+ v.spec +'" data-unit="'+ v.unit+'" data-gname="'+ v.gname +'"></tr>');
                        var trid = $('<td><div align="center">'+ v.gcode +'</div></td>');
                        var trname = $('<td><div align="left">'+ v.gname +'</div></td>');
                        var trcode = $('<td><div align="center">'+ v.barcode +'</div></td>');
                        var spec = $('<td><div align="center">'+ v.spec +'</div></td>');

                        tr.append(trid);
                        tr.append(trname);
                        tr.append(trcode);
                        tr.append(spec);

                        tr.on('dblclick',function(){
                            var goods_info = {
                                'id': $(this).attr('data-id'),
                                'gname': $(this).attr('data-gname'),
                                'gcode': $(this).attr('data-gcode'),
                            };
                            add_giving_goods_row(goods_info, 'giving_', 'giving_');
                            $('#scontent-gift').empty();
                            $('#gkey-gift').val('');

                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#scontent-gift').append(tb);
                }
            })
        }

        /* ------------------------------------- 赠送商品搜索 END ------------------------------------- */



    });


    var giving_num = 0;
    /**
     * 添加一行商品(活动定制)
     *   需要全局变量 giving_num
     * @param goods_info
     * @param prefix 参数前缀 如:giving_ / bind_
     */
    function add_giving_goods_row(goods_info, prefix_goods, prefix_num){
        var goods = {
            'id': '',
            'gname': '',
            'gcode': '',
            'num': '',
        };
        $.extend(goods, goods_info);
        prefix_goods = prefix_goods ? prefix_goods : 'giving_';
        prefix_num = prefix_num ? prefix_num : 'z';

        var lists = $('#'+prefix_goods+'goods_lists');
        var doit = true;

        //检测是否已经有了
        $('input[name="'+prefix_goods+'goods[]"]').each(function(){
            if ($(this).val() == goods.id) {
                parent.layer.msg('该商品已经添加过', { icon: 2, time: 2000 });
                doit = false;
                return;
            }
        });


        if (doit){
            var dgroup = $('<div class="form-group"></div>');
            var dlabel = $('<label class="col-xs-3 control-label"></label>');
            var dlabel_close = $('<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

            dlabel.append(dlabel_close);
            dgroup.append(dlabel);
            dlabel_close.on('click',function(){
                dgroup.remove();
            });


            giving_num = giving_num + 1;
            var dgiving = $('<div class="col-xs-7"></div>');
            var dgiving_goods_title = $('<div class="input-group"><div class="input-group-addon">标题</div><input type="text" class="form-control"  id="'+prefix_goods+'title_'+ giving_num +'" name="'+prefix_goods+'title[]" value="'+goods.gname+'"></div>');
            var dgiving_goods_name = $('<div class="input-group"><div class="input-group-addon">赠品</div><input type="text" disabled="disabled" class="form-control"  id="'+prefix_goods+'goods_name_show_'+ giving_num +'" name="'+prefix_goods+'goods_name_show[]" value="'+goods.gname+'"></div>');
            var dgiving_goods = $('<input type="hidden" class="form-control"  id="'+prefix_goods+'goods_name_'+ giving_num +'" name="'+prefix_goods+'goods_name[]" value="'+goods.gname+'">');
            var dgiving_goods_id = $('<input type="hidden" name="'+prefix_goods+'goods[]" id="'+prefix_goods+'goods_'+ giving_num +'_id" value="'+goods.id+'" />');
            var dgiving_goods_code = $('<input type="hidden" name="'+prefix_goods+'goods_code[]" id="'+prefix_goods+'goods_'+ giving_num +'_code" value="'+goods.gcode+'" />');
            dgiving_goods_title.appendTo(dgiving);
            dgiving_goods_name.appendTo(dgiving);
            dgiving_goods.appendTo(dgiving);
            dgiving_goods_id.appendTo(dgiving);
            dgiving_goods_code.appendTo(dgiving);
            dgiving.appendTo(dgroup);

            var dgiving_num = $('<div class="col-xs-2"></div>');
            var dgiving_num_group = $('<div class="input-group"></div>');
            var dgiving_num_num = $('<div style="height:34px;"></div><input onblur="modifyGivingTitle(this);" type="text" class="form-control" name="'+prefix_num+'goods_num[]" placeholder="数量" value="'+ goods.num +'">');
            dgiving_num_num.appendTo(dgiving_num_group);
            dgiving_num_group.appendTo(dgiving_num);
            dgiving_num.appendTo(dgroup);

            lists.append(dgroup);
            $('input[name="'+prefix_num+'goods_num[]"]:last').focus();
        }
    }


    /**
     * 自动填充赠品标题
     */
    function modifyGivingTitle(obj){
        var name = $.trim($(obj).parent().parent().parent().find('input[name="giving_goods_name[]"]').val());
        var num = $.trim($(obj).parent().parent().parent().find('input[name="giving_goods_num[]"]').val());
        var title_obj = $(obj).parent().parent().parent().find('input[name="giving_title[]"]');
        if (title_obj.val().indexOf(name) == 0){
            $(obj).parent().parent().parent().find('input[name="giving_title[]"]').val(name + ' ×' + num);
        }
    }

    /**
     * 检测并提交
     * @returns {boolean}
     */
    function checkForm(){
        var msg = '';
        var doit = false;
        //商品信息检测
        var type = $('#goods_type').val();
        if (type == 1){ //大包装
            if ($.trim($('#maingoods').val()) == ''){
                $('#maingoods').focus();
                msg = '请填写促销商品名称';
                doit = false;
            } else if ($.trim($('#basegoodsid').val()) == ''){
                $('#basegoodsid').focus();
                msg = '请选择基础商品';
                doit = false;
            } else if ($.trim($('#showunit').val()) == ''){
                $('#showunit').focus();
                msg = '请填写促销商品单位';
                doit = false;
            } else if ($.trim($('#spec').val()) == ''){
                $('#spec').focus();
                msg = '请选择基础商品规格';
                doit = false;
            } else if ($.trim($('#tid').val()) == '0'){
                msg = '请选择促销商品分类';
                $('#tid').focus();
                doit = false;
            } else {
                //$('#eform').submit();
                //return;
                doit = true;
            }
            if (!doit){
                parent.layer.msg(msg, { icon: 2, time: 2000 });
                return;
            }

        } else if (type == 2){ //捆绑商品
            if ($.trim($('#maingoods').val()) == ''){
                $('#maingoods').focus();
                msg = '请填写促销商品名称';
                doit = false;
            } else if ($.trim($('#showunit').val()) == ''){
                $('#showunit').focus();
                msg = '请填写显示单位';
                doit = false;
            } else if ($.trim($('#tid').val()) == '0'){
                $('#tid').focus();
                msg = '请选择促销商品分类';
                doit = false;
            } else if ($.trim($('input[name="bind_goods[]"]').val()) == ''){
                msg = '请添加绑定商品';
                doit = false;
            } else {
                var err = 0;
                $('input[name="bind_goods_num[]"]').each(function(){
                    if ($.trim($(this).val()) == '' || $.trim($(this).val()) == 0){
                        msg = '请输入绑定商品数量';
                        err = 1;
                        doit = false;
                    }
                });
                if (!err){
                    //$('#eform').submit();
                    //return;
                    doit = true;
                }
            }
            if (!doit){
                parent.layer.msg(msg, { icon: 2, time: 2000 });
                return;
            }
        }

        //策略检测
        if ($.trim($('#begin_time').val()) == ''){
            parent.layer.msg('请选择开始时间', { icon: 2, time: 2000 });
            $('#begin_time').focus();
            doit = false;
        } else if ($.trim($('#end_time').val()) == ''){
            parent.layer.msg('请选择结束时间', { icon: 2, time: 2000 });
            $('#end_time').focus();
            doit = false;
        } else if ($.trim($('input[name="giving_goods[]"]').val()) == ''){
            parent.layer.msg('请添加赠品', { icon: 2, time: 2000 });
            $('#giving_goods_1').focus();
            doit = false;
        } else {
            $('#timer').val($.trim($('#begin_time').val()) + ' - ' + $.trim($('#end_time').val()));

            var miss_giving_title = false;
            $('input[name="giving_title[]"]').each(function(){
                if ($.trim($(this).val()) == ''){
                    parent.layer.msg('请输入赠品标题', { icon: 2, time: 2000 });
                    $(this).focus();
                    miss_giving_title = true;
                    return;
                }
            });
            if (miss_giving_title){
                return;
            }

            var miss_num = false;
            $('input[name="giving_goods_num[]"]').each(function(){
                if ($.trim($(this).val()) == ''){
                    parent.layer.msg('请输入赠品数量', { icon: 2, time: 2000 });
                    $(this).focus();
                    miss_num = true;
                    return;
                }
            });
            if (miss_num){
                return;
            } else {
                if (!doit){
                    parent.layer.msg(msg, { icon: 2, time: 2000 });
                    return;
                }
            }

            $('#eform').submit();
        }


    }


    /**
     * 切换活动商品类型
     * @param type
     */
    function f_goods_type(type){
        if(type == 1){ //大包装商品
            $('#div_pkg').show();
            $('#div_bind').hide();
            $('#searchbox-base').show();
            $('#searchbox-bind').hide();
            //解除自身禁用
            //$('#basegoods').removeAttr('disabled');
            $('#basegoodsid').removeAttr('disabled');
            $('#spec').removeAttr('disabled');
            //禁用其他
            $('#place').attr('disabled', 'disabled');
            $('#bind_goods_lists').empty();

        } else if(type == 2){ //绑定商品
            $('#div_pkg').hide();
            $('#div_bind').show();
            $('#searchbox-base').hide();
            $('#searchbox-bind').show();
            //解除自身禁用
            $('#place').removeAttr('disabled');
            //禁用其他
            $('#basegoods').attr('disabled', 'disabled');
            $('#basegoodsid').attr('disabled', 'disabled');
            $('#spec').attr('disabled', 'disabled');
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
                新建买赠促销
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
                            <form class="form-horizontal" id="eform" method="post" action="<?php echo U('goods/gift');?>">

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">促销商品类型</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <select class="form-control" id="goods_type" name="goods_type" onchange="f_goods_type(this.value)">
                                                <option value="1" selected>大包装商品</option>
                                                <option value="2">捆绑商品</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">促销商品名称</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="newmainname" id="maingoods" placeholder="如：双沟小青花买一送一">
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>



                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">促销商品分类</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <!--<div class="input-group-addon">N</div>-->
                                            <select class="form-control" id="tid" name="tid">
                                                <option value="0">- 请选择商品分类 -</option>
                                                <?php
 foreach($tlist as $key=>$val){ $ronly = (isset($val['data']) && !empty($val['data']))?'disabled':''; echo "<option ".$ronly." value='".$val['id']."'>".$val['name']."</option>"; foreach($val['data'] as $key2=>$val2){ $ronly = (isset($val2['data']) && !empty($val2['data']))?'disabled':''; echo "<option ".$ronly." value='".$val2["id"]."'>------".$val2["name"]."</option>"; foreach($val2['data'] as $key3=>$val3){ $ronly = (isset($val3['data']) && !empty($val3['data']))?'disabled':''; echo "<option  ".$ronly." value='".$val3["id"]."'>-----------".$val3["name"]."</option>"; } } } ?>
                                            </select>
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 大包装商品 BEGIN -->
                                <div id="div_pkg">

                                    <div class="form-group ">
                                        <label class="col-sm-3 control-label">基础商品名称</label>
                                        <div class="col-xs-7">
                                            <div class="input-group">
                                                <input type="text" disabled class="form-control" name="basegoods" id="basegoods" placeholder="请通过右边搜索添加">
                                                <input type="hidden" id="basegoodsid" name="basegoodsid">
                                                <div class="input-group-addon">必填</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">促销商品规格</label>
                                        <div class="col-xs-7">
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">N</div>-->
                                                <input type="text" class="form-control" name="spec" id="spec" placeholder="如：6">
                                                <div class="input-group-addon">必填</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <!-- 大包装商品 END -->

                                <!-- 捆绑商品 BEGIN -->

                                <div id="div_bind" style="display:none">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">商品产地</label>
                                        <div class="col-xs-7">
                                            <div class="input-group">
                                                <!--<div class="input-group-addon">N</div>-->
                                                <input type="text" class="form-control" name="place" id="place" placeholder="如：南京">
                                                <div class="input-group-addon">选填</div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label  class="col-sm-3 control-label">绑定商品</label>
                                        <div class="col-xs-7">
                                            <span class="checkbox-inline">请到右侧搜索要绑定的商品，然后双击添加</span>
                                        </div>
                                    </div>

                                    <div id="bind_goods_lists"></div>
                                </div>
                                <!-- 捆绑商品 END -->


                                <!-- 新建商品共用部分 BEGIN -->

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">促销商品单位</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <!--<div class="input-group-addon">N</div>-->
                                            <input type="text" class="form-control" name="showunit" id="showunit" placeholder="如：套">
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" id="cctypename">可见客户</label>
                                    <div class="col-xs-8">
                                        <?php if(is_array($customer)): foreach($customer as $key=>$vo): ?><label class="checkbox-inline">
                                                <input type="checkbox" checked="checked" name="supplier[]" value="<?php echo ($vo["id"]); ?>"><span><?php echo ($vo["name"]); ?></span>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">出货仓库</label>
                                    <div class="col-xs-7">
                                        <?php if(is_array($ck)): foreach($ck as $key=>$vo): ?><label class="checkbox-inline">
                                                <input type="checkbox" checked="checked" name="ck[]" class="ck" value="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["name"]); ?>"> <?php echo ($vo["name"]); ?>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>

                                <!-- 新建商品共用部分 END -->

                                <hr />


                                <!-- 活动策略部分 BEGIN -->

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label"></label>
                                    <div class="col-xs-9">
                                        <span class="" style="color:#B13030;">注意：赠送商品价格将为0！活动结束后将不再赠送赠品！</span>
                                    </div>
                                </div>

                                <!---
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">赠品栏说明</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="title" name="title" placeholder="如：送青岛冰纯500ml一瓶">
                                    </div>

                                </div>
                                -->

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">角标名称</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="iconname" name="iconname" value="买赠" placeholder="如：买赠">
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">促销开始时间</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="begin_time" name="begin_time" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">促销结束时间</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="end_time" name="end_time" />
                                        <input type="hidden" class="form-control" id="timer" name="timer" value="" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">添加赠品</label>
                                    <div class="col-xs-7">
                                        <span class="checkbox-inline">请到右侧搜索要添加的赠品，然后双击添加</span>
                                    </div>
                                </div>

                                <div id="giving_goods_lists"></div>


                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <button type="button" onclick="checkForm();" class="btn btn-primary">确定新建</button>
                                    </div>
                                </div>

                                <!-- 活动策略部分 END -->



                            </form>
                            <!-- params end -->
                        </div>
                        <div class="col-md-4">
                            <div id="searchbox-base">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="gkey-base" placeholder="搜索基础商品">
                                            <div class="input-group-addon" id="gsearch-base"><i class="fa fa-search"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id='scontent-base' class="col-xs-12">
                                    </div>
                                </div>
                            </div>

                            <div id="searchbox-bind" style="display:none;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="gkey-bind" placeholder="搜索绑定商品">
                                            <div class="input-group-addon" id="gsearch-bind"><i class="fa fa-search"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id='scontent-bind' class="col-xs-12">
                                    </div>
                                </div>
                            </div>

                            <div id="searchbox-gift">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="gkey-gift" placeholder="搜索赠送商品">
                                            <div class="input-group-addon" id="gsearch-gift"><i class="fa fa-search"></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div id='scontent-gift' class="col-xs-12">
                                    </div>
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