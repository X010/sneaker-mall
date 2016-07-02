<?php
namespace Admin\Controller;

use Think\Controller;

class GoodsController extends BaseController
{

    private $r = array("error" => 0, "msg" => "", "data" => "");

    public function index()
    {
        $this->display('Goods:index');
    }

    public function gsearch()
    {
        $key = I('key', '');
        $gtyp = I('gtyp', 0, 'intval');
        $r['error'] = 0;
        $r['msg'] = '成功';
        $r['data'] = '';

        if ($key) {

            $option = array();
            if ($key) {
                $w['_string'] = 'gname like "%' . $key . '%" OR barcode like "%' . $key . '%"';
                $option['where'] = $w;
            }

            if ($gtyp == 1) {
                $option['where']['isbind'] = 0;
                $option['where']['pkgsize'] = 0;
            } else if ($gtyp == 2) {
                $option['where']['isbind'] = 1;
                $option['where']['pkgsize'] = 0;
            } else if ($gtyp == 3) {
                $option['where']['isbind'] = 0;
                $option['where']['pkgsize'] = 1;
            } else if ($gtyp == 4) {
                $option['where']['flagdel'] = 1;
            } else if ($gtyp == 5) {
                $option['where']['flagdel'] = 0;
            }

            $option['limit'] = 15;
            $goods_result = $this->goods($option);
            $r['data'] = $goods_result;
            $this->ajaxReturn($r);
        } else {
            $r['error'] = 1;
            $r['msg'] = '请填写关键词';
            $this->ajaxReturn($r);
        }

    }

    /**
     * 捆绑商品搜索
     */
    public function bind_search()
    {
        $key = I('key', '');
        if (!empty($key)) {
            $option['where']['_string'] = 'gname like "%' . $key . '%" OR barcode like "%' . $key . '%"';
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 0;
            $option['limit'] = 20;
            $rs = $this->goods($option);
            $this->r['data'] = $rs;
            $this->ajaxReturn($this->r);
        } else {
            $this->ajaxReturn($this->r);
        }

    }

    /**
     * 大小包装商品搜索
     */
    public function pkg_search()
    {
        $this->bind_search();
    }


