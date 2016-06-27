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
<script>

    var giving_num_s = 0;
    $().ready(function(){

        if (<?php echo ($_SESSION["business"] == "B2C" ? 1 : 0); ?>){
            $('#cctypename').html('会员类型');
            $('input[name="supplier[]"]').each(function(i){
                $(this).attr('data-name', VIP_TYPES[i]).next('span').text(VIP_TYPES[i]);
            });
        }

        $('#qs').val(getQueryString()); //填充状态保持参数

        $('#tid').change(function(){
            var val = $(this).val();
            var tcode = $(this).find('option[value="' + val + '"]').attr('ext');
            $('#tcode').val(tcode);
        }).val('<?php echo ($good["tid"]); ?>').trigger('change'); //填充分类


        <?php
 if(!$good['pkgsize'] && !$good['isbind']){ echo "$('#tid').attr('disabled', 'disabled');"; } ?>

        <?php echo ($json_ck); ?>

        ck.forEach(function(e){
            var _ck = e;
            $('input[name="ck[]"]').each(function(){
                if ($(this).val() == _ck.id) {
                    $(this).attr('checked', 'checked');
                }
            });
        });


        /** 图片处理 **/
        $('#baseimg').on('click',function(){
            $('#baseimgarea').css('display','block');
            $('#styleimgarea').hide();
        });
        $('#styleimg').on('click',function(){
            $('#baseimgarea').hide();
            $('#styleimgarea').show();
        });

        //显示自定义图片
        var gphoto = parseInt('<?php echo ($good["gphoto"]); ?>');
        if(gphoto <= 100){
            $('#baseimg').attr('checked','checked');
            $('#baseimgarea').show();
        }else{
            $('#styleimg').attr('checked','checked');
            $('#styleimgarea').show();
            //console.log('<?php echo ($good["gphoto"]); ?>');
            var gphoto = '<?php echo ($good["gphoto"]); ?>';
            var gphoto_list = gphoto.split(',');
            for(var i= 0;i<gphoto_list.length;i++){
                var img = $('<img src="<?php echo C('BASE_GOOD_IMG_URL'); echo ($good["gcode"]); ?>_'+gphoto_list[i]+'.jpg<?php echo C('IMG_SPEC_SM');?>" width="100px" height="100px">');
                img.appendTo($('#imgshow'));
                $('#imgshow').append('<a href="#" imgid="'+gphoto_list[i]+'" onclick="deletePhoto(this)" class="img-delete">×</a>');
            }
            //$('#imgid').val('<?php echo ($good["gphoto"]); ?>');
        }


        function add_stgy_row(title,id){
            var title = title?title:'';
            var id = id?id:'';
            var lists = $('#strategy_lists');

            var doit = true;

            //检测是否已经有了
            $('input[name="stgy[]"]').each(function(){
                if ($(this).val() == id) {
                    parent.layer.msg('该策略已经添加过', { icon: 2, time: 2000 });
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


                giving_num_s = giving_num_s + 1;
                var dgiving = $('<div class="col-xs-7"></div>');
                var dgiving_goods_name = $('<input type="text" disabled="disabled" class="form-control"  id="stgy_'+ giving_num_s +'" name="stgy_name[]" value="'+title+'" />');
                var dgiving_goods = $('<input type="hidden" class="form-control"  id="stgy_'+ giving_num_s +'" name="stgy_title[]" value="'+title+'" />');
                var dgiving_goods_id = $('<input type="hidden" name="stgy[]" value="'+id+'" id="stgy_'+ giving_num_s +'_id">');
                dgiving_goods_name.appendTo(dgiving);
                dgiving_goods.appendTo(dgiving);
                dgiving_goods_id.appendTo(dgiving);
                dgiving.appendTo(dgroup);

                lists.append(dgroup);
            }

        }


        //策略搜索 BEGIN
        $('#skey').on("keyup", function (e) {
            if (e.keyCode == 13) {
                ssearch();
            }
        });
        $('#ssearch').on('click',function(){
            ssearch();
        });

        function ssearch(){
            var key = $('#skey').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或条码', { icon: 2, time: 3000 });
                return;
            }
            var url = "<?php echo U('market/search');?>&key="+key;
            $.get(url,'',function(data){
                //console.log(data);
                if(data.error === 0){
                    $('#scontent').empty();
                    var tb = $('<table class="table table-striped table-hover table-bordered"></table>');
                    var thead = $('<thead> \ ' +
                            '<tr> \ ' +
                            '<th><div align="center">ID</div></th>\ ' +
                            '<th><div align="center">开始时间</div></th>\ ' +
                            '<th><div align="center">结束时间</div></th>\ ' +
                            '<th><div align="center">内容</div></th>\ ' +
                            '</tr>\ ' +
                            '</thead>');
                    tb.append(thead);

                    var tbody = $('<tbody></tbody>');
                    $.each(data.data,function(k,v){
                        var tr = $('<tr data-id="'+ v.id +'" data-title="'+ v.title+'"></tr>');
                        var tr_id = $('<td><div align="center">'+ v.id +'</div></td>');
                        var tr_start_time = $('<td><div align="center">'+ v.start_time +'</div></td>');
                        var tr_end_time = $('<td><div align="left">'+ v.end_time +'</div></td>');
                        var tr_title = $('<td><div align="center">'+ v.title +'</div></td>');

                        tr.append(tr_id);
                        tr.append(tr_start_time);
                        tr.append(tr_end_time);
                        tr.append(tr_title);

                        tr.on('dblclick',function(){
                            add_stgy_row($(this).attr('data-title'), $(this).attr('data-id'));
                            $('#scontent').empty();
                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#scontent').append(tb);
                }
            })
        }
        // 策略搜索 END

        //上传图片
        $('input[type="file"]').change(function(e){
            var file = $(this).get(0).files[0];
            //判断类型是不是图片
            if(!/image\/\w+/.test(file.type)){
                parent.layer.msg('不支持该图片类型', { icon: 2, time: 3000 });
                return false;
            }

            var reader = new FileReader();
            reader.readAsDataURL(file);
            uploading(1);
            reader.onload = function(e){
                $.post(
                        "<?php echo U('goods/upload_img');?>",
                        {src:this.result,gcode:'<?php echo ($good["gcode"]); ?>'},
                        function(data){
                            uploading(0);
                            if(data.error == 0) {
                                var img = $('<img src="<?php echo C('BASE_GOOD_IMG_URL'); echo ($good["gcode"]); ?>_'+data.data+'.jpg<?php echo C('IMG_SPEC_SM');?>" width="100px" height="100px">');
                                img.appendTo($('#imgshow'));
                                $('#imgshow').append('<a href="#" imgid="'+data.data+'" onclick="deletePhoto(this)" class="img-delete">×️</a>');
                                /*if($('#imgid').val()){
                                    var new_img_data = $('#imgid').val()+','+data.data;
                                }else{
                                    var new_img_data = data.data;
                                }
                                $('#imgid').val(new_img_data);*/
                            } else{
                                parent.layer.msg('上传图片失败', { icon: 2, time: 2000 });
                            }
                        }
                )
            }

        });

        function uploading(flag){
            $('#uploading').remove();
            if (flag){
                $('#imgshow').append('<span id="uploading">上传中...</span>');
            }
        }


        // 初始化策略
        <?php
 $i = 1; foreach($stgy as $key=>$val) { echo ' add_stgy_row("'.$val['title'].'","'.$val['id'].'");'; $now = date('Y-m-d H:i:s', time()); if ($now < $val['begin_time']){ echo '$("#stgy_'.$i.'").css("color", "red");'; echo '$("#stgy_'.$i.'").val("［未开始］" + $("#stgy_'.$i.'").val());'; } else if ($now > $val['end_time']){ echo '$("#stgy_'.$i.'").css("color", "red");'; echo '$("#stgy_'.$i.'").val("［已结束］" + $("#stgy_'.$i.'").val());'; } } ?>


        //移除绑定商品
        $('span[name="btn-close-giving_goods"]').click(function(){
            $(this).parent().parent().parent().remove();
        });



        /** 搜索 **/
        $('#gkey').on("keyup", function (e) {
            if (e.keyCode == 13) {
                gsearch();
            }
        });
        $('#gsearch').on('click',function(){
            gsearch();
        });

        function gsearch(){
            var key = $('#gkey').val();
            if(key == ""){
                parent.layer.msg('请填写要搜索的商品名称或编码', { icon: 2, time: 3000 });
                return;
            }
            var url = "<?php echo U('goods/bind_search');?>&key="+key;
            $.get(url,'',function(data){
                if(data.error === 0){
                    $('#gcontent').empty();
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
                            $('#gcontent').empty();
                            /*if(focus_item_g) {
                                if(focus_item_g[1] == 'z'){
                                    var id = focus_item_g[0].attr('id');
                                    focus_item_g[0].val($(this).attr('data-gname'));
                                    $('#' + id + '_id').val($(this).attr('data-id'));
                                }
                            }else{
                                parent.layer.msg('请将鼠标放入需要添加商品的输入框中，再添加商品', { icon: 2, time: 3000 });
                            }*/
                        });
                        tr.appendTo(tbody);
                    });

                    tb.append(tbody);
                    $('#gcontent').append(tb);
                }
            })
        }



    });

    /**
     * 删除自定图片
     **/
    function deletePhoto(a){
        $(a).prev('img').remove();
        $(a).remove();
    }

    /**
     * 检测后提交
     */
    function checkForm(){
        if ($.trim($('#gname').val()) == ''){
            msg = '请输入商品名称';
            $('#gname').focus();
        } else if ($.trim($('#spec').val()) == ''){
            msg = '请输入商品规格';
            $('#spec').focus();
        } else if ($.trim($('#unit').val()) == ''){
            msg = '请输入商品单位';
            $('#unit').focus();
        } else if ($.trim($('#tid').val()) == ''){
            msg = '请选择商品分类';
            $('#tid').focus();
        }  else if (<?php echo $good['isbind']; ?> && $.trim($('input[name="bind_goods[]"]').val()) == ''){
            msg = '请添加绑定商品';
        } else {
            var err = 0;
            $('input[name="bind_goods_num[]"]').each(function(){
                if ($.trim($(this).val()) == '' || $.trim($(this).val()) == 0){
                    msg = '请输入绑定商品数量';
                    err = 1;
                }
            });
            if (!err){
                var imgid = [];
                $('#imgshow a').each(function(){
                    if ($(this).attr('imgid')){
                        imgid.push($(this).attr('imgid'));
                    }
                });
                $('#imgid').val(imgid.join(','));
                $('#tid').removeAttr('disabled');
                $('#eform').submit();
                return;
            }
        }
        parent.layer.msg(msg, { icon: 2, time: 3000 });
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
            <h1>商品编辑
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
                            <form class="form-horizontal" id="eform" method="post" action="<?php echo U('goods/gone');?>">

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">
                                        <?php
 if($good['pkgsize']){ echo '大包装'; } else if ($good['isbind']){ echo '捆绑'; } ?>商品名称
                                    </label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="gname" name='gname' value="<?php echo ($good["gname"]); ?>">
                                        <input type="hidden" name="id" value="<?php echo ($good["id"]); ?>">
                                    </div>

                                </div>

                                <?php if($good['pkgsize']){ ?>
                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">基础商品名称</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" name="basegoods" id="basegoods" placeholder="请通过右边搜索添加" value="<?php echo ($small_gname); ?>" disabled="disabled">
                                        <input type="hidden" id="basegoodsid" name="basegoodsid">
                                    </div>
                                </div>
                                <?php } ?>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">商品规格</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="spec" name='spec' value="<?php echo ($good["spec"]); ?>" <?php if(!$good['isbind'] && !$good['pkgsize']) echo 'disabled="disabled"';?>>
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">商品单位</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="unit" name='unit' value="<?php echo ($good["unit"]); ?>" <?php if(!$good['isbind'] && !$good['pkgsize']) echo 'disabled="disabled"';?>>
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">商品编码</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="gcode" name='gcode' value="<?php echo ($good["gcode"]); ?>" disabled="disabled" placeholder="如：81000001" <?php if(!$good['isbind'] && !$good['pkgsize']) echo 'disabled="disabled"';?>>
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-3 control-label">建议零售价</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" id="retail_price" name='retail_price' value="<?php echo ($good["retail_price"]); ?>"  placeholder="如：20.00" <?php if($good['isbind'] || $good['pkgsize']) echo 'disabled="disabled"';?>>
                                    </div>

                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">商品参数</label>
                                    <div class="col-xs-7">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                            <th>规格</th>
                                            <th>包装规格</th>
                                            <th>产地</th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><?php echo ($good["spec"]); ?></td>
                                                <td><?php echo ($good["pkgspec"]); ?></td>
                                                <td><?php echo ($good["place"]); ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">商品分类</label>
                                    <div class="col-xs-7">
                                        <div class="input-group">
                                            <!--<div class="input-group-addon">N</div>-->
                                            <select class="form-control" id="tid" name="tid">
                                                <option value="">- 请选择商品分类 -</option>
                                                <?php
 foreach($tlist as $key=>$val){ $ronly = (isset($val['data']) && !empty($val['data']))?'disabled':''; echo "<option ".$ronly." ext='".$val["code"]."' value='".$val['id']."'>".$val['name']."</option>"; foreach($val['data'] as $key2=>$val2){ $ronly = (isset($val2['data']) && !empty($val2['data']))?'disabled':''; echo "<option ".$ronly." ext='".$val2["code"]."' value='".$val2["id"]."'>------".$val2["name"]."</option>"; foreach($val2['data'] as $key3=>$val3){ $ronly = (isset($val3['data']) && !empty($val3['data']))?'disabled':''; echo "<option  ".$ronly." ext='".$val3["code"]."' value='".$val3["id"]."'>-----------".$val3["name"]."</option>"; } } } ?>
                                            </select>
                                            <input type="hidden" name="tcode" id="tcode" value="" />
                                            <div class="input-group-addon">必填</div>
                                        </div>
                                    </div>
                                </div>


                                <?php $cids = explode(',',$good['cctype']); ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" id="cctypename">客户类型</label>
                                    <div class="col-xs-8">
                                        <?php if(is_array($sp)): foreach($sp as $key=>$vo): ?><label class="checkbox-inline">
                                                <input type="checkbox" class="sp" <?php if(in_array($vo['id'],$cids)) echo "checked";?> name="supplier[]" value="<?php echo ($vo["id"]); ?>"><span><?php echo ($vo["name"]); ?></span>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>


                                <?php $cids = explode(',',$good['sids']); ?>
                                <?php if ($good['pkgsize'] || $good['isbind']){ ?>
                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">出货仓库</label>
                                    <div class="col-xs-7">
                                        <?php if(is_array($ck)): foreach($ck as $key=>$vo): ?><label class="checkbox-inline">
                                                <input class="ck" type="checkbox" name="ck[]" value="<?php echo ($vo["id"]); ?>" <?php if(in_array($vo['id'],$cids)) echo "checked";?> data-name="<?php echo ($vo["name"]); ?>"> <?php echo ($vo["name"]); ?>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>
                                <?php } ?>


                                <div class="form-group"><label  class="col-sm-2 control-label"></label><div class="col-xs-7"></div></div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">图片管理</label>
                                    <div class="col-xs-7">
                                        <label class="checkbox-inline">
                                            <input type="radio" id="baseimg" name="imgmodel" value="0"> 基础图片
                                        </label>
                                        <label class="checkbox-inline">
                                            <input type="radio"  id="styleimg" name="imgmodel" value="1"> 自定义图片
                                        </label>
                                    </div>
                                </div>
                                <div id="baseimgarea" class="form-group" style="display: none;">
                                    <label  class="col-sm-3 control-label"></label>
                                    <div class="col-xs-7">
                                        <?php for($i = 0;$i<$good['gphoto_index'];$i++){?>
                                        <span style="display:inline-block;">
                                            <input type="radio" name="gphoto" <?php if($good['gphoto']==$i+1) echo 'checked';?> value="<?php echo $i+1;?>">
                                            <img src="<?php echo C('BASE_GOOD_IMG_URL'); echo $good['gcode'];?>_<?php echo $i+1;?>.jpg<?php echo C('IMG_SPEC_SM');?>" class="img-thumbnail ">
                                        </span>
                                        <?php } ?>
                                    </div>
                                </div>


                                <div id="styleimgarea" class="form-group" style="display: none;">
                                    <label  class="col-sm-3 control-label"></label>
                                    <div class="col-xs-9">
                                        <div class="input-group">
                                            <input type="file" class="btn btn-default" id="picfile">
                                        </div>
                                        <div class="input-group" id="imgshow">

                                        </div>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label"></label>

                                </div>
                                <input type="hidden" name="imgid" id="imgid">
                                <!-- 绑定商品 BEGIN -->
                                <?php if($good['isbind']){ ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">绑定商品</label>
                                    <div class="col-xs-7">
                                        <span class="checkbox-inline">请到右侧搜索要添加的商品,然后双击添加</span>
                                    </div>
                                </div>

                                <div id="bind_goods_lists">

                                    <?php
 for($i = 0;$i<count($goods_bind);$i++){ ?>
                                    <div class="form-group">
                                        <label class="col-xs-3 control-label">
                                            <button type="button" class="close" aria-label="Close"><span name="btn-close-giving_goods" aria-hidden="true">×</span></button>
                                        </label>
                                        <div class="col-xs-7">
                                            <?php
 if ($goods_bind[$i]['publish']){ echo '<input type="text" disabled="disabled" name="bind_goods_name[]" class="form-control" value="'.$goods_bind[$i]['gname'].'">'; } else { echo '<input type="text" disabled="disabled" name="bind_goods_name[]" class="form-control" value="［未上架］'.$goods_bind[$i]['gname'].'" style="color:#B214B5;">'; } ?>
                                            <input type="hidden" name="bind_goods_title[]" class="form-control" disabled="disabled" value="<?php echo $goods_bind[$i]['gname']; ?>">
                                            <input type="hidden" name="bind_goods[]" value="<?php echo $goods_bind[$i]['child_mgid']; ?>">
                                        </div>
                                        <div class="col-xs-2">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="bind_goods_num[]" placeholder="数量" value="<?php echo $goods_bind[$i]['num']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php }?>
                                </div>
                                <?php } ?>
                                <!-- 绑定商品 END -->

                                <!-- 添加策略 BEGIN -->
                                <?php if ($good['pkgsize'] || $good['isbind']){ ?>

                                <div class="form-group">
                                    <label  class="col-sm-3 control-label">添加赠品栏</label>
                                    <div class="col-xs-7">
                                        <span class="checkbox-inline">请到右侧搜索赠品栏说明,然后双击添加</span>
                                    </div>
                                </div>

                                <div id="strategy_lists"></div>

                                <?php } ?>
                                <!-- 添加策略 END -->

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">商品描述</label>
                                    <div class="col-xs-7">
                                        <!--<textarea class="form-control w4" id="content" name="content" rows="6" cols="60"><?php echo ($good["content"]); ?></textarea>-->
                                        <!-- 加载编辑器的容器 -->
                                        <script id="container" name="content" type="text/plain"><?php echo ($good["content"]); ?></script>
                                        <!-- 配置文件 -->
                                        <script type="text/javascript" src="./Assets/Admin/Public/ueditor/ueditor.config.js"></script>
                                        <!-- 编辑器源码文件 -->
                                        <script type="text/javascript" src="./Assets/Admin/Public/ueditor/ueditor.all.js"></script>
                                        <!-- 实例化编辑器 -->
                                        <script type="text/javascript">
                                            var ue = UE.getEditor('container', {
                                                toolbars: [
                                                    [
                                                        'fullscreen', //全屏

                                                        'cleardoc', //清空文档
                                                        'template', //模板
                                                        //'drafts', // 从草稿箱加载
                                                        'background', //背景
                                                        '|',
                                                        'undo', //撤销
                                                        'redo', //重做
                                                        'print', //打印
                                                        '|',
                                                        //'preview', //预览
                                                        'source', //源代码
                                                        'help', //帮助
                                                    ],[
                                                        'bold', //加粗
                                                        'italic', //斜体
                                                        'underline', //下划线
                                                        'strikethrough', //删除线
                                                        //'snapscreen', //截图
                                                        'spechars', //特殊字符
                                                        'fontborder', //字符边框
                                                        //'subscript', //下标
                                                        //'superscript', //上标
                                                        //'touppercase', //字母大写
                                                        //'tolowercase', //字母小写
                                                        'forecolor', //字体颜色
                                                        'backcolor', //背景色
                                                        'formatmatch', //格式刷
                                                        'removeformat', //清除格式
                                                        'horizontal', //分隔线
                                                        'pagebreak', //分页
                                                    ],[
                                                        'fontfamily', //字体
                                                        'fontsize', //字号
                                                        'paragraph', //段落格式
                                                        'customstyle', //自定义标题
                                                        'insertorderedlist', //有序列表
                                                        'insertunorderedlist', //无序列表
                                                    ],[
                                                        'justifyleft', //居左对齐
                                                        'justifyright', //居右对齐
                                                        'justifycenter', //居中对齐
                                                        'justifyjustify', //两端对齐
                                                        'indent', //首行缩进
                                                        'rowspacingtop', //段前距
                                                        'rowspacingbottom', //段后距
                                                        'lineheight', //行间距
                                                        'directionalityltr', //从左向右输入
                                                        'directionalityrtl', //从右向左输入
                                                        //'insertframe', //插入Iframe
                                                        'imagenone', //默认
                                                        'imageleft', //左浮动
                                                        'imageright', //右浮动
                                                        //'attachment', //附件
                                                        'imagecenter', //居中
                                                        //'wordimage', //图片转存
                                                        'edittip ', //编辑提示
                                                        'autotypeset', //自动排版
                                                    ],[
                                                        'inserttable', //插入表格
                                                        'edittable', //表格属性
                                                        'edittd', //单元格属性
                                                        'insertrow', //前插入行
                                                        'insertcol', //前插入列
                                                        'mergeright', //右合并单元格
                                                        'mergedown', //下合并单元格
                                                        'deleterow', //删除行
                                                        'deletecol', //删除列
                                                        'splittorows', //拆分成行
                                                        'splittocols', //拆分成列
                                                        'splittocells', //完全拆分单元格
                                                        'deletecaption', //删除表格标题
                                                        'inserttitle', //插入标题
                                                        'mergecells', //合并多个单元格
                                                        'deletetable', //删除表格
                                                        'insertparagraphbeforetable', //"表格前插入行"
                                                        //'insertcode', //代码语言
                                                    ],[
                                                        'emotion', //表情
                                                        'simpleupload', //单图上传
                                                        //'insertimage', //多图上传
                                                        'map', //Baidu地图
                                                        //'gmap', //Google地图
                                                        'link', //超链接
                                                        'unlink', //取消链接
                                                        'blockquote', //引用
                                                        'pasteplain', //纯文本粘贴模式
                                                        'selectall', //全选
                                                        'anchor', //锚点
                                                        'searchreplace', //查询替换
                                                        'time', //时间
                                                        'date', //日期

                                                    ],
                                                    //[
                                                        //'insertvideo', //视频
                                                        //'webapp', //百度应用
                                                        //'scrawl', //涂鸦
                                                        //'music', //音乐
                                                        //'charts', // 图表

                                                    //]
                                                ]
                                            });
                                        </script>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-10">
                                        <input type="hidden" id="qs" name="qs" value="" />
                                        <button type="button" onclick="checkForm();" class="btn btn-primary">保存修改</button>
                                    </div>
                                </div>


                            </form>
                            <!-- params end -->
                        </div>

                        <!-- 商品搜索 BEGIN --->
                        <?php if ($good['isbind']){ ?>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">

                                        <input type="text" class="form-control" id="gkey" placeholder="请搜索想要的商品">
                                        <div class="input-group-addon" id="gsearch"><i class="fa fa-search"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id='gcontent' class="col-xs-12">
                                </div>
                            </div>

                        </div>
                        <?php } ?>
                        <!-- 商品搜索 END --->

                        <!-- 策略搜索 BEGIN --->
                        <?php if ($good['pkgsize'] || $good['isbind']){ ?>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="skey" placeholder="请输入赠品栏说明">
                                        <div class="input-group-addon" id="ssearch"><i class="fa fa-search"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div id='scontent' class="col-xs-12">
                                </div>
                            </div>

                        </div>

                        <?php } ?>

                        <!-- 策略搜索 END --->

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