<?php
// 微信商城首页橱窗设置
namespace Admin\Controller;
use Think\Controller;

class MallController extends BaseController{

    private $__comany;
    private $__company_id;

    public function _initialize() {
        parent::_initialize();
        
        $this->__company =  $this->get_company();
        $this->__company_id = $this->__company['id'];
    }
    
//    商城初始化
    public function init() {
        $mall = $this->get_mall();
        
        if (empty($mall)) {
            $data = array();
            $data['company_id'] = $this->__company_id;
            $data['name'] = '微信商城';
            $data['create_time'] = date('Y-m-d H:i:s');
            $mall_id = M('mall')->add($data);
            if (!$mall_id) {
                $this->error('微信商城激活失败');
            }
        } else {
            $mall_id = $mall['id'];
        }
        
        $this->redirect(U('mall/edit', array('id' => $mall_id)));
    }
    
    public function edit() {
        $this->mall_init();
        $mall = $this->_mall;
        $mall_id = I('id', '');
        
        $model = M('mall');
        $tab = I('tab', 'base');
        
        $post_data = I('POST.');

        if ($tab == 'delivery'){
            //配送信息
            if (sizeof($post_data)) {
                $data = array();
                $data['delivery_time'] = I('delivery_time', '');
                $data['delivery_fee'] = I('delivery_fee', '');
                if (!$model->where('id=%d', array($mall['id']))->save($data)) {
                    $this->error('配送信息 更新失败');
                }

                $this->_mall = $this->get_mall();
            }

        } else {
            //企业信息 / 公众号绑定
            $url = $this->__get_wx_callback_url($mall['app_id'], $mall['state'], $mall['bind']);

            if (sizeof($post_data)) {
                $error = false;
                $name = I('brand_name', $mall['name']);
                $intro = I('brand_intro', $mall['intro']);
                $cs_phone = I('brand_cs_phone', $mall['cs_phone']);
                $pic = I('brand_logo', '');



                if (!empty($pic)) {
                    $logo  = substr($pic, 0, strpos($pic, '@'));
                } else {
                    $logo = $mall['logo'];
                }
                $wx_name = I('wx_name', $mall['wx_name']);
                $wx_oid = I('wx_oid', $mall['wx_oid']);
                $wx_id = I('wx_id', $mall['wx_id']);

                $wx_url = I('wx_url', $mall['wx_url']);
                $wx_token = I('wx_token', $mall['wx_token']);
                $app_id = I('app_id', $mall['app_id']);
                $app_secret = I('app_secret', $mall['app_secret']);
                $bind = 1;
                $state = $this->__gen_wx_state($app_id, $mall);


                $data = array();
                $data['name'] = $name;
                $data['intro'] = $intro;
                $data['cs_phone'] = $cs_phone;
                $data['logo'] = $logo;
                $data['wx_name'] = $wx_name;
                $data['wx_oid'] = $wx_oid;
                $data['wx_id'] = $wx_id;
                $data['wx_url'] = $wx_url;
                $data['wx_token'] = $wx_token;
                $data['app_id'] = $app_id;
                $data['app_secret'] = $app_secret;
                $data['bind'] = $bind;
                $data['state'] = $state;


                if ($tab == "base") {
                    $message = '企业信息';
                } else {
                    $message = '公众号信息';
                }

                if (!$model->where('id=%d', array($mall_id))->save($data)) {
                    $this->error($message . '更新失败');
                } else {
                    $url = $this->__get_wx_callback_url($app_id, $state);

                }

                $this->_mall = $this->get_mall();
            }

            $this->assign('url', $url);
        }

        $this->assign('mall', $this->_mall);
        $this->assign('tab', $tab);
        $this->display('Mall/edit');
        
    }
    
    
    public function unbind_wx() {
        $mall = $this->get_mall();
        $mall_id = $mall['id'];
        
        $data = array();
        $data['bind'] = 0;
        $data['wx_oid'] = '';
        $data['wx_id'] = '';
        
        $model = M('mall');
        
        $error = 0;
        $msg = '成功解除绑定。';
        
        if (!$model->where('id=%d', array($mall_id))->save($data)) {
            $error = 1;
            $msg = '解除绑定失败。';
        }
        
        $return = array(
            'error' => $error,
            'msg'   => $msg
        );
        
        $this->ajaxReturn($return);
    }
    
