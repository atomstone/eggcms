<?php
namespace app\admin\controller;
use think\Db;
class Ad extends Base
{
    private $spaceModel;
    private $adModel;

    public function __construct(){
        parent::__construct();
        $this->spaceModel = model('AdSpace');
        $this->adModel    = model('Ad');
    }

    /*
     * 广告位列表
     */
    public function spaceList()
    {
        $where = "siteid = " . $this->siteid;
        $spaces = $this->spaceModel->where($where)->order('space_id desc')->select();
        //构造返回
        $this->assign('spaces', $spaces);
        return view(__FUNCTION__);
    }

    /*
     * 广告位校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function spaceCheck($data, $opType = false){
        if(empty($data['space_name'])){
            return error("请填写广告位名称");
        }
        if(empty($data['space_type']) || !isset(config('ad.space_type')[$data['space_type']])){
            return error("请选择广告位类型");
        }

        if(isset($data['space_setting'])){
            if($data['space_type'] == 'imagechange'){
                if(!isset($data['space_setting']['myfoucs_pattern']) || empty($data['space_setting']['myfoucs_pattern'])){
                    return error("请选择图片轮换模式");
                }
                if(empty($data['space_setting']['myfoucs_trigger'])){
                    return error("请选择触发切换模式");
                }
                if(empty($data['space_setting']['myfoucs_wrap'])){
                    return error("请选择是否保留边框");
                }
            }
            $data['space_setting'] = json_encode($data['space_setting']);
        }

        if(empty($data['space_width'])){
            return error("请填写宽度");
        }
        if(empty($data['space_height'])){
            return error("请填写高度");
        }
        if(empty($data['space_enable']) || !isset(config('ad.space_enable')[$data['space_enable']])){
            return error("请选择启用状态");
        }
        return success(array('data' => $data));
    }

    /*
     * 广告位添加
     */
    public function spaceAdd(){
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->spaceCheck($data);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行添加
            $data['siteid'] = $this->siteid;
            $space_id = $this->spaceModel->insertGetId($data);
            if(!$space_id){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }
        return view(__FUNCTION__);
    }

