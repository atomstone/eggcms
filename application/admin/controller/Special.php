<?php
namespace app\admin\controller;

class Special extends Base
{
    private $specialModel;
    private $dataModel;
    private $typeModel;

    public function __construct(){
        parent::__construct();
        $this->specialModel = model('Special');
        $this->dataModel    = model('SpecialData');
        $this->typeModel    = model('SpecialType');
    }


    /*
     * 专题列表
     */
    public function specialList()
    {
        $where = "siteid = " . $this->siteid;
        $specials = $this->specialModel->where($where)->order('disabled asc, elite desc, listorder asc')->select();
        //构造返回
        $this->assign('specials', $specials);
        return view(__FUNCTION__);
    }

    /*
     * 专题校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function specialCheck($data, $opType = false){
        if(empty($data['title'])){
            return error("请填写专题名称");
        }
        if(empty($data['thumb'])){
            return error("请上传缩略图");
        }
        if(empty($data['banner'])){
            return error("请上传专题横幅");
        }
        if(empty($data['description'])){
            return error("请填写专题导读");
        }
        if(empty($data['index_template'])){
            return error("请选择首页模板");
        }
        /*
        if(empty($data['list_template'])){
            return error("请选择列表页模板");
        }
        */
        if(empty($data['show_template'])){
            return error("请选择内容页模板");
        }
        if($opType){
            if(empty($data['specialid'])){
                return error("请传入专题id");
            }
        }
        return success();
    }

    /*
     * 专题添加
     */
    public function specialAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $type = input('post.type/a');
            //校验参数
            $rs = $this->specialCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //校验分类
            if(empty($type)){
                return error("请添加分类节点");
            }
            foreach($type as $k => $v){
                if(empty($v['typename'])){
                    return $this->error("请填写第".$k."行分类节点名称");
                }
            }
            //执行添加
            $data['siteid'] = $this->siteid;
            $data['createtime'] = time();
            $specialid = $this->specialModel->insertGetId($data);
            if(!$specialid){
                return $this->error("添加失败");
            }
            //添加分类
            foreach($type as $v){
                $typeData = array(
                    'specialid' => $specialid,
                    'typename'  => $v['typename'],
                    'listorder' => $v['listorder']
                );
                $this->typeModel->insert($typeData);
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 专题修改
     */
    public function specialEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $type = input('post.type/a');
            //校验参数
            $rs = $this->specialCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //校验分类
            if(empty($type)){
                return error("请添加分类节点");
            }
            $typeids = array();
            foreach($type as $k => $v){
                if(!empty($v['typeid'])){
                    $typeids[] = $v['typeid'];
                }
                if(empty($v['typename'])){
                    return $this->error("请填写第".$k."行分类节点名称");
                }
            }
            //执行修改
            $rs = $this->specialModel->where("specialid = {$data['specialid']}")->update($data);
            if($rs === false){
                return $this->error("修改失败");
            }
            //删除分类
            if(empty($typeids)){
                $this->typeModel->where("specialid = " . $data['specialid'])->delete();
            }else{
                $this->typeModel->where("specialid = " . $data['specialid'] . " and typeid not in(".implode(',', $typeids).")")->delete();
            }
            //添加修改分类
            foreach($type as $k => $v){
                if(!empty($v['typeid'])){
                    $typeData = array(
                        'typename'  => $v['typename'],
                        'listorder' => $v['listorder']
                    );
                    $this->typeModel->where("typeid = " . $v['typeid'])->update($typeData);
                }else{
                    $typeData = array(
                        'specialid' => $data['specialid'],
                        'typename'  => $v['typename'],
                        'listorder' => $v['listorder']
                    );
                    $this->typeModel->insert($typeData);
                }
            }
            return $this->success('修改成功');
        }

        $specialid = input('param.specialid/d');
        if(empty($specialid)){
            return $this->errorPage("请传入专题id");
        }
        $special = $this->specialModel->where("siteid = ".$this->siteid." and specialid = " . $specialid)->find();
        if(empty($special)){
            return $this->errorPage("未检索到专题");
        }
        //分类
        $types = $this->typeModel->where("specialid = " . $specialid)->order("listorder asc")->select();
        $this->assign('special', $special);
        $this->assign('types', $types);
        return view(__FUNCTION__);
    }


    /*
     * 专题排序
     */
    public function specialSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的专题');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->specialModel->where("specialid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


    /*
     * 数据列表
     */
    public function dataList()
    {
        //获取参数
        $specialid = input('param.specialid/d');
        $limitRows = input('param.pagesize/d',10);
        $search    = input('param.search/a');
        //校验
        if(empty($specialid)){
            return $this->error("请传入专题id");
        }
        $special = $this->specialModel->where("siteid = ".$this->siteid." and specialid = " . $specialid)->find();
        if(empty($special)){
            return $this->error("未检索到专题");
        }
        //构造条件
        $where = array();
        $where[] = "specialid = " . $specialid;
        if(!empty($search)){
            foreach($search as $k => & $v){
                $v = trim($v);
                if($v != ''){
                    if(in_array($k, array('title'))){
                        $where[] = "$k like '%{$v}%'";
                    }else{
                        $where[] = "$k = '{$v}'";
                    }
                }
            }
        }
        $where = implode(' AND ', $where);

        //构造数据
        $count = $this->dataModel->where($where)->count();
        $page = new \com\Ajaxpage($count, $limitRows, "TableList.changePage");
        $datas = $this->dataModel->where($where)->order('stick DESC, listorder asc')->limit($page->firstRow, $page->listRows)->select();

        //构造返回
        $this->assign('special', $special);
        $this->assign('datas', $datas);
        $this->assign("page", $page);
        $this->assign('search', $search);
        return view(__FUNCTION__);
    }

    /*
     * 专题数据校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function dataCheck($data, $opType = false){
        if(empty($data['specialid'])){
            return error("请传入专题id");
        }
        if(empty($data['typeid'])){
            return error("请选择分类id");
        }
        if(empty($data['title'])){
            return error("请填写标题");
        }
        if(empty($data['thumb'])){
            return error("请上传文章图片");
        }
        if(empty($data['content'])){
            return error("请填写内容");
        }
        return success();
    }

    /*
     * 专题数据添加
     */
    public function dataAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->dataCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $data['inputtime'] = time();
            $data['updatetime'] = time();
            $dataid = $this->dataModel->insertGetId($data);
            if(!$dataid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        //获取参数
        $specialid = input('param.specialid/d');
        //校验
        if(empty($specialid)){
            return $this->error("请传入专题id");
        }
        $special = $this->specialModel->where("siteid = ".$this->siteid." and specialid = " . $specialid)->find();
        if(empty($special)){
            return $this->error("未检索到专题");
        }
        //返回
        $this->assign('special', $special);
        return view(__FUNCTION__);
    }

    /*
     * 专题数据修改
     */
    public function dataEdit()
    {
        $dataid = input('param.dataid/d');
        if(empty($dataid)){
            return $this->error("请传入数据id");
        }
        $info = $this->dataModel->get($dataid);
        if(empty($info)){
            return $this->error("未检索到数据");
        }
        $special = $this->specialModel->where("siteid = ".$this->siteid." and specialid = " . $info['specialid'])->find();
        if(empty($special)){
            return $this->error("未检索到专题");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->dataCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->dataModel->where("dataid = {$dataid}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }
        $this->assign('info', $info);
        $this->assign('special', $special);
        return view(__FUNCTION__);
    }

    /*
     * 数据删除
     */
    public function dataDel(){
        $dataid = input('param.dataid/d',0);
        if(empty($dataid)){
            return $this->error("请传入数据id");
        }
        $rs = $this->dataModel->where("dataid = ".$dataid)->delete();
        if($rs){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }

    /*
     * 数据排序
     */
    public function dataSetOrder()
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
                $rs = $this->dataModel->where("dataid = " . $k)->update(array('listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }

}
