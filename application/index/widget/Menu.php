<?php
namespace app\index\widget;
use app\common\model;
class Menu
{

    /*
     * 根据菜单分类编码获取菜单
     */
    public function getMenus($siteid, $code){
        $type = model('MenuType')->where("siteid = ".$siteid." and code = '".$code."'")->find();
        if(empty($type)){
            return false;
        }
        $menus = $this->getArrayMenus($type['typeid']);
        return $menus;
    }

    private function getArrayMenus($typeid, $parentid=0){
        $rs = model("Menu")->where("typeid = " . $typeid . " and parentid = " . $parentid)->order("listorder asc")->select();
        $arr = array();
        if($rs){//如果有子类
            foreach($rs as $row){ //循环记录集
                if($row['linkstype'] == 1){
                    $row['url'] = "#this";
                }elseif($row['linkstype'] == 3){
                    $site = model('Site')->find($row['siteid']);
                    if(isset($site['domain'])){
                        $row['url'] = $site['domain'] . url('Cms/index',array('catid'=>$row['catid']));
                    }else{
                        $row['url'] = "#this";
                    }
                }
                $row['list'] = $this->getArrayMenus($typeid, $row['menuid']); //调用函数，传入参数，继续查询下级
                $arr[] = $row; //组合数组
            }
            return $arr;
        }
    }


}
