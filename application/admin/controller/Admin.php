<?php
namespace app\admin\controller;

class Admin extends Base
{
    private $adminModel;
    private $roleModel;
    private $menuModel;
    private $rolePrivModel;

    public function __construct(){
        parent::__construct();
        $this->adminModel = model('Admin');
        $this->roleModel = model('AdminRole');
        $this->menuModel = model('AdminMenu');
        $this->rolePrivModel = model('AdminRolePriv');
    }

    /*
     * 管理员列表
     */
    public function adminList()
    {
        //获取参数
        $limitRows = input('param.pagesize/d',10);
        $search = input('param.search/a');
        //构造条件
        $where = array();
        $where[] = "1 = 1";
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('adminname','realname'))){
                        $where[] = "`$k` like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);
        //构造数据
        $count = $this->adminModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $admins = $this->adminModel->where($where)->order("adminid desc")->limit($page->firstRow, $page->listRows)->select();
        foreach($admins as & $v){
            $v['role'] = $this->roleModel->get($v['roleid']);
        }
        //构造返回
        $this->assign("page", $page);
        $this->assign('admins', $admins);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 管理员校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function adminCheck($data, $opType = false){
        $pw1 = $data['pwd1'];
        $pw2 = $data['pwd2'];
        unset($data['pwd1']);
        unset($data['pwd2']);
        //是否校验密码
        $isPwd = true;
        if($opType){
            if(empty($data['adminid'])){
                return error("请传入管理员id");
            }
            if(empty($pw1) && empty($pw2)){
                $isPwd = false;
            }
        }else{
            if(empty($data['adminname'])){
                return error("请填写用户名");
            }
            if(!preg_match("/^[A-Za-z0-9]+$/",$data['adminname'])){
                return error("用户名只能包含字母和数字");
            }
            if(strlen($data['adminname']) < 2 || strlen($data['adminname']) > 20){
                return error("用户名长度错误 ");
            }
            $rs = $this->adminModel->where("adminname = '".$data['adminname']."'")->find();
            if(!empty($rs)){
                return error("此用户名已存在");
            }
        }
        if($isPwd){
            if(empty($pw1)){
                return error("请填写密码 ");
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
        return success(array('data' => $data));
    }

    /*
     * 管理员添加
     */
    public function adminAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->adminCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行添加
            $adminid = $this->adminModel->insertGetId($data);
            if(!$adminid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 管理员修改
     */
    public function adminEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->adminCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行修改
            $rs = $this->adminModel->where("adminid = {$data['adminid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $adminid = input('param.adminid/d');
        if(empty($adminid)){
            return $this->errorPage("请传入管理员id");
        }
        $admin = $this->adminModel->get($adminid);
        if(empty($admin)){
            return $this->errorPage("未检索到管理员");
        }
        $this->assign('admin', $admin);
        return view(__FUNCTION__);
    }


    /*
     * 角色列表
     */
    public function roleList()
    {
        $roles = $this->roleModel->order("listorder ASC")->select();
        //构造返回
        $this->assign('roles', $roles);
        return view(__FUNCTION__);
    }

    /*
     * 角色校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function roleCheck($data, $opType = false){
        if(empty($data['rolename'])){
            return error("请填写角色名称");
        }
        if($opType){
            if(empty($data['roleid'])){
                return error("请传入角色id");
            }
            $rs = $this->roleModel->where("rolename = '".$data['rolename']."' and roleid <> " . $data['roleid'])->find();
            if(!empty($rs)){
                return error("此角色名称已存在");
            }
        }else{
            $rs = $this->roleModel->where("rolename = '".$data['rolename']."'")->find();
            if(!empty($rs)){
                return error("此角色名称已存在");
            }
        }
        return success();
    }

    /*
     * 角色添加
     */
    public function roleAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->roleCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $roleid = $this->roleModel->insertGetId($data);
            if(!$roleid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 角色修改
     */
    public function roleEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->roleCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->roleModel->where("roleid = {$data['roleid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $roleid = input('param.roleid/d');
        if(empty($roleid)){
            return $this->errorPage("请传入角色id");
        }
        $role = $this->roleModel->get($roleid);
        if(empty($role)){
            return $this->errorPage("未检索到角色");
        }
        $this->assign('role', $role);
        return view(__FUNCTION__);
    }

    /*
     * 角色设置权限
     */
    public function roleSetting()
    {
        $roleid = input('param.roleid/d');
        if(empty($roleid)){
            return $this->errorPage("请传入角色id");
        }
        $role = $this->roleModel->get($roleid);
        if(empty($role)){
            return $this->errorPage("未检索到角色");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //操作
            $this->rolePrivModel->startTrans();
            try{
                //清除所有权限
                $rs = $this->rolePrivModel->where("roleid = {$roleid}")->delete();
                if($rs === false){
                    throw new \Exception("删除权限失败");
                }
                //循环添加权限
                foreach($data as $siteid => $menuids){
                    if(!empty($menuids)){
                        foreach(explode(',', $menuids) as $menuid){
                            $menu = $this->menuModel->get($menuid);
                            if($menu){
                                $privData = array(
                                    'roleid' => $roleid,
                                    'menuid' => $menu['menuid'],
                                    'c'      => strtolower($menu['c']),
                                    'a'      => strtolower($menu['a']),
                                    'siteid' => $siteid
                                );
                                $rs = $this->rolePrivModel->insert($privData);
                                if(!$rs){
                                    throw new \Exception("添加权限失败");
                                }
                            }
                        }
                    }
                }
                // 提交事务
                $this->rolePrivModel->commit();
            } catch (\Exception $e) {
                // 回滚事务
                $this->rolePrivModel->rollback();
                return $this->error($e->getMessage());
            }
            return $this->success('操作成功');
        }

        $menus = $this->menuModel->order("`listorder` ASC")->select();
        $sites = model('Site')->select();
        foreach($sites as & $v){
            $v['zNodes'] = '';
            foreach($menus as $menu){
                $priv = $this->rolePrivModel->where("roleid = {$roleid} and menuid = {$menu['menuid']} and siteid = {$v['siteid']}")->find();
                $checked = 'false';
                if($priv){
                    $checked = 'true';
                }
                $v['zNodes'] .= '{id:'.$menu['menuid'].', pId:'.$menu['parentid'].', name:"'.$menu['menuname'].'", checked:'.$checked.', open:true},';
            }
            $v['zNodes'] = '[' . $v['zNodes'] . ']';
        }
        $this->assign('sites', $sites);
        $this->assign('role', $role);
        return view(__FUNCTION__);
    }


    /*
     * 角色设置权限
     */
    public function categoryRoleSetting()
    {
        $roleid = input('param.roleid/d');
        if(empty($roleid)){
            return $this->error("请传入角色id");
        }
        $role = $this->roleModel->get($roleid);
        if(empty($role)){
            return $this->error("未检索到角色");
        }

        if (request()->isPost() && request()->isAjax()){
            $data = input('post.a/a');
            //操作
            model('CategoryPriv')->startTrans();
            try{
                //清除所有权限
                $rs = model('CategoryPriv')->where("roleid = {$roleid}")->delete();
                if($rs === false){
                    throw new \Exception("删除权限失败");
                }
                //循环添加权限
                if(!empty($data)){
                    foreach($data as $siteid => $catids){
                        if(!empty($catids)){
                            foreach($catids as $catid => $actions){
                                if(!empty($actions)){
                                    foreach($actions as $action){
                                        $catPrivData = array(
                                            'catid'  => $catid,
                                            'siteid' => $siteid,
                                            'roleid' => $roleid,
                                            'action' => strtolower($action)
                                        );
                                        $rs = model('CategoryPriv')->insert($catPrivData);
                                        if(!$rs){
                                            throw new \Exception("添加权限失败");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                // 提交事务
                model('CategoryPriv')->commit();
            } catch (\Exception $e) {
                // 回滚事务
                model('CategoryPriv')->rollback();
                return $this->error($e->getMessage());
            }
            return $this->success('操作成功');
        }

        $sites = model('Site')->select();
        foreach($sites as & $v){
            $categorys = model('Category')->where("siteid = " . $v['siteid'])->order("`listorder` ASC")->select();
            $v['zNodes'] = '';
            foreach($categorys as $cat){
                $priv = model('CategoryPriv')->where("roleid = {$roleid} and catid = {$cat['catid']} and siteid = {$v['siteid']}")->select();
                $checked = 'false';
                $actions = array();
                if($priv){
                    foreach($priv as $p){
                        $actions[] = $p['action'];
                    }
                    //$checked = 'true';
                }
                $actions = implode(',', $actions);
                $v['zNodes'] .= '{id:'.$cat['catid'].', siteid:'.$v['siteid'].', cattype:'.$cat['type'].', actions:"'.$actions.'", pId:'.$cat['parentid'].', name:"'.$cat['catname'].'", checked:'.$checked.', open:true},';
            }
            $v['zNodes'] = '[' . $v['zNodes'] . ']';
        }
        $this->assign('sites', $sites);
        $this->assign('role', $role);
        return view(__FUNCTION__);
    }

    /*
     * 菜单列表
     */
    public function menuList()
    {
        $menus = $this->menuModel->getTreeMenus();
        //构造返回
        $this->assign('menus', $menus);
        return view(__FUNCTION__);
    }

    /*
     * 菜单校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function menuCheck($data, $opType = false){
        if(empty($data['menuname'])){
            return error("请填写菜单名称");
        }
        if($opType){
            if(empty($data['menuid'])){
                return error("请传入菜单id");
            }
            if($data['menuid'] == $data['parentid']){
                return error("归属菜单错误,上级不能是自己");
            }
            $childids = $this->menuModel->getChildIds($data['menuid']);
            if(in_array($data['parentid'], $childids)){
                return error("归属菜单错误，不能是自己的下级");
            }
        }
        return success();
    }

    /*
     * 菜单添加
     */
    public function menuAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->menuCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $menuid = $this->menuModel->insertGetId($data);
            if(!$menuid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        $parentid = input('param.parentid/d',0);
        $this->assign('parentid', $parentid);
        return view(__FUNCTION__);
    }

    /*
     * 菜单修改
     */
    public function menuEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->menuCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->menuModel->where("menuid = {$data['menuid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $menuid = input('param.menuid/d');
        if(empty($menuid)){
            return $this->errorPage("请传入菜单id");
        }
        $menu = $this->menuModel->get($menuid);
        if(empty($menu)){
            return $this->errorPage("未检索到菜单");
        }
        $this->assign('menu', $menu);
        return view(__FUNCTION__);
    }

    /*
     * 菜单删除
     */
    public function menuDel(){
        $menuid = input('param.menuid/d',0);
        if(empty($menuid)){
            return $this->error("请传入菜单id");
        }
        $rs = $this->menuModel->where("parentid = " . $menuid)->find();
        if($rs){
            return $this->error("此菜单下有子菜单，请先删除子菜单！");
        }
        $rs = $this->menuModel->where("menuid = " . $menuid)->delete();
        if($rs){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

    /*
     * 菜单排序
     */
    public function menuSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的菜单');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->menuModel->where("menuid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


}
