<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class SaleController extends BaseController{

    private $r = array("error"=>0,"msg"=>"","data"=>"");

    public function index(){
        $this->display('Index:main');
    }

    /**
     * * < !!!!!! 弃用 !!!!!!>
     * 买赠列表
     * @params key search key
     * @params p   page num
     * @params o   order string
     * @params del
     */
    public function giving(){
        $key = I('key','');
        $p = I('p',0,'intval');
        $order = I('o','');
        $del = I('del',0,'intval');


        $option = array();
        if($key){
            $w['_string'] = 'gname like "%' .$key. '%" OR barcode like "%' .$key. '%"';
            $option['where'] = $w;
        }

        # 分页
        $option['limit'] = 15;
        if($p) $option['start'] = $option['limit'] * ($p-1);

        # 查询条件
        $option['where']['isbind'] = 1;
        $option['where']['flagdel'] = $del;
        $option['order'] = (!empty($order))?$order:'update_time desc';
        $goods_result = $this->goods($option);

        # 分页
        $Page = new \Extend\Page($goods_result['count'],$option['limit']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        # 仓库信息
        $ck = $this->get_CK();

        foreach($goods_result['data'] as $key=>$val){
            $goods_result['data'][$key]['ck'] = array();
            if($val['sids'] != null){
                $ckid = explode(',',$val['sids']);
                foreach($ck as $v){
                    if(in_array($v['id'],$ckid)){
                        array_push($goods_result['data'][$key]['ck'], $v['name']);
                    }
                }
            }
        }

        $this->assign('total',$goods_result['count']);
        $this->assign('goods',$goods_result['data']);
        $this->assign('page',$show);
        $this->assign('moudle_name', C('LANG_MOUDLE_SALE'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_GIVING'));
        $this->display('Sale:giving_list');
    }

    /**
     * < !!!!!! 弃用 !!!!!!>
     * 编辑买赠
     * @params sid
     * @params condition_mgoods_num 捆绑数量
     * @params condition_mgoods_price 捆绑商品价格
     */
    public function edit_giving(){
        $id = I('sid',0,'intval');

        # 展示编辑信息
        if($_GET && $id){
            $model = M('goods_bind');

            # 先选取捆绑商品
            $bind_good = $model->where('db_goods_bind.mgid=%d AND giveaway=0',array($id))->join('LEFT JOIN db_goods ON db_goods.id=db_goods_bind.mgid')->find();

            # 捆绑售卖条件判断
            if($bind_good['salemodel'] == 1){
                $bind_good['mnum'] = $bind_good['num'];
                $bind_good['num'] = $bind_good['num'] / $bind_good['spec'];
            }else if($bind_good['salemodel'] == 0){
                $bind_good['mnum'] = $bind_good['num'];
            }

            # 选取主商品
            $main_good = M('goods')->where('db_goods.id=%d',array($bind_good['child_mgid']))->join('LEFT JOIN db_goods_bind ON db_goods_bind.child_mgid=db_goods.id')->find();

            # 选取赠品
            $giving_good = $model->where('db_goods_bind.mgid=%d AND giveaway=1',array($id))->join('LEFT JOIN db_goods ON db_goods.id=db_goods_bind.child_mgid')->select();

            $this->assign('ck', $this->get_CK());
            $this->assign('customer',C('CUSTOMER_TYPE'));
            $this->assign('bind_goods',$bind_good);
            $this->assign('main_goods',$main_good);
            $this->assign('giving_goods',$giving_good);
            $this->display('Sale:edit_giving');
        }

        # 确认更改信息
        if($_POST){
            $num = I('condition_mgoods_num',0,'intval');
            $cprice = I('condition_mgoods_price');
            $bid = I('bindgoods_id',0,'intval');
            $salemodel = I('salemodel',0,'intval');


            // 更新捆绑商品
            $dat['num'] = $num;
            $dat['cprice'] = $cprice;
            $dat['salemodel'] = $salemodel;

            $m = M('goods_bind');
            $m->where('mgid=%d AND giveaway=0',array($bid))->save($dat);
            //echo $m->getLastSql();

            // 更新捆绑商品其他信息
            $main_goods_name = I('newmainname','');
            $showunit = I('showunit','套');
            $ck = I('ck',array());
            $restrict = I('restrict',0,'intval');
            $supplier = I('supplier',array());


            $data['gname'] = $main_goods_name;
            $data['unit'] = $showunit;
            $data['sids'] = implode(',',$ck);
            $data['cctype'] = implode(',',$supplier);
            $data['restrict'] = $restrict;
            $data['update_time'] = date("Y-m-d H:i:s",time());

            $m = M('goods');
            $m->where('id=%d',array($bid))->save($data);

            // 清空现有赠品
            M('goods_bind')->where('mgid=%d AND giveaway=1',array($bid))->delete();


            $giving_goods = I('giving_goods','');
            $giving_goods_title = I('giving_goods_title','');

            $condition_zgoods_num = I('condition_zgoods_num','');
            # 写入赠品
            foreach($giving_goods as $key=>$val) {
                $bind_m_data['mgid'] = $bid;
                $bind_m_data['giveaway'] = 1;
                $bind_m_data['num'] = $condition_zgoods_num[$key];
                $bind_m_data['memo'] = '赠品';
                $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                $bind_m_data['child_mgid'] = $val;
                $bind_m_data['cprice'] = 0;
                M('goods_bind')->add($bind_m_data);

                M('goods')->where('id=%d',array($val))->setField('title',$giving_goods_title[$key]);
            }

            $this->redirect(U('sale/giving'));
            exit;
        }
    }


    /**
     * * < !!!!!! 弃用 !!!!!!>
     * 新建买赠商品
     */
    public function new_giving(){

        if($_POST){
            $main_goods_id = I('maingoodsid',0,'intval');
            $main_goods_name = I('newmainname','');
            $condition_mgoods_num = I('condition_mgoods_num',0,'intval');
            $condition_mgoods_price = I('condition_mgoods_price','0.0');
            $giving_goods = I('giving_goods','');
            $giving_goods_title = I('giving_goods_title','');

            $condition_zgoods_num = I('condition_zgoods_num','');
            $unit = I('unit','套');
            $showunit = I('showunit','套');
            $ck = I('ck',array());
            $restrict = I('restrict',0,'intval');
            $supplier = I('supplier',array());
            $salemodel = I('salemodel',0,'intval');


            if($main_goods_id) {
                $model = M('goods');
                $set = $model->where("id = %d", array($main_goods_id))->find();
                foreach($set as $key=>$val){
                    if($key == 'id' || $key == 'publish' || $key == 'cateid') continue;
                    $d[$key] = $val;
                }
                $d['create_time'] = $d['update_time'] = date("Y-m-d H:i:s",time());
                $id = $model->add($d);

                if($id && $id > 1){
                    # 更新打包商品属性
                    $data['isbind'] = 1;
                    $data['gname'] = $main_goods_name;
                    $data['unit'] = $showunit;
                    $data['sids'] = implode(',',$ck);
                    $data['cctype'] = implode(',',$supplier);
                    $data['restrict'] = $restrict;

                    $model->where("id = %d", array($id))->save($data);

                    $model = M('goods_bind');
                    # 写入主商品
                    $bind_m_data['mgid'] = $id;
                    $bind_m_data['giveaway'] = 0;
                    $bind_m_data['num'] = $condition_mgoods_num;
                    $bind_m_data['memo'] = '主商品';
                    $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                    $bind_m_data['child_mgid'] = $main_goods_id;
                    $bind_m_data['cprice'] = $condition_mgoods_price;
                    $bind_m_data['salemodel'] = $salemodel;


                    $model->add($bind_m_data);

                    # 写入赠品
                    foreach($giving_goods as $key=>$val) {
                        $bind_m_data['mgid'] = $id;
                        $bind_m_data['giveaway'] = 1;
                        $bind_m_data['num'] = $condition_zgoods_num[$key];
                        $bind_m_data['memo'] = '赠品';
                        $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                        $bind_m_data['child_mgid'] = $val;
                        $bind_m_data['cprice'] = 0;
                        $model->add($bind_m_data);

                        M('goods')->where('id=%d',array($val))->setField('title',$giving_goods_title[$key]);

                    }

                    //this->success('新建绑定商品成功',U('sale/giving'));
                    $this->redirect(U('sale/giving_1'));
                    exit;

                }
                else {
                    $this->error('非法操作');
                    exit;
                }

            }else {
                $this->error('非法操作');
                exit;
            }

        }


        $this->assign('ck', $this->get_CK());


        $this->assign('customer',C('CUSTOMER_TYPE'));
        $this->assign('moudle_name', C('LANG_MOUDLE_SALE'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_GIVING'));

        $this->display('Sale:giving_1');
    }


    /**
     * 定价售卖
     */
    public function price_list(){
        $key = I('key','');
        $p = I('p',0,'intval');
        $current_ck = I('ck',0,'intval');
        $gtyp = I('gtyp',1);
        $publish = I('publish', '');
        $option = array();
        if($key){
            $w['_string'] = 'gname like "%' .$key. '%" OR barcode like "%' .$key. '%"';
            $option['where'] = $w;
        }else{
            // 基础商品不能设置价格
            $w['_string'] = 'isbind =1 OR pkgsize = 1';
        }
        # 分页
        $option['limit'] = 15;
        if($p) $option['start'] = $option['limit'] * ($p-1);

        if($publish !== ''){
            $option['where']['publish'] = $publish;
        }
        $option['where']['flagdel'] = 0;

        $option2 = $option;
        //$goods_result0 = $this->goods($option2, True);

        $option2['where']['isbind'] = 0;
        $option2['where']['pkgsize'] = 0;
        $goods_result1 = $this->goods($option2, True);

        $option2['where']['isbind'] = 1;
        $option2['where']['pkgsize'] = 0;
        $goods_result2 = $this->goods($option2, True);

        $option2 = $option;
        $option2['where']['isbind'] = 0;
        $option2['where']['pkgsize'] = 1;
        $goods_result3 = $this->goods($option2, True);

        if($gtyp == 1){
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 0;
        }else if($gtyp == 2){
            $option['where']['isbind'] = 1;
            $option['where']['pkgsize'] = 0;
        }else if($gtyp == 3){
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 1;
        }else if($gtyp == 4){
            $option['where']['flagdel'] = 1;
        }

        $option['order'] = 'publish desc, top desc, update_time desc, create_time desc';
        $goods_result = $this->goods($option);


        # 分页
        $Page = new \Extend\Page($goods_result['count'],$option['limit']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        # 仓库信息
        $ck = $this->get_CK();
        if(!$current_ck){
            $current_ck = $ck[0]['id'];
        }

        # 读取仓库及客户类型价格

        $this->assign('current_menu',I('current_menu',''));
        $this->assign('search_key',$key);
        $this->assign('gtyp',$gtyp);
        $this->assign('total',$goods_result['count']);
        $this->assign('goods',$goods_result['data']);
        $this->assign('page',$show);
        $this->assign('ck',$ck);
        $this->assign('cck',$current_ck);
        $this->assign('publish',$publish);
        $this->assign('customer',C('CUSTOMER_TYPE'));
        $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
        $this->assign('action_name',C('LANG_GOODS_ACTION_LIST'));
        //$this->assign('total0',$goods_result0['count']);
        $this->assign('total1',$goods_result1['count']);
        $this->assign('total2',$goods_result2['count']);
        $this->assign('total3',$goods_result3['count']);
        $this->display("Sale:price_list");
    }

    /**
     * 定价售卖
     */
    public function price_list_excel(){
        $key = I('key','');
        $sid = I('ck',0,'intval');
        $gtyp = I('gtyp',1);
        $publish = I('publish', '');
        $option = array();
        if($key){
            $w['_string'] = 'gname like "%' .$key. '%" OR barcode like "%' .$key. '%"';
            $option['where'] = $w;
        }else{
            // 基础商品不能设置价格
            $w['_string'] = 'isbind =1 OR pkgsize = 1';
        }
        # 分页
        if($publish !== ''){
            $option['where']['publish'] = $publish;
        }
        $option['where']['flagdel'] = 0;

        if($gtyp == 1){
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 0;
        }else if($gtyp == 2){
            $option['where']['isbind'] = 1;
            $option['where']['pkgsize'] = 0;
        }else if($gtyp == 3){
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 1;
        }else if($gtyp == 4){
            $option['where']['flagdel'] = 1;
        }
        $option['limit'] = 5000;

        $option['order'] = 'publish desc, top desc, update_time desc, create_time desc';
        $goods_result = $this->goods($option);
        $goods_result = $goods_result['data'];
        //var_dump($goods_result);exit;
        $id_list = [];
        foreach($goods_result as $val){
            $id_list[] = $val['id'];
        }
        $id = implode(',',$id_list);
        $id = trim($id, ',');

        $gtype = I('gtyp',0,'intval');
        if($gtype != 1){
            $where = "mgid in($id)";
            if($sid){
                $where .= " and sid=$sid";
            }
            $rs = M('shop_price')->field('mgid,cctype,price,sid')->where($where)->select();
            //echo $model->getLastSql();
            $rs2 = [];
            $gid_list = explode(',', $id);
            foreach($rs as $k=>$v){
                $rs2[$v['mgid']]['price'.$v['cctype']] = $v['price'];
            }
            foreach($gid_list as $v){
                if(!isset($rs2[$v]) && $v){
                    $rs2[$v] = [
                        'price1'=>'0.00',
                        'price2'=>'0.00',
                        'price3'=>'0.00',
                        'price4'=>'0.00',
                    ];
                }
            }
        }
        else{
            $g_res = M('goods')->where("id in ($id)")->select();
            //echo M('goods')->getLastSql();exit;
            $gcode_list = [];
            $gcode_2_id = [];
            foreach($g_res as $val){
                $gcode_list[] = [
                    'gcode'=>$val['gcode']
                ];
                $gcode_2_id[$val['gcode']] = $val['id'];
            }
            //var_dump($gcode_2_id);
            $erp_res = $this->erp_price(json_encode($gcode_list), $sid);
            //var_dump($erp_res);
            $rs2 = [];
            foreach($erp_res as $key=>$val){
                $rs2[$gcode_2_id[$key]] = [
                    'price1'=>$val['out_price1'],
                    'price2'=>$val['out_price2'],
                    'price3'=>$val['out_price3'],
                    'price4'=>$val['out_price4'],
                ];
            }
        }

        $excel_data = [];
        $excel_data[] = ['商品名称','商品条码','单位','经销商','酒店饭店','商场超市','便利店'];
        foreach($goods_result as $val){
            $my_id = $val['id'];
            if(isset($rs2[$my_id])){
                $temp_rs = $rs2[$my_id];
            }
            else{
                $temp_rs = [
                    'price1'=>'0.00',
                    'price2'=>'0.00',
                    'price3'=>'0.00',
                    'price4'=>'0.00',
                ];
            }
            $excel_data[] = [$val['gcode'],$val['gname'],$val['unit'],$temp_rs['price1'],$temp_rs['price2'],
                $temp_rs['price3'],$temp_rs['price4']];
        }

        # 仓库信息
        $ck = $this->get_CK();
        $sname = '';
        foreach($ck as $val){
            if($val['id'] == $sid){
                $sname = $val['name'];
            }
        }

        //$excel_data[] = ['总计','','','','','',''];
        write_excel($excel_data, '售卖定价-'.$sname.'('.date('Y-m-d').')');
    }

    /**
     * 读取商品价格
     */
    public function shop_price(){
        $id = I('id','');
        $id = trim($id,',');

        $list = I('list',0,'intval');
        $sid = I('sid',0,'intval');
        $gtype = I('gtyp',0,'intval');

        $r['error'] = 1;
        $r['msg'] = '查询价格失败';
        $r['data'] = '';
        $model = M('shop_price');

        if($list === 1) {
            if (!empty($id)) {
                //$rs = $model->field("concat(cctype,'-',price)")->where('mgid in(%s)',array($ids))->group("mgid")->select();
                if($gtype != 1){
                    $where = "mgid in($id)";
                    if($sid){
                        $where .= " and sid=$sid";
                    }
                    $rs = $model->field('mgid,cctype,price,sid')->where($where)->select();
                    //echo $model->getLastSql();
                    $rs2 = [];
                    $gid_list = explode(',', $id);
                    foreach($rs as $k=>$v){
                        $rs2[$v['mgid']]['price'.$v['cctype']] = $v['price'];
                    }
                    foreach($gid_list as $v){
                        if(!isset($rs2[$v]) && $v){
                            $rs2[$v] = [
                                'price1'=>'0.00',
                                'price2'=>'0.00',
                                'price3'=>'0.00',
                                'price4'=>'0.00',
                            ];
                        }
                    }
                    if ($rs2) {
                        $r['error'] = 0;
                        $r['data'] = $rs2;
                        $this->ajaxReturn($r);
                    } else {
                        $r['error'] = 0;
                        $r['data'] = [];
                        return $this->ajaxReturn($r);
                    }
                }
                else{
                    $g_res = M('goods')->where("id in ($id)")->select();
                    //echo M('goods')->getLastSql();exit;
                    $gcode_list = [];
                    $gcode_2_id = [];
                    foreach($g_res as $val){
                        $gcode_list[] = [
                            'gcode'=>$val['gcode']
                        ];
                        $gcode_2_id[$val['gcode']] = $val['id'];
                    }
                    //var_dump($gcode_2_id);
                    $erp_res = $this->erp_price(json_encode($gcode_list), $sid);
                    //var_dump($erp_res);
                    $rs2 = [];
                    foreach($erp_res as $key=>$val){
                        $rs2[$gcode_2_id[$key]] = [
                            'price1'=>$val['out_price1'],
                            'price2'=>$val['out_price2'],
                            'price3'=>$val['out_price3'],
                            'price4'=>$val['out_price4'],
                        ];
                    }
                    $r['error'] = 0;
                    $r['data'] = $rs2;
                    return $this->ajaxReturn($r);
                }

            } else {
                $r['error'] = 0;
                $this->ajaxReturn($r);
            }
        }else{
            $ckid = I('ckid',0,'intval');
            $spid = I('spid',0,'intval');

            $gres = M('goods')->where("id=$id")->select();
            if($gres[0]['isbind']==0 && $gres[0]['pkgsize']==0){
                $gcode = $gres[0]['gcode'];
                $gcode_list[] = [
                    'gcode'=>$gcode
                ];

                $erp_res = $this->erp_price(json_encode($gcode_list), $ckid);
                //var_dump($erp_res);

                $r['error'] = 0;
                $r['data'] = ['price'=>$erp_res[$gcode]['out_price'.$spid]];
                $r['msg'] = '';
                return $this->ajaxReturn($r);
            }
            else{
                if($ckid && $spid && $id){
                    //$rs = $model->field("concat(cctype,'-',price)")->where('mgid in(%s)',array($ids))->group("mgid")->select();
                    $rs = $model->field('price')->where('mgid=%d AND sid=%d AND cctype=%d', array($id,$ckid,$spid))->find();
                    //echo $model->getLastSql();exit;
                    if ($rs) {
                        $r['error'] = 0;
                        $r['data'] = $rs;
                        $r['msg'] = '';
                        $this->ajaxReturn($r);
                    } else {
                        $r['error'] = 0;
                        $r['data'] = ['price'=>0];
                        $r['msg'] = '';
                        return $this->ajaxReturn($r);
                    }
                }else{
                    $this->ajaxReturn($r);
                }
            }
        }
    }

    public function shop_prices(){
        $id = I('id','');
        $id = trim($id,',');

        $ckid = I('ckid',0,'intval');

        $model = M('shop_price');
        $r['error'] = 1;
        $r['msg'] = '';
        $r['data'] = '';

        if($ckid && $id){
            $rs = $model->where('mgid=%d AND sid=%d', array($id,$ckid))->select();
            if ($rs) {
                $r['error'] = 0;
                foreach($rs as $k=>$v){
                    $r['data'][$v['cctype']] = $v['price'];
                }
                $this->ajaxReturn($r);
            } else {
                $r['error'] = 0;
                $r['data'] = json_encode([]);
                return $this->ajaxReturn($r);
            }
        }else{
            $this->ajaxReturn($r);
        }
    }

    /**
     * 读取ERP商品价格
     */

    public function erp_price($gcode, $sid){
        //$gcode = I('gcode',0,'intval');
        //$sid = I('sid',0,'intval');

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $price = post(C('API_ERP_B_PRICE_URL'),array('goods_list'=>$gcode,'cid'=>$company_id,'sid'=>$sid));
        $price = json_decode($price,true);
        if($price['err'] == 0){
            //$this->r['data'] = $price['msg'];
            return $price['msg'];
            //$this->ajaxReturn($this->r);
        }else{
            $this->r['error'] = 1;
            $this->r['msg'] = '读取参考价格失败';
            $this->ajaxReturn($this->r);
        }

    }

    /**
     * 展示单品价格
     */
    public function show_goods_price(){
        $mgid = I('mgid',0,'intval');
        $company = $this->get_company();
        $company_id = $company['id'];
        $good_data = $this->good($mgid);
        //var_dump($good_data);exit;
//        $res = M('shop_price')->where("mgid=$mgid and company_id=$company_id")->select();
//        $price_ck = [];
//        foreach($res as $v){
//            if(!in_array($v['sid'], $price_ck)){
//                $price_ck[] = $v['sid'];
//            }
//        }

        // 页面价格初始化数据
        // 仓库
        $ck = $this->get_CK();
        $mck = explode(',',$good_data['sids']);
        $json_ck = "var ck = [";
        foreach($ck as $val){
            if(in_array($val['id'],$mck)){
                $json_ck.='{"id":"'.$val['id'].'","name":"'.$val['name'].'"},';
            }
        }
        $json_ck = rtrim($json_ck,',')."];";

        // 客户
        $msp = explode(',',$good_data['cctype']);
        $json_sp = "var sp = [";
        foreach(C('CUSTOMER_TYPE') as $val){
            if(in_array($val['id'],$msp)){
                $json_sp.='{"id":"'.$val['id'].'","name":"'.$val['name'].'"},';
            }
        }
        $json_sp = rtrim($json_sp,',')."];";



        $this->assign('json_ck',$json_ck);
        $this->assign('json_sp',$json_sp);
        $this->assign('sp',C('CUSTOMER_TYPE'));
        $this->assign('ck',$ck);
        //$this->assign('price_ck',json_encode($price_ck));
        $this->assign('good', $good_data);
        $this->display('Sale:fixed_price');
    }

    /**
     * 调整商品价格
     */
    public function fix_price_byck(){
        $price = I('price','','');
        $mgid = I('mgid',0,'intval');
        $gid = I('gid',0,'intval');
        $sid = I('sid',0,'intval');


        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        if ($price) {
            $price = json_decode($price, true);
            $model = M('shop_price');
            $model->startTrans();

            foreach ($price['data'] as $v) {
                $this->_fix_price($model, $mgid, $gid, $sid, $company_id,$v['ck'],$v['price']);
            }

            $model->commit(); //成功则提交


            # 更改商品表状态
            ($model->table(C('DB_PREFIX') . 'goods')->where('id=%d', array($mgid))->setField('shop_price', 1));

            $this->r['error'] = 0;
            $this->r['msg'] = '更新价格成功';
            $this->ajaxReturn($this->r);
        } else {
            $this->r['msg'] = '更新价格失败';
            $this->ajaxReturn($this->r);
        }
    }

    /**
     *
     */
    public function fix_price(){
        $id = I('id',0,'intval');
        $ck = I('ck','');
        $sp = I('sp','');
        $price_style = I('price_style','0');
        $pricelist = I('pricelist','');

        $ck = rtrim($ck,',');
        $sp = rtrim($sp,',');

        $model = M('goods');

        $d['cctype'] = $sp;
        $d['sids'] = $ck;
        $d['price_style'] = $price_style;
        // 更新仓库信息
        $model->where('id=%d',array($id))->save($d);

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $pricelist = rtrim($pricelist,',');
        if($pricelist){
            $pricelist = explode(',',$pricelist);
            foreach($pricelist as $val){
                $val_list = explode('-',$val);
                if(intval($val_list[2]*100) == 0){
                    $this->r['error'] = 1;
                    $this->r['msg'] = '价格不能为0';
                    $this->ajaxReturn($this->r);
                }
            }
        }

        $pricelist = I('pricelist','');
        $gres = $model->where('id=%d',array($id))->select();
        if($gres[0]['isbind']==0 && $gres[0]['pkgsize']==0){
            //如果是基础商品，到ERP进行价格修改
            $pricelist = rtrim($pricelist,',');
            if($pricelist){
                $pricelist = explode(',',$pricelist);
                $price_list = [];
                foreach($pricelist as $val){
                    $val_list = explode('-',$val);
                    $price_list[] = [
                        'sid'=>$val_list[0],
                        'cctype'=>$val_list[1],
                        'price'=>$val_list[2]
                    ];
                }
                $param = [
                    'cid'=>$company_id,
                    'gid'=>$gres[0]['gid'],
                    'price_list'=>json_encode($price_list)
                ];
                $res = post(C('API_ERP_PRICE_CHG_URL'),$param);
                $res = json_decode($res,true);
                if($res['err'] != 0){
                    $this->r['error'] = 1;
                    $this->r['msg'] = '修改价格失败';
                    $this->ajaxReturn($this->r);
                }
            }
        }
        else{
            //大包装和捆绑商品，到商城后台修改
            $pricelist = rtrim($pricelist,',');
            if($pricelist){
                $price = explode(',',$pricelist);
            }
            else{
                $price = [];
            }
            foreach($price as $val){
                $_s = explode('-',$val);
                if(!$_s[2] || intval($_s[2]*100)==0){
                    $this->r['error'] = 1;
                    $this->r['msg'] = '价格不能为空或为0';
                    $this->ajaxReturn($this->r);
                }
            }

            // 更新价格信息
            $model = M('shop_price');
            $model->startTrans();
            foreach($price as $val){
                $_s = explode('-',$val);
                if(!$_s[2] || intval($_s[2]*100)==0){
                    $this->r['error'] = 0;
                    $this->r['msg'] = '更新价格成功';
                }
                // gid只是冗余，此处设置为0
                $this->_fix_price($model,$id,0,$_s[0],$company_id,$_s[1],$_s[2]);
            }
            $model->commit(); //成功则提交
        }

        //写价格历史表
        $pricelist = I('pricelist','');
        $pricelist = rtrim($pricelist,',');
        if($pricelist){
            $pricelist = explode(',',$pricelist);
            foreach($pricelist as $val){
                $val_list = explode('-',$val);
                M('shop_price_history')->add([
                    'gid' => $gres[0]['gid'],
                    'mall_gid' => $id,
                    'cid' => $company_id,
                    'sid' => $val_list[0],
                    'uid' => session('aid'),
                    'cctype' => $val_list[1],
                    'price' => $val_list[2],
                    'createtime' => date('Y-m-d H:i:s')
                ]);
            }
        }

        $this->r['error'] = 0;
        $this->r['msg'] = '更新价格成功';
        $this->ajaxReturn($this->r);

    }

    /**
     * @param $model
     * @param $mgid
     * @param $gid
     * @param $sid
     * @param $company_id
     * @param $cctype
     * @param $price
     */

    private function _fix_price($model,$mgid,$gid,$sid,$company_id,$cctype,$price){
        $d['mgid'] = $mgid;
        $d['gid'] = $gid;
        $d['sid'] = $sid;
        $d['company_id'] = $company_id;
        $d['create_time'] = $d['update_time'] = date("Y-m-d H:i:s", time());
        $d['cctype'] = $cctype;
        $d['price'] = $price;
        $where = sprintf("company_id=%d AND mgid=%d AND cctype=%d AND sid=%d",
            $company_id, $mgid, $d['cctype'], $sid);

        if ($model->table(C('DB_PREFIX') . 'shop_price')->where($where)->find()) {
            $model->table(C('DB_PREFIX') . 'shop_price')->where($where)->setField('price', $d['price']);
        } else {
            $model->table(C('DB_PREFIX') . 'shop_price')->add($d);
        }
    }
}
