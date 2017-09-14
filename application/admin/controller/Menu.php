<?php
namespace app\admin\controller;

class Menu extends Base
{
    private $menuModel;
    private $typeModel;

    public function __construct(){
        parent::__construct();
        $this->menuModel = model('Menu');
        $this->typeModel = model('MenuType');
    }


    /*
     * 类型列表
     */
    public function typeList()
    {
        $types = $this->typeModel->where("siteid = " . $this->siteid)->select();
        //构造返回
        $this->assign('types', $types);
        return view(__FUNCTION__);
    }

    /*
     * 分类分类校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function typeCheck($data, $opType = false){
        if(empty($data['typename'])){
            return error("请填写分类名称");
        }
        if(empty($data['code'])){
            return error("请填写分类标识符");
        }
        if(!preg_match("/^[A-Za-z]+$/",$data['code'])){
            return error("标识符只能包含字母");
        }

        if($opType){
            if(empty($data['typeid'])){
                return error("请传入分类id");
            }
            $rs = $this->typeModel->where("siteid = " . $this->siteid . " and code = '".$data['code']."' and typeid <> " . $data['typeid'])->find();
            if(!empty($rs)){
                return error("标识符已存在");
            }
        }else{
            $rs = $this->typeModel->where("siteid = " . $this->siteid . " and code = '".$data['code']."'")->find();
            if(!empty($rs)){
                return error("标识符已存在");
            }
        }
        return success();
    }

    /*
     * 分类分类添加
     */
    public function typeAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->typeCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $data['siteid'] = $this->siteid;
            $typeid = $this->typeModel->insertGetId($data);
            if(!$typeid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 分类分类修改
     */
    public function typeEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->typeCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->typeModel->where("typeid = {$data['typeid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $typeid = input('param.typeid/d');
        if(empty($typeid)){
            return $this->errorPage("请传入分类id");
        }
        $type = $this->typeModel->get($typeid);
        if(empty($type)){
            return $this->errorPage("未检索到分类");
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }


    /*
     * 菜单列表
     */
    public function menuList()
    {
        //获取参数
        $typeid = input('param.typeid/d',0);
        $type = $this->typeModel->where("siteid = ".$this->siteid." and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到分类");
        }
        $menus = $this->menuModel->getTreeMenus($typeid);
        //构造返回
        $this->assign('menus', $menus);
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 菜单校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function menuCheck($data, $opType = false){
        if($opType){
            if($data['menuid'] == $data['parentid']){
                return error("归属菜单错误,上级不能是自己");
            }
            $childids = $this->menuModel->getChildIds($data['typeid'],$data['menuid']);
            if(in_array($data['parentid'], $childids)){
                return error("归属菜单错误，不能是自己的下级");
            }
        }
        if(empty($data['typeid'])){
            return error("请传入分类id");
        }
        if(empty($data['menuname'])){
            return error("请填写菜单名称");
        }
        if(!isset($data['linkstype'])){
            return error("请选择链接");
        }
        if($data['linkstype'] == 2){
            if(empty($data['url']) || $data['url'] == 'http://'){
                return error("请填写链接地址");
            }
        }
        if($data['linkstype'] == 3){
            if(empty($data['siteid'])){
                return error("请选择链接站点");
            }
            if(empty($data['catid'])){
                return error("请选择链接栏目");
            }
        }
        return success();
    }

    /*
     * 菜单添加
     */
    public function menuAdd(){
        //获取参数
        $typeid = input('param.typeid/d',0);
        //校验
        $type = $this->typeModel->where("siteid = ".$this->siteid." and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到分类");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $data['typeid'] = $typeid;
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
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 菜单修改
     */
    public function menuEdit()
    {
        $menuid = input('param.menuid/d',0);
        $menu = $this->menuModel->get($menuid);
        if(empty($menu)){
            return $this->error("未检索到菜单");
        }
        $type = $this->typeModel->where("siteid = ".$this->siteid." and typeid = " . $menu['typeid'])->find();
        if(empty($type)){
            return $this->error("未检索到分类");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $data['menuid'] = $menuid;
            $data['typeid'] = $menu['typeid'];
            //校验参数
            $rs = $this->menuCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->menuModel->where("menuid = " . $menuid)->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('menu', $menu);
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 菜单删除
     */
    public function menuDel(){
        $typeid = input('param.typeid/d',0);
        $menuid = input('param.menuid/d',0);
        if(empty($menuid)){
            return $this->error("请传入菜单id");
        }
        $type = $this->typeModel->where("siteid = ".$this->siteid." and typeid = " . $typeid)->find();
        if(empty($type)){
            return $this->error("未检索到分类");
        }
        $rs = $this->menuModel->where("typeid = ".$typeid." and parentid = " . $menuid)->find();
        if($rs){
            return $this->error("此菜单下有子菜单，请先删除子菜单！");
        }
        $rs = $this->menuModel->where("typeid = ".$typeid." and menuid = " . $menuid)->delete();
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
