<?php
namespace app\common\model;

class Menu extends Common
{


    /*
     * 格式树形结构
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getTreeMenus($typeid, $pid = 0, $where = "menuid > 0", $select = "*", $order = "listorder ASC,menuid ASC", $tags = "---", $level = 0, & $childs = array() ){
        $ret = $this->where("typeid = {$typeid} AND `parentid` = {$pid} AND " . $where)->field($select)->order($order)->select();
        if(is_array($ret)){
            foreach($ret as $item){
                $str = '';
                for($i = 0; $i < $level; $i++){
                    $str .= $tags;
                }
                //$keys = array_keys($item);
                $item['tag'] = $str;
                $item['level'] = $level;
                $childs[] = $item;
                self::getTreeMenus($typeid, $item['menuid'], $where, $select, $order, $tags, $level+1, $childs);
            }
        }
        return $childs;
    }

    /*
     * 返回分类下所有子分类ID
     * @param string $pid
     * @param array $childs
     * @return array
     */
    public function getChildIds($typeid, $pid, $select = "menuid", $order = "menuid ASC", & $childids = array() ){
        $ret = $this->where("typeid = {$typeid} AND `parentid` = {$pid}")->field($select)->order($order)->select();
        if(is_array($ret)){
            foreach($ret as $item){
                $childids[] = $item['menuid'];
                self::getChildIds($typeid, $item['menuid'], $select, $order, $childids);
            }
        }
        return $childids;
    }

    /*
     * 返回顶级分类
     * @param string $id
     * @return array
     */
    public function getTopMenu($typeid, $id, & $topClass = null){
        $ret = $this->where("typeid = " . $typeid . " and menuid = " . $id)->find();
        if($ret['parentid'] == 0){
            $topClass = $ret;
        }else{
            self::getTopMenu($typeid, $ret['parentid'], $topClass);
        }
        return $topClass;
    }


}