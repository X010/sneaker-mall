<?php
namespace Admin\Controller;
use Think\Controller;

class PermissionController extends BaseController{

    public function index(){
        $this->display('Index:main');
    }

    public function group(){
        $m = M('auth_group');
        $data = $m->order('id DESC')->select();
        $this->assign('data',$data);
        $this->assign('moudle_name', C('LANG_MOUDLE_PERMISSION'));
        $this->assign('action_name',C('LANG_PERMISSION_ACTION_GROUP'));
        $this->display('Permission:group');
    }

    //删除组
    public function group_del(){
        $where['id'] = I('id');
        if($where['id'] == 30){
            $this->ajaxReturn(0);
        }
        $m = M('auth_group');
        if($m->where($where)->delete()){
            $this->ajaxReturn(1);
        }else{
            $this->ajaxReturn(0);
        }
    }

    //编辑组
    public function group_edit(){
        $m = M('auth_group');
        if(!empty($_POST)){
            $data['id'] = I('id');
            $data['title'] = I('title');
            $data['rules'] = implode(',', I('rules'));
            if($m->save($data)){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }else{
            $where['id'] = I('id');	//组ID
            $reuslt = $m->field('id,title,rules')->where($where)->find();
            $reuslt['rules'] = ','.$reuslt['rules'].',';
            $this->assign('reuslt',$reuslt);

            $m = M('auth_rule');
            $data = $m->field('id,title')->where('pid = 0')->select();
            $arr = array();
            foreach ($data as $k => $v){
                $data[$k]['sub'] = $m->field('id,title')->where('pid ='.$v['id'])->select();
            }
            $this->assign('data',$data);
            $this->assign('moudle_name', C('LANG_MOUDLE_PERMISSION'));
            $this->assign('action_name',C('LANG_PERMISSION_ACTION_GROUP'));
            $this->display('Permission:edit');
        }
    }

    public function rule(){
        if(!empty($_POST)){
            $m = M('auth_rule');
            $data['name'] = I('name');
            $data['title'] = I('title');
            $data['pid'] = I('pid');
            $data['create_time'] = time();
            if($m->add($data)){
                $this->success('添加成功');	//成功
            }else{
                $this->success('添加失败');	//失败
            }
        }else{
            $m = M('auth_rule');
            $field = 'id,name,title,create_time,status,sort';
            $data = $m->field($field)->where('pid=0')->select();
            foreach ($data as $k=>$v){
                $data[$k]['sub'] = $m->field($field)->where('pid='.$v['id'])->select();
            }
            $this->assign('data',$data);	// 顶级
            $this->assign('moudle_name', C('LANG_MOUDLE_PERMISSION'));
            $this->assign('action_name',C('LANG_PERMISSION_ACTION_RULE'));
            $this->display('Permission:rule');
        }


    }
}
