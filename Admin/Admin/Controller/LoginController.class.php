<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller {
    //登陆主页
    public function index(){
        $this->display('Login:sign-in');
    }
    //登陆验证
        public function login(){
        if(!empty($_POST)){
            $map['account'] = I('username');   //用户名
            $map['password'] = md5(I('password'));	//密码
            $smscode = I('smscode','');
            if(C('SMS_VERIFY')){
                if(empty($smscode) || $smscode != $_SESSION['smscode']){
                    $this->error('短信验证码错误',U('login/sign-in'));
                    exit;
                }else{
                    $_SESSION['smscode'] = '';
                }
            }
            /**
            $m = M('admin');
            $result = $m->field('id,account,login_count,status')->where($map)->find();
            if($result){
                if($result['status'] == 0){
                    $this->error('登录失败，账号被禁用',U('Public/login'));
                }
                session('aid',$result['id']);	//管理员ID
                session('account',$result['account']);	//管理员密码
                //保存登录信息
                $data['id'] = $result['id'];	//用户ID
                $data['login_ip'] = get_client_ip();	//最后登录IP
                $data['login_time'] = time();		//最后登录时间
                $data['login_count'] = $result['login_count'] + 1;
                $m->save($data);
                $this->success('验证成功，正在跳转到首页',U('Index/main'));
            }else{
                $this->error('账户或密码错误',U('Public/login'));
            }**/
            $username = I('username','');
            $password = I('password','');
            $data = [
                'username'=>$username,'password'=>$password
            ];
            $response = get(C('API_LOGIN_URL'),$data);

  
            if($response){
                $res = json_decode($response,true);
                if($res['status'] && $res['message'] && $res['status'] == 200){
                    if($res['data'] && !empty($res['data'])){
                        //echo $res['data']['id']; exit;
                        // 供应商ID
                        $company = $res['data']['cid'];

                        # 仓库
                        $url = C('API_CK_URL').'?cid='.$company;
                        $ck = file_get_contents($url);

                        $ck = json_decode($ck, true);
                        if($ck && $ck['status'] === 200 && $ck['data'] && !empty($ck['data'])){
                            $res['data']['ck'] = $ck['data'];
                        }else{
                            $this->error('仓库API出现错误',U('login/sign-in'));
                            exit;
                        }

                        session('aid',$res['data']['id']);
                        session('info',$res['data']);
                        print_r(session('aid'));
                        #登录时间
                        $login_time = time();
                        session('opexpired', $login_time);
                        echo "shijian";
                        echo "<br />";
                        echo session('opexpired');
                        echo "<br />";
                        echo $login_time;
                        //die;
                        //print_r($res);
                       
                        //$this->success('验证成功，正在跳转到首页',U('Index/index'));
                        $this->redirect(U('Index/index'));
                    }else{
                        $this->error('用户名或密码错误',U('login/sign-in'));
                    }
                }else{
                    $this->error($res['message'],U('login/sign-in'));
                }
            }else{
                $this->error('登录时出现错误',U('login/sign-in'));
            }
        }else{
            if(session('aid')){
                $this->error('已登录，正在跳转到主页',U('Index/index'));
            }
            $this->display('login:sign-in');
        }
    }

    /**
     * 用户名密码验证
     */
    public function logincheck(){
        $d['error'] = 1;
        $d['msg'] = '发送手机短信验证码失败';
        $d['data'] = '';

        $username = I('username','');
        $password = I('password','');
        $response = get(C('API_LOGIN_URL'),array('username'=>$username,'password'=>$password));

        if($response){
            $res = json_decode($response,true);
            if($res['status'] && $res['message'] && $res['status'] == 200){
                $d['error'] = 0;
                $this->ajaxReturn($d);
            }else{
                $this->ajaxReturn($d);
            }
        }else{
            $this->ajaxReturn($d);
        }
    }


    /**
     * 短信验证码
     */
    public function smssend(){
        $_SESSION['smscode'] = '';
        $code = rand(pow(10,(6-1)), pow(10,6)-1);
        $mobile = I('username',0,'intval');

        $d['error'] = 1;
        $d['msg'] = '发送手机短信验证码失败';
        $d['data'] = '';
        if (!is_numeric($mobile) || strlen($mobile) != 11){
            $d['msg'] = '手机号格式错误,请填写正确的手机号账号';
            $this->ajaxReturn($d);
        } else {
            $params['phone'] = $mobile;
            $params['message'] = $code;
            $params['source_from'] = $_SERVER["SERVER_ADDR"];
            $params['tempcode'] = "SMS_4040319";
            $params['system_name'] = "业务管理平台";
            $params['freeSign'] = "登录验证";
            $params['sign'] = "__";
            $sendback = post(C('API_SMS_URL'),$params);
            $sb = json_decode($sendback,true);
            if($sb['status'] == 200){
                $d['error'] = 0;
                $d['msg'] = '验证码已经发送到该手机。';
                $_SESSION['smscode'] = $code;
                $this->ajaxReturn($d);
            }else{
                $this->ajaxReturn($d);
            }
        }

    }

    /**
     * 登出
     */
    //退出登录
    public function logout(){
//        session('aid',null);	//注销 uid ，account
//        session('account',null);
//        session('goods_type', null);
        session_destroy();
        $this->success('退出登录成功',U('login/sign-in'));
    }
}