

/**
 * 防止重复提交
 */
function disableBtn(btn) {
    clickBtn = null;
    var class_name = $(btn).attr('disabled', 'disabled').attr('class');
    $(btn).attr('class', 'btn btn-default');
    setTimeout(function () {
        $(btn).attr("disabled", false).attr('class', class_name);
    }, 5000);
}

/**
 * 获取当前页面的QuerySring(过滤掉c和a参数)
 * @returns {string}
 */
function getQueryString(){
    var params = GetRequest();
    var querystring = '';
    $.each(params, function(k, v){
        if (k && k != 'c' && k != 'a' && k != 'id'){
            querystring += k + '=' + v + '&';
        }
    });
    return querystring;
}

/**
 * 获取当前页面的GET参数
 * @returns {Object}
 * @constructor
 */
function GetRequest() {
    var url = location.search; //获取url中"?"符后的字串
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        strs = str.split("&");
        for(var i = 0; i < strs.length; i ++) {
            theRequest[strs[i].split("=")[0]]=(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}


/**
 * js模拟提交POST(用于下载)
 * @param URL
 * @param PARAMS
 * @returns {Element}
 */
function jsPOST(URL, PARAMS) {
    var temp = document.createElement("form");
    temp.action = URL;
    temp.method = "post";
    temp.style.display = "none";
    for (var x in PARAMS) {
        var opt = document.createElement("textarea");
        opt.name = x;
        opt.value = PARAMS[x];
        temp.appendChild(opt);
    }
    document.body.appendChild(temp);
    temp.submit();
    return temp;
}

var giving_num = 0;
/**
 * 添加一行商品(用于商品详情页/策略详情页)
 *   需要全局变量 giving_num
 * @param goods_info
 * @param prefix 参数前缀 如:giving_ / bind_
 */
function add_goods_row(goods_info, prefix_goods, prefix_num){
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
        var dgiving_goods_name = $('<input type="text" disabled="disabled" class="form-control"  id="'+prefix_goods+'goods_'+ giving_num +'" name="'+prefix_goods+'goods_name[]" value="'+goods.gname+'">');
        var dgiving_goods = $('<input type="hidden" class="form-control"  id="'+prefix_goods+'goods_'+ giving_num +'" name="'+prefix_goods+'goods_title[]" value="'+goods.gname+'">');
        var dgiving_goods_id = $('<input type="hidden" name="'+prefix_goods+'goods[]" id="'+prefix_goods+'goods_'+ giving_num +'_id" value="'+goods.id+'" />');
        var dgiving_goods_code = $('<input type="hidden" name="'+prefix_goods+'goods_code[]" id="'+prefix_goods+'goods_'+ giving_num +'_code" value="'+goods.gcode+'" />');
        dgiving_goods_name.appendTo(dgiving);
        dgiving_goods.appendTo(dgiving);
        dgiving_goods_id.appendTo(dgiving);
        dgiving_goods_code.appendTo(dgiving);
        dgiving.appendTo(dgroup);

        var dgiving_num = $('<div class="col-xs-2"></div>');
        var dgiving_num_group = $('<div class="input-group"></div>');
        var dgiving_num_num = $('<input type="text" class="form-control" name="'+prefix_num+'goods_num[]" placeholder="数量" value="'+ goods.num +'">');
        dgiving_num_num.appendTo(dgiving_num_group);
        dgiving_num_group.appendTo(dgiving_num);
        dgiving_num.appendTo(dgroup);

        lists.append(dgroup);
        $('input[name="'+prefix_num+'goods_num[]"]:last').focus();
    }
}


/**
 * 获取当前时间(yyyy-MM-dd HH:MM:SS)
 * @returns {string}
 */
function now() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var hours = date.getHours();
    if (hours >= 0 && hours <= 9) {
        hours = "0" + hours;
    }
    var minutes = date.getMinutes();
    if (minutes >= 0 && minutes <= 9) {
        minutes = "0" + minutes;
    }
    var seconds = date.getSeconds();
    if (seconds >= 0 && seconds <= 9) {
        seconds = "0" + seconds;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate + " " + hours + seperator2 + minutes + seperator2 + seconds;
    return currentdate;
}

