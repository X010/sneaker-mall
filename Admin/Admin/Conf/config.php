<?php
return array(
    //'配置项' =>'配置值'
    'MODULE_ALLOW_LIST' =>    array('Admin'),

    //我们用了入口版定 所以下面这行可以注释掉
    'DEFAULT_MODULE'    =>    'Admin',  // 默认模块
    'SHOW_PAGE_TRACE'   =>  true,
    'LOAD_EXT_CONFIG'   => 'db,lang',
    'URL_CASE_INSENSITIVE'  =>  true,  //url不区分大小写
    'URL_MODEL'   =>0,
    'URL_HTML_SUFFIX'  =>'html',
    'TMPL_ACTION_ERROR' => 'Public/error',
    'TMPL_ACTION_SUCCESS' =>  __ROOT__.'Public/success',

    //'DEFAULT_FILTER'        => 'htmlspecialchars',
    'SUPER_ADMIN_ID'=>1,  //超级管理员id 删除用户的时候用这个禁止删除
    'SHOW_ERROR_MSG'        =>  true,

    // 系统默认参数
    'DEFAULT_PAGE_LIMIT' => 15,
    'DEFAULT_TYPE_DEEP' => 3,
    'SMS_VERIFY' => false,
    'OP_EXPIRED' => 600000, // 长时间不操作，退出秒数。

    //用户注册默认信息
    'DEFAULT_SCORE'=>100,
    'LOTTERY_NUM'=>3,  //每天最多的抽奖次数
    'SITE_URL' => 'tbm.ms9d.com',
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Admin/'.MODULE_NAME.'/View/' . '/Public/static',
        '__PUBLIC__' => __ROOT__.'Public'),

    // 上传相关
    'OSS_ACCESS_KEYID' => "J6WgVtIfrRC9NiGN",
    'OSS_ACCESS_KEYSECRET' => "gLaEko5fGJfg6lbzIfQ34uFCHhONfL",
    //'OSS_ENDPOINT' => "img.tbm.ms9d.com",
    'OSS_ENDPOINT' => "imgsrc.ms9d.com",

    //'OSS_ENDPOINT' => "http://oss-cn-hangzhou.aliyuncs.com",
    'OSS_BUCKET' => 'imgsrc',

    // 图片相关
    'ROOT_TBM_PHTOT_URL'=>'http://tbm.photo.ms9d.com',
    'BASE_GOOD_IMG_URL' => 'http://photo.ms9d.com/og/',
    //'BASE_GOOD_IMG_URL' => 'http://tbm.photo.ms9d.com/og/',
    'UPLOAD_GOOD_IMG_URL'=>'http://tbm.photo.ms9d.com/og/',
    'ALLOW_IMG_SUFFIX' => '.jpg',
    'IMG_SPEC_SM' => '@0o_0l_160w_90q.src',
    'IMG_SPEC_MD' => '@1e_1c_0o_0l_400h_350w_90q.src',
    'IMG_SPEC_LG' => '@0o_0l_800w_90q',
    'IMG_SPEC_HB' => '@1e_1c_0o_0l_250h_768w_90q.src',

    'MAX_IMG_NUM'  => 6,

    // 服务API
    'API_URL' => 'http://115.28.8.173:8082/',
    'API_LOGIN_URL' => C('API_URL') . 'inc/businessadminlogin.action',
    'API_CK_URL' => C('API_URL') . 'inc/getstorebycid.action',
    'API_TYPE_URL' => C('API_URL') . 'inc/getGtype.action',
    'API_SMS_URL' => 'http://sms.ms9d.com/message/sms.do',
    // ERP API
    'API_ERP_URL' =>'http://115.28.8.173:808/',
    'API_ERP_A_PRICE_URL'=> C('API_ERP_URL') . 'mall/price_read_by_gcode',
    'API_ERP_B_PRICE_URL'=> C('API_ERP_URL') . 'mall/price_read_by_company',
    'API_ERP_PRICE_CHG_URL'=> C('API_ERP_URL') . 'mall/price_change',

    'CUSTOMER_TYPE' => array(
        array('id'=>1,'name'=>'经销商','sname'=>'经'),
        array('id'=>2,'name'=>'酒店饭店','sname'=>'酒'),
        array('id'=>'3','name'=>'商场超市','sname'=>'商'),
        array('id'=>'4','name'=>'便利店','sname'=>'便')
    ),
   // 微信商城设置相关
    'WEIXIN_AUTH_URL' => 'https://open.weixin.qq.com/connect/oauth2/authorize?',
    'WEIXIN_AUTH_CALLBACK_URL' => 'http://wx.ms9d.com/index.html'

);
