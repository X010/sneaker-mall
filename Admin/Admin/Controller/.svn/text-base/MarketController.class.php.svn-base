<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

class MarketController extends BaseController{

    private $r = array("error"=>0,"msg"=>"","data"=>"");

    public function index(){
        $this->display('Index:main');
    }

    public function market_list(){
        $search_key = I('key','');
        $p = I('p',0,'intval');

        $company = $this->get_company();
        $company_id = $company['id'];

        $db_where = "company_id=$company_id";
        if(!empty($search_key)){
            //$option['where']['_string'] = 'title like "%' .$key. '%" OR description like "%' .$key. '%"';
            $db_where .= " and (title like '%$search_key%' or description like '%$search_key%')";
        }

        $count = M('market')->where($db_where)->count();
        $limit = 15;
        if($p){
            $start = $limit * ($p-1);
        }
        else{
            $start = 0;
        }

        $res = M('market')->where($db_where)->limit($start,$limit)->order('start_time DESC')->select();

        foreach($res as $key=>$val){
            $res[$key]['scope'] = $val['scope']==0?'商品':'';
            $res[$key]['mtype'] = $val['mtype']==1?'买赠':'';
        }

        $this->assign('market_list',$res);

        # 分页
        $Page = new \Extend\Page($count, $limit, ['key'=>$search_key]);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $this->assign('current_menu',I('current_menu',''));
        $this->assign('search_key',$search_key);
        $this->assign('page',$show);
        $this->display('Market:mlist');
    }

    /**
     * 编辑策略
     */
    public function gone(){
        # 管理平台的策略ID
        $id = I('id',0,'intval');
        if($id){
            $m_data = M('market')->where("id=$id")->select()[0];
            if($_POST){
                $glist = I('giving_goods','');
                $gname = I('giving_goods_title','');
                $gnum = I('zgoods_num','');
                $gcode = I('giving_goods_code','');
                $timer = I('timer','');
                $des = I('des','');
                $title = I('title','');

                $ti = explode(' - ',$timer);
                $stime = trim($ti[0]);
                $etime = trim($ti[1]);
                if(!empty($glist)){
                    $model = M('market');
                    $d['start_time'] = $stime;
                    $d['end_time'] = $etime;
                    $d['scope'] = 0;
                    $d['mtype'] = 1;
                    $d['description'] = $des;
                    $d['title'] = $title;

                    $gl = array();
                    foreach($glist as $key=>$val){
                        $g['gname'] = $gname[$key];
                        $g['total'] = $gnum[$key];
                        $g['mgid'] = $val;
                        $g['gcode'] = $gcode[$key];
                        array_push($gl,$g);
                    }
                    $d['strategy'] = json_encode($gl);
                    if($model->where("id=$id")->save($d)){
                        $this->redirect(U('market/market_list'));
                    }
                    else{
//                        $this->r['error'] = 1;
//                        $this->r['msg'] = '策略无改动';
//                        $this->ajaxReturn($this->r);
                        $this->error('策略无改动');
                    }
                }else{
//                    $this->r['error'] = 1;
//                    $this->r['msg'] = '修改策略建立失败';
//                    $this->ajaxReturn($this->r);
                    $this->error('修改策略建立失败');
                }

            }else {
                if ($m_data) {
                    $this->assign('market', $m_data);
                    $this->display('Market:giving_edit');
                }
            }
        }else{
            $this->error('非法操作');
        }
    }

    /**
     * [{"gname":"iphone6","total":1,"mgid":12,"gcode":"102022"}]
     */
    public function add_giving_strgy(){
        $glist = I('giving_goods','');
        $gname = I('giving_goods_title','');
        $gnum = I('zgoods_num','');
        $gcode = I('giving_goods_code','');
        $timer = I('timer','');
        $des = I('des','');
        $title = I('title','');
        $iconname = I('iconname','');
        $company = $this->get_company();
        $company_id = $company['id'];

        $ti = explode(' - ',$timer);
        $stime = trim($ti[0]);
        $etime = trim($ti[1]);
        if(!empty($glist)){
            $model = M('market');
            //$d['start_time'] = date('Y-m-d H:i:s',strtotime($stime));
            //$d['end_time'] = date('Y-m-d H:i:s',strtotime($etime));
            $d['start_time'] = $stime;
            $d['end_time'] = $etime;
            $d['scope'] = 0;
            $d['mtype'] = 1;
            $d['description'] = $des;
            $d['title'] = $title;
            $d['iconName'] = $iconname;
            $d['company_id'] = $company_id;

            $gl = array();
            foreach($glist as $key=>$val){
                $g['gname'] = $gname[$key];
                $g['total'] = $gnum[$key];
                $g['mgid'] = $val;
                $g['gcode'] = $gcode[$key];
                array_push($gl,$g);
            }
            $d['strategy'] = json_encode($gl);
            if($model->add($d)){
                $this->success('新建成功');
            }
        }else{
            $this->r['error'] = 1;
            $this->r['msg'] = '赠品建立失败';
            $this->ajaxReturn($this->r);
        }
    }

    /**
     *
     */
    /**
     * 新建买赠商品
     */
    public function giving(){

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
                    $this->redirect(U('Market/giving'));
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

        $this->assign('current_menu',I('current_menu',''));

        $this->assign('customer',C('CUSTOMER_TYPE'));
        $this->assign('moudle_name', C('LANG_MOUDLE_SALE'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_GIVING'));

        $this->display('Market:giving');
    }

    public function search(){
        $key = I('key','');
        $company = $this->get_company();
        $company_id = $company['id'];
        if(!empty($key)){
            $where = '(title like "%' .$key. '%" OR description like "%' .$key. '%") and company_id='. $company_id;
            $model = M('market');
            $rs = $model->where($where)->limit(20)->select();
            $this->r['data'] = $rs;
            $this->ajaxReturn($this->r);
        }else{
            $this->r['error'] = 1;
            $this->r['msg'] = '没有查询到策略';
            $this->ajaxReturn($this->r);
        }
    }

    public function delete(){
        $id = I('id','');
        if(!$id){
            $this->error('非法操作');
        }

        $g_model = M('goods')->where("marketid like '%$id%'")->select();
        foreach($g_model as $val){
            $temp_market_id = $val['marketid'];
            $marketid_list = explode(',',$temp_market_id);
            if(in_array($id, $marketid_list)){
                $new_marketid_list = [];
                foreach($marketid_list as $marketid){
                    if($marketid != $id){
                        $new_marketid_list[] = $marketid;
                    }
                }
                if($new_marketid_list){
                    $new_marketid = implode(',',$new_marketid_list);
                }
                else{
                    $new_marketid = "";
                }
                M('goods')->where("id=".$val['id'])->save(['marketid'=>$new_marketid]);
            }
        }
        M('market')->where(['id'=>$id])->delete();
        $this->success('删除成功',U('market/market_list'));
    }

}
