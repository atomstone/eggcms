<?php
namespace app\admin\controller;

class Links extends Base
{
    private $linksModel;
    private $typeModel;

    public function __construct(){
        parent::__construct();
        $this->linksModel = model('Links');
        $this->typeModel = model('LinksType');
    }

    /*
     * 友情链接列表
     */
    public function linksList()
    {
        $typeid = input('param.typeid/d', 0);
        $where = "siteid = " . $this->siteid;
        if(!empty($typeid)){
            $where .= " and typeid = " . $typeid;
        }
        $links = $this->linksModel->where($where)->order('listorder asc')->select();
        //构造返回
        $this->assign('typeid', $typeid);
        $this->assign('links', $links);
        return view(__FUNCTION__);
    }

    /*
     * 友情链接校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function linksCheck($data, $opType = false){
        if(empty($data['typeid'])){
            return error("请选择分类");
        }
        if(empty($data['name'])){
            return error("请填写网站名称");
        }
        if(empty($data['url'])){
            return error("请填写网站网址");
        }
        if(empty($data['logo'])){
            return error("请上传图片logo");
        }
        if($opType){
            if(empty($data['linksid'])){
                return error("请传入友情链接id");
            }
        }
        return success();
    }

    /*
     * 友情链接添加
     */
    public function linksAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->linksCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $data['siteid'] = $this->siteid;
            $linksid = $this->linksModel->insertGetId($data);
            if(!$linksid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 友情链接修改
     */
    public function linksEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->linksCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->linksModel->where("linksid = {$data['linksid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $linksid = input('param.linksid/d');
        if(empty($linksid)){
            return $this->errorPage("请传入友情链接id");
        }
        $links = $this->linksModel->get($linksid);
        if(empty($links)){
            return $this->errorPage("未检索到友情链接");
        }
        $this->assign('links', $links);
        return view(__FUNCTION__);
    }


    /*
     * 友情链接排序
     */
    public function linksSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的友情链接');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->linksModel->where("linksid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 类型列表
     */
    public function typeList()
    {
        $types = $this->typeModel->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        //构造返回
        $this->assign('types', $types);
        return view(__FUNCTION__);
    }

    /*
     * 友情链接分类校验
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
     * 友情链接分类添加
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
     * 友情链接分类修改
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
            return $this->errorPage("请传入友情链接id");
        }
        $type = $this->typeModel->get($typeid);
        if(empty($type)){
            return $this->errorPage("未检索到分类");
        }
        $this->assign('type', $type);
        return view(__FUNCTION__);
    }

    /*
     * 分类排序
     */
    public function typeSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的分类');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->typeModel->where("typeid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }

}
