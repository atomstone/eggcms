<?php
namespace app\common\model;

class specialData extends Common
{

    /*
     * 通过catid和aid获取文章信息
     * $getSub 是否获取附表信息 true 是 false 否
     */
    public function getOneData($siteid, $dataid){
        //获取文章信息
        $data = $this->get($dataid);
        if(empty($data)){
            return false;
        }
        $specialObj = new Special();
        //获取专题信息
        $data['special'] = $specialObj->where("specialid = " . $data['specialid'] . " and siteid = " . $siteid . " and disabled = 0")->find();
        if(empty($data['special'])){
            return false;
        }
        //获取类型
        $typeObj = new SpecialType();
        $data['type'] = $typeObj->where("typeid = ".$data['typeid'])->find();
        if(empty($data['type'])){
            return false;
        }
        return $data;
    }


}