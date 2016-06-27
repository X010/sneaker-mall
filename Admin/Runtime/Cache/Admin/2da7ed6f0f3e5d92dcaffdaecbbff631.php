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
                            location.replace(location.href);
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
                            location.replace(location.href);
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
                            parent.layer.msg(data.msg, {icon: 1, time: 1000});
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
            var id = $(this).attr('data-id');
            var url = "<?php echo U('goods/gone');?>&id="+id;
            location.href = url;
        });

        //勾选栏目
        $('.huodong input').each(function(){
            $(this).on('click',function(){
                var cinput = $(this);
                var dis = cinput.prop('checked') ? 0 : 1;
                $.post(
                        "<?php echo U('goods/ucate');?>",
                        {   id:$(this).attr('data-id'),
                            cid:$(this).attr('data-cate'),
                            dis:dis
                        },
                        function(data){
                            if(data.error == 0){
                                parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                            }else{
                                parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                            }
                        }
                )
            })
        })
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
                售卖定价
                <small>设置商城价格后，未设置价格的类型客户将看不到该商品!</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <script>
                $().ready(function(){
                    var tabsli = $('#tabs li');
                    var tabat = ('<?php echo ($gtyp); ?>'  == '') ? '1' : '<?php echo ($gtyp); ?>' ;
                    tabsli.eq(tabat - 1).addClass('active');
                })
            </script>
            <!-- Default box -->
            <div class="box box-primary">
                <div class="box-header">
                    <ul class="nav nav-tabs" id="tabs">
                        <!--<li><a href="<?php echo U('sale/price_list',array('key'=>$search_key));?>">全部商品 <span class="badge" id="ltotal"><?php echo ($total0); ?></span></a></li>-->
                        <li><a href="<?php echo U('sale/price_list',array('gtyp'=>1,'key'=>$search_key,'publish'=>$publish));?>">基础商品 <span class="badge"><?php echo ($total1); ?></span></a></li>
                        <li><a href="<?php echo U('sale/price_list',array('gtyp'=>2,'key'=>$search_key,'publish'=>$publish));?>">捆绑商品 <span class="badge"><?php echo ($total2); ?></span></a></li>
                        <li><a href="<?php echo U('sale/price_list',array('gtyp'=>3,'key'=>$search_key,'publish'=>$publish));?>">大包装商品 <span class="badge"><?php echo ($total3); ?></span></a></li>
                    </ul>
                </div>

                <div class="box-body table-responsive" style="padding-top: 0;">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pull-left" style="padding: 0 0 10px 0;">
                                <a class="btn btn-default btn-sm" onclick="location.reload();">刷新</a>
                                <!--<a class="btn btn-default btn-sm" id="checkall">全选/取消</a>
                                <a class="btn btn-default btn-sm" id="pub_all">批量上架</a>
                                <a class="btn btn-default btn-sm" id="unpub_all">批量下架</a>
                                <a class="btn btn-default btn-sm" id="del_all">不再展示</a>-->
                            </div>
                            <script>
                                $().ready(function(){
                                    if (<?php echo ($_SESSION["business"] == "B2C" ? 1 : 0); ?>){
                                        $('div[name="div-cctype"]').each(function(i){
                                            $(this).html(VIP_TYPES[i]);
                                        });
                                    }

                                    changeStore();

                                    $('#ck').change(function(){
                                        changeStore();
                                    });


                                    $('#publish').change(function(){
                                        changePublishStatus();
                                    });

                                    $('input[name="btn-detail"]').on('click', function(){
                                        var id=$(this).attr('data-id');
                                        location.href="<?php echo U('sale/show_goods_price');?>&mgid="+id;
                                    });

                                    $('input[name="btn-update"]').on('click',function(){
                                        var tr = $(this).parent().parent().parent();
                                        var gid = tr.attr('data-gid');
                                        var mgid = tr.attr('data-id');
                                        //var sid = <?php echo ($cck); ?>;
                                        var sid = $('#ck').val();
                                        var price = {"data":[]};
                                        tr.find('td[name="td-price"] input').each(function(){
                                            var kv = $(this).val();
                                            var kk = $(this).attr('data-customer');
                                            //console.log(kk,kv);
                                            price.data.push(jQuery.parseJSON('{"ck":"'+kk+'","price":"'+kv+'"}'));
                                        });
                                        parent.layer.confirm('确定要更改商品价格？', {
                                            btn: ['确定','取消'], //按钮
                                            shade: 0.5
                                        }, function() {
                                            $.post(
                                                    "<?php echo U('sale/fix_price_byck');?>",
                                                    {gid:gid,mgid:mgid,sid:sid,price:JSON.stringify(price)},
                                                    function(data){
                                                        if(data.error == 0){
                                                            parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                                                            //location.reload();
                                                        }else{
                                                            parent.layer.msg(data.msg, {icon: 2, time: 2000 });
                                                        }
                                                    }
                                            )
                                        });

                                    })



                                }); //JQ END



                                /**
                                 * 切换仓库,重新取价格
                                 */
                                function changeStore(){
                                    var ids = '';
                                    $('.price_list').each(function(){
                                        ids += $(this).attr('data-id') + ',';
                                    });

                                    var sid = $('#ck').val();
                                    $.post(
                                            "<?php echo U('sale/shop_price');?>",
                                            {id:ids, list:1, sid:sid, gtyp:'<?php echo ($gtyp); ?>'},
                                            function(data){
                                                if(data.error == 0) {
                                                    var rs = data.data;
                                                    if (rs.length == 0){
                                                        $('.price_list').each(function(){
                                                            var tr = $(this);
                                                            tr.find('input[name="price1"]').val('0.00');
                                                            tr.find('input[name="price2"]').val('0.00');
                                                            tr.find('input[name="price3"]').val('0.00');
                                                            tr.find('input[name="price4"]').val('0.00');
                                                        });
                                                    } else {
                                                        $('.price_list').each(function(){
                                                            var tr = $(this);
                                                            var k = tr.attr('data-id');
                                                            tr.find('input[name="price1"]').val(rs[k].price1);
                                                            tr.find('input[name="price2"]').val(rs[k].price2);
                                                            tr.find('input[name="price3"]').val(rs[k].price3);
                                                            tr.find('input[name="price4"]').val(rs[k].price4);
                                                        });
                                                    }
                                                } else{
                                                    parent.layer.msg("获取价格出错", {icon: 2, time: 2000 });
                                                }
                                            }
                                    );
                                }



                                /**
                                 * 切换上架状态,刷新页面
                                 */
                                function changePublishStatus(){
                                    var params = {
                                        'sid': $('#ck').val(),
                                        'key': '<?php echo ($search_key); ?>',
                                        'gtyp': '<?php echo ($gtyp); ?>',
                                        'publish': $('#publish').val(),
                                    };
                                    var qs = '';
                                    $.each(params, function(k, v){
                                        qs += k + '=' + v + '&';
                                    });
                                    var url = "<?php echo U('sale/price_list');?>&" + qs;
                                    location.href = url;
                                }

                            </script>

                            <div class="pull-left input-group input-group-sm">
                                <select name="publish" id="publish" class="form-control">
                                    <option value="1" <?php if($publish == 1): ?>selected="selected"<?php endif; ?> >已上架</option>
                                    <option value="0" <?php if($publish == 0): ?>selected="selected"<?php endif; ?> >未上架</option>
                                    <option value="" <?php if($publish == ''): ?>selected="selected"<?php endif; ?> >全部</option>
                                </select>
                            </div>


                            <div class="pull-right form-inline">

                                <div class="form-group input-group-sm">
                                    <label>仓库</label>
                                    <select name="ck" id="ck" class="form-control">
                                        <?php if(is_array($ck)): $i = 0; $__LIST__ = $ck;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo['id']); ?>" <?php if($cck == $vo['id']): ?>selected="selected"<?php endif; ?> ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>


                                <form id="eform" class="" method="post" action="<?php echo U('sale/price_list');?>" style="display:inline-block;">
                                    <div class="input-group input-group-sm" style="text-align: right;">
                                        <input type="text" name="key" class="form-control pull-right" style="width: 200px;" value="<?php echo ($search_key); ?>" placeholder="请输入商品名称">
                                        <input type="hidden" name="gtyp" value="<?php echo ($gtyp); ?>" />
                                        <input type="hidden" name="publish" value="<?php echo ($publish); ?>" />
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table id="table-price" class="table table-striped table-hover table-bordered" style="margin-bottom:0;">
                                <thead>
                                <tr >
                                    <th style="min-width:200px;">商品名称</th>
                                    <th>商品条码</th>
                                    <th><div align="center">单位</div></th>
                                    <!--<th><div align="center">商品类型</div> </th>-->
                                    <?php if(is_array($customer)): $i = 0; $__LIST__ = $customer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><th><div align="center" name="div-cctype"><?php echo ($co["name"]); ?></div> </th><?php endforeach; endif; else: echo "" ;endif; ?>
                                    <!--<?php if($gtyp!=1) { ?>
                                    <th><div align="center">变更</div> </th>
                                    <?php } ?>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($goods)): foreach($goods as $key=>$vo): ?><tr ondblclick="javscript:location.href='<?php echo U('sale/show_goods_price');?>&mgid=<?php echo ($vo['id']); ?>'" class="price_list" data-id="<?php echo ($vo["id"]); ?>" data-gid="<?php echo ($vo["gid"]); ?>" id="g_<?php echo ($vo["id"]); ?>">
                                        <!--<td><div align="center"><input name='opbox' class='opbox' type="checkbox" data-id="<?php echo ($vo["id"]); ?>"></div></td>-->
                                        <td><?php echo ($vo["gname"]); ?></td>
                                        <td><?php echo ($vo["barcode"]); ?></td>
                                        <td><div align="center"><?php echo ($vo["unit"]); ?></div> </td>
                                        <!--<td><div align="center">
                                            <?php if($vo["isbind"] == 1): ?>捆绑
                                                <?php elseif($vo["pkgsize"] == 1): ?>大包装
                                                <?php else: ?>基础商品<?php endif; ?>

                                        </div> </td>-->

                                        <?php if(is_array($customer)): $i = 0; $__LIST__ = $customer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$co): $mod = ($i % 2 );++$i;?><td name="td-price" class="price-list-td-disabled">
                                                <input type="text" disabled="disabled" class="price-list-input-disabled" value="" class="custumer_price" name="price<?php echo ($co["id"]); ?>" id="customer_<?php echo ($vo["id"]); ?>_<?php echo ($co["id"]); ?>_<?php echo ($cck); ?>" data-customer="<?php echo ($co["id"]); ?>">
                                            </td>

                                            <!--
                                            <?php if($gtyp==1) { ?>

                                            <td name="td-price" class="price-list-td-disabled">
                                                <input type="text" disabled="disabled" class="price-list-input-disabled" value="" class="custumer_price" name="price<?php echo ($co["id"]); ?>" id="customer_<?php echo ($vo["id"]); ?>_<?php echo ($co["id"]); ?>_<?php echo ($cck); ?>" data-customer="<?php echo ($co["id"]); ?>">
                                            </td>

                                            <?php } else { ?>

                                            <td name="td-price" class="price-list-td">
                                                <input type="text" class="price-list-input" value="" class="custumer_price" name="price<?php echo ($co["id"]); ?>" id="customer_<?php echo ($vo["id"]); ?>_<?php echo ($co["id"]); ?>_<?php echo ($cck); ?>" data-customer="<?php echo ($co["id"]); ?>">
                                            </td>

                                            <?php } ?>
                                            --><?php endforeach; endif; else: echo "" ;endif; ?>

                                        <!--
                                        <?php if($gtyp!=1) { ?>
                                        <td><div align="center">
                                            <input type="button" value="修改" name="btn-update" class="btn btn-default btn-sm">
                                            <input type="button" value="详情" name="btn-detail" class="btn btn-default btn-sm" data-id="<?php echo ($vo['id']); ?>">
                                        </div></td>
                                        <?php } ?>
                                        -->
                                    </tr><?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
                <div class="box-footer">
                    <?php echo ($page); ?>
                    <script>
                        function saveExcel(){
                            var params = {
                                'ck':$('#ck').val(),
                                'gtyp':'<?php echo ($gtyp); ?>',
                                'key':'<?php echo ($search_key); ?>',
                            };
                            jsPOST("<?php echo U('sale/price_list_excel');?>", params);
                        }

                    </script>
                    <a class="btn btn-default btn-sm btn-excel" onclick="saveExcel()">导出Excel</a>

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