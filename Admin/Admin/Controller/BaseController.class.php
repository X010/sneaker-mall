<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Auth;
use Think\Model;

//权限认证
class BaseController extends Controller {

    # 用户组
    protected  $__group;
    protected $_mall;

    protected function _initialize(){
        $this->login_from_erp();

        $this->assign('com',session('info')['com']);
        $this->assign('name',session('info')['name']);

        //session不存在时，不允许直接访问
        if(!session('aid')){
            //$this->error('还没有登录，正在跳转到登录页',U('login/sign-in'));
            $this->redirect(U('login/sign-in'));
        }

        # 更新操作时间
        if($this->op_expired()){
            session('aid',null);	//注销 uid ，account
            session('account',null);
            $this->error('您长时间未操作，请重新登录',U('login/sign-in'));
        }

        session('opexpired',time());

        //session存在时，不需要验证的权限
        $not_check = array('Index/index','Index/main','Index/clear_cache',
            'Index/edit_pwd','Index/logout','Admin/admin_list',
            'Admin/admin_list','Admin/admin_edit','Admin/admin_add');

        //当前操作的请求                 模块名/方法名
        if(in_array(CONTROLLER_NAME.'/'.ACTION_NAME, $not_check)){
            return true;
        }

        //下面代码动态判断权限
        //$auth = new Auth();
        //if(!$auth->check(CONTROLLER_NAME.'/'.ACTION_NAME,session('aid')) && session('aid') != 1){
            //$this->error('没有权限');
        //}

        # 初始化用户组
        $this->__group = $this->get_c_group();

        if($_GET['current_menu']){
            session('current_menu',$_GET['current_menu']);
        }

    }

    ###########################################
    # 一下为系统核心数据提取方法
    # 为了数据隔离，请业务控制器
    # 尽量使用下面的方法提取数据
    # 避免数据混淆
    ###########################################

    /**
     * 取得供应商信息
     */
    public function get_company(){
        $company['id'] = $this->__group['company_id'];
        $company['name'] = $this->__group['title'];
        return $company;
    }

    /**
     * 取得用户组信息
     */
    protected function get_c_group(){
        # 权限组ID
        $group_id = session('info')['cid'];
        $g = M('auth_group')->where("status = 1 AND type = 1 AND company_id=%d",array($group_id))->find();
        if($g)
            return $g;
        else{
            $this->error('没有权限的权限组');
        }
    }

    /**
     * 取得仓库
     */
    public function get_CK(){
        return session("info")["ck"];
    }

    /**
     * 登录过期
     */
    public function op_expired(){
        $exp = false;
        if(time() - session('opexpired') > C('OP_EXPIRED')){
            $exp = true;
        }
        return  $exp;
    }

    /**
     * 取得商品信息
     */
    public function goods($options='', $only_count=False){
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $model = M('goods');
        $start = 0;
        $limit = C('DEFAULT_PAGE_LIMIT');

        if($options){
            # where
            if($options['where'])
               $where = $options['where'];

            if($options['start'])
                $start = $options['start'];

            if($options['limit'])
                $limit = $options['limit'];

            if($options['order'])
                $order = $options['order'];
            else
                $order = 'create_time DESC';
        }


        $where['company_id'] = $company_id;


        $count  = $model->where($where)->count();// 查询满足要求的总记录数

        if(!$only_count){
            $goods_data = $model->where($where)->limit($start,$limit)->order($order)->select();
            //echo $model->getLastSql(); exit;
        }
        else{
            $goods_data = [];
        }
        //echo $model->getLastSql();
        return array('count'=>$count,'data'=>$goods_data);
    }

    public function good($id){
        # 供应商ID
        $company = $this->get_company();
        $company_id = $company['id'];

        $model = M('goods');
        $where['company_id'] = $company_id;

        $where['id'] = $id;
        //$where['flagdel'] = 0;
        $good_data = $model->where($where)->find();
        return $good_data;
    }

    public function login_from_erp(){
        //如果没有传ticket，直接返回
        if(!$_GET['ticket']){
            return False;
        }
        $url = C('API_ERP_URL').'exists/getuser_admin';
        $param = ['ticket'=>$_REQUEST['ticket']];
        $res = post($url, $param);
        $res_data = json_decode($res,true);
        //var_dump($res);exit;
        if($res_data['err'] != 0){
            return False;
        }
        $erp_data = $res_data['msg'];
        if($erp_data['admin'] != 1 && !in_array(194, $erp_data['mids'])){
            $this->error('没有相应系统的权限');
            return False;
        }
        session('aid',$erp_data['id']);
        session('info',$erp_data);
        session('business', $erp_data['business']);
        #登录时间
        session('opexpired',time());
        return True;
    }
    
    
    /**
     * 取得微信商城信息
     */
    protected function get_mall(){
        $mall = array();
        $company = $this->get_company();
        $company_id = $company['id'];
        
        // type 微信商城标志
        // enable 是否激活
        $mall = M('mall')->where('type = 1 AND enable = 1 AND company_id=%d', array($company_id))->find();
        return $mall;
    }
    

    protected function mall_init() {
        $mall = $this->get_mall();
        if ( ! isset($mall['id']) ||  empty($mall['id'])) {
            $this->display('Mall/index');
            exit;
        } else {
            $this->_mall = $mall;
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