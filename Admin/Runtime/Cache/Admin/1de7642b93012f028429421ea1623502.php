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
    var checkall = true;
    $().ready(function(){
        if (<?php echo ($_SESSION["business"] == "B2C" ? 1 : 0); ?>){
            $('#cctypename').html('会员类型');
            $('#div-cctype').html('可见会员');

            $('#cctype').html('<option value="">- 全部 -</option>');
            for (var i=0; i<VIP_TYPES.length; i++){
                var n = i + 1;
                $('#cctype').append('<option value="'+ n +'">'+VIP_TYPES[i]+'</option>');
            }

            $('.label-bg-1').html('非');
            $('.label-bg-2').html('计');
            $('.label-bg-3').html('年');
            $('.label-bg-4').html('伙');
        } else {
            $('th[name="reserve"]').remove();
            $('td[name="reserve"]').remove();
        }

        //全选/取消
        $('#checkall').on('click',function(){
            //console.log(checkall);
            if(checkall){
                $("[name='opbox']").prop("checked",'true');//全选
                $(this).removeClass('btn-default');
                $(this).addClass('btn-danger');
                checkall = false;
            }else{
                $("[name='opbox']").removeAttr("checked");
                $(this).removeClass('btn-danger');
                $(this).addClass('btn-default');
                checkall = true;
            }
        });

        //批量上架
        $("#pub_all").on('click',function(){
            var ids = [];
            $(".opbox").each(function(){
                if($(this).is(':checked')){
                    ids.push($(this).attr('data-id'));
                }
            });
            if (!ids.length){
                parent.layer.msg('请先勾选商品', {icon: 2, time: 2000 });

            } else {
                parent.layer.confirm('确定批量上架？', {
                    btn: ['确定', '取消'], //按钮
                    shade: 0.5
                }, function () {
                    $.post("<?php echo U('goods/pub_all');?>", {"id": ids}, function (data) {

                        if (data.error == 0) {
                            parent.layer.msg(data.msg, {icon: 1, time: 1000});
                            //location.replace(location.href);
                            location.reload();
                        } else {
                            parent.layer.msg('上架失败', {icon: 2, time: 2000});
                        }
                    });
                });
            }
        });

        //批量下架
        $("#unpub_all").on('click',function(){
            var ids = [];
            $(".opbox").each(function(){
                if($(this).is(':checked')){
                    ids.push($(this).attr('data-id'));
                }
            });
            if (!ids.length){
                parent.layer.msg('请先勾选商品', {icon: 2, time: 2000 });

            } else {
                parent.layer.confirm('确定批量下架？', {
                    btn: ['确定','取消'], //按钮
                    shade: 0.5
                }, function(){
                    $.post("<?php echo U('goods/unpub_all');?>", { "id": ids },function(data){

                        if(data.error == 0){
                            parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                            //location.replace(location.href);
                            location.reload();
                        }else{
                            parent.layer.msg('下架失败', {icon: 2, time: 2000 });
                        }
                    });
                });
            }

        });

        //不再展示
        $("#del_all").on('click',function(){
            var ids = [];
            $(".opbox").each(function(){
                if($(this).is(':checked')){
                    ids.push($(this).attr('data-id'));
                }
            });
            if (!ids.length){
                parent.layer.msg('请先勾选商品', {icon: 2, time: 2000 });

            } else {
                parent.layer.confirm('确定批量删除商品？', {
                    btn: ['确定', '取消'], //按钮
                    shade: 0.5
                }, function () {
                    $.post("<?php echo U('goods/del_all');?>", {"id": ids}, function (data) {
                        if (data.error == 0) {
                            //parent.layer.msg(data.msg, {icon: 1, time: 1000});
                            location.replace(location.href);
                        } else {
                            parent.layer.msg('删除失败', {icon: 2, time: 2000});
                        }
                    });
                });
            }
        });

        //双击打开
        $('.editgoods').on('dblclick',function(){
            //参数用于回到列表页时的状态保存
            var qs = getQueryString();
            var id = $(this).attr('data-id');
            var url = "<?php echo U('goods/gone');?>&id="+id+'&'+qs;
            location.href = url;
        });



        //勾选栏目
        $('.huodong input').each(function(){
            $(this).on('click',function(){
                var cinput = $(this);
                var dis = cinput.prop('checked') ? 0 : 1;
                cinput.parent().parent().parent().find('input[type="checkbox"]').removeAttr('checked');
                $.post(
                        "<?php echo U('goods/ucate');?>",
                        {   id:$(this).attr('data-id'),
                            cid:$(this).attr('data-cate'),
                            dis:dis
                        },
                        function(data){
                            if(data.error == 0){
                                var msg = '取消栏目成功';
                                if (!dis){
                                    cinput.prop('checked', true);
                                    msg = data.msg;
                                }
                                parent.layer.msg(msg, { icon: 1, time: 1000 });
                            }else{
                                parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                            }
                        }
                )
            })
        });

        var args = GetRequest();
        if (typeof(args.cctype) == 'undefined') args['cctype'] = '';
        $('#cctype').val(args.cctype).change(function(){
            location.replace("<?php echo U('goods/glist');?>&cctype="+$(this).val());
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
            <h1>
                商品列表
                <small>管理商城商品展示的资料和进行上下架等管理操作</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <script>
                $().ready(function(){
                    var tabsli = $('#tabs li');
                    var tabat = ('<?php echo ($_GET['gtyp']); ?>'  == '') ? '0' : '<?php echo ($_GET['gtyp']); ?>' ;
                    tabsli.eq(tabat).addClass('active');
                })
            </script>
            <!-- Default box -->
            <div class="box box-primary">
                <div class="box-header">
                    <ul class="nav nav-tabs" id="tabs">
                        <li><a href="<?php echo U('goods/glist',array('key'=>$key));?>">全部商品 <span class="badge" id="ltotal"><?php echo ($total0); ?></span></a></li>
                        <li><a href="<?php echo U('goods/glist',array('gtyp'=>1,'key'=>$key));?>">基础商品 <span class="badge"><?php echo ($total1); ?></span></a></li>
                        <li><a href="<?php echo U('goods/glist',array('gtyp'=>2,'key'=>$key));?>">捆绑商品 <span class="badge"><?php echo ($total2); ?></span></a></li>
                        <li><a href="<?php echo U('goods/glist',array('gtyp'=>3,'key'=>$key));?>">大包装商品 <span class="badge"><?php echo ($total3); ?></span></a></li>
                        <li><a href="<?php echo U('goods/glist',array('gtyp'=>4,'key'=>$key));?>" style="color:#A8A8A8">不再展示 <span class="badge"><?php echo ($total4); ?></span></a></li>
                    </ul>
                </div>

                <div class="box-body table-responsive" style="padding-top: 0;">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left" style="padding: 0 0 10px 0;">
                                <a class="btn btn-default btn-sm" onclick="location.reload();">刷新</a>
                                <a class="btn btn-default btn-sm" id="checkall">全选/取消</a>
                                <a class="btn btn-default btn-sm" id="pub_all">批量上架</a>
                                <a class="btn btn-default btn-sm" id="unpub_all">批量下架</a>
                                <a class="btn btn-default btn-sm" id="del_all">不再展示</a>
                            </div>
                            <div class="pull-right form-inline">
                                <div class="form-group input-group-sm">
                                    <label id="cctypename">客户类型</label>
                                    <select name="cctype" id="cctype" class="form-control">
                                        <option value="">- 全部 -</option>
                                        <option value="1">经销商</option>
                                        <option value="2">酒店饭店</option>
                                        <option value="3">商场超市</option>
                                        <option value="4">便利店</option>
                                    </select>
                                </div>

                                <form class="" method="post" action="<?php echo U('goods/glist');?>" style="display:inline-block;">
                                    <div class="input-group input-group-sm" style="width: 250px;">
                                        <input type="text" name="key" class="form-control pull-right" value="<?php echo ($key); ?>" placeholder="请输入名称或条码">
                                        <input type="hidden" name="pub" id="spub" value="0">
                                        <input type="hidden" name="bind" id="sbind" value="0">
                                        <input type="hidden" name="del" id="sdel" value="0">
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-striped table-hover table-bordered" style="margin-bottom:0;">
                                <thead>
                                <tr >
                                    <th></th>
                                    <!--<th>NO</th>-->
                                    <th>排序</th>
                                    <th>上架ID</th>
                                    <th style="min-width:200px;">商品名称</th>
                                    <th>商品条码</th>
                                    <!--<th><div align="center">规格</div></th>
                                    <th><div align="center">包装规格</div></th>-->
                                    <th><div align="center">单位</div></th>
                                    <!--<th><div align="center">产地</div></th>-->
                                    <th><div align="center">捆绑</div></th>
                                    <th><div align="center">大包装</div></th>
                                    <th><div align="center">是否买赠</div></th>
                                    <th><div align="center" id="div-cctype">可见客户</div></th>
                                    <?php if(is_array($coll)): foreach($coll as $key=>$cl): ?><th><div align="center"><u><?php echo ($cl["name"]); ?></u></div></th><?php endforeach; endif; ?>
                                    <th>置顶</th>
                                    <th name="reserve">库存</th>
                                    <th>发布</th>

                                </tr>
                                </thead>
                                <tbody>
                                <script>
                                    $().ready(function(){
                                        var s_tr = null;
                                        $('.trmove').on('mousedown',function(){
                                            s_tr = $(this).parent();
                                        });
                                        $('.trmove').on('mouseup',function(){
                                            var e_tr = $(this).parent();

                                            // 更改排序数据
                                        });
                                        // 手工排序
                                        $('.order').on('keydown',function(e){
                                            if (e.keyCode == 13) {
                                                var order = {"data":[]};
                                                $('.order').each(function(){
                                                    var kk = $(this).attr('data-id');
                                                    var kv = $(this).val();
                                                    order.data.push(jQuery.parseJSON('{"'+kk+'":"'+kv+'"}'));
                                                });
                                                $.post(
                                                        "<?php echo U('goods/order');?>",
                                                        {order:JSON.stringify(order)},
                                                        function(data){
                                                            if(data.error == 0){
                                                                //parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                                                                location.reload(); //某些浏览器下有bug
                                                                //location.replace(location.href);//这个也有bug好吗!
                                                            }else{
                                                                parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                                                            }
                                                        }
                                                )
                                            }
                                        }).dblclick(function(){return false;});

                                        $('input[name="reserve"]').dblclick(function(){return false;});

                                        $('.btn-info').click(function(){
                                            goodsPutOn($(this));
                                        });
                                        $('.btn-danger').click(function(){
                                            goodsPutOff($(this));
                                        });
                                    });


                                    function getPostParams(url){
                                        var params = url.substr(url.indexOf('?')+1).split('&');
                                        var ret = {};
                                        for (var i=0; i<params.length; i++){
                                            var item = params[i].split('=');
                                            ret[item[0]] = item[1];
                                        }
                                        return ret;
                                    }
                                    /**
                                     * 商品上架
                                     * @param a
                                     */
                                    function goodsPutOn(a){
                                        var reserve = $.trim(a.parent().parent().parent().find('input[name="reserve"]').val());
                                        var url = a.attr('data-url');
                                        url += '&reserve=' + (reserve ? reserve : '-1');
                                        //location.href = url;
                                        $.post(
                                                url,
                                                getPostParams(url),
                                                function(data){
                                                    if(data.error == 0){
                                                        var new_url = url.replace('=pub', '=unpub').replace('reserve','x');
                                                        //console.log(new_url);
                                                        a.removeClass('btn-info').addClass('btn-danger').attr('data-url', new_url).html('下架');
                                                        $('.btn-danger').unbind('click').click(function(){
                                                            goodsPutOff($(this));
                                                        });
                                                        a.parent().parent().parent().find('td[name="reserve"]').html(reserve);
                                                    }else{
                                                        parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                                                    }
                                                }
                                        );

                                    }


                                    /**
                                     * 商品下架
                                     * @param a
                                     */
                                    function goodsPutOff(a){
                                        var url = a.attr('data-url');
                                        //location.href = url;
                                        $.post(
                                                url,
                                                getPostParams(url),
                                                function(data){
                                                    if(data.error == 0){
                                                        var new_url = url.replace('=unpub', '=pub');
                                                        //console.log(new_url);
                                                        a.removeClass('btn-danger').addClass('btn-info').attr('data-url', new_url).html('上架');
                                                        $('.btn-info').unbind('click').click(function(){
                                                            goodsPutOn($(this));
                                                        });
                                                        var reserve = a.parent().parent().parent().find('td[name="reserve"]');
                                                        var input = '<input name="reserve" value="'+reserve.text()+'" style="width:50px; border: 0px; background-color:#A5E9F9;">';
                                                        reserve.html(input);
                                                    }else{
                                                        parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                                                    }
                                                }
                                        );

                                    }
                                </script>
                                <?php $i=1;?>
                                <?php if(is_array($goods)): foreach($goods as $key=>$vo): ?><tr class="editgoods" data-id="<?php echo ($vo["id"]); ?>" id="tr_<?php echo ($i); ?>">
                                        <td><div align="center"><input name='opbox' class='opbox' type="checkbox" data-id="<?php echo ($vo["id"]); ?>"></div></td>
                                        <!--<td class="trmove"><?php echo ($i); ?></td>-->
                                        <td><input name="order[]" data-id="<?php echo ($vo["id"]); ?>" class="order" value="<?php echo ($vo["order"]); ?>" style="width:30px; border: 0px; background-color:#FFEBC0;"></td>
                                        <td><?php echo ($vo["id"]); ?></td>
                                        <td><?php echo ($vo["gname"]); ?></td>
                                        <td><?php echo ($vo["barcode"]); ?></td>
                                        <!--<td><div align="center"><?php echo ($vo["spec"]); ?></div> </td>
                                        <td><div align="center"><?php echo ($vo["pkgspec"]); ?></div> </td>-->
                                        <td><div align="center"><?php echo ($vo["unit"]); ?></div> </td>
                                        <!--<td><div align="center"><?php echo ($vo["place"]); ?></div> </td>-->

                                        <td><div align="center"><?php if($vo["isbind"] == 1): ?><i class="fa fa-check"></i><?php endif; ?></div> </td>
                                        <td><div align="center"><?php if($vo["pkgsize"] == 1): ?><i class="fa fa-check"></i><?php endif; ?></div> </td>
                                        <td><div align="center"><?php if($vo['marketid']) echo '<i class="fa fa-check"></i>';?></div> </td>
                                        <td>
                                            <?php
 if($vo['cctype'] !== null){ $cctyp = explode(',',$vo['cctype']); foreach($cctyp as $v){ foreach(C('CUSTOMER_TYPE') as $val){ if($v == $val['id']) echo "<span class=\"label label-bg-".$val['id']."\">".$val['sname']."</span>"; } } } ?>
                                        </td>
                                        <?php if(is_array($coll)): foreach($coll as $key=>$cl): ?><td class="huodong">
                                                <div align="center">
                                                    <!--<input type="radio" name="<?php echo ($vo["id"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-cate="<?php echo ($cl["id"]); ?>" <?php if($cl['id'] == $vo['cateid']): ?>checked="checked"<?php endif; ?> -->
                                                    <input type="checkbox" name="<?php echo ($vo["id"]); ?>" data-id="<?php echo ($vo["id"]); ?>" data-cate="<?php echo ($cl["id"]); ?>" <?php if($cl['id'] == $vo['cateid']): ?>checked="checked"<?php endif; ?> >
                                                </div>
                                            </td><?php endforeach; endif; ?>
                                        <td>
                                            <!-- Split button -->
                                            <div align="center">
                                                <?php if($vo["top"] != 1): ?><a class="btn btn-xs btn-info" href="<?php echo U('goods/top',array('id'=>$vo['id']));?>">置顶</a><?php endif; ?>
                                                <?php if($vo["top"] == 1): ?><a class="btn btn-xs label-danger" href="<?php echo U('goods/untop',array('id'=>$vo['id']));?>">取消置顶</a><?php endif; ?>
                                            </div>
                                        </td>
                                        <?php if($vo["publish"] == 0): ?><td name="reserve"><input name="reserve" value="<?php echo ($vo["reserve"]); ?>" style="width:50px; border: 0px; background-color:#A5E9F9;"></td>
                                            <?php else: ?>
                                            <td name="reserve"><?php echo ($vo["reserve"]); ?></td><?php endif; ?>
                                        <td>
                                            <!-- Split button -->
                                            <div align="center">
                                                <?php if($vo["flagdel"] == 1): ?><a class="btn btn-xs btn-info" href="<?php echo U('goods/redel',array('id'=>$vo['id']));?>">展示</a>
                                                    <?php else: ?>
                                                    <?php if($vo["publish"] == 0): ?><a class="btn btn-xs btn-info" href="#" data-url="<?php echo U('goods/pub',array('id'=>$vo['id']));?>">上架</a><?php endif; ?>
                                                    <?php if($vo["publish"] == 1): ?><a class="btn btn-xs btn-danger" href="#" data-url="<?php echo U('goods/unpub',array('id'=>$vo['id']));?>">下架</a><?php endif; endif; ?>
                                            </div>
                                        </td>

                                    </tr>
                                    <?php $i++; endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <?php echo ($page); ?>
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