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
                编辑商品价格详情
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
                            <form class="form-horizontal" id="eform" method="post" action="">

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label">商品名称</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" readonly id="gname" name='gname' value="<?php echo ($good["gname"]); ?>">
                                        <input type="hidden" name="id" value="<?php echo ($good["id"]); ?>">
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label">商品规格</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" readonly name='specz' value="<?php echo ($good["spec"]); ?>">
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label">商品单位</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" readonly name='unit' value="<?php echo ($good["unit"]); ?>">
                                    </div>

                                </div>

                                <div class="form-group ">
                                    <label class="col-sm-2 control-label">商品编码</label>
                                    <div class="col-xs-7">
                                        <input type="text" class="form-control" readonly name='gcode' value="<?php echo ($good["gcode"]); ?>" placeholder="如：81000001">
                                    </div>

                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">商品参数</label>
                                    <div class="col-xs-7">
                                        <table class="table table-striped table-hover table-bordered">
                                            <thead>
                                            <th>规格</th>
                                            <th>包装规格</th>
                                            <th>单位</th>
                                            <th>产地</th>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td><?php echo ($good["spec"]); ?></td>
                                                <td><?php echo ($good["pkgspec"]); ?></td>
                                                <td><?php echo ($good["unit"]); ?></td>
                                                <td><?php echo ($good["place"]); ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>


                                <?php $cids = explode(',',$good['cctype']); ?>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label" id="cctypename">客户类型</label>
                                    <div class="col-xs-8">
                                        <?php if(is_array($sp)): foreach($sp as $key=>$vo): ?><label class="checkbox-inline">
                                                <input type="checkbox" name="supplier[]" class="sp" <?php if(in_array($vo['id'],$cids)) echo "checked";?>  value="<?php echo ($vo["id"]); ?>" data-name="<?php echo ($vo["name"]); ?>"><span><?php echo ($vo["name"]); ?></span>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>

                                <?php $cids = explode(',',$good['sids']); ?>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">出货仓库</label>
                                    <div class="col-xs-7">
                                        <?php if(is_array($ck)): foreach($ck as $key=>$vo): ?><label class="checkbox-inline">
                                                <input type="checkbox" name="ck[]" class="ck"  value="<?php echo ($vo["id"]); ?>" <?php if(in_array($vo['id'],$cids)) echo "checked";?> data-name="<?php echo ($vo["name"]); ?>"> <?php echo ($vo["name"]); ?>
                                            </label><?php endforeach; endif; ?>
                                    </div>
                                </div>
                                <script>
                                    $().ready(function(){
                                        if (<?php echo ($_SESSION["business"] == "B2C" ? 1 : 0); ?>){
                                            $('#cctypename').html('会员类型');
                                            $('input[name="supplier[]"]').each(function(i){
                                                $(this).attr('data-name', VIP_TYPES[i]).next('span').text(VIP_TYPES[i]);
                                            });

                                            $('#th-cctype').html('会员类型');
                                        }

                                        // 初始化价格表
                                        <?php echo ($json_ck); ?>

                                        <?php echo ($json_sp); ?>

                                        ck.forEach(function(e){
                                            var _ck = e;
                                            $('input[name="ck[]"]').each(function(){
                                                if ($(this).val() == _ck.id) {
                                                    $(this).attr('checked', 'checked');
                                                }
                                            });
                                            sp.forEach(function(v){
                                                buildtb(_ck.id, _ck.name, v.id, v.name);
                                            })
                                        });

                                        //勾选客户类型
                                        $('.sp').on('click',function(){
                                            var _this = $(this);
                                            if($(this).is(':checked')) {
                                                //被选中客户类型
                                                if($.inArray($(this).val(), sp) == -1){
                                                    sp.push($.parseJSON('{"id":"'+_this.val()+'","name":"'+_this.attr('data-name')+'"}'));
                                                }

                                                ck.forEach(function(v){
                                                    buildtb( v.id, v.name, _this.val(),_this.attr('data-name'));
                                                })
                                            }else{
                                                // 清除表格信息
                                                var _sp = [];
                                                var _input = $(this).val();
                                                sp.forEach(function(v){
                                                    if(v.id !== _input){
                                                        _sp.push($.parseJSON('{"id":"'+ v.id+'","name":"'+ v.name +'"}'));
                                                    }
                                                });
                                                sp = _sp;
                                                removetb(_input,'sp');
                                            }
                                            //console.log(sp);
                                        });

                                        // 勾选仓库
                                        $('.ck').on('click',function(){
                                            var _this = $(this);
                                            if($(this).is(':checked')) {
                                                // 被选中仓库
                                                // 存储仓库信息
                                                if($.inArray($(this).val(),ck) == -1){
                                                    ck.push($.parseJSON('{"id":"'+_this.val()+'","name":"'+_this.attr('data-name')+'"}'));
                                                }

                                                $('input[name="supplier[]"]').each(function(){
                                                    if ($(this).prop('checked')){
                                                        buildtb(_this.val(), _this.attr('data-name'), $(this).val(), $(this).attr('data-name'));
                                                    }
                                                });
                                            }else{
                                                // 清除表格信息
                                                var _ck = [];
                                                var _input = _this.val();
                                                ck.forEach(function(v){
                                                    if(v.id !== _input){
                                                        _ck.push($.parseJSON('{"id":"'+ v.id+'","name":"'+ v.name +'"}'));
                                                    }
                                                });
                                                ck = _ck;
                                                removetb(_input,'ck');
                                            }
                                            //console.log(ck);
                                        });

                                        /**
                                         * 添加一行价格
                                         * @param ckid
                                         * @param ckname
                                         * @param spid
                                         * @param spname
                                         */
                                        function buildtb(ckid,ckname,spid,spname){
                                            //console.log(ckid,ckname,spid,spname);
                                            if($('#maingoodsid').val() === "0"){
                                                parent.layer.msg('请选择活动商品', { icon: 2, time: 3000 });
                                                return ;
                                            }

                                            var tr = $('<tr id="'+ckid+'_'+spid+'" data-ckid="'+ckid+'" data-spid="'+spid+'">');
                                            var _ck = $('<td style="min-width:100px;padding-top:12px;">'+ckname+'</td>');
                                            var _sp = $('<td style="padding-top:12px;">'+spname+'</td>');
                                            //var _npr = $('<td class="tbyellow"><input <?php if ($good["isbind"]==0 && $good["pkgsize"]==0) echo "disabled=\"disabled\""; ?> size="5" name="gprice" data-ckid="'+ckid+'" data-spid="'+spid+'" data-value="" class="form-control input-sm" type="text" value="加载中..."></td>');
                                            var _npr = $('<td class="tbyellow"><input size="5" name="gprice" data-ckid="'+ckid+'" data-spid="'+spid+'" data-value="" class="form-control input-sm" type="text" value="加载中..."></td>');
                                            getprice(<?php echo ($good["id"]); ?>,ckid,spid,_npr.find('input'));

                                            tr.append(_ck);
                                            tr.append(_sp);

                                            tr.append(_npr);

                                            var empy = true;
                                            $('#ttbody tr').each(function(){
                                                if($(this).attr('data-ckid') === tr.attr('data-ckid')){
                                                    $(this).before(tr);
                                                    empy = false;
                                                }
                                            });
                                            if(empy) {
                                                tr.appendTo($('#ttbody'));
                                            }
                                        }

                                        /**
                                         * 移除一行价格
                                         * @param id
                                         * @param tp
                                         */
                                        function removetb(id,tp){
                                            $('#ttbody tr').each(function(){
                                                if(tp == 'ck'){
                                                    if($(this).attr('data-ckid')===id){
                                                        $(this).remove();
                                                    }
                                                }else if(tp === 'sp'){
                                                    if($(this).attr('data-spid') === id){
                                                        $(this).remove();
                                                    }
                                                }
                                            })
                                        }

                                        /**
                                         * 获取一个价格
                                         * @param id
                                         * @param ckid
                                         * @param spid
                                         * @param obj
                                         */
                                        function getprice(id,ckid,spid,obj){
                                            var params = {id:id,ckid:ckid,spid:spid,list:0};
                                            <?php
 if ($good["isbind"]==0 && $good["pkgsize"]==0){ echo "params['gtyp'] = 1;"; } ?>
                                            $.post(
                                                    "<?php echo U('sale/shop_price');?>",
                                                    {id:id,ckid:ckid,spid:spid,list:0,gtyp:1},
                                                    function(data){
                                                        if(data.error == 0) {
                                                            obj.val(data.data.price).attr('data-value', data.data.price);
                                                        }
                                                    }
                                            )
                                        }

                                        //修改价格时,顺便修改属性价格
                                        $('input[name="gprice"]').change(function(){
                                            $(this).attr('data-value', $(this).val());
                                        });

                                        // 更改商品价格
                                        $('#submit_btn').on('click',function(){
                                            var sp = '';
                                            $('input[name="supplier[]"]:checked').each(function(v){
                                                sp+=$(this).val()+',';
                                            });
                                            var ck = '';
                                            $("input[name='ck[]']:checked").each(function(v){
                                                ck+=$(this).val()+',';
                                            });
                                            var price_style = $('#price_style').prop('checked') ? 1 : 0;
                                            var price = '';
                                            $("input[name='gprice']").each(function(){
                                                var _c = $(this).attr('data-ckid');
                                                var _s = $(this).attr('data-spid');
                                                var _p = $(this).val();
                                                price += _c+'-'+_s+'-'+_p+',';
                                            });

                                            var params = {
                                                ck:ck,
                                                sp:sp,
                                                price_style:price_style,
                                                pricelist:price,
                                                id:<?php echo ($good["id"]); ?>,
                                            };
                                            $.post(
                                                    "<?php echo U('sale/fix_price');?>",
                                                    params,
                                                    function(data){
                                                        if (data.error){
                                                            parent.layer.msg(data.msg, { icon: 2, time: 2000 });
                                                        } else {
                                                            //parent.layer.msg(data.msg, { icon: 1, time: 3000 });
                                                            history.go(-1);
                                                        }
                                                    }
                                            )
                                        });


                                    });

                                    //填充价格
                                    function full(){
                                        //var price = $.trim($('#price_list tbody tr').eq(0).find('td:last input').attr('data-value'));
                                        var price = $.trim($('#price_list tbody tr').eq(0).find('td:last input').val());
                                        price = price ? parseFloat(price).toFixed(2) : '0.00';
                                        $('#price_list tbody tr').each(function(){
                                            $(this).find('td:last input').val(price).attr('data-value', price)
                                        });
                                    }


                                </script>
                                <?php if ($good["pkgsize"]==1){ ?>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">价格显示</label>
                                    <div class="col-xs-7">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="price_style" id="price_style" value="1" <?php if($good['price_style']==1) echo "checked";?>> 折算单价显示
                                        </label>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">售价详情</label>
                                    <div class="col-xs-7">
                                        <table id="price_list" class="table table-striped table-hover table-bordered">
                                            <thead>
                                            <th>仓库</th>
                                            <th id="th-cctype">客户类型</th>
                                            <th>价格<a href="javascript:full()"> [填充]</a></th>
                                            </thead>
                                            <tbody id="ttbody" >

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group"><label  class="col-sm-2 control-label"></label><div class="col-xs-7"></div></div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button" id="submit_btn" class="btn btn-primary" onclick="disableBtn(this)">确定保存</button>
                                    </div>
                                </div>



                            </form>
                            <!-- params end -->
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