    /**
     * 商品列表
     *
     */
    public function glist()
    {
        $key = I('key', '');
        $p = I('p', 0, 'intval');
        //$del = I('del',0,'intval');
        //$bind = I('bind',0,'intval');
        //$pub = I('pub',0,'intval');
        $gtyp = I('gtyp', 0, 'intval');
        $cctype = I('cctype', 0, 'intval');

        $option = array();
        //$db_where = ' flagdel=0';
        $db_where = " 1=1";
        if ($key) {
            $db_where .= ' and (gname like "%' . $key . '%" OR barcode like "%' . $key . '%")';
//            $w['_string'] = 'gname like "%' .$key. '%" OR barcode like "%' .$key. '%"';
//            $option['where'] = $w;
        }
        # 默认只取没被删除的
        if ($cctype) {
            $db_where .= ' and cctype like "%' . $cctype . '%"';
        }

        $option['where']['_string'] = $db_where;
        # 分页
        $option['limit'] = 15;
        if ($p) $option['start'] = $option['limit'] * ($p - 1);

        //if($pub) $option['where']['publish'] = 1;


        $option2 = $option;
        $goods_result0 = $this->goods($option2, True);

        $option['where']['flagdel'] = 0;

        $option2 = $option;
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

        $option2 = $option;
        $option2['where']['flagdel'] = 1; //取删除的
        $goods_result4 = $this->goods($option2, True);

        if ($gtyp == 1) {
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 0;
        } else if ($gtyp == 2) {
            $option['where']['isbind'] = 1;
            $option['where']['pkgsize'] = 0;
        } else if ($gtyp == 3) {
            $option['where']['isbind'] = 0;
            $option['where']['pkgsize'] = 1;
        } else if ($gtyp == 4) {
            $option['where']['flagdel'] = 1;
        }

        $option['order'] = "  top DESC, `publish` DESC, `order` DESC, tid DESC, id DESC";
        $goods_result = $this->goods($option);

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        # 得到栏目
        $coll = M('cate')->where('company_id=%d AND type=1 AND publish = 1', array($company_id))->select();

        # 分页
        $Page = new \Extend\Page($goods_result['count'], $option['limit']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        //$huodong = M('goods')->where('company_id=%d AND publish=1 AND isbind=1',array($company_id))->count();
        //$is_pub = M('goods')->where('company_id=%d AND publish=1',array($company_id))->count();
        //$is_del = M('goods')->where('company_id=%d AND flagdel=1',array($company_id))->count();
        //$this->assign('huodong',$huodong);
        //$this->assign('ispub',$is_pub);
        //$this->assign('isdel',$is_del);
        $this->assign('current_menu', I('current_menu', ''));
        $this->assign('key', $key);
        $this->assign('coll', $coll);
        $this->assign('total', $goods_result['count']);
        $this->assign('goods', $goods_result['data']);
        $this->assign('page', $show);
        $this->assign('p', $p);
        $this->assign('gtyp', $gtyp);
        $this->assign('customer', C('CUSTOMER_TYPE'));
        $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
        $this->assign('action_name', C('LANG_GOODS_ACTION_LIST'));
        $this->assign('total0', $goods_result0['count']);
        $this->assign('total1', $goods_result1['count']);
        $this->assign('total2', $goods_result2['count']);
        $this->assign('total3', $goods_result3['count']);
        $this->assign('total4', $goods_result4['count']);
        $this->display('Goods:glist');
    }

    /**
     * 置顶
     */
    public function top()
    {
        $id = I('id', 0, 'intval');
        $this->_top($id, true);
    }

    /**
     * 取消置顶
     */
    public function untop()
    {
        $id = I('id', 0, 'intval');
        $this->_top($id, false);
    }

    protected function _top($id, $top)
    {
        $model = M('goods');
        if ($id) {
            if ($top) {
                //$dat['toptime'] = date('Y-m-d H:i:s',time());
                $dat['top'] = 1;
            } else {
                //$dat['toptime'] = null;
                $dat['top'] = 0;
            }
            if ($model->where("id=$id")->save($dat)) {
                $this->redirect($_SERVER["HTTP_REFERER"]);
            } else {
                $this->error('操作失败1');
            }
        } else {
            $this->error('操作失败2');
        }

    }

    /**
     * 编辑商品
     */
    public function gone()
    {
        # 管理平台的商品ID
        $good_id = I('id', 0, 'intval');
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];
        if ($good_id) {
            $good_data = $this->good($good_id);
            if ($_POST) {
                $supplier = I('supplier', array());
                $ck = I('ck', array());
                $imgmodel = I('imgmodel', 0, 'intval');
                $qs = $_REQUEST['qs'];
                $main_goods_tid = I('tid', 0, 'intval');
                $main_goods_tcode = I('tcode', '');
                if ($imgmodel) {
                    $good_data_edit['gphoto'] = I('imgid', 0, 'string');
                } else {
                    $good_data_edit['gphoto'] = I('gphoto', 0, 'intval');
                }
                $good_data_edit['cctype'] = implode(',', $supplier);
                if ($ck) {
                    $good_data_edit['sids'] = implode(',', $ck);
                } else {
                    $good_data_edit['sids'] = Null;
                }

                $good_data_edit['gname'] = I('gname', $good_data['gname']);
                $good_data_edit['spec'] = I('spec', $good_data['spec']);
                $good_data_edit['unit'] = I('unit', $good_data['unit']);
                $good_data_edit['retail_price'] = I('retail_price', $good_data['retail_price']);
                $good_data_edit['tid'] = $main_goods_tid;
                $good_data_edit['tcode'] = $main_goods_tcode;
                //$good_data_edit['gcode'] = I('gcode','');

                $stgy = I('stgy', '');
                if (!empty($stgy)) {
                    $good_data_edit['marketid'] = implode(',', $stgy);
                } else {
                    $good_data_edit['marketid'] = Null;
                }
                $good_data_edit['content'] = I('content', '');

                //$good_data_edit['content'] = I('content','');
                //$good_data_edit['sale_text'] = I('sale_text','');
                M('goods')->where('id=%d', array($good_id))->save($good_data_edit);

                # 加入捆绑商品
                $model = M('goods_bind');
                $bind_goods = I('bind_goods', '');
                $bind_goods_num = I('bind_goods_num', '');
                if ($bind_goods) {
                    # 写入主商品
                    $model->where("mgid=$good_id")->delete();
                    foreach ($bind_goods as $key => $val) {
                        $bind_m_data['mgid'] = $good_id;
                        $bind_m_data['num'] = $bind_goods_num[$key];
                        $bind_m_data['memo'] = '主商品';
                        $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                        $bind_m_data['child_mgid'] = $val;
                        $model->add($bind_m_data);
                    }
                }
                $this->redirect(U('goods/glist', $qs));
            } else {
                if ($good_data) {
                    # 先选取捆绑商品
                    if ($good_data['isbind']) {
                        $bind_good = M('goods_bind')->where('db_goods_bind.mgid=%d AND giveaway=0', array($good_id))->join('LEFT JOIN db_goods ON db_goods.id=db_goods_bind.child_mgid')->find();
                        $good_data['gphoto_index'] = $bind_good['gphoto_index'];
                    }
                    $type_data = $this->gtype();
                    // 读取策略
                    if (!empty($good_data['marketid']))
                        $stgy = M('market')->where('id in(%s)', array(trim($good_data['marketid'], ',')))->select();
                    else
                        $stgy = '';

                    $ck = $this->get_CK();

                    // 页面价格初始化数据
                    // 仓库
                    $mck = explode(',', $good_data['sids']);
                    $json_ck = "var ck = [";
                    foreach ($ck as $val) {
                        if (in_array($val['id'], $mck)) {
                            $json_ck .= '{"id":"' . $val['id'] . '","name":"' . $val['name'] . '"},';
                        }
                    }
                    $json_ck = rtrim($json_ck, ',') . "];";

                    // 客户
                    $msp = explode(',', $good_data['cctype']);
                    $json_sp = "var sp = [";
                    foreach (C('CUSTOMER_TYPE') as $val) {
                        if (in_array($val['id'], $msp)) {
                            $json_sp .= '{"id":"' . $val['id'] . '","name":"' . $val['name'] . '"},';
                        }
                    }
                    $json_sp = rtrim($json_sp, ',') . "];";

                    $goods_bind = [];
                    if ($good_data['isbind']) {
                        $goods_bind = M('goods_bind')->where("mgid=" . $good_data['id'])->select();
                        foreach ($goods_bind as $key => $val) {
                            $temp_res = M('goods')->where("id=" . $val['child_mgid'])->select();
                            $goods_bind[$key]['gname'] = $temp_res[0]['gname'];
                            $goods_bind[$key]['publish'] = $temp_res[0]['publish'];
                        }
                        $good_data['retail_price'] = '（无）';
                    }
                    $small_gname = "";
                    if ($good_data['pkgsize']) {
                        $temp_res = M('goods')->where("gid=" . $good_data['gid'] . " and pkgsize=0")->select();
                        $small_gname = $temp_res[0]['gname'];
                        $good_data['retail_price'] = '（同基础商品）';
                    }
                    if ($good_data['content']) {
                        $good_data['content'] = htmlspecialchars_decode($good_data['content']);
                    }

                    # ERP分类

                    $tp = '';
                    $list = getTypeList($tp, $company_id, C('DEFAULT_TYPE_DEEP'));

                    $this->assign('json_ck', $json_ck);
                    $this->assign('json_sp', $json_sp);
                    $this->assign('stgy', $stgy);
                    $this->assign('gtype', $type_data);
                    $this->assign('sp', C('CUSTOMER_TYPE'));
                    $this->assign('ck', $ck);
                    $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
                    $this->assign('action_name', C('LANG_GOODS_ACTION_ONE'));
                    $this->assign('good', $good_data);
                    $this->assign('tlist', $list);
                    $this->assign('goods_bind', $goods_bind);
                    $this->assign('small_gname', $small_gname);
                    //var_dump($goods_bind);
                    $this->display('Goods:gedit');
                }
            }
        } else {
            $this->error('非法操作');
        }
    }

