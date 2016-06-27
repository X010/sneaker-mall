<?php
namespace Admin\Controller;
use Think\Controller;

class ColumnController extends BaseController{

    public function index(){
        $this->display('Index:main');
    }

    public function clist(){
        $model = M('cate');

        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $coll = $model->where('company_id=%d',array($company_id))->select();
        $coll = getSortedColl($coll);

        $this->assign('current_menu',I('current_menu',''));
        $this->assign('coll',$coll);
        $this->assign('moudle_name', C('LANG_MOUDLE_COLUMN'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_COLUMN'));
        $this->display('Column:clist');
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

    public function column_edit(){
        $cid = I('id','0','intval');
        $model = M('cate');
        if(!$_POST){
            $coll = $model->where('id=%d',array($cid))->find();
            $coll['description'] = json_decode($coll['description'],true);
            $this->assign('coll',$coll);
        }else if($_POST){

            $name = I('name','');
            $pic = I('activity_pic','');
            $wx_home = I('wx_home','0');
            $wx_home_goods = I('wx_home_goods','4');
            $type = I('ctype',1,'intval');
            $href = I('href','');
            $orderby = I('orderby','');
            $piccontent = array();
            if($type == 2){
                foreach($pic as $key=>$val){
                    $val2 = substr($val,0,strpos($val,'@'));
                    $a = array('pic'=>$val2,'href'=>$href[$key],'orderby'=>$orderby[$key]);
                    array_push($piccontent,$a);
                }
                $piccontent = array_reverse($piccontent);
                $piccontent = json_encode($piccontent,JSON_UNESCAPED_SLASHES);
            }
            if($cid){
                $dat['name'] = $name;
                $dat['wx_home'] = $wx_home;
                $dat['wx_home_goods'] = $wx_home_goods;
                $dat['description'] = $piccontent;
                $dat['update_time'] = date('Y-m-d H:i:s',time());
                $dat['type'] = $type;
                if($model->where('id=%d',array($cid))->save($dat)){
                    //$this->success('更改栏目成功', U('column/clist'));
                    $this->redirect(U('column/clist'));
                    exit;
                }else {
                    $this->error('更新栏目失败');
                    exit;
                }

            }else{
                # 供应商ID
                $company = $this->get_company();
                $company_id = $company['id'];

                $dat['name'] = $name;
                $dat['wx_home'] = $wx_home;
                $dat['wx_home_goods'] = $wx_home_goods;
                $dat['description'] = $piccontent;
                $dat['update_time'] = date('Y-m-d H:i:s',time());
                $dat['create_time'] = $dat['update_time'];
                $dat['type'] = $type;
                $dat['company_id'] = $company_id;
                $dat['csort'] = 1;
                if($model->add($dat)){
                    //$this->success('添加栏目成功', U('column/clist'));
                    $this->redirect(U('column/clist'));
                    exit;
                }else {
                    $this->error('添加栏目失败');
                    exit;
                }
            }
        }

        $this->assign('current_menu',I('current_menu',''));
        $this->assign('moudle_name', C('LANG_MOUDLE_COLUMN'));
        $this->assign('action_name',C('LANG_COLUMN_ACTION_COLUMN'));
        $this->display('Column:cedit');
    }

    public function get_activity_imgs(){
        $id = I('id',0,'intval');
        $r['error'] = 0;
        $r['msg'] = '获取活动焦点图成功';
        $r['data'] = '';
        if($id){
            $result = M('cate')->where('id=%d',array($id))->find();
            $lists = $result['description'];
            $r['data'] = json_decode($lists,true);
            $this->ajaxReturn($r);
        }else{
            $r['error'] = 1;
            $r['msg'] = '获取活动焦点图失败';
            $this->ajaxReturn($r);
        }
    }
    public function column_del(){
        $id = I('id',0,'intval');
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $r['error'] = 0;
        $r['msg'] = '删除栏目成功';
        $r['data'] = '';
        if($id){
            if(M('cate')->where('id=%d AND company_id',array($id,$company_id))->delete()){
                $this->ajaxReturn($r);
            }else{
                $r['error'] = 1;
                $r['msg'] = '删除失败';
                $this->ajaxReturn($r);
            }
        }else{
            $r['error'] = 1;
            $r['msg'] = '删除时出现错误';
            $this->ajaxReturn($r);
        }
    }

    public function picupload(){
        $rd = rand(pow(10,(10-1)), pow(10,10)-1);
        $path = 'og/ord_'.$rd;
        //$object = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $pic = $this->save_mem_img($_POST['src'],$path);

        //array(2) { [0]=> string(17) "og/ord_5863514117" [1]=> string(3) "jpg" }

        //var_dump($pic);exit;
        //$pic[0] = C('BASE_GOOD_IMG_URL').'ord_'+data.data+'.jpg{:C('IMG_SPEC_SM')
        //$pic[0] = C('BASE_GOOD_IMG_URL').'/'.$pic[0];
        $pic = C('BASE_GOOD_IMG_URL').str_replace("og/",'',$pic[0]).'.'.$pic[1].C('IMG_SPEC_SM');
        $r['error'] = 1;
        $r['msg'] = '上传图片错误';
        $r['data'] = '';
        if($pic){
            $r['error'] = 0;
            $r['msg'] = '上传成功';
            $r['data'] = $pic;
            $this->ajaxReturn($r);
        }else
            $this->ajaxReturn($r);
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
            if(M('cate')->where('id=%d',array($id))->setField('publish',$dis)){
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

    /**
     * 存储图片到阿里OSS
     */
    public function save_mem_img($img, $path){
        $attached_type = '';
        if(strstr($img,'data:image/jpeg;base64,')) {
            $img_base = str_replace('data:image/jpeg;base64,', '', $img);
            $attached_type = 'jpg';
        } elseif(strstr($img,'data:image/png;base64,')) {
            $img_base = str_replace('data:image/png;base64,', '', $img);
            $attached_type = 'png';
        } elseif(strstr($img,'data:image/gif;base64,')) {
            $img_base = str_replace('data:image/gif;base64,', '', $img);
            $attached_type = 'gif';
        } else {
            //return $img;
            return false;
        };
        if($attached_type!='') {
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
                $ossClient->createBucket($bucket,\Extend\OSS\OssClient::OSS_ACL_TYPE_PUBLIC_READ);
            } catch (OssException $e) {
                //print $e->getMessage();
                return false;
            }

            try{ $ossClient->putObject($bucket, $path . '.' . $attached_type, $img_decode); }
            catch(OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return false;
            }
            return array($path,$attached_type);
        }
        return false;
    }

}