    // 会销设置
    public function promotion_setting() {
        $this->mall_init();
        $mall = $this->_mall;
        $mall_id = $mall['id'];
        
        $customer_type = C('CUSTOMER_TYPE');
        $storage = $this->get_CK();
        
        $post_data = I('POST.');
        
        if (sizeof($post_data)) {
            $model = M('mall');
            
            $data = array();
            $data['promotion_code'] = I('promotion_code', $mall['promotion_code']);
            $data['promotion_start_time'] = I('promotion_start_time', $mall['promotion_start_time']);
            $data['promotion_end_time'] = I('promotion_end_time', $mall['promotion_end_time']);
            $data['default_cctype'] = I('default_cctype', $mall['default_cctype'], 'number_int');
            $data['default_sid'] = I('default_sid', $mall['default_sid'], 'number_int');
            
            extract($data, EXTR_OVERWRITE);
            
            $error = false;
            $msg = '';
            $fields = array(
                'promotion_code' => '会销码不能为空。',
                'promotion_start_time' => '会销开始时间不能为空。',
                'promotion_end_time' => '会销结束时间不能为空。',
                'default_cctype' => '会销客户类型不能为空。',
                'default_sid'   => '会销出货仓库不能为空。'
            );
            $check = $this->_check_fields_not_empty($data, $fields);
            
            extract($check, EXTR_OVERWRITE);
            
            if (ctype_alnum($promotion_code)) {
                if (strlen($promotion_code) > 8) {
                    $error = $error || true;
                    $msg = '促销码不能超过 8 位。';
                }
            } else  {
                $error = $error || true;
                $msg = '促销码只能由字母或数字组成，不能包含中文或空格等。';
            }
            
  
            if (strtotime($promotion_end_time) < strtotime($promotion_start_time)) {
                $error = $error || true;
                $msg = '会销的结束时间必须大于开始时间。';
            }
            
            
            
            if (!$model->where('id=%d', array($mall_id))->save($data)) {
                $error = true;
                $msg = '保存失败';
            }  else {
                $msg = '保存成功';
            }
            
            
            $return = array(
                'error' => $error,
                'msg'   => $msg
            );
            
            $this->ajaxReturn($return);
            exit;
        }
        
        
        
        $this->assign('mall', $mall);
        $this->assign('customer_type', $customer_type);
        $this->assign('storage', $storage);
        $this->display('Mall/promotion_setting');
    }


    
    /**
     * 上传图片
     */
    public function upload_img(){
     $rd = rand(pow(10,(10-1)), pow(10,10)-1);
        $path = 'og/ord_' . $rd;
        
        $src = I('post.src', '');
        
        if (!empty($src)) {
            $pic = $this->save_mem_img($src, $path);
            
            $pic = C('BASE_GOOD_IMG_URL') . str_replace("og/", '', $pic[0]) . '.' . $pic[1] . C('IMG_SPEC_SM');
            $r['error'] = 1;
            $r['msg'] = '上传图片错误';
            $r['data'] = '';
            if($pic) {
                $r['error'] = 0;
                $r['msg'] = '上传成功';
                $r['data'] = $pic;
                $this->ajaxReturn($r);
            } else {
                $this->ajaxReturn($r);
            }
        }
    }
    
    

    
    private function __gen_wx_state($app_id, $mall) {
        $return = '';
        $company_id        = $mall['company_id'];
        $mall_id    = $mall['id'];
        $model = M('mall_app');
        
        if (!empty($app_id))  {
            $app_ids = M('mall_app')->where("app_id like '%s'", array($app_id))->select();

            if (sizeof($app_ids)) {
                $return = $app_ids[0]['state'];
            } else {
                $state = time() . rand(0, 9);
                $data = array();
                $data['company_id'] = $company_id;
                $data['mall_id'] = $mall_id;
                $data['app_id'] = $app_id;
                $data['createtime'] = date('Y-m-d H:i:s');
                $data['state'] = $state;

                
                $state_id = M('mall_app')->add($data);
                
                if ($state_id) {  
                    $return = $state;
                }
            }
        }
        

        
        return $return;
    }
    
    private function __get_wx_callback_url($app_id, $state, $bind = 1) {
        $wx_auth_url = rtrim(C('WEIXIN_AUTH_URL'), '?');
        $wx_auth_url .= '?';
        
        $wx_auth_callback_url = C('WEIXIN_AUTH_CALLBACK_URL');
        $url = '';
        
        if (!empty($app_id) && !empty($state) && $bind) {
            $url = $wx_auth_url . 'appid=' . $app_id . '&redirect_url=' . urlencode($wx_auth_callback_url) . '&response_type=code&scope=snsapi_base&state=' . $state . '#wechat_redirect';
        }
        
        return $url;
    }

    
    public function _check_fields_not_empty($data, $fields) {
        $error = false;
        $msg = '';
        
        
        foreach($fields as $field => $error_msg) {
            if (!isset($data[$field])  || empty($data[$field])) {
                $error = $error || true;
                $msg = $error_msg;
            }
        }
        
        $return = array(
            'error' => $error,
            'msg'   => $msg
        );
        
        return $return;
    }
    
    
}
