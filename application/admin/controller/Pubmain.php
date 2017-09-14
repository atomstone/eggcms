<?php
namespace app\admin\controller;

class Pubmain extends Base
{

    public function __construct(){
        parent::__construct();
    }

    /*
     * 修改我的信息
     */
    public function myinfoEdit()
    {
        $admin = model('Admin')->get($this->admininfo['adminid']);
        if(empty($admin)){
            return $this->errorPage("未检索到您的信息");
        }
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $pw0 = $data['pwd0'];
            $pw1 = $data['pwd1'];
            $pw2 = $data['pwd2'];
            unset($data['pwd0']);
            unset($data['pwd1']);
            unset($data['pwd2']);
            if(!empty($pw1) || !empty($pw2)){
                if(empty($pw0)){
                    return error("请填写旧密码");
                }
                if(md5($pw0) != $admin['password']){
                    return error("旧密码错误");
                }
                if(empty($pw1)){
                    return error("请填写新密码 ");
                }
                if(!preg_match("/^[A-Za-z0-9]+$/",$pw1)){
                    return error("密码只能包含字母和数字");
                }
                if(strlen($pw1) < 6 || strlen($pw1) > 20){
                    return error("密码长度错误 ");
                }
                if($pw1 != $pw2){
                    return error("两次密码不一致确认密码 ");
                }
                $data['password'] = md5($pw1);
            }
            //执行修改
            $rs = model('Admin')->where("adminid = " . $this->admininfo['adminid'])->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }
        $this->assign('admin', $admin);
        return view(__FUNCTION__);
    }



}
