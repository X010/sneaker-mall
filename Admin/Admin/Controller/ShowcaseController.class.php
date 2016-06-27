<?php
// 微信商城首页橱窗设置
namespace Admin\Controller;
use Think\Controller;

class ShowcaseController extends BaseController{
    private $showcase_position_type = array(
        '1' =>  array(
            'name' => '顶部焦点图',
            'num'   => 5,
            'tpl_name' => 'temp-focus-default'
        ),
        '2' => array(
            'name' => '功能入口',
            'num'  => 5,
            'tpl_name' => 'temp-quick-default'
        ),
        '3' => array(
            'name' => '快报',
            'num'  => 5,
            'tpl_name' => 'temp_broadcast_default'
        ),
        '4' => array(
            'name' => '橱窗展示',
            'num'  => 5,
            'tpl_name' => 'temp_showcase_default'
        )
    );
    

    function __construct() {
        parent::__construct();
        parent::_initialize();
        $this->mall_init();
    }
    
    public function sinit() {
        $model = M('showcase');
        $return['error'] = 0;
        
        $company = $this->get_company();
        $company_id = $company['id'];
        
        $showcase_list = $model->where('company_id=%d', array($company_id))->select();
        
        if (empty($showcase_list)) {
            foreach($this->showcase_position_type as $type => $value) {
                $data = array();
                $data['company_id'] = $company_id;
                $data['mall_id'] = $this->_mall['id'];
                $data['name'] = $value['name'];
                $data['tpl_name'] = $value['tpl_name'];
                $data['create_time'] = date('Y-m-d H:i:s');
                $data['type']  = $type;
                $model->add($data);
            }

        }
 
        $this->ajaxReturn($return);
    }

    public function index(){
        $this->display('Index:main');
    }

    public function slist(){
        $this->mall_init();
        $model = M('showcase');

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $showcase_list = $model->where('company_id=%d', array($company_id))->select();
        $showcase_list = getSortedColl($showcase_list);
        

        $this->assign('current_menu', I('current_menu',''));
        $this->assign('showcase_list', $showcase_list);
        $this->assign('moudle_name', C('LANG_MOUDLE_COLUMN'));
        $this->assign('action_name', C('LANG_COLUMN_ACTION_COLUMN'));
        $this->display('Showcase:slist');
    }

    public function csort(){
        $id = I('id',0,'intval');
        $sort = I('sort_val','0','intval');

        $r['error'] = 1;
        $r['msg'] = '更改排序成功';
        $r['data'] = '';

        if($id && $sort) {
            # 供应商ID
            $company = $this->get_company();
            $company_id = $company['id'];
            if(M('cate')->where('id=%d AND company_id=%d',array($id,$company_id))->setField('csort',$sort)){
                $r['error'] = 0;
                $this->ajaxReturn($r);
            }else{
                $r['msg'] = '更改排序失败';
                $this->ajaxReturn($r);
            }
        }else{
            $r['msg'] = '更改排序时出现错误';
            $this->ajaxReturn($r);
        }
    }