    /*
     * 广告位修改
     */
    public function spaceEdit()
    {
        $space_id = input('param.space_id/d', 0);
        $space = $this->spaceModel->where("siteid = ".$this->siteid." and space_id = " . $space_id)->find();
        if(empty($space)){
            return $this->error("未检索到广告位");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            //校验参数
            $rs = $this->spaceCheck($data, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行修改
            $rs = $this->spaceModel->where("space_id = {$space_id}")->update($data);
            if($rs === false){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }

        $this->assign('space', $space);
        return view(__FUNCTION__);
    }


    /*
     * 广告位删除
     */
    public function spaceDel(){
        $space_id = input('param.space_id/d',0);
        if(empty($space_id)){
            return $this->error("请传入广告位id");
        }
        Db::startTrans();
        $rs1 = $this->adModel->where("space_id = ".$space_id)->delete();
        $rs2 = $this->spaceModel->where("space_id = ".$space_id)->delete();
        if($rs1 && $rs2){
            Db::commit();
            $this->success('删除成功！');
        }else{
            Db::rollback();
            $this->error('删除失败');
        }
    }


    /*
     * 广告列表
     */
    public function adList()
    {
        //获取参数
        $space_id = input('param.space_id/d', 0);
        //校验
        $space = $this->spaceModel->where("siteid = ".$this->siteid." and space_id = " . $space_id)->find();
        if(empty($space)){
            return $this->error("未检索到广告位");
        }
        //构造数据
        $ads = $this->adModel->where("space_id = " . $space_id)->order('ad_listorder asc')->select();
        //构造返回
        $this->assign('space', $space);
        $this->assign('ads', $ads);
        return view(__FUNCTION__);
    }

    /*
     * 广告校验
     * $data 数据
     * $opType false:添加 true:修改
     */
    private function adCheck($data, $space, $opType = false){
        if(empty($data['space_id'])){
            return error("请传入广告位id");
        }
        if(empty($data['ad_name'])){
            return error("请填写广告名称");
        }
        if(empty($data['ad_startdate'])){
            return error("请选择启用时间");
        }
        if(empty($data['ad_enddate'])){
            return error("请选择结束时间");
        }
        if($data['ad_startdate'] > $data['ad_enddate']){
            return error("开始时间不能大于结束时间");
        }
        if(empty($data['ad_enable']) || !isset(config('ad.ad_enable')[$data['ad_enable']])){
            return error("请选择启用状态");
        }
        if($space['space_type'] == 'code'){
            if(empty($data['ad_setting']['content'])){
                return error("请填写内容");
            }
        }elseif($space['space_type'] == 'imagechange'){
            if(empty($data['ad_setting']['src'])){
                return error("请选择图片");
            }
            if(empty($data['ad_setting']['title'])){
                //return error("请填写图片标题");
            }
            if(empty($data['ad_setting']['desc'])){
                //return error("请填写图片介绍");
            }
        }
        $data['ad_setting'] = json_encode($data['ad_setting']);
        return success(array('data' => $data));
    }

    /*
     * 广告添加
     */
    public function adAdd(){
        //获取参数
        $space_id = input('param.space_id/d', 0);
        $space = $this->spaceModel->where("siteid = ".$this->siteid." and space_id = " . $space_id)->find();
        if(empty($space)){
            return $this->error("未检索到广告位");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $data['ad_setting'] = input('post.ad_setting/a');
            //校验参数
            $rs = $this->adCheck($data,$space);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行添加
            $data['ad_addtime']    = time();
            $data['ad_updatetime'] = time();
            $data['ad_startdate']  = strtotime($data['ad_startdate']);
            $data['ad_enddate']    = strtotime($data['ad_enddate']);
            $ad_id = $this->adModel->insertGetId($data);
            if(!$ad_id){
                return $this->error("添加失败");
            }
            return $this->success('添加成功');
        }

        //返回
        $this->assign('space', $space);
        return view(__FUNCTION__);
    }

    /*
     * 广告修改
     */
    public function adEdit()
    {
        $ad_id = input('param.ad_id/d');
        if(empty($ad_id)){
            return $this->error("请传入数据id");
        }
        $ad = $this->adModel->get($ad_id);
        if(empty($ad)){
            return $this->error("未检索到广告");
        }
        $space = $this->spaceModel->where("siteid = ".$this->siteid." and space_id = " . $ad['space_id'])->find();
        if(empty($space)){
            return $this->error("未检索到广告位");
        }

        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.data/a');
            $data['ad_setting'] = input('post.ad_setting/a');
            //校验参数
            $rs = $this->adCheck($data, $space, true);
            if(!$rs['status']){
                return $this->error($rs['msg']);
            }
            $data = $rs['data']['data'];
            //执行修改
            $data['ad_updatetime'] = time();
            $data['ad_startdate']  = strtotime($data['ad_startdate']);
            $data['ad_enddate']    = strtotime($data['ad_enddate']);
            $rs = $this->adModel->where("ad_id = {$ad_id}")->update($data);
            if(!$rs){
                return $this->error("修改失败");
            }
            return $this->success('修改成功');
        }
        $ad['ad_startdate'] = date('Y-m-d H:i:s', $ad['ad_startdate']);
        $ad['ad_enddate']   = date('Y-m-d H:i:s', $ad['ad_enddate']);
        $this->assign('ad', $ad);
        $this->assign('space', $space);
        return view(__FUNCTION__);
    }


    /*
     * 广告删除
     */
    public function adDel(){
        $ad_id = input('param.ad_id/d',0);
        if(empty($ad_id)){
            return $this->error("请传入广告id");
        }
        $rs = $this->adModel->where("ad_id = ".$ad_id)->delete();
        if($rs){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败');
        }
    }


    /*
     * 广告排序
     */
    public function adSetOrder()
    {
        if (request()->isPost() && request()->isAjax()){
            //获取参数
            $data = input('post.setorder/a',0);
            //校验参数
            if(empty($data)){
                return $this->error('没有要排序的广告');
            }
            //执行排序
            foreach($data as $k => $v){
                $rs = $this->adModel->where("ad_id = " . $k)->update(array('ad_listorder' => $v));
                if($rs === false){
                    return $this->success($k.'排序失败');
                }
            }
            return $this->success('排序成功');
        }
    }


}