    /**
     * 批量上架商品
     */
    public function pub_all()
    {
        $ids = I('id', '');
        $r['error'] = 0;
        $r['msg'] = '批量上架成功';
        $r['data'] = '';
        if (is_array($ids)) {
            $m = M('goods');
            $ids = implode(',', $ids);
            $where['id'] = array('in', $ids);
            if ($m->where($where)->setField('publish', 1)) {
                //echo $m->getLastSql();
                $this->ajaxReturn($r);
            } else {
                //echo $m->getLastSql();
                $r['error'] = 1;
                $r['msg'] = '批量上架失败';
                $this->ajaxReturn($r);
            }
        } else {
            $r['error'] = 1;
            $r['msg'] = '非法操作';
            $this->ajaxReturn($r);
        }
    }

    /**
     * 上架商品
     */
    public function pub()
    {
        //$ref = I('ref',0,'intval');
        $good_id = I('id', 0, 'intval');
        $r['error'] = 1;
        $r['msg'] = '上架失败';
        $r['data'] = '';
        if ($good_id) {
            $reserve = I('reserve', 0, 'intval');
            $goods_res = M('goods')->where("id=$good_id")->find();
            //大包装或者捆绑要判断价格
            if ($goods_res['is_bind'] == 1 || $goods_res['pkgsize'] == 1) {
                $cctypes = explode(',', $goods_res['cctype']);
                $sids = explode(',', $goods_res['sids']);
                foreach ($cctypes as $cctype) {
                    foreach ($sids as $sid) {
                        $temp = M('shop_price')->where("cctype=$cctype and sid=$sid and mgid=$good_id")->find();
                        if (!$temp || intval($temp['price'] * 100) == 0) {
                            //$this->error('存在价格为0的组合，不允许上架');
                            $r['msg'] = '存在价格为0的组合，不允许上架';
                            $this->ajaxReturn($r);
                        }
                    }
                }

            }
            //$good_data = M('goods')->where("id=$good_id")->select();
            if (M('goods')->where('id=%d', array($good_id))->setField(['publish' => 1, 'reserve' => $reserve])) {
                /*if($ref){
                    //$this->success('发布商品成功', $_SERVER['HTTP_REFERER']);
                    $this->redirect($_SERVER['HTTP_REFERER']);
                }else{
                    //$this->success('发布商品成功', U('goods/glist'));
                    $this->redirect(U('goods/glist'));
                }*/
                $r['error'] = 0;
                $r['msg'] = '上架成功';
            } else {
                //$this->error('上架失败');
            }
        } else {
            //$this->error('非法操作');
            $r['msg'] = '请指定商品';
        }
        $this->ajaxReturn($r);
    }

