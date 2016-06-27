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
        if($('#ctype').val()==2){
            $('#tonglan').css('display','block');
            $('#wx_home').hide();
            $('#wx_home_goods').hide();
        }
        /**$('#ctype option').each(function(){
        $(this).on('click',function(){alert(1);
        $('#tonglan').css('display','none');
        if($(this).val()=='2')
          $('#tonglan').css('display','block');
            })
        })**/
            //切换栏目类型
        $('#ctype').change(function(){
            //if( $(this).children('option:selected').val() == '2' ){
            if ( $(this).val() == '2'){
                $('#tonglan').show();
                $('#wx_home').hide();
                $('#wx_home_goods').hide();
            } else {
                $('#tonglan').hide();
                $('#wx_home').show();
                $('#wx_home_goods').show();
            }
        });

        $('#btn-save').click(function(){
            if ($.trim($('#name').val()) == ''){
                parent.layer.msg('请填写栏目名称', { icon: 2, time: 3000 });
                return false;
            } else {
                $('#templatemo-preferences-form').submit();
            }
        });
    });

    function readFile(obj,id,img,inpt) {
        var file = obj.files[0];
        //判断类型是不是图片
        if (!/image\/\w+/.test(file.type)) {
            parent.layer.msg('请确保文件为图像类型', { icon: 2, time: 3000 });
            return false;
        }
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function (e) {
            var timestamp3 = new Date().getTime();
            $.ajax({
                type: 'POST',
                url: '<?php echo U("column/picupload");?>',
                data: {src: this.result},
                success: function (data) {
                    if(data.error == 0) {
                        //$li = $('<img src="' + data.data + '">');
                        img.attr('src',data.data + "");
                        inpt.val(data.data);
                    }else{
                        parent.layer.msg(data.msg, { icon: 2, time: 3000 });
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    var err_msg = 'status:' + XMLHttpRequest.status + '\nreadyState:' + XMLHttpRequest.readyState + '\ntextStatus:' + textStatus;
                    parent.layer.msg(err_msg, { icon: 2, time: 5000 });
                }
            });
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
                编辑栏目
                <small>业务员APP活动栏目列表及顶部焦点图设置</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">正在编辑栏目: <strong class="text-danger"><?php echo ($coll["name"]); ?></strong></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form role="form" class="form-horizontal" id="templatemo-preferences-form" method="post" action="<?php echo U('column/column_edit');?>">

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">栏目名称</label>
                                        <div class="col-xs-7">
                                            <input type="text" class="form-control" id="name" name='name' value="<?php echo ($coll["name"]); ?>">
                                            <input type="hidden" class="form-control" name='id' value="<?php echo ($coll["id"]); ?>">
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label for="ctype" class="col-sm-3 control-label">栏目类型</label>
                                        <div class="col-xs-7">
                                            <select id="ctype" name="ctype" class="form-control margin-bottom-15">
                                                <option value="1" <?php if($coll["type"] == 1): ?>selected<?php endif; ?> >活动栏目</option>
                                                <option value="2" <?php if($coll["type"] == 2): ?>selected<?php endif; ?> >顶部焦点图</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="wx_home">
                                        <label class="col-sm-3 control-label">商城首页展示</label>
                                        <div class="col-xs-7">
                                            <label class="checkbox-inline">
                                                <input type="radio" name="wx_home" <?php if($coll["wx_home"] == 1): ?>checked="checked"<?php endif; ?> value="1"><span style="margin:0 10px 0 5px;">是</span>
                                                <input type="radio" name="wx_home" <?php if($coll["wx_home"] == 0): ?>checked="checked"<?php endif; ?> value="0"><span style="margin:0 10px 0 5px;">否</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group" id="wx_home_goods">
                                        <label class="col-sm-3 control-label">展示商品数</label>
                                        <div class="col-xs-7">
                                            <input type="text" class="form-control" name='wx_home_goods' value="<?php echo ($coll["wx_home_goods"]); ?>">
                                        </div>
                                    </div>

                                    <script>
                                        $().ready(function(){
                                            $('#jiaodiantianjia').on('click',function(){
                                                buildImgList();
                                            });
                                            <?php if($coll['id']){?>
                                                var id = '<?php echo $coll["id"];?>';
                                                $.post(
                                                        "<?php echo U('column/get_activity_imgs');?>",
                                                        {id:id},
                                                        function(data){
                                                            if(data.error == 0){
                                                                if (data.data){
                                                                    for(var i=0; i<data.data.length;i++){
                                                                        buildImgList(data.data[i].pic+'<?php echo C("IMG_SPEC_SM");?>', data.data[i].href, data.data[i].orderby);
                                                                    }
                                                                }
                                                            }else{
                                                                parent.layer.msg(data.msg, { icon: 1, time: 1000 });
                                                            }
                                                        }
                                                );
                                            <?php } ?>



                                        });

                                        function buildImgList(imgsrc,href,orderby){
                                            var imgsrc = imgsrc?imgsrc:'';
                                            var href = href?href:'';
                                            var orderby = orderby?orderby:'';
                                            var ddrow = $('<li class=""></li>');
                                            var ddrow_col = $('<div class="highlight"></div>');
                                            var ddrow_con = $('<pre style="position: relative; padding-left: 200px;"></pre>');
                                            var dd_img = $('<img style="position: absolute; left: 10px; top: 10px; width: 180px;" width="170" max-height="90" src="'+imgsrc+'" class="img-thumbnail">');
                                            var dd_del = $('<button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
                                            var dd_orderby = $('<input type="text" class="form-control" name="orderby[]" value="'+orderby+'" placeholder="请输入顺序数字，数字越大，排序越靠前">');
                                            var dd_href = $('<input type="text" class="form-control margin-bottom" name="href[]" value="'+href+'" placeholder="请输入跳转URL">');
                                            var dd_img_val = $('<input type="hidden" class="form-control" name="activity_pic[]" value="'+imgsrc+'">');
                                            var dd_upload = $('<div style="position: absolute; left: 10px; top: 110px; width: 180px;"></div>');

                                            var dd_upload_input = $('<input type="file" id="picfile">');

                                            dd_upload_input.appendTo(dd_upload);
                                            dd_del.appendTo(ddrow_con);
                                            dd_img.appendTo(ddrow_con);
                                            $('<label>跳转URL:</label>').appendTo(ddrow_con);
                                            dd_href.appendTo(ddrow_con);
                                            $('<label>展示顺序:</label>').appendTo(ddrow_con);
                                            dd_orderby.appendTo(ddrow_con);
                                            dd_img_val.appendTo(ddrow_con);
                                            if(!imgsrc && !href){
                                                dd_upload.appendTo(ddrow_con);
                                                dd_img.attr("src","/Assets/Admin/Public/images/thumb_empty.png");
                                            }


                                            ddrow_con.appendTo(ddrow_col);
                                            ddrow_col.appendTo(ddrow);

                                            dd_upload_input.on('change',function(){
                                                readFile(this,1,dd_img,dd_img_val);
                                                dd_upload.empty();
                                            });
                                            dd_del.on('click',function(){
                                                ddrow.remove();
                                            });
                                            ddrow.prependTo($('#jiaodiantu-list'));
                                        }
                                    </script>
                                    <!-- 焦点图开始-->
                                    <div id="tonglan" style="display:none;">
                                        <button id="jiaodiantianjia" type="button" class="btn btn-default margin-bottom" aria-label="add"><i class="fa fa-plus"></i> 新增焦点图</button>
                                        <ul class="products-list product-list-in-box" id="jiaodiantu-list">
                                            <!-- <li>
                                               <div class="row">
                                                 <div class="col-md-12">
                                                   <div role="alert" class="alert alert-warning alert-dismissible">
                                                     <img id='activity_img' src="<?php echo ($coll['description']['pic']); echo C('IMG_SPEC_MD');?>" class="img-thumbnail ">
                                                     <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                     <input type="text" class="form-control" id="href" name='href' value="<?php echo ($coll['description']['href']); ?>">

                                                   </div>

                                                 </div>
                                               </div>
                                             </li>
                                             -->
                                        </ul>
                                    </div>
                                    <!-- 焦点图结束-->
                                    <div class="row templatemo-form-buttons">
                                        <div class="col-md-12">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="pull-left">
                            <button type="reset" class="btn btn-default" onclick="javascript:history.go(-1);">返回</button>
                        </div>
                        <div class="pull-right">
                            <input type="hidden" name="id" value="<?php echo ($coll["id"]); ?>">
                            <button type="submit" class="btn btn-primary" id="btn-save">保存</button>
                        </div>
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