<?php
namespace app\index\widget;
use app\common\model;
class Position
{

    /*
     * 根据推荐位id获取多条推荐数据
     */
    public function getDatas($posid, $limit = false){
        $position = model('Position')->where("enable = 1 and posid = " . $posid)->find();
        if(empty($position)){
            return false;
        }
        if(!$limit){
            $limit = $position['maxnum'];
        }
        $rs = model('PositionData')->where("posid = " . $posid)->order("listorder DESC")->limit($limit)->select();
        if(empty($rs)){
            return false;
        }
        if(!empty($rs)){
            foreach($rs as & $v){
                $data = json_decode($v['data'],true);
                $v['title']       = isset($data['title'])?$data['title']:'';
                $v['description'] = isset($data['description'])?$data['description']:'';
                $v['inputtime']   = isset($data['inputtime']) ? date("Y-m-d H:i:s", $data['inputtime']) :'';
                $v['inputdate']   = isset($data['inputtime']) ? date("Y-m-d", $data['inputtime']) :'';
            }
        }
        return $rs;
    }


    /*
     * 根据推荐位id获取单条推荐数据
     */
    public function getOneData($posid){
        $position = model('Position')->where("enable = 1 and posid = " . $posid)->find();
        if(empty($position)){
            return false;
        }
        $rs = model('PositionData')->where("posid = " . $posid)->order("listorder DESC")->find();
        if(empty($rs)){
            return false;
        }
        $data = json_decode($rs['data'],true);
        $rs['title']       = isset($data['title'])?$data['title']:'';
        $rs['description'] = isset($data['description'])?$data['description']:'';
        $rs['inputtime']   = isset($data['inputtime']) ? date("Y-m-d H:i:s", $data['inputtime']) :'';
        $rs['inputdate']   = isset($data['inputtime']) ? date("Y-m-d", $data['inputtime']) :'';
        return $rs;
    }

}