    /**
     * 批量下架商品
     */
    public function unpub_all()
    {
        $ids = I('id', '');
        $r['error'] = 0;
        $r['msg'] = '批量下架成功';
        $r['data'] = '';
        if (is_array($ids)) {
            $m = M('goods');
            foreach ($ids as $k => $v) {
                if ($this->checkbindgoods($v) > 0) {
                    array_splice($ids, $k, 1);
                }
            }
            $where['id'] = array('in', $ids);
            if ($m->where($where)->setField('publish', 0)) {
                $this->ajaxReturn($r);
            } else {
                $r['error'] = 1;
                $r['msg'] = '批量下架失败';
                $this->ajaxReturn($r);
            }
        } else {
            $r['error'] = 1;
            $r['msg'] = '非法操作';
            $this->ajaxReturn($r);
        }
    }

    /**
     * 下架商品
     */
    public function unpub()
    {
        //$ref = I('ref',0,'intval');
        $good_id = I('id', 0, 'intval');
        $r['error'] = 1;
        $r['msg'] = '下架失败';
        $r['data'] = '';
        if ($this->checkbindgoods($good_id) > 0) {
            //$this->success('不能下架，请先下架相关的捆绑和大包装商品', U('goods/glist'));
            $r['msg'] = '不能下架，请先下架相关的捆绑和大包装商品';
        } else {
            if ($good_id) {
                if (M('goods')->where('id=%d', array($good_id))->setField('publish', 0)) {
                    /*if($ref){
                        //$this->success('发布商品成功', $_SERVER['HTTP_REFERER']);
                        $this->redirect($_SERVER['HTTP_REFERER']);
                    }else{
                        //$this->success('发布商品成功', U('goods/glist'));
                        $this->redirect(U('goods/glist'));
                    }*/
                    $r['error'] = 0;
                    $r['msg'] = '下架成功';
                } else {
                    //$this->error('下架失败');
                }
            } else {
                $r['msg'] = '请指定商品';
                //$this->error('非法操作');
            }
        }

        $this->ajaxReturn($r);
    }

    /**
     * 批量删除
     */
    public function del_all()
    {
        $ids = I('id', '');
        $r['error'] = 0;
        $r['msg'] = '批量删除成功';
        $r['data'] = '';
        if (is_array($ids)) {
            $m = M('goods');
            /**foreach($ids as $k=>$v){
             * if($this->checkbindgoods($v) > 0){
             * array_splice($ids,$k,1);
             * }
             * }**/
            $where['id'] = array('in', $ids);
            //$d['publish'] = 0;
            $d['flagdel'] = 1;
            if ($m->where($where)->save($d)) {
                $this->ajaxReturn($r);
            } else {
                $r['error'] = 1;
                $r['msg'] = '批量删除失败';
                $this->ajaxReturn($r);
            }
        } else {
            $r['error'] = 1;
            $r['msg'] = '非法操作';
            $this->ajaxReturn($r);
        }
    }

    /**
     * 恢复删除
     * @params id 商品ID
     */
    public function redel()
    {
        $id = I('id', 0, 'intval');
        if ($id) {
            if (M('goods')->where('id=%d', array($id))->setField('flagdel', 0)) {
                $this->success('恢复成功');
            } else {
                $this->error('恢复失败');
            }
        } else {
            $this->error('恢复出错');
        }
    }

    /**
     * 查看主商品是否有绑定的商品存在
     */
    protected function checkbindgoods($id)
    {
        $c = 0;
        $e = M('goods')->where('id=%d', array($id))->find();
        if ($e and $e['isbind'] == '0')
            $c = M('goods')->where('gid=%d AND isbind=1 AND publish=1', array($e['gid']))->count();
        return $c;
    }

    /**
     * 改变商品栏目
     */
    public function ucate()
    {
        $id = I('id', 0, 'intval');
        $cid = I('cid', 0, 'intval');
        $dis = I('dis', 0, 'intval');
        $r['error'] = 0;
        $r['msg'] = '设置栏目成功';
        $r['data'] = '';
        if ($id && $cid) {
            if ($dis == 1) $cid = 0;
            if (M('goods')->where('id=%d', array($id))->setField('cateid', $cid)) {
                $this->ajaxReturn($r);
            } else {
                $r['error'] = 1;
                $r['msg'] = '设置栏目失败';
                $this->ajaxReturn($r);
            }
        } else {
            $r['error'] = 1;
            $r['msg'] = '设置栏目时出现错误';
            $this->ajaxReturn($r);
        }
    }

    /**
     * 取得排序好的分类
     * @param int $id 如果ID存在则取一条分类信息
     * @return array|mixed
     */
    public function gtype($id = 0)
    {
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $model = M('goods_type');

        $where['company_id'] = $company_id;
        if ($id) {
            $where['id'] = $id;
            $type_data = $model->where($where)->find();
            return $type_data;
        } else {
            $type_data = $model->where($where)->select();
            return getSortedCategory($type_data);
        }


    }

