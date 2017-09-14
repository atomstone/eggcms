<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model;
class Index extends Controller
{


    public function test()
    {

        return $this->fetch('test');
    }


    /*
    * 登录
    */
    public function index()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $adminname = input('post.adminname/s');
            $password = input('post.password/s');
            //校验
            if(empty($adminname)){
                return $this->error("请填写登录名");
            }
            if(empty($password)){
                return $this->error("请填写密码");
            }
            $adminModel = model('Admin');
            $admin = $adminModel->where("`adminname` = '".$adminname."'")->find();
            if(empty($admin)){
                return $this->error("登录名或密码错误！");
            }
            if(MD5($password) != $admin['password']){
                return $this->error("登录名或密码错误！！");
            }
            if($admin['enable'] != 1){
                return $this->error("您已被禁止登录");
            }
            if(empty($admin['roleid'])){
                return $this->error("您没有管理权限!");
            }
            $role = model('AdminRole')->get($admin['roleid']);
            if(empty($role)){
                return $this->error("您没有管理权限!!");
            }
            if($role['disabled'] == 1){
                return $this->error("您没有管理权限!!!");
            }
            //登录成功 记录日志 写session
            $upData = array(
                'lastlogintime' => time(),
                'lastloginip'   => get_real_ip(),
                'loginnum'      => $admin['loginnum']+1
            );
            $adminModel->where("adminid = " . $admin['adminid'])->update($upData);
            //session('admininfo', $admin, 'admin');
            cookie('admininfo', json_encode($admin));
            //返回
            return $this->success('登录成功');
        }
        return view('login');
    }

    public function home()
    {
        //判断是否登录
        //$admininfo = session('admininfo','','admin');
        $admininfo = cookie('admininfo');
        if(empty($admininfo)){
            return $this->index();
        }
        $admininfo = json_decode($admininfo, true);

        //站点设置
        $siteid = input('param.siteid/d',0);
        //有权限的站点
        if($admininfo['roleid'] == 1){
            $where = "1=1";
            $siteid = !empty($siteid) ? $siteid :  1;
        }else{
            $mysites = model('AdminRolePriv')->where("roleid = " . $admininfo['roleid'])->field('siteid')->group('siteid')->select();
            $siteids = array();
            foreach($mysites as $v){
                $siteids[$v['siteid']] = $v['siteid'];
            }
            if(empty($siteids)){
                $this->error("您没有站点权限1！", url('index/index'));
            }
            if(!empty($siteid)){
                if(!isset($siteids[$siteid])){
                    $this->error("您没有站点权限2！", url('index/index'));
                }
            }else{
                $siteid = current($siteids);
            }
            $where = "siteid in (".implode(',', $siteids).")";
        }
        $sites = model('Site')->where($where)->select();
        //获取站点
        $site = model('Site')->get($siteid);

        //session('siteid',$siteid,'admin');
        cookie('siteid', $siteid);

        //获取菜单
        $privs = model('AdminRolePriv')->where("siteid = {$siteid} and roleid = {$admininfo['roleid']}")->select();
        $menuids = array();
        foreach($privs as $v){
            $menuids[] = $v['menuid'];
        }
        if(!empty($menuids)){
            $menuids = implode(',', $menuids);
            $where = "menuid in (".$menuids.")";
        }else{
            $where = "menuid < 0";
        }
        if($admininfo['roleid'] == 1){
            $where = "1=1";
        }
        $menus = model('AdminMenu')->where($where . " and parentid = 0")->order('listorder Asc')->select();
        foreach($menus as & $v){
            $v['sub'] =  model('AdminMenu')->where($where . " and parentid = " . $v['menuid'])->order('listorder Asc')->select();
        }


        //返回
        $this->assign('menus', $menus);
        $this->assign('sites', $sites);
        $this->assign('site', $site);
        $this->assign('admininfo', $admininfo);
        return $this->fetch('home');
    }


    /**
    * @ $fun: 退出登录
    * @ $Author: sunlei  孙磊 13260022690
    * @ $Date: 2015-10-06
    **/
    public function logout(){
        //session(null, 'admin');
        cookie(null, 'admin_');
        return $this->index();
    }

}
