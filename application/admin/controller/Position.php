<?php
namespace app\admin\controller;

class position extends Base
{
    private $positionModel;
    private $dataModel;

    public function __construct(){
        parent::__construct();
        $this->positionModel = model('Position');
        $this->dataModel = model('PositionData');
    }

    /*
     * 推荐位列表
     */
    public function positionList()
    {
        $positions = $this->positionModel->where("siteid = " . $this->siteid)->order('listorder asc')->select();
        //构造返回
        $this->assign('positions', $positions);
        return view(__FUNCTION__);
    }

    /*
     * 推荐位校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function positionCheck($data, $opType = false){
        if(empty($data['name'])){
            return error("请填写推荐位名称");
        }
        if(empty($data['maxnum'])){
            return error("请填写最大保存数量 ");
        }
        if($data['maxnum'] <= 0){
            return error("最大保存数量需大于0 ");
        }
        if($opType){
            if(empty($data['posid'])){
                return error("请传入推荐位id");
            }
        }
        return success();
    }

    /*
     * 推荐位添加
     */
    public function positionAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->positionCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行添加
            $data['siteid'] = $this->siteid;
            $posid = $this->positionModel->insertGetId($data);
            if(!$posid){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }

        $categorys = model('category')->where("siteid = " . $this->siteid)->order("listorder ASC")->select();
        $zNodes = '';
        foreach($categorys as $k => $v){
            $iconClose = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/10.png";
            $iconOpen = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/11.png";
            $chkDisabled = '"chkDisabled":true';
            if($v['type'] == 0){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/12.png";
                $chkDisabled = '"chkDisabled":false';
            }elseif($v['type'] == 1){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/2.png";
            }elseif($v['type'] == 2){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/3.png";
            }
            $zNodes .= '{'.$chkDisabled.', id:'.$v['catid'].', pId:'.$v['parentid'].',type:'.$v['type'].', name:"'.$v['catname'].'", open:true, icon:"'.$ico.'", iconClose:"'.$iconClose.'", iconOpen:"'.$iconOpen.'"},';
        }
        $zNodes = '[' . $zNodes . ']';

        $this->assign('zNodes', $zNodes);
        return view(__FUNCTION__);
    }

    /*
     * 推荐位修改
     */
    public function positionEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->positionCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $rs = $this->positionModel->where("posid = {$data['posid']}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $posid = input('param.posid/d');
        if(empty($posid)){
            return $this->errorPage("请传入推荐位id");
        }
        $position = $this->positionModel->get($posid);
        if(empty($position)){
            return $this->errorPage("未检索到推荐位");
        }

        $categorys = model('category')->where("siteid = " . $this->siteid)->order("listorder ASC")->select();
        $zNodes = '';
        $checkArr = array();
        if(!empty($position['catid'])){
            $checkArr = explode(',', $position['catid']);
        }
        foreach($categorys as $k => $v){
            $iconClose = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/10.png";
            $iconOpen = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/11.png";
            $chkDisabled = '"chkDisabled":true';
            $checked = 'false';
            if(in_array($v['catid'], $checkArr)){
                $checked = 'true';
            }
            if($v['type'] == 0){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/12.png";
                $chkDisabled = '"chkDisabled":false';
            }elseif($v['type'] == 1){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/2.png";
            }elseif($v['type'] == 2){
                $ico = "/static/admin/assets/scripts/ztree/css/zTreeStyle/img/diy/3.png";
            }
            $zNodes .= '{checked:'.$checked.', '.$chkDisabled.', id:'.$v['catid'].', pId:'.$v['parentid'].',type:'.$v['type'].', name:"'.$v['catname'].'", open:true, icon:"'.$ico.'", iconClose:"'.$iconClose.'", iconOpen:"'.$iconOpen.'"},';
        }
        $zNodes = '[' . $zNodes . ']';

        $this->assign('zNodes', $zNodes);
        $this->assign('position', $position);
        return view(__FUNCTION__);
    }


    /*
     * 推荐位排序
     */
    public function positionSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的推荐位');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->positionModel->where("posid = " . $k)->update(array('listorder' => $v));
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
        $posid = input('param.posid/d');
        if(empty($posid)){
            return $this->errorPage("请传入推荐位id");
        }
        $position = $this->positionModel->get($posid);
        if(empty($position)){
            return $this->errorPage("未检索到推荐位");
        }
        $datas = $this->dataModel->where("posid = " . $posid)->order('listorder DESC')->select();
        //构造返回
        $this->assign('position', $position);
        $this->assign('datas', $datas);
        return view(__FUNCTION__);
    }

    /*
     * 推荐位数据校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function dataCheck($data, $opType = false){
        if(empty($data['title'])){
            return error("请填写标题");
        }
        if(empty($data['description'])){
            return error("请填写描述 ");
        }
        if(empty($data['inputtime'])){
            return error("请选择发布时间 ");
        }
        if($opType){
            if(empty($data['dataid'])){
                return error("请传入推荐位数据id");
            }
        }
        return success();
    }

    /*
     * 推荐位数据修改
     */
    public function dataEdit()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->dataCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            //执行修改
            $data['inputtime'] = strtotime($data['inputtime']);
            $upData = array(
                'thumb' => $data['thumb'],
                'data'  => json_encode($data)
            );
            $rs = $this->dataModel->where("dataid = {$data['dataid']}")->update($upData);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $dataid = input('param.dataid/d');
        if(empty($dataid)){
            return $this->errorPage("请传入数据id");
        }
        $positiondata = $this->dataModel->get($dataid);
        if(empty($positiondata)){
            return $this->errorPage("未检索到推荐位数据");
        }
        $this->assign('positiondata', $positiondata);
        $this->assign('data', json_decode($positiondata['data'],true));
        return view(__FUNCTION__);
    }


    /*
     * 推荐位数据排序
     */
    public function dataSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a');
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的数据');
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

    /*
     * 推荐位数据删除
     */
    public function dataDel()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.dataids/a');
            //校验参数
            if(empty($data)){
                return $this->error('请选择要移除的数据');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->dataModel->where("dataid = " . $v)->delete();
                if($rs === false){
                    return $this->success($k.'移除失败');
                }
            }
            return $this->success('移除成功');
        }
    }



}