    /**
     * 取得分类列表
     */
    public function tlist()
    {
        /**
         * $type_data = $this->gtype();**/

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        # ERP分类
        $url = C('API_TYPE_URL') . '?cid=' . $company_id;
        $tp = file_get_contents($url);

        $tp = json_decode($tp, true);

        if ($tp && $tp['status'] === 200 && $tp['data'] && !empty($tp['data'])) {
            $list = getTypeList($tp['data'], $company_id, C('DEFAULT_TYPE_DEEP'));
            // 获取二级分类
            /**$type_data = $tp['data'];
             *
             * foreach($type_data as $key=>$val){
             * $url = C('API_TYPE_URL').'?cid='.$company_id.'&code='.$val['code'];
             * $tp_2 = file_get_contents($url);
             * $tp_2 = json_decode($tp_2, true);
             * if($tp && !empty($tp['data'])){
             * $type_data[$key]['data'] = $tp_2['data'];
             *
             *
             * //
             * }
             * }
             **/
            $setting = M('company_setting')->where('company_id=%d', $company_id)->find();
            $types = M('goods_type')->where('company_id=%d', $company_id)->select();
            $_types = array();
            foreach ($types as $key => $val) {
                array_push($_types, $val['tid']);
            }
            $this->assign('current_menu', I('current_menu', ''));
            $this->assign('typesid', $_types);
            $this->assign('types', $types);
            $this->assign('level_setting', $setting);
            $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
            $this->assign('action_name', C('LANG_GOODS_ACTION_TYPE'));
            $this->assign('typelist', $list);
            $this->display('Goods:tlist');
        } else {
            $this->error('分类API出现错误', U('login/sign-in'));
            exit;
        }


    }

    public function tsortlist()
    {
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];
        $dat['error'] = 1;
        $dat['msg'] = '';
        $dat['data'] = '';

        $types = M('goods_type')->where('company_id=%d', $company_id)->select();