    public function showcase_edit(){
        $this->mall_init();
        
        $showcase_id = I('id','0','intval');
        $model = M('showcase');
        $post_data = I('POST.');
        $mall = $this->_mall;
        
        if(!$post_data) {
            $showcase = $model->where('id=%d', array($showcase_id))->find();
            $showcase['description'] = json_decode($showcase['description'], true);
            
            $showcase['type_text'] = $this->showcase_position_type[$showcase['type']];
            // 橱窗内容可设置的条数
            $showcase_item_num = (int)$this->showcase_position_type[$showcase['type']]['num'];
            $this->assign('showcase', $showcase);
        } else if (sizeof($post_data)) {
            
  
            $name       = I('name', '');
            $pics       = I('pic', '');
            $texts      = I('text', '');
            $titles     = I('title', '');
            $sub_titles = I('sub_title', '');
            $type       = I('stype', 1, 'intval');
            $href       = I('href', '');
            $orderby    = I('orderby', '');
            $descriptions = array();
            
            //当前类型为顶部焦点图(type=1)，功能入口(type=2)， 橱窗显示(type=4) 上传图片， 且在这三种情况下时 description['c'] 的值表示为图片地址；当前类型为快报(type=3)时，description['c'] 的值表示为快报的文字内容
       // 橱窗内容可设置的条数
            $showcase_item_num = (int)$this->showcase_position_type[$type]['num'];

        
            if (in_array($type, array(1, 2, 4))) {
                foreach($pics as $key => $pic_total_addr) {
                    $pic_addr = substr($pic_total_addr, 0, strpos($pic_total_addr, '@'));
                    
                    if (!empty($pic_addr)) {
                        $description  = array('c' => $pic_addr, 'href' => $href[$key], 'orderby' => (int)$orderby[$key]);
                        if ($type == 2 || $type == 4) {
                            $title = trim($titles[$key]);
                            $description['title'] = $title;
                        }
                        
                        if ($type == 4) {
                            $sub_title = trim($sub_titles[$key]);
                            $description['sub_title'] = $sub_title;
                        }
                        array_push($descriptions, $description);                     
                    }
                }
                
             }  else {
                foreach($texts as $key => $text ) {
                    $text = trim($text);
                    if (!empty($text)) {
                        $description = array('c' => $text, 'href' => $href[$key], 'orderby' => (int)$orderby[$key]);
                        array_push($descriptions, $description);
                    }
        
                }
            }

            if (!empty($descriptions)) {
                //$descriptions = array_reverse($descriptions);
                $descriptions = $this->__sort_by_field($descriptions, 'orderby');
                //$descriptions = array_reverse($descriptions);
                $descriptions = json_encode($descriptions, JSON_UNESCAPED_SLASHES);  
            }

            
            if ($showcase_id) {
                $data = array();
                $data['name'] = $name;
                $data['description'] = $descriptions;
                $data['type'] = $type;
                
                if (sizeof($descriptions) > $showcase_item_num) {
                    $this->error($showcase['name'] . "只能有" . $showcase_item_num . "条内容");
                    exit;
                }
            
                    
                
                
                //var_dump($data);
                //die;
                
                if ($model->where('id=%d', array($showcase_id))->save($data)) {
                    $this->redirect(U('showcase/slist'));
                    exit;
                } else {
                    $this->error('更新' . $data['name'] . '失败');
                    exit;
                }
                
            }
            
            
        } else {
            $comany = $this->get_company();
            $company_id = $company['id'];
            $data['name'] = $name;
            $data['description'] = $piccontent;
            $data['create_time'] = date('Y-m-d H:i:s', time());
            $date['type'] = $type;
            $data['company_id'] = $company_id;
            $data['csort'] = 1;
            
            if ($model->add($data)) {
                $this->redirect(U('showcase/slist'));
                exit;
            } else {
                $this->eror('添加' + $showcase['name'] + '失败');
                exit;
            }
        }

        //商品分类 和 活动栏目
        $company = $this->get_company();
        $company_id = $company['id'];
        $goods_types = M('goods_type')->where('company_id=%d', $company_id)->select();
        if(is_array($types)){
            foreach($types as $k=>$v){
                $vals[$k] = $v['csort'];
            }
            array_multisort($vals, SORT_ASC, $goods_types);
        }
        $this->assign('goods_types', $goods_types);
        $coll = M('cate')->where('company_id=%d',array($company_id))->select();
        $coll = getSortedColl($coll);
        $this->assign('coll',$coll);

        $this->assign('mall', $mall);
        $this->assign('current_menu',I('current_menu',''));
        $this->assign('moudle_name', C('LANG_MOUDLE_COLUMN'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_COLUMN'));
        $this->assign('showcase_item_num', $showcase_item_num);
        $this->display('Showcase:showcase_edit');
    }

       public function get_pics(){
        $id = I('id',0, 'intval');
        $r['error'] = 0;
        $r['msg'] = '获取数据成功';
        $r['data'] = '';
        if($id){
            $result = M('showcase')->where('id=%d',array($id))->find();
            $lists = $result['description'];
            $r['data'] = json_decode($lists,true);
            $this->ajaxReturn($r);
        }else{
            $r['error'] = 1;
            $r['msg'] = '获取数据失败';
            $this->ajaxReturn($r);
        }
    }
    public function picupload(){
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

    /**
     * 发布栏目
     */
    public function publish(){
        $id = I('id',0,'intval');
        $dis = I('dis',0,'intval');
        $r['error'] = 0;
        if($dis){
            $r['msg'] = '发布成功';
        }
        else{
            $r['msg'] = '取消成功';
        }
        $r['data'] = '';
        if($id){
            if(M('showcase')->where('id=%d', array($id))->setField('publish', $dis)){
                $this->ajaxReturn($r);
            }else{
                $r['error'] = 1;
                $r['msg'] = '操作失败';
                $this->ajaxReturn($r);
            }
        }else{
            $r['error'] = 1;
            $r['msg'] = '操作时出现错误';
            $this->ajaxReturn($r);
        }
    }
    
    private  function __sort_by_field($params, $field_name = 'orderby') {
        $len = sizeof($params);
        $tmp = array();
        $return = $params;
    

        for ($i = 0; $i < $len; $i++) {
            for ($j = 0; $j < ($len - $i - 1); $j++) {
                if ($params[$j][$field_name] < $params[$j + 1][$field_name]) {
                    $tmp = $params[$j];
                    $params[$j] = $params[$j + 1];
                    $params[$j + 1] = $tmp;
                }

            }
        }
        return $params;
    }

}