        if (is_array($types)) {

            foreach ($types as $k => $v) {
                $vals[$k] = $v['csort'];
            }
            array_multisort($vals, SORT_ASC, $types);

            $dat['error'] = 0;
            $dat['data'] = $types;
            $dat['status'] = 'success';
            $this->ajaxReturn($dat);
        } else {
            $this->ajaxReturn($dat);
        }
    }

    /**
     *
     */
    public function type_show_edit()
    {

        if (IS_POST) {
            $show_level = I('define_level', 1, 'intval');
            //$show_type = I('level_'.$show_level.'_show');
            $order = $_POST['order'];
            $my_data = json_decode($order, true);
            if ($my_data) {
                # 供应商ID
                $company = $this->get_company();
                $company_id = $company['id'];

                $model = M('company_setting');

                $seting['company_id'] = $company_id;
                $seting['ckey'] = 'show_cate_layout';
                $seting['cvalue'] = $show_level;

                if ($model->where('company_id=%d', $company_id)->find()) {
                    $model->where('company_id=%d', $company_id)->setField('cvalue', $show_level);
                } else {
                    $model->add($seting);
                }

                $tp = '';
                $list = getTypeList($tp, $company_id, C('DEFAULT_TYPE_DEEP'));
                // 清除所有历史数据
                $model = M('goods_type');
                $model->where('company_id=%d', $company_id)->delete();
                //$sorts = json_decode($order, true);


                foreach ($my_data as $key => $val) {
                    $typ['company_id'] = $company_id;
                    $typ['tid'] = $key;
                    $typ['status'] = 1;
                    $typ['csort'] = $val;

                    $tdata = $this->get_data_by_tid($list, $key);
                    //var_dump($key);
                    //var_dump($tdata);exit;
                    $typ['tcode'] = $tdata['code'];
                    $typ['name'] = $tdata['name'];
                    $model->add($typ);
                }
                //$this->success('操作成功',U('goods/tlist'));
                $this->redirect(U('goods/tlist'));

            } else {
                $this->error('请选择分类以后再保存');
            }
        } else {
            $this->error('非法操作');
        }
    }

    public function get_data_by_tid($data, $tid)
    {
        foreach ($data as $val) {
            if ($val['id'] == $tid) {
                return $val;
            }
            if (isset($val['data']) && $val['data']) {
                $res = $this->get_data_by_tid($val['data'], $tid);
                if ($res) {
                    return $res;
                }
            }
        }
        return False;
    }

    /**
     * 添加新分类
     */
    public function type_edit()
    {

        $tid = I('id', 0, 'intval');

        if (IS_GET and $tid) {
            # 所有分类信息
            $types_data = $this->gtype();
            $this->assign('gtypes', $types_data);
            # 当前分类数据
            $type_data = $this->gtype($tid);
            $this->assign('gtype', $type_data);
        } else if (IS_POST) {
            # 供应商ID
            $company = $this->get_company();
            $company_id = $company['id'];

            $name = I('name', '');
            $parent_id = I('pid', 0, 'intval');
            if (!empty($name)) {
                $type['name'] = $name;
                $type['parent_id'] = $parent_id;
                $type['update_time'] = date("Y-m-d H:i:s", time());
                $model = M('goods_type');

                if ($tid) {
                    $where['id'] = $tid;
                    if ($model->where($where)->save($type)) {
                        //$this->success('更新分类成功',U('goods/tlist'));
                        $this->redirect(U('goods/tlist'));
                    } else {
                        $this->error('更新分类失败');
                    }
                } else {
                    $type['create_time'] = date("Y-m-d H:i:s", time());
                    $type['company_id'] = $company_id;
                    if ($model->add($type)) {
                        $this->success('增加分类成功');
                    } else {
                        $this->error('增加分类失败');
                    }
                }
            } else {
                $this->error('请填写分类标题');
            }
        }
        $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
        $this->assign('action_name', C('LANG_GOODS_ACTION_TYPE'));
        $this->display('Goods:tedit');
    }

    public function pu_resource()
    {
        $key = I('key', '');
        $p = I('p', 0, 'intval');

        $option = array();
        if ($key) {
            $w['_string'] = 'title like "%' . $key . '%"';
            $option['where'] = $w;
        }
        # 分页
        $option['limit'] = 30;
        if ($p) $option['start'] = $option['limit'] * ($p - 1);

        $model = M('resource');
        $goods_result_count = $model->where($option['where'])->limit($option['start'], $option['limit'])->count();
        $goods_result = $model->where($option['where'])->limit($option['start'], $option['limit'])->select();

        # 分页
        $Page = new \Extend\Page($goods_result_count, $option['limit'], array('key' => $key));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出


        $this->assign('total', $goods_result_count);
        $this->assign('goods', $goods_result);
        $this->assign('page', $show);
        $this->assign('moudle_name', C('LANG_MOUDLE_GOODS'));
        $this->assign('action_name', C('LANG_GOODS_ACTION_LIST'));
        $this->display('Goods:reslist');
    }

    /**
     * 新建绑定商品
     */
    public function new_bind()
    {
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        if ($_POST) {
            $bind_goods = I('bind_goods', '');
            $bind_goods_title = I('bind_goods_title', '');
            $bind_goods_num = I('bind_goods_num', '');
            $main_goods_name = I('newmainname', '');
            $main_goods_unit = I('showunit', '');
            $place = I('place', '');
            $supplier = I('supplier', array());
            $sids = I('ck', '');
            $sids = implode(',', $sids);

            $main_goods_supplier = implode(',', $supplier);
            $main_goods_tid = I('tid', 0, 'intval');


            # 添加捆绑商品信息
            $model = M('goods');
            $data['gname'] = $data['title'] = $main_goods_name;
            $data['unit'] = $main_goods_unit;
            $data['place'] = $place;
            $data['cctype'] = $main_goods_supplier;
            $data['isbind'] = 1;
            $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s', time());
            $data['company_id'] = $company_id;
            $data['tid'] = $main_goods_tid;
            $data['company_name'] = $company['name'];
            $data['gcode'] = time() . mt_rand(10, 99);
            $data['sids'] = $sids;

            if ($mid = $model->add($data)) {
                # 加入捆绑商品
                $model = M('goods_bind');
                # 写入主商品
                foreach ($bind_goods as $key => $val) {
                    $bind_m_data['mgid'] = $mid;
                    $bind_m_data['num'] = $bind_goods_num[$key];
                    $bind_m_data['memo'] = '主商品';
                    $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                    $bind_m_data['child_mgid'] = $val;
                    $model->add($bind_m_data);
                }
                $this->success('新建成功');
            } else {
                # 主商品插入失败
                $this->error('操作失败');
            }
            exit;
        }


        $tp = '';
        $list = getTypeList($tp, $company_id, C('DEFAULT_TYPE_DEEP'));

        $ck = $this->get_CK();
        $this->assign('current_menu', I('current_menu', ''));
        $this->assign('ck', $ck);
        $this->assign('tlist', $list);
        $this->assign('customer', C('CUSTOMER_TYPE'));
        $this->display('Goods:new_bind');
    }

    /**
     * 大小包装设置
     */
    public function pkgsize()
    {
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];
        if ($_POST) {
            $p_name = I('newmainname', '');
            $b_id = I('basegoodsid', 0, 'intval');
            $p_unit = I('showunit', '');
            $p_spec = I('spec', '');
            $supplier = I('supplier', array());
            $p_supplier = implode(',', $supplier);
            $sids = I('ck', '');
            $sids = implode(',', $sids);
            $main_goods_tid = I('tid', 0, 'intval');

            $model = M('goods');
            $set = $model->where("id = %d", array($b_id))->find();
            foreach ($set as $key => $val) {
                if ($key == 'id' || $key == 'publish') continue;
                $d[$key] = $val;
            }

            $d['gname'] = $d['title'] = $p_name;
            $d['unit'] = $p_unit;
            $d['spec'] = $p_spec;
            $d['cctype'] = $p_supplier;
            $d['pkgsize'] = 1;
            $d['gcode'] = time() . mt_rand(10, 99);
            $d['sids'] = $sids;
            $d['flagdel'] = 0;
            $d['tid'] = $main_goods_tid;

            if ($model->add($d)) {
                $this->success('新建成功');
            } else {
                $this->error('捆绑失败');
            }
            exit;
        }

        $tp = '';
        $list = getTypeList($tp, $company_id, C('DEFAULT_TYPE_DEEP'));

        $this->assign('current_menu', I('current_menu', ''));
        $ck = $this->get_CK();
        $this->assign('ck', $ck);
        $this->assign('tlist', $list);
        $this->assign('customer', C('CUSTOMER_TYPE'));
        $this->display('Goods:pkgsize');
    }

    /**
     * 商品排序
     */
    public function order()
    {
        if ($_POST) {
            $order = I('order', '', '');
            $order = json_decode($order, true);
            $model = M('goods');
            $model->startTrans();

            foreach ($order['data'] as $v) {
                $id = $c = array_keys($v)[0];
                $or = $v[$c];
                $model->where("id=%d", array($id))->setField('order', $or);
            }

            $model->commit(); //成功则提交
            $r['error'] = 0;
            $r['msg'] = '更改排序成功';
            $r['data'] = '';
            $this->ajaxReturn($r);
        }
    }


    /**
     * 上传图片
     */
    public function upload_img()
    {
        $img = I('src', '', '');
        $gcode = I('gcode', 0, 'intval');
        $rd = rand(pow(10, (10 - 1)), pow(10, 10) - 1);
        $path = 'og/' . $gcode . '_' . $rd;
        $r['error'] = 1;
        $r['msg'] = '';
        $r['data'] = '';
        if ($path = $this->save_mem_img($_POST['src'], $path)) {
            $r['error'] = 0;
            //$r['data'] = array($rd,$path[1]); 数据库暂支持JPG
            $r['data'] = $rd;
            $this->ajaxReturn($r);
        } else {
            $this->ajaxReturn($r);
        }
    }

    /**
     * 存储图片到阿里OSS
     */
    public function save_mem_img($img, $path)
    {
        $attached_type = '';
        if (strstr($img, 'data:image/jpeg;base64,')) {
            $img_base = str_replace('data:image/jpeg;base64,', '', $img);
            $attached_type = 'jpg';
        } elseif (strstr($img, 'data:image/png;base64,')) {
            $img_base = str_replace('data:image/png;base64,', '', $img);
            $attached_type = 'png';
        } elseif (strstr($img, 'data:image/gif;base64,')) {
            $img_base = str_replace('data:image/gif;base64,', '', $img);
            $attached_type = 'gif';
        } else {
            //return $img;
            return false;
        };
        if ($attached_type != '') {
            $img_decode = base64_decode($img_base);

            try {
                $ossClient = new \Extend\OSS\OssClient(C('OSS_ACCESS_KEYID'), C('OSS_ACCESS_KEYSECRET'), C('OSS_ENDPOINT'), true);
            } catch (OssException $e) {
                //print $e->getMessage();
                return false;
            }

            $ossClient->setTimeout(3600);      // 设置请求超时时间，单位秒，默认是5184000秒, 这里建议不要设置太小，如果上传文件很大，消耗的时间会比较长
            $ossClient->setConnectTimeout(10); // 设置连接超时时间，单位秒，默认是10秒


            $bucket = C('OSS_BUCKET');
            try {
                $ossClient->createBucket($bucket, \Extend\OSS\OssClient::OSS_ACL_TYPE_PUBLIC_READ);
            } catch (OssException $e) {
                //print $e->getMessage();
                return false;
            }

            try {
                $ossClient->putObject($bucket, $path . '.' . $attached_type, $img_decode);
            } catch (OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return false;
            }
            return array($path, $attached_type);
        }
        return false;
    }

    //新建买赠活动
    public function gift()
    {
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];
        if ($_POST) {
            //新建策略
            $glist = I('giving_goods', '');
            $gname = I('giving_goods_name', '');
            $gtitle = I('giving_title', '');
            $gnum = I('giving_goods_num', '');
            $gcode = I('giving_goods_code', '');
            $timer = I('timer', '');
            $des = I('des', '');
            $title = I('title', '');
            $iconname = I('iconname', '');
            $company = $this->get_company();
            $company_id = $company['id'];

            $ti = explode(' - ', $timer);
            $stime = trim($ti[0]);
            $etime = trim($ti[1]);
            if (!$glist) {
                $this->error('赠品建立失败');
            }

            $model = M('market');

            $mid_list = [];
            foreach ($glist as $key => $val) {
                $d['start_time'] = $stime;
                $d['end_time'] = $etime;
                $d['scope'] = 0;
                $d['mtype'] = 1;
                $d['description'] = $des;
                $d['title'] = $gtitle[$key];
                $d['iconName'] = $iconname;
                $d['company_id'] = $company_id;

                $g['gname'] = $gname[$key];
                $g['total'] = $gnum[$key];
                $g['mgid'] = $val;
                $g['gcode'] = $gcode[$key];
                $gl = [$g];

                $d['strategy'] = json_encode($gl);
                $mid = $model->add($d);
                if (!$mid) {
                    $this->error('赠品建立失败');
                }
                $mid_list[] = $mid;
            }

//            $d['start_time'] = $stime;
//            $d['end_time'] = $etime;
//            $d['scope'] = 0;
//            $d['mtype'] = 1;
//            $d['description'] = $des;
//            $d['title'] = $title;
//            $d['iconName'] = $iconname;
//            $d['company_id'] = $company_id;
//
//            $gl = array();
//            foreach($glist as $key=>$val){
//                $g['gname'] = $gname[$key];
//                $g['total'] = $gnum[$key];
//                $g['mgid'] = $val;
//                $g['gcode'] = $gcode[$key];
//                array_push($gl,$g);
//            }
//            $d['strategy'] = json_encode($gl);
//            $mid = $model->add($d);
//            if(!$mid){
//                $this->error('赠品建立失败');
//            }

            $goods_type = I('goods_type');
            if ($goods_type == 1) {
                //新建大包装商品
                $p_name = I('newmainname', '');
                $b_id = I('basegoodsid', 0, 'intval');
                $p_unit = I('showunit', '');
                $p_spec = I('spec', '');
                $supplier = I('supplier', array());
                $p_supplier = implode(',', $supplier);
                $sids = I('ck', '');
                $sids = implode(',', $sids);
                $main_goods_tid = I('tid', 0, 'intval');

                $model = M('goods');
                $set = $model->where("id = %d", array($b_id))->find();
                foreach ($set as $key => $val) {
                    if ($key == 'id' || $key == 'publish') continue;
                    $d[$key] = $val;
                }

                $d['gname'] = $d['title'] = $p_name;
                $d['unit'] = $p_unit;
                $d['spec'] = $p_spec;
                $d['cctype'] = $p_supplier;
                $d['pkgsize'] = 1;
                $d['gcode'] = time() . mt_rand(10, 99);
                $d['sids'] = $sids;
                $d['flagdel'] = 0;
                $d['tid'] = $main_goods_tid;
                $d['marketid'] = implode(',', $mid_list);
                $gid = $model->add($d);
                if (!$gid) {
                    $this->error('捆绑失败');
                }
            } elseif ($goods_type == 2) {
                //新建捆绑商品
                $bind_goods = I('bind_goods', '');
                $bind_goods_title = I('bind_goods_title', '');
                $bind_goods_num = I('bind_goods_num', '');
                $main_goods_name = I('newmainname', '');
                $main_goods_unit = I('showunit', '');
                $place = I('place', '');
                $supplier = I('supplier', array());
                $sids = I('ck', '');
                $sids = implode(',', $sids);

                $main_goods_supplier = implode(',', $supplier);
                $main_goods_tid = I('tid', 0, 'intval');


                # 添加捆绑商品信息
                $model = M('goods');
                $data['gname'] = $data['title'] = $main_goods_name;
                $data['unit'] = $main_goods_unit;
                $data['place'] = $place;
                $data['cctype'] = $main_goods_supplier;
                $data['isbind'] = 1;
                $data['create_time'] = $data['update_time'] = date('Y-m-d H:i:s', time());
                $data['company_id'] = $company_id;
                $data['tid'] = $main_goods_tid;
                $data['company_name'] = $company['name'];
                $data['gcode'] = time() . mt_rand(10, 99);
                $data['sids'] = $sids;
                $data['marketid'] = implode(',', $mid_list);
                $gid = $model->add($data);
                if (!$gid) {
                    # 主商品插入失败
                    $this->error('操作失败');
                }

                # 加入捆绑商品
                $model = M('goods_bind');
                # 写入主商品
                foreach ($bind_goods as $key => $val) {
                    $bind_m_data['mgid'] = $gid;
                    $bind_m_data['num'] = $bind_goods_num[$key];
                    $bind_m_data['memo'] = '主商品';
                    $bind_m_data['createtime'] = date('Y-m-d H:i:s', time());
                    $bind_m_data['child_mgid'] = $val;
                    $model->add($bind_m_data);
                }
            }

            $this->success('新建成功');
        } else {
            //显示买赠页面
            $tp = '';
            $list = getTypeList($tp, $company_id, C('DEFAULT_TYPE_DEEP'));

            $ck = $this->get_CK();
            $this->assign('current_menu', I('current_menu', ''));
            $this->assign('ck', $ck);
            $this->assign('tlist', $list);
            $this->assign('customer', C('CUSTOMER_TYPE'));
            $this->display('Goods:gift');
        }
        return false;
    }
}